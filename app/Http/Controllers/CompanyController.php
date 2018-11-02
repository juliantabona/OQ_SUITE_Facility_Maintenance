<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use App\Jobcard;
use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show($id)
    {
        //  Get the company
        $company = Company::with('recentActivities', 'documents')->where('id', $id)->first();

        //  Get all associated jobcards for this company
        $jobcards = Jobcard::where('client_id', $id)->paginate(10, ['*'], 'jobcards');

        //  Get the details of the company creator
        $createdBy = oq_createdBy($company);

        //  Get the pagination of the companies contacts
        $contactsList = oq_paginateClientContacts($company);

        $jobcardProcessSteps = Auth::user()->companyBranch->company->processForms->where('selected', 1)->where('type', 'jobcard')->first()->steps;

        return $company->recentActivities;

        return view('dashboard.pages.company.client.show', compact('company', 'contactsList', 'jobcards', 'jobcardProcessSteps'));
    }

    public function store(Request $request)
    {
        /*  If we are creating a client. Lets ensure that we do not already
        *   have another company saved with the same name
        */

        if ($request->input('company_type') == 'client') {
            $CompanyAlreadyExists = oq_checkClientExists(Auth::user(), $request->input('company_name'));

        /*  If we are creating a contractor. Lets ensure that we do not already
        *   have another company saved with the same name
        */
        } elseif ($request->input('company_type') == 'contractor') {
            $CompanyAlreadyExists = oq_checkContractorExists(Auth::user(), $request->input('company_name'));
        }

        /*  If the company already exists with the same name
        *   We must go back with a custom error nofifying the user
        */
        if ($CompanyAlreadyExists) {
            oq_backWithCustomErrors(['company_name' => 'A '.$request->input('company_type').' with the name "'.$request->input('company_name').'" already exists. Try searching for it instead.']);
        }

        //  Validate and Create the new company and associated branch and upload related documents [e.g logo, company profile, other documents]
        $company = oq_createCompany($request, Auth::user());

        //  If the validation did not pass
        if (oq_failed_validation($company)) {
            //  Return back with the alert and failed validation rules
            return $company['failed_validation'];
        }

        //  If the company was created successfully
        if ($company) {
            //  Save the company as part of the companies client/contractor directory depending on the type
            oq_addCompanyToDirectory(Auth::user()->companyBranch->company->id, $company, $request->input('company_type'), Auth::user());

            /*  If we have the Jobcard ID within the request, then we can add the client/contractor
            *  to the jobcard respectively as either the jobcard assigned client/potential contractor
            */
            if (!empty($request->input('jobcard_id'))) {
                $jobcard_id = $request->input('jobcard_id');
                //  If this is a client add to the jobcard directory
                if ($request->input('company_type') == 'client') {
                    oq_addClientToJobcard($jobcard_id, $company, Auth::user());

                //  If this is a contractor add to the jobcard directory along with associated files e.g) quotation
                } else {
                    //  Validate and add contractor to the jobcard directory
                    $jobcardContractor = oq_addContractorToJobcard($jobcard_id, $company, Auth::user(), [$request->only('company_quote'), $request->input('company_total_price')]);

                    //  If the validation did not pass e.g) saving the quotation file
                    if (oq_failed_validation($jobcardContractor)) {
                        //  Return back with the alert and failed validation rules
                        return $jobcardContractor['failed_validation'];
                    }
                }
            }
            //  Notify the user that the update was successful
            oq_notify($request->input('company_type').' added successfully!', 'success');
        } else {
            //  Notify the user that the update was unsuccessful
            oq_notify('Something went wrong creating the '.$request->input('company_type').'. Please try again', 'warning');
        }

        //  If we have the jobcard ID
        if (!empty($request->input('jobcard_id'))) {
            return redirect()->route('jobcard-show', $request->input('jobcard_id'));
        //  If we added a new client
        } elseif ($request->input('company_type') == 'client') {
            return redirect()->route('client-show', $company->id);
        //  If we added a new contractor
        } elseif ($request->input('company_type') == 'contractor') {
            return redirect()->route('contractor-show', $company->id);
        }
    }
}
