<?php

namespace App\Http\Controllers\Api;

use App\CompanyBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = null;

        //  Get the branch id
        if (!empty(request('company_branch_id'))) {
            $company_branch_id = request('company_branch_id');
        } else {
            //  No branch id specified error
            return oq_api_notify_error('include company_branch_id', null, 404);
        }

        //  Check if a branch with the given id exists
        $branchExists = CompanyBranch::where('id', $company_branch_id)->count();

        if ($branchExists) {
            //  Get all and trashed
            if (request('withtrashed') == 1) {
                try {
                    //  Run query
                    $clients = CompanyBranch::withTrashed()->where('id', $company_branch_id)->first()->company()->first()->clients()->advancedFilter();
                } catch (\Exception $e) {
                    return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                }
                //  Get only trashed
            } elseif (request('onlytrashed') == 1) {
                try {
                    //  Run query
                    $clients = CompanyBranch::onlyTrashed()->where('id', $company_branch_id)->first()->company()->first()->clients()->advancedFilter();
                } catch (\Exception $e) {
                    return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                }
                //  Get all except trashed
            } else {
                try {
                    //  Run query
                    $clients = CompanyBranch::where('id', $company_branch_id)->first()->company()->first()->clients()->advancedFilter();
                } catch (\Exception $e) {
                    return oq_api_notify_error('Query Error', $e->getMessage(), 404);
                }
            }

            if (count($clients)) {
                //  Eager load other relationships wanted if specified
                if (request('connections')) {
                    $clients->load(oq_url_to_array(request('connections')));
                }

                //  Action was executed successfully
                return oq_api_notify($clients, 200);
            }
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
