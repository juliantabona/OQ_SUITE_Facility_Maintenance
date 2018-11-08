<?php

namespace App\Http\Controllers\Api;

use App\RecentActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecentActivityController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $recentActivities = RecentActivity::withTrashed()->advancedFilter();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $recentActivities = RecentActivity::onlyTrashed()->advancedFilter();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $recentActivities = RecentActivity::advancedFilter();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($recentActivities)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $recentActivities->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($recentActivities, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($recent_activity_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $recent_activity = RecentActivity::withTrashed()->where('id', $recent_activity_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $recent_activity = RecentActivity::where('id', $recent_activity_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($recent_activity)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $recent_activity->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($recent_activity, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }
}
