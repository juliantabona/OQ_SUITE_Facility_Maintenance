<?php

namespace App\Http\Controllers;

use Auth;
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
        /*  If we are creating a client. Lets ensure that we do not already
        *   have another company saved with the same name
        */
        if ($request->input('new_company_type') == 'client') {
            $CompanyAlreadyExists = oq_checkClientExists($request->input('new_company_name'));

        /*  If we are creating a contractor. Lets ensure that we do not already
        *   have another company saved with the same name
        */
        } elseif ($request->input('new_company_type') == 'contractor') {
            $CompanyAlreadyExists = oq_checkContractorExists($request->input('new_company_name'));
        }

        /*  If the company already exists with the same name
        *   We must go back with a custom error nofifying the user
        */
        if ($CompanyAlreadyExists) {
            oq_backWithCustomErrors(['new_company_name' => 'A '.$request->input('new_company_type').' with the name "'.$request->input('new_company_name').'" already exists. Try searching for it instead.']);
        }

        //  Lets get the logo file if it exists
        $logoFile = oq_getFile($request->only('new_company_logo'));

        //  Lets get the quotation file if it exists
        $quoteFile = oq_getFile($request->only('new_company_quote'));

        //  Add all uploads for validation
        $fileArray = array_merge(array(
                            'new_company_logo' => $logoFile,
                            'new_company_quote' => $quoteFile, ),
                    $request->all());

        //  Get the rules for validating a company on creation
        $rules = oq_company_create_v_rules();

        //  Customized error messages
        $messages = oq_company_create_v_msgs();

        // Now pass the input and rules into the validator
        $validator = Validator::make($fileArray, $rules, $messages);

        // Check to see if validation fails or passes
        if ($validator->fails()) {
            //  Notify the user that validation failed
            oq_notify('Couldn\'t create '.$request->input('new_company_type').', check your information!', 'danger');
            //  Return back with errors and old inputs
            return Redirect::back()->withErrors($validator)->withInput();
        }

        //  Create the new company
        $company = Company::create([
            'name' => $request->input('new_company_name'),
            'city' => $request->input('new_company_city'),
            'state_or_region' => $request->input('new_company_state_or_region'),
            'address' => $request->input('new_company_address'),
            'email' => $request->input('new_company_email'),
        ]);

        //  Save the company as part of the companies client/contractor directory
        $company->companyDirectory()->attach(Auth::user()->companyBranch->company->id, ['type' => $request->input('new_company_type')]);

        //  If the company was created successfully
        if ($company) {
            //  Record activity of a new company created
            $companyCreatedActivity = oq_saveActivity($company, 'created', Auth::user());

            //  Create the branch
            $branch = CompanyBranch::create([
                'name' => 'Main',
                'company_id' => $company->id,
            ]);

            //  If company branch was created successfully
            if ($branch) {
                //  Record activity of a new branch created
                $branchCreatedActivity = oq_saveActivity($branch, 'created', Auth::user());
            }

            //  If we have the logo and has been approved, then save it to Amazon S3 bucket
            if ($request->hasFile('new_company_logo')) {
                $document = oq_saveDocument($company, Input::file('new_company_logo'), 'company_logos', 'logo');
                //  If the logo was uploaded successfully
                if ($document) {
                    //  Record activity of success document created
                    $documentUploadedActivity = oq_saveActivity($document, 'logo uploaded', Auth::user());
                } else {
                    //  Record activity of failed document created
                    $documentUploadedActivity = oq_saveActivity(null, 'logo failed', Auth::user());
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
                } elseif ($request->input('new_company_type') == 'contractor') {
                    //  If we have the quotation and has been approved, then save it to Amazon S3 bucket
                    if ($request->hasFile('new_company_quote')) {
                        $document = oq_saveDocument($company, Input::file('new_company_quote'), 'company_quotes', 'quotation');
                        //  If the logo was uploaded successfully
                        if ($document) {
                            //  Record activity of success document created
                            $documentUploadedActivity = oq_saveActivity($document, 'quotation uploaded', Auth::user());
                        } else {
                            //  Record activity of failed document created
                            $documentUploadedActivity = oq_saveActivity(null, 'quotation failed', Auth::user());
                        }
                    }

                    //  Add the company as part of the jobcards list of potential contractors
                    $jobcard->contractorsList()->attach([$company->id => [
                        'amount' => $request->input('new_company_total_price'),
                        'quotation_doc_id' => $document->id,
                    ]]);
                }

                //  Record activity of success client client/contractor added
                $jobcardActivity = oq_saveActivity([$jobcard, $company], $request->input('new_company_type').' added', Auth::user());
            }
        }

        //  Notify the user that the update was successful
        oq_notify($request->input('new_company_type').' added successfully!', 'success');

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
