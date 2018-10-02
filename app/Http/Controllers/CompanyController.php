<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Session;
use Storage;
use Redirect;
use Validator;
use App\Jobcard;
use App\Company;
use App\CompanyBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        //  If the company has a logo
        if ($request->hasFile('new_company_logo')) {
            //  Lets get the logo file
            $logoFile = $request->only('new_company_logo')['new_company_logo'];
        } else {
            //  Otherwise set the logo file and URL to nothing
            $logoFile = [];
            $logo_url = null;
        }

        //  If we are creating a client
        if ($request->input('new_company_type') == 'client') {
            $CompanyAlreadyExists = Auth::user()->companyBranch->company->with('clients')
                                            ->where('name', $request->input('new_company_name'))->first();
        } elseif ($request->input('new_company_type') == 'contractor') {
            $CompanyAlreadyExists = Auth::user()->companyBranch->company->with('contractors')
                                            ->where('name', $request->input('new_company_name'))->first();
        }

        //  If company already exists
        if ($CompanyAlreadyExists) {
            // Return error with old input fields
            return Redirect::back()
                            ->withInput()
                            ->withErrors(['new_company_name' => 'A '.$request->input('new_company_type').' with the name "'.$request->input('new_company_name').'" already exists. Try searching for it instead.']);
        }

        // Add all uploads for validation
        $fileArray = array_merge(array('new_company_logo' => $logoFile), $request->all());

        // Rules for form data
        $rules = array(
            //General Validation

            'new_company_name' => 'required',
            'new_company_phone_ext' => 'max:3',
            'new_company_phone_num' => 'max:13',
        );

        //  If we have the new company logo then validate it
        if ($request->hasFile('new_company_logo')) {
            $rules = array_merge($rules, [
                    // Rules for logo image data
                    'new_company_logo' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000Kb/2Mb
                ]
            );
        }

        //  Customized error messages
        $messages = [
            /*  General Validation
             *
             *  Simple and straight forward form validation
             */
            'new_company_name.required' => 'Enter company name',
            'new_company_name.max' => 'Company name cannot be more than 255 characters',
            'new_company_name.min' => 'Company name must be atleast 3 characters',
            'new_company_email.unique' => 'This company email is already being used',
            'new_company_phone_ext.max' => 'Company phone number extension cannot be more than 3 characters',
            'new_company_phone_num.max' => 'Company phone number cannot be more than 13 characters',

            /*  Logo validation
             *
             *  We only except the following formats "jpeg,jpg,png,gif"
             *  and a maximum image size of 2MB(Megabytes)
             */
            'new_company_logo.mimes' => 'Company logo must be an image format e.g) jpeg,jpg,png,gif',
            'new_company_logo.max' => 'Company logo should not be more than 2MB in size',
          ];

        // Now pass the input and rules into the validator
        $validator = Validator::make($fileArray, $rules, $messages);

        // Check to see if validation fails or passes
        if ($validator->fails()) {
            //  Notify the user that validation failed
            Session::forget('alert');
            Session::flash('alert', array('Couldn\'t create '.$request->input('new_company_type').', check your information!', 'icon-exclamation icons', 'danger'));

            return Redirect::back()->withErrors($validator)->withInput();
        }

        //  Create the new company
        $company = Company::create([
            'name' => $request->input('new_company_name'),
            'city' => $request->input('new_company_city'),
            'state_or_region' => $request->input('new_company_state_or_region'),
            'address' => $request->input('new_company_address'),
            //'phone_ext' => $request->input('new_company_phone_ext'),
            //'phone_num' => $request->input('new_company_phone_num'),
            'email' => $request->input('new_company_email'),
        ]);

        //  If the company was created successfully
        if ($company) {
            //  Record activity of a new company created
            $companyCreatedActivity = $company->recentActivities()->create([
                                        'type' => 'created',
                                        'detail' => [
                                                        'company' => $company,
                                                    ],
                                        'who_created_id' => Auth::user()->id,
                                        'company_branch_id' => Auth::user()->company_branch_id,
                                    ]);

            //  Create the branch
            $branch = CompanyBranch::create([
                'name' => 'Main',
                'company_id' => $company->id,
            ]);

            //  If company branch was created successfully
            if ($branch) {
                //  Record activity of a new branch created
                $branchCreatedActivity = $branch->recentActivities()->create([
                                            'type' => 'created',
                                            'detail' => [
                                                            'branch' => $branch,
                                                        ],
                                            'who_created_id' => Auth::user()->id,
                                            'company_branch_id' => Auth::user()->company_branch_id,
                                        ]);
            }

            //  If we have the logo and has been approved, then save it to Amazon S3 bucket
            if ($request->hasFile('new_company_logo')) {
                //  Get the logo
                $logo_file = Input::file('new_company_logo');

                //  Store the logo file to Amazon s3 and retrieve the new logo name
                $logo_file_name = Storage::disk('s3')->putFile('company_logos', $logo_file, 'public');

                //  Construct the URL to the new uploaded file
                $logo_url = env('AWS_URL').$logo_file_name;

                //  Record the uploaded logo
                $document = $company->documents()->create([
                                'type' => 'logo',                                  //  Used to identify from other documents
                                'name' => $logo_file->getClientOriginalName(),     //  e.g) aircon picture
                                'mime' => getimagesize($logo_file)['mime'],        //  e.g) "mime": "image/jpeg"
                                'size' => $logo_file->getClientSize(),             //  e.g) 101936
                                'url' => $logo_url,
                                'who_created_id' => Auth::user()->id,
                            ]);

                //  If the document was created successfully
                if ($document) {
                    //  Record activity of document created
                    $documentActivity = $document->recentActivities()->create([
                        'type' => 'logo uploaded',
                        'detail' => [
                                        'document' => $document,
                                    ],
                        'who_created_id' => Auth::user()->id,
                        'company_branch_id' => Auth::user()->company_branch_id,
                    ]);
                }
            }

            //  If we have the jobcard ID
            if (!empty($request->input('jobcard_id'))) {
                //  Get the associated jobcard
                $jobcard = Jobcard::find($request->input('jobcard_id'));

                //  If we are adding a new client
                if ($request->input('new_company_type') == 'client') {
                    //  Save the company to the jobcard as the current client
                    $jobcard->client_id = $company->id;
                    $jobcard->save();

                    //  Save the company as part of the companies client directory
                    $clientDirectory = $company->clients()->create([
                        'company_id' => $company->id,
                        'type' => $request->input('new_company_type'),
                    ]);
                } elseif ($request->input('new_company_type') == 'contractor') {
                    //  Save the company as part of the companies contractor directory
                    $clientDirectory = $company->contractors()->create([
                        'company_id' => $company->id,
                        'type' => $request->input('new_company_type'),
                    ]);

                    //  If we have the quotation and has been approved, then save it to Amazon S3 bucket
                    if ($request->hasFile('new_company_quote')) {
                        //  Get the quotation
                        $quotation_file = Input::file('new_company_quote');

                        //  Store the quotation file to Amazon s3 and retrieve the new quotation name
                        $quotation_file_name = Storage::disk('s3')->putFile('company_logos', $quotation_file, 'public');

                        //  Construct the URL to the new uploaded file
                        $quotation_url = env('AWS_URL').$quotation_file_name;

                        //  Record the uploaded quotation
                        $document = $company->documents()->create([
                                        'type' => 'quotation',                                  //  Used to identify from other documents
                                        'name' => $quotation_file->getClientOriginalName(),     //  e.g) aircon picture
                                        'mime' => getimagesize($quotation_file)['mime'],        //  e.g) "mime": "image/jpeg"
                                        'size' => $quotation_file->getClientSize(),             //  e.g) 101936
                                        'url' => $quotation_url,
                                        'who_created_id' => Auth::user()->id,
                                    ]);

                        //  If the document was created successfully
                        if ($document) {
                            //  Record activity of document created
                            $documentActivity = $document->recentActivities()->create([
                                'type' => 'quotation uploaded',
                                'detail' => [
                                                'document' => $document,
                                            ],
                                'who_created_id' => Auth::user()->id,
                                'company_branch_id' => Auth::user()->company_branch_id,
                            ]);
                        }
                    }

                    $jobcard->contractorsList()->attach([$company->id => [
                        'amount' => $request->input('new_company_total_price'),
                        'quotation_doc_id' => $document->id,
                    ]]);
                }

                $jobcardActivity = $jobcard->recentActivities()->create([
                    'type' => $request->input('new_company_type').' added',       //  "client added",
                    'detail' => [
                                    'company' => $company,
                                ],
                    'who_created_id' => Auth::user()->id,
                    'company_branch_id' => Auth::user()->company_branch_id,
                ]);
            }
        }

        //Alert update success
        $request->session()->flash('alert', array($request->input('new_company_type').' added successfully!', 'icon-check icons', 'success'));

        //  If we have the jobcard ID
        if (!empty($request->input('jobcard_id'))) {
            return redirect()->route('jobcard-show', $request->input('jobcard_id'));
        //  If we added a new client
        } elseif ($request->input('new_company_type') == 'client') {
            return redirect()->route('client-show', $company->id);
        //  If we added a new contractor
        } elseif ($request->input('new_company_type') == 'contractor') {
            return redirect()->route('contractor-show', $company->id);
        }
    }
}
