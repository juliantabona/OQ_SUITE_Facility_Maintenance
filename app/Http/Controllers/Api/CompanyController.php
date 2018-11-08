<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Jobcard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $companies = Company::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $companies = Company::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $companies = Company::orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($companies)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $companies->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($companies, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($company_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $company = Company::withTrashed()->where('id', $company_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $company = Company::where('id', $company_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($company)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $company->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($company, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        //  Current authenticated user
        $user = auth('api')->user();

        /*  If we are creating a client. Lets ensure that we do not already
        *   have another company saved with the same name
        */
        $CompanyAlreadyExists = false;

        if ($request->input('company_relation') == 'client') {
            $CompanyAlreadyExists = oq_checkClientExists($user, $request->input('company_name'));

        /*  If we are creating a contractor. Lets ensure that we do not already
        *   have another company saved with the same name
        */
        } elseif ($request->input('company_relation') == 'contractor') {
            $CompanyAlreadyExists = oq_checkContractorExists($user, $request->input('company_name'));
        }

        /*  If the company already exists with the same name
        *   We must go back with a custom error nofifying the user
        */
        if ($CompanyAlreadyExists) {
            $validator = ['company_name' => 'A '.$request->input('company_relation').' with the name "'.$request->input('company_name').'" already exists. Try searching for it instead.'];

            return oq_failed_validation_return($request, ['failed_validation' => true, 'validator' => $validator]);
        }

        /*  Validate and Create the new company and associated branch and upload related documents
         *  [e.g logo, company profile, other documents]. Update recent activities
         *
         *  @param $request - The request with all the parameters to create
         *  @param $company - The company model if we are updating, in this case it must be null
         *  @param $user - The user updating the company
         *
         *  @return Validator - If validation failed
         *  @return Company - If successful
         */

        $response = oq_createOrUpdateCompany($request, null, null);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  If we had issues while trying to create the company
        if ($response == false) {
            //  Notify the user that the creation was unsuccessful
            oq_notify('Something went wrong creating the '.$request->input('company_relation').'. Please try again', 'warning');

            return oq_api_notify_error('Query Error', $e->getMessage(), 404);
        }

        //  At this point we are certain we have a company
        $company = $response;

        /*
         *   If the company was created successfully
         */

        //  Save the company as part of the companies client/contractor directory depending on the type
        oq_addCompanyToDirectory($company, $request->input('company_relation'), $user);

        /*  If we have the Jobcard ID within the request, then we can add the client/contractor
        *  to the jobcard respectively as either the jobcard assigned client/potential contractor
        */

        if (!empty($request->input('jobcard_id'))) {
            $jobcard_id = $request->input('jobcard_id');

            //  If this is a client add to the jobcard directory
            if ($request->input('company_relation') == 'client') {
                oq_addClientToJobcard($jobcard_id, $company, $user);

            //  If this is a contractor add to the jobcard directory along with associated files e.g) quotation
            } else {
                //  Validate and add contractor to the jobcard directory
                $contractorResponse = oq_addOrUpdateContractorToJobcard($jobcard_id, $company, $user, $request, 'create');

                //  If the validation did not pass
                if (oq_failed_validation($contractorResponse)) {
                    //  Return validation errors with an alert or json response if API request
                    return oq_failed_validation_return($request, $contractorResponse);
                }

                //  Refetch the contractor to return as the company with quotation details
                $company = Jobcard::find($jobcard_id)->contractorsList()->where('contractor_id', $company->id)->first();
            }
        }

        //  Notify the user that the update was successful
        oq_notify($request->input('company_relation').' added successfully!', 'success');

        //  return created company
        return oq_api_notify($company, 201);
    }

    public function update(Request $request, $company_id)
    {
        //  Current authenticated user
        $user = auth('api')->user();

        //  Get the company, even if trashed
        $company = Company::withTrashed()->where('id', $company_id)->first();

        //  Check if we don't have a resource
        if (!count($company)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        /*  Validate and Update the company and upload related documents
         *  [e.g logo, company profile, other documents]. Update recent activities
         *
         *  @param $request - The request with all the parameters to update
         *  @param $company - The company we are updating
         *  @param $user - The user updating the company
         *
         *  @return Validator - If validation failed
         *  @return Company - If successful
         */

        $response = oq_createOrUpdateCompany($request, $company, null);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  If we had issues while trying to update the company
        if ($response == false) {
            //  Notify the user that the creation was unsuccessful
            oq_notify('Something went wrong updating the '.$request->input('company_relation').'. Please try again', 'warning');

            return oq_api_notify_error('Query Error', $e->getMessage(), 404);
        }

        //  At this point we are certain we have a company
        $company = $response;

        /*
         *   If the company was created successfully
         */

        if (!empty($request->input('jobcard_id'))) {
            $jobcard_id = $request->input('jobcard_id');

            //  If this is a contractor add to the jobcard directory along with associated files e.g) quotation
            if ($request->input('company_relation') == 'contractor') {
                //  Validate and add contractor to the jobcard directory
                $contractorResponse = oq_addOrUpdateContractorToJobcard($jobcard_id, $company, $user, $request, 'update');

                //  If the validation did not pass
                if (oq_failed_validation($contractorResponse)) {
                    //  Return validation errors with an alert or json response if API request
                    return oq_failed_validation_return($request, $contractorResponse);
                }

                //  Refetch the contractor to return as the company with quotation details
                $company = Jobcard::find($jobcard_id)->contractorsList()->where('contractor_id', $company->id)->first();
            }
        }

        //  Notify the user that the update was successful
        oq_notify($request->input('company_relation').' updated successfully!', 'success');

        //  return updated company
        return oq_api_notify($company, 200);
    }

    public function delete($company_id)
    {
        //  Get the company, even if trashed
        $company = Company::withTrashed()->find($company_id);

        //  Check if we have a resource
        if (!count($company)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete company
            $company->forceDelete();
        } else {
            //  Soft delete (trash) the company
            $company->delete();
        }

        return response()->json(null, 204);
    }
}
