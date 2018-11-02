<?php

namespace App\Http\Controllers\Api;

use App\Jobcard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobcardController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $jobcards = Jobcard::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $jobcards = Jobcard::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $jobcards = Jobcard::advancedFilter();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($jobcards)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $jobcards->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($jobcards, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($jobcard_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $jobcard = Jobcard::withTrashed()->where('id', $jobcard_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $jobcard = Jobcard::where('id', $jobcard_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($jobcard)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $jobcard->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($jobcard, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function contractors($jobcard_id)
    {
        try {
            //  Run query
            $contractors = Jobcard::find($jobcard_id)->contractorsList()->paginate();
            //advancedFilter()
        } catch (\Exception $e) {
            return oq_api_notify_error('Query Error', $e->getMessage(), 404);
        }

        if (count($contractors)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $contractors->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($contractors, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        /*  Validate and Create the new jobcard and associated branch and upload related documents
         *  [e.g logo, jobcard profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new jobcard
         *  @param $user - The user creating the jobcard
         *
         *  @return Validator - If validation failed
         *  @return jobcard - If successful
         */
        $response = oq_createOrUpdateJobcard($request, null, null);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return created jobcard
        return oq_api_notify($response, 201);
    }

    public function update(Request $request, $jobcard_id)
    {
        //  Get the jobcard, even if trashed
        $jobcard = Jobcard::withTrashed()->where('id', $jobcard_id)->first();

        //  Check if we don't have a resource
        if (!count($jobcard)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        /*  Validate and Update the jobcard and upload related documents
         *  [e.g jobcard images or files]. Update recent activities
         *
         *  @param $request - The request with all the parameters to update
         *  @param $jobcard - The jobcard we are updating
         *  @param $user - The user updating the jobcard
         *
         *  @return Validator - If validation failed
         *  @return jobcard - If successful
         */

        $response = oq_createOrUpdateJobcard($request, $jobcard, null);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated jobcard
        return oq_api_notify($response, 200);
    }

    public function removeClient($jobcard_id)
    {
        try {
            //  Run query
            $jobcard = Jobcard::find($jobcard_id);
            $jobcard->client_id = null;
            $jobcard->save();
        } catch (\Exception $e) {
            return oq_api_notify_error('Query Error', $e->getMessage(), 404);
        }

        if ($jobcard) {
            //  Action was executed successfully
            return response()->json(null, 204);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function removeContractor($jobcard_id, $contractor_id)
    {
        try {
            //  Run query
            $jobcard = Jobcard::find($jobcard_id);
            $jobcard->contractorsList()->detach($contractor_id);
        } catch (\Exception $e) {
            return oq_api_notify_error('Query Error', $e->getMessage(), 404);
        }

        if ($jobcard) {
            //  Action was executed successfully
            return response()->json(null, 204);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function delete($jobcard_id)
    {
        //  Get the jobcard, even if trashed
        $jobcard = Jobcard::withTrashed()->find($jobcard_id);

        //  Check if we have a resource
        if (!count($jobcard)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete jobcard
            $jobcard->forceDelete();
        } else {
            //  Soft delete (trash) the jobcard
            $jobcard->delete();
        }

        return response()->json(null, 204);
    }
}
