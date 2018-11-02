<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Priority;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriorityController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $priorities = Priority::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $priorities = Priority::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $priorities = Priority::orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($priorities)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $priorities->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($priorities, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($priority_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $priority = Priority::withTrashed()->where('id', $priority_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $priority = Priority::where('id', $priority_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($priority)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $priority->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($priority, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        /*  Validate and Create the new priority and associated priority and upload related documents
         *  [e.g logo, priority profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new priority
         *  @param $user - The user creating the priority
         *
         *  @return Validator - If validation failed
         *  @return priority - If successful
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

        $response = oq_createOrUpdatePriority($request, null, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return created priority
        return oq_api_notify($response, 201);
    }

    public function update(Request $request, $priority_id)
    {
        //  Get the priority, even if trashed
        $priority = Priority::withTrashed()->where('id', $priority_id)->first();

        /*  Validate and Create the new priority and associated priority and upload related documents
         *  [e.g logo, priority profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new priority
         *  @param $user - The user creating the priority
         *
         *  @return Validator - If validation failed
         *  @return priority - If successful
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

        $response = oq_createOrUpdatePriority($request, $priority, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated priority
        return oq_api_notify($response, 200);
    }

    public function delete($priority_id)
    {
        //  Get the priority, even if trashed
        $priority = Priority::withTrashed()->find($priority_id);

        //  Check if we have a resource
        if (!count($priority)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete priority
            $priority->forceDelete();
        } else {
            //  Soft delete (trash) the priority
            $priority->delete();
        }

        return response()->json(null, 204);
    }
}
