<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
    HTTP Status Codes and the Response Format - FOR MY OWN GOOD :)

    200: OK. The standard success code and default option.
    201: Object created. Useful for the store actions.
    204: No content. When an action was executed successfully, but there is no content to return.
    206: Partial content. Useful when you have to return a paginated list of resources.
    400: Bad request. The standard option for requests that fail to pass validation.
    401: Unauthorized. The user needs to be authenticated.
    403: Forbidden. The user is authenticated, but does not have the permissions to perform an action.
    404: Not found. Usually returned automatically by Laravel when the resource is not found.
    405: Indicates that the request method is known by the server but is not supported by the target resource e.g) GET, POST, PUT, DELETE
    500: Internal server error. Ideally you're not going to be explicitly returning this, but if something unexpected breaks, this is what your user is going to receive.
    503: Service unavailable. Pretty self explanatory, but also another code that is not going to be returned explicitly by the application.
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*   AUTH ROUTES
     -  Login
     -  Register
*/

Route::post('/login', 'Auth\LoginController@login');
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/register/resend-activation/', 'Auth\AccountActivation@resend');
Route::post('/register/activate-account', 'Auth\AccountActivation@activate');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', 'Auth\LoginController@logout');
});

/*   COMPANY RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('companies', 'Api\CompanyController@index');
Route::get('companies/{company_id}', 'Api\CompanyController@show');
Route::post('companies', 'Api\CompanyController@store');
Route::put('companies/{company_id}', 'Api\CompanyController@update');
Route::delete('companies/{company_id}', 'Api\CompanyController@delete');

/*   COMPANY BRANCH RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('branches', 'Api\CompanyBranchController@index');
Route::get('branches/{branch_id}', 'Api\CompanyBranchController@show');
Route::post('branches', 'Api\CompanyBranchController@store');
Route::put('branches/{branch_id}', 'Api\CompanyBranchController@update');
Route::delete('branches/{branch_id}', 'Api\CompanyBranchController@delete');

/*   COMPANY CLIENTS RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('clients', 'Api\ClientController@index');
//Route::get('companies/{company_id}', 'Api\ClientController@show');
//Route::post('companies', 'Api\ClientController@store');
//Route::put('companies/{company_id}', 'Api\ClientController@update');
//Route::delete('companies/{company_id}', 'Api\ClientController@delete');

/*   COMPANY CONTRACTORS RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('contractors', 'Api\ContractorController@index');
//Route::get('companies/{company_id}', 'Api\ContractorController@show');
//Route::post('companies', 'Api\ContractorController@store');
//Route::put('companies/{company_id}', 'Api\ContractorController@update');
//Route::delete('companies/{company_id}', 'Api\ContractorController@delete');

/*   JOBCARD RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('jobcards', 'Api\JobcardController@index');
Route::get('jobcards/{jobcard_id}', 'Api\JobcardController@show');
Route::post('jobcards', 'Api\JobcardController@store');
Route::put('jobcards/{jobcard_id}', 'Api\JobcardController@update');
Route::delete('jobcards/{jobcard_id}', 'Api\JobcardController@delete');
Route::get('jobcards/{jobcard_id}/contractors', 'Api\JobcardController@contractors');
Route::post('jobcards/{jobcard_id}/removeclient', 'Api\JobcardController@removeClient');
Route::post('jobcards/{jobcard_id}/{contractor_id}/remove', 'Api\JobcardController@removeContractor');

/*   DOCUMENT RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('documents', 'Api\DocumentController@index');
Route::get('documents/{document_id}', 'Api\DocumentController@show');
Route::post('documents', 'Api\DocumentController@store');
Route::put('documents/{document_id}', 'Api\DocumentController@update');
Route::delete('documents/{document_id}', 'Api\DocumentController@delete');

/*   PRIORITY RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('priorities', 'Api\PriorityController@index');
Route::get('priorities/{priority_id}', 'Api\PriorityController@show');
Route::post('priorities', 'Api\PriorityController@store');
Route::put('priorities/{priority_id}', 'Api\PriorityController@update');
Route::delete('priorities/{priority_id}', 'Api\PriorityController@delete');

/*   CATEGORY RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('categories', 'Api\CategoryController@index');
Route::get('categories/{category_id}', 'Api\CategoryController@show');
Route::post('categories', 'Api\CategoryController@store');
Route::put('categories/{category_id}', 'Api\CategoryController@update');
Route::delete('categories/{category_id}', 'Api\CategoryController@delete');

/*   COST CENTER RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('costcenters', 'Api\CostCenterController@index');
Route::get('costcenters/{costcenter_id}', 'Api\CostCenterController@show');
Route::post('costcenters', 'Api\CostCenterController@store');
Route::put('costcenters/{costcenter_id}', 'Api\CostCenterController@update');
Route::delete('costcenters/{costcenter_id}', 'Api\CostCenterController@delete');

/*   RECENT ACTIVITY RESOURCE ROUTES
     -  Get, Show, Update, Trash, Delete
*/
Route::get('recentactivities', 'Api\RecentActivityController@index');
Route::get('recentactivities/{recent_activity_id}', 'Api\RecentActivityController@show');
