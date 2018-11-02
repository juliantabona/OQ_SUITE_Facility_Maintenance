<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\CompanyBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyBranchController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $branches = CompanyBranch::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $branches = CompanyBranch::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $branches = CompanyBranch::orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($branches)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $branches->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($branches, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($branch_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $branch = CompanyBranch::withTrashed()->where('id', $branch_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $branch = CompanyBranch::where('id', $branch_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($branch)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $branch->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($branch, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        /*  Validate and Create the new branch and associated branch and upload related documents
         *  [e.g logo, branch profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new branch
         *  @param $user - The user creating the branch
         *
         *  @return Validator - If validation failed
         *  @return branch - If successful
         */

        if (!empty(request('user_id'))) {
            $user = User::with('companyBranch.company')->where('id', request('user_id'))->first();
        } else {
            //  No document type specified error
            return oq_api_notify_error('include user_id', null, 404);
        }

        if (count($user->companyBranch)) {
            if (count($user->companyBranch->company)) {
                $company = $user->companyBranch->company;
            } else {
                //  No company error
                return oq_api_notify_error('user must belong to a company', null, 404);
            }
        } else {
            //  No branch error
            return oq_api_notify_error('user must belong to a branch', null, 404);
        }

        $response = oq_createOrUpdatebranch($request, null, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return created branch
        return oq_api_notify($response, 201);
    }

    public function update(Request $request, $branch_id)
    {
        //  Get the branch, even if trashed
        $branch = CompanyBranch::withTrashed()->where('id', $branch_id)->first();

        /*  Validate and Create the new branch and associated branch and upload related documents
         *  [e.g logo, branch profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new branch
         *  @param $user - The user creating the branch
         *
         *  @return Validator - If validation failed
         *  @return branch - If successful
         */

        if (!empty(request('user_id'))) {
            $user = User::with('companyBranch.company')->where('id', request('user_id'))->first();
        } else {
            //  No document type specified error
            return oq_api_notify_error('include user_id', null, 404);
        }

        if (count($user->companyBranch)) {
            if (count($user->companyBranch->company)) {
                $company = $user->companyBranch->company;
            } else {
                //  No company error
                return oq_api_notify_error('user must belong to a company', null, 404);
            }
        } else {
            //  No branch error
            return oq_api_notify_error('user must belong to a branch', null, 404);
        }

        $response = oq_createOrUpdatebranch($request, $branch, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated branch
        return oq_api_notify($response, 200);
    }

    public function delete($branch_id)
    {
        //  Get the branch, even if trashed
        $branch = CompanyBranch::withTrashed()->find($branch_id);

        //  Check if we have a resource
        if (!count($branch)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete branch
            $branch->forceDelete();
        } else {
            //  Soft delete (trash) the branch
            $branch->delete();
        }

        return response()->json(null, 204);
    }
}
