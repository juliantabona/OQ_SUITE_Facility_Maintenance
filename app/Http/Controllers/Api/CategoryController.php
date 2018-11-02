<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $categories = Category::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $categories = Category::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $categories = Category::orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($categories)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $categories->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($categories, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($category_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $category = Category::withTrashed()->where('id', $category_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $category = Category::where('id', $category_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($category)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $category->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($category, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        /*  Validate and Create the new category and associated category and upload related documents
         *  [e.g logo, category profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new category
         *  @param $user - The user creating the category
         *
         *  @return Validator - If validation failed
         *  @return category - If successful
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

        $response = oq_createOrUpdateCategory($request, null, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return created category
        return oq_api_notify($response, 201);
    }

    public function update(Request $request, $category_id)
    {
        //  Get the category, even if trashed
        $category = Category::withTrashed()->where('id', $category_id)->first();

        /*  Validate and Create the new category and associated category and upload related documents
         *  [e.g logo, category profile, other documents]. Update recent activities
         *
         *  @param $request - The request parameters used to create a new category
         *  @param $user - The user creating the category
         *
         *  @return Validator - If validation failed
         *  @return category - If successful
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

        $response = oq_createOrUpdateCategory($request, $category, $company, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated category
        return oq_api_notify($response, 200);
    }

    public function delete($category_id)
    {
        //  Get the category, even if trashed
        $category = Category::withTrashed()->find($category_id);

        //  Check if we have a resource
        if (!count($category)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            //  Permanently delete category
            $category->forceDelete();
        } else {
            //  Soft delete (trash) the category
            $category->delete();
        }

        return response()->json(null, 204);
    }
}
