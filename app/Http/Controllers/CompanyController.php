<?php

namespace App\Http\Controllers;

use Auth;
use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('new_company_logo')) {
            $logoFile = $request->only('new_company_logo')['new_company_logo'];
        } else {
            $logoFile = [];
            $logo_url = null;
        }

        if ($request->input('new_company_type') == 'client') {
            $CompanyAlreadyExists = Auth::user()->companyBranch->company->clients()
                                            ->where('name', $request->input('new_company_name'))->first();
        } elseif ($request->input('new_company_type') == 'contractor') {
            $CompanyAlreadyExists = Auth::user()->companyBranch->company->contractors()
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

        //If we have the new company logo then validate it
        if ($request->hasFile('new_company_logo')) {
            $rules = array_merge($rules, [
                    // Rules for logo image data
                    'new_company_logo' => 'mimes:jpeg,jpg,png,gif|max:2000', // max 2000Kb/2Mb
                ]
            );
        }

        //Customized error messages
        $messages = [
            //General Validation
            'new_company_name.required' => 'Enter company name',
            'new_company_name.max' => 'Company name cannot be more than 255 characters',
            'new_company_name.min' => 'Company name must be atleast 3 characters',
            'new_company_email.unique' => 'This company email is already being used',
            'new_company_phone_ext.max' => 'Company phone number extension cannot be more than 3 characters',
            'new_company_phone_num.max' => 'Company phone number cannot be more than 13 characters',

            //Logo image Validation
            'new_company_logo.mimes' => 'Company logo must be an image format e.g) jpeg,jpg,png,gif',
            'new_company_logo.max' => 'Company logo should not be more than 2MB in size',
          ];

        // Now pass the input and rules into the validator
        $validator = Validator::make($fileArray, $rules, $messages);

        // Check to see if validation fails or passes
        if ($validator->fails()) {
            //Alert update error
            $request->session()->flash('alert', array('Couldn\'t create '.$request->input('new_company_type').', check your information!', 'icon-exclamation icons', 'danger'));

            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            //If we have the new company logo and has been approved, then save it to Amazon S3 bucket
            if ($request->hasFile('new_company_logo')) {
                $logo = Input::file('new_company_logo');

                $logo_resized = Image::make($logo->getRealPath())->resize(250, 250, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $logo_name = 'company_logos/cl_'.time().uniqid().'.'.$logo->guessClientExtension();

                Storage::disk('s3')->put($logo_name, $logo_resized->stream()->detach(), 'public');

                $logo_url = env('AWS_URL').$logo_name;
            }
        }

        //Create the new company
        $company = Company::create([
            'name' => $request->input('new_company_name'),
            'city' => $request->input('new_company_city'),
            'state_or_region' => $request->input('new_company_state_or_region'),
            'address' => $request->input('new_company_address'),
            'logo_url' => $logo_url,
            'phone_ext' => $request->input('new_company_phone_ext'),
            'phone_num' => $request->input('new_company_phone_num'),
            'email' => $request->input('new_company_email'),
            'created_by' => Auth::id(),
        ]);

        //If the company was created successfully
        if ($company) {
            $companyActivity = Auth::user()->companyBranch->recentActivities()->create([
                'activity' => [
                                'type' => 'created',
                                'company' => $company,
                            ],
                'created_by' => Auth::id(),
                'company_branch_id' => Auth::user()->companyBranch,
            ]);

            //  If we have the jobcard ID
            if (!empty($request->input('jobcard_id'))) {
                //Get the associated jobcard
                $jobcard = Jobcard::find($request->input('jobcard_id'));
                //  If we are adding a new client
                if ($request->input('new_company_type') == 'client') {
                    //  Save the company to the jobcard as the current client
                    $jobcard->client_id = $company->id;
                    $jobcard->save();
                    //  Save the company as part of the companies client directory
                    $jobcard->owningBranch->company->clients()->attach([$company->id => ['created_by' => Auth::id()]]);

                    $jobcardActivity = $jobcard->recentActivities()->create([
                        'activity' => [
                                        'type' => 'client_added',
                                        'company' => $company,
                                    ],
                        'created_by' => Auth::id(),
                        'company_branch_id' => Auth::user()->companyBranch,
                    ]);
                } elseif ($request->input('new_company_type') == 'contractor') {
                    //  Save the company as part of the companies contractor directory
                    $jobcard->owningBranch->company->contractors()->attach([$company->id => ['created_by' => Auth::id()]]);
                    //  Save the contractory for this jobcard list of potential contractors

                    //If we have the quotation, then save it to Amazon S3 bucket
                    if ($request->hasFile('new_company_quote')) {
                        $doc_file = Input::file('new_company_quote');

                        //  Store the file to Amazon s3 and retrieve the new file name
                        $doc_file_name = Storage::disk('s3')->putFile('jobcard_quotations', $doc_file, 'public');

                        //  Construct the URL to the new uploaded file
                        $doc_file_url = env('AWS_URL').$doc_file_name;
                    } else {
                        $doc_file_url = null;
                    }

                    $jobcard->contractorsList()->attach([$company->id => [
                        'amount' => $request->input('new_company_total_price'),
                        'quotation_doc_url' => $doc_file_url,
                        'created_by' => Auth::id(),
                    ]]);

                    $jobcardActivity = $jobcard->recentActivities()->create([
                        'activity' => [
                                        'type' => 'contractor_added',
                                        'company' => $company,
                                    ],
                        'created_by' => Auth::id(),
                        'company_branch_id' => Auth::user()->companyBranch,
                    ]);
                }
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
