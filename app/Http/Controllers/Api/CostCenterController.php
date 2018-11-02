<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\CostCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CostCenterController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $cost_centers = CostCenter::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $cost_centers = CostCenter::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $cost_centers = CostCenter::orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($cost_centers)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $cost_centers->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($cost_centers, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($cost_center_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $cost_center = CostCenter::withTrashed()->where('id', $cost_center_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $cost_center = CostCenter::where('id', $cost_center_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($cost_center)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $cost_center->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($cost_center, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        /*  Validate and Create the new cost_center and associated cost_center and upload related documents
         *  [e.g logo, cost_center profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new cost_center
         *  @param $user - The user creating the cost_center
         *
         *  @return Validator - If validation failed
         *  @return cost_center - If successful
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

        $response = oq_createOrUpdateCostCenter($request, null, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return created cost_center
        return oq_api_notify($response, 201);
    }

    public function update(Request $request, $cost_center_id)
    {
        //  Get the cost_center, even if trashed
        $cost_center = CostCenter::withTrashed()->where('id', $cost_center_id)->first();

        /*  Validate and Create the new cost_center and associated cost_center and upload related documents
         *  [e.g logo, cost_center profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new cost_center
         *  @param $user - The user creating the cost_center
         *
         *  @return Validator - If validation failed
         *  @return cost_center - If successful
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

        $response = oq_createOrUpdateCostCenter($request, $cost_center, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated cost_center
        return oq_api_notify($response, 200);
    }

    public function delete($cost_center_id)
    {
        //  Get the cost_center, even if trashed
        $cost_center = CostCenter::withTrashed()->find($cost_center_id);

        //  Check if we have a resource
        if (!count($cost_center)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete cost_center
            $cost_center->forceDelete();
        } else {
            //  Soft delete (trash) the cost_center
            $cost_center->delete();
        }

        return response()->json(null, 204);
    }
}
