<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = null;
        $model_id = request('id');
        $model_type = request('model', 'CompanyBranch');

        //  If we have the model and id specified
        if (!empty($model_id)) {
            //  Get the associated model other default to get the branch model instance
            $DynamicModel = '\\App\\'.$model_type;

            if (class_exists($DynamicModel) && in_array($model_type, ['Company', 'CompanyBranch'])) {
                //  To avoid sql order_by error for ambigious fields e.g) created_at
                //  we must specify the order_join to archieve users.field e.g) users.created_at
                $order_join = 'users';

                //  Get all and trashed
                if (request('withtrashed') == 1) {
                    try {
                        //  Run query
                        $model = $DynamicModel::withTrashed()->where('id', $model_id)->first();
                        if (count($model)) {
                            $staff = $model->staff()->advancedFilter($order_join);
                        }
                    } catch (\Exception $e) {
                        return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                    }
                    //  Get only trashed
                } elseif (request('onlytrashed') == 1) {
                    try {
                        //  Run query
                        $model = $DynamicModel::onlyTrashed()->where('id', $model_id)->first();
                        if (count($model)) {
                            $staff = $model->staff()->advancedFilter($order_join);
                        }
                    } catch (\Exception $e) {
                        return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                    }
                    //  Get all except trashed
                } else {
                    try {
                        //  Run query
                        $model = $DynamicModel::where('id', $model_id)->first();
                        if (count($model)) {
                            $staff = $model->staff()->advancedFilter($order_join);
                        }
                    } catch (\Exception $e) {
                        return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                    }
                }

                if (count($staff)) {
                    //  Eager load other relationships wanted if specified
                    if (request('connections')) {
                        $staff->load(oq_url_to_array(request('connections')));
                    }

                    //  Action was executed successfully
                    return oq_api_notify($staff, 200);
                }
            } else {
                //  No such class, the user provided incorrect details
                return oq_api_notify_error('Class does not exist. Only company or companyBranch are accepted as models', null, 404);
            }
        } else {
            //  No branch id specified error
            return oq_api_notify_error('include branch or company id', null, 404);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($company_id)
    {
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $company_id)
    {
    }

    public function delete($company_id)
    {
    }
}
