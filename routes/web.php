<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 *  WEBSITE ROUTES
 */

Route::get('/email', function () {
    return view('dashboard.emails.activate_account');
})->name('welcome');

Route::get('/', function () {
    return view('web.pages.welcome');
})->name('welcome');

Route::get('/features', function () {
    return view('web.pages.features');
})->name('features');

/*
 *  DASHBOARD ROUTES
 */

Auth::routes();

Route::group(['prefix' => 'activate-account',  'middleware' => 'auth'], function () {
    Route::get('/', 'AccountActivation@index')->name('activate-show');
    Route::post('/resend', 'AccountActivation@resend')->name('activate-resend');
});

Route::get('/overview', function () {
    return view('dashboard.pages.overview');
})->name('overview')->middleware('auth');

/*  JOBCARDS    list, create, show, edit, save, delete */
Route::group(['prefix' => 'jobcards',  'middleware' => 'auth'], function () {
    Route::get('/', 'JobcardController@index')->name('jobcards');
    Route::post('/', 'JobcardController@store')->name('jobcard-store');
    Route::get('/create', 'JobcardController@create')->name('jobcard-create');
    Route::get('/{jobcard_id}', 'JobcardController@show')->name('jobcard-show');
    Route::put('/{jobcard_id}', 'JobcardController@update')->name('jobcard-update');
    Route::delete('/{jobcard_id}', 'JobcardController@delete')->name('jobcard-delete');
});
