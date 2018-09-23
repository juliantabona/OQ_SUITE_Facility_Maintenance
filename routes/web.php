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
 *  START TESTS
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

Route::get('/form', function () {
    $processForm = App\ProcessForm::where('company_id', Auth::user()->companyBranch->company->id)
                                ->where('type', 'jobcard')
                                ->where('selected', 1)
                                ->first();

    return view('dashboard.pages.process-form.create', compact('processForm'));
})->name('form');

Route::get('/sendfile', function () {
    return view('test.sendfile');
})->name('sendfile');

Route::post('/sendfile', function (Request $request) {
    $image_file = Input::file('image');

    //return getimagesize($image_file);

    return $image_file->getClientSize();

    //  If the user uploaded an image
    if ($request->hasFile('image')) {
        //  Lets get the image file
        $imageFile = $request->only('image')['image'];
    } else {
        //  Otherwise set the image file and URL to nothing
        $imageFile = [];
        $image_url = null;
    }

    // Add all uploads for validation
    $fileArray = array_merge(array('image' => $imageFile), $request->all());
    $rules = [];
    //  If the user uploaded an image then validate it
    if ($request->hasFile('image')) {
        //  Rules for image data
        $rules = array_merge($rules, [
            /* General validation
                *
                * We only except the following formats "jpeg,jpg,png,gif"
                * and a maximum image size of 2MB(Megabytes)
                */
                'image' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000Kb/2Mb
            ]
        );
    }

    //  Customized error messages
    $messages = [
        //  Image related
        'image.mimes' => 'Image must be an image format e.g) jpeg,jpg,png,gif',
        'image.max' => 'Image should not be more than 2MB in size',
        ];

    // Now pass the input and rules into the validator
    $validator = Validator::make($fileArray, $rules, $messages);

    // Check to see if validation fails or passes
    if ($validator->fails()) {
        //  Notify the user that validation failed
        Session::forget('alert');
        Session::flash('alert', array('Couldn\'t create jobcard, check your information!', 'icon-exclamation icons', 'danger'));

        return Redirect::back()->withErrors($validator)->withInput();
    } else {
        //  If we have the image and has been approved, then save it to Amazon S3 bucket
        if ($request->hasFile('image')) {
            //  Get the image
            $image_file = Input::file('image');

            //  Store the image file to Amazon s3 and retrieve the new image name
            $image_file_name = Storage::disk('s3')->putFile('jobcard_images', $image_file, 'public');

            //  Construct the URL to the new uploaded file
            $image_url = env('AWS_URL').$image_file_name;
        }
    }

    return $image_url;
})->name('sendfile-store');

/*
 *  END TESTS
 */

/*
 *  WEBSITE ROUTES
 */
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
    Route::get('/activate', 'AccountActivation@activate')->name('activate-account');
    Route::post('/resend', 'AccountActivation@resend')->name('activate-resend');
});

Route::get('/overview', function () {
    return view('dashboard.pages.overview');
})->name('overview')->middleware('auth');

Route::group(['prefix' => 'profiles',  'middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index')->name('profiles');
    Route::post('/', 'UserController@store')->name('profile-store');
    Route::get('/{profile_id}', 'UserController@show')->name('profile-show');
    Route::put('/{profile_id}', 'UserController@update')->name('profile-update');
    Route::get('/{profile_id}/edit', 'UserController@edit')->name('profile-edit');
    Route::delete('/{profile_id}/docs/{doc_id}', 'UserController@deleteDocument')->name('profile-doc-delete');
});

/*  JOBCARDS    list, create, show, edit, save, delete */
Route::group(['prefix' => 'jobcards',  'middleware' => 'auth'], function () {
    Route::get('/', 'JobcardController@index')->name('jobcards');
    Route::post('/', 'JobcardController@store')->name('jobcard-store');
    Route::get('/create', 'JobcardController@create')->name('jobcard-create');
    Route::get('/{jobcard_id}', 'JobcardController@show')->name('jobcard-show');
    Route::put('/{jobcard_id}', 'JobcardController@update')->name('jobcard-update');
    Route::put('/{jobcard_id}/edit', 'JobcardController@edit')->name('jobcard-edit');
    Route::delete('/{jobcard_id}', 'JobcardController@delete')->name('jobcard-delete');
});
