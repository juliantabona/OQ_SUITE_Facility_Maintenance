<?php

use Illuminate\Support\Facades\Input;

/**************************************************************
***************************************************************
***************************************************************
    COMPANY CONTROLLER HELPER FUNCTIONS
***************************************************************
***************************************************************
**************************************************************/

/*  Checks if the client already exists in the company directory
 *  by matching existing ID or Name. If exists, return true,
 *  otherwise false.
 *
 * @param $user - The user we need to get the company directory
 * @param $company_name_or_id - The company ID thats being checked for existence
 *
 * @return true  for company exists!
 * @return false for company does not exist
 */
function oq_checkClientExists($user, $company_name_or_id)
{
    $result = $user->companyBranch->company->clients()
                    ->where('companies.id', $company_name_or_id)
                    ->orWhere('name', $company_name_or_id)->first();

    return (!empty($result)) ? true : false;
}

/*  Checks if the contractor already exists in the company directory
 *  by matching existing ID or Name. If exists, return true,
 *  otherwise false.
 *
 * @param $user - The user we need to get the company directory
 * @param $company_name_or_id - The company ID thats being checked for existence
 *
 * @return true  for company exists!
 * @return false for company does not exist
 */
function oq_checkContractorExists($user, $company_name_or_id)
{
    $result = $user->companyBranch->company->contractors()
                    ->where('companies.id', $company_name_or_id)
                    ->orWhere('name', $company_name_or_id)->first();

    return (!empty($result)) ? true : false;
}

/*  Returns back with the old inputs and custom errors
 *  Error Format ['field_name', 'Associated Error Message']
 *  e.g) ['new_company_name' => 'A company with that name already exists.']
 *
 * @param $errors - The custom error
 *
 * @return Redirect
 */
function oq_backWithCustomErrors($errors)
{
    // Return error with old input fields
    return Redirect::back()->withInput()->withErrors($errors);
}

/*  Creates a flash session for alerting the user
 *
 * @param $msg - The custom messsage
 * @param $type - The type of alert
 *
 * @return void
 */
function oq_notify($msg, $type = 'default')
{
    if ($type == 'success') {
        $icon = 'icon-check icons';
    } elseif ($type == 'danger') {
        $icon = 'icon-exclamation icons';
    } elseif ($type == 'warning') {
        $icon = 'icon-exclamation icons';
    } else {
        $icon = '';
    }

    Session::forget('alert');
    Session::flash('alert', array($msg, $icon, $type));
}

/*  Creates a recent activity for the associated model
 *  $model is the associated model that can generate recentActivity() such as User, Company, e.t.c.
 *  $type is the type of activity such as 'creating', 'updating', 'deleting', 'uploading', e.t.c.
 *  $user is the reference to the actual user creating the activity
 *
 *  @param $model - Is the associated model that can generate recentActivity() such as User, Company, e.t.c.
 *  @param $type - Is the type of activity such as 'creating', 'updating', 'deleting', 'uploading', e.t.c.
 *  @param $user - Is the reference to the actual user creating the activity
 *
 *  @return true  for update success
 *  @return false for update failed
 */
function oq_saveActivity($model, $type, $user, $customDetails = null)
{
    if ($model != null) {
        $model_1 = is_array($model) ? $model[0] : $model;
        $model_2 = is_array($model) ? $model[1] : $model;

        if ($customDetails == null) {
            $details = [
                '\''.strtolower(snake_case(class_basename($model_2))).'\'' => $model_2,    //  'document' => [Document Object]
            ];
        } else {
            $details = $customDetails;
        }

        $update = $model_1->recentActivities()->create([
            'type' => $type,
            'detail' => $details,
            'who_created_id' => $user->id,
            'company_branch_id' => $user->company_branch_id,
        ]);
    } else {
        $update = \App\RecentActivity::create([
            'type' => $type,
            'detail' => '',
            'who_created_id' => $user->id,
            'company_branch_id' => $user->company_branch_id,
        ]);
    }

    if ($update) {
        return true;
    }

    return false;
}

/*  Save the document to Amazon S3 and update the database with a reference to the file
 *  as well as record the document properties such as [size, mime, type, e.t.c]
 *
 *  @param $model - Is the model that the document will belong to e.g) User, Jobcard, e.t.c
 *  @param $document - The actual file we want to save to Amazon s3
 *  @param $location - The location path we want to save the file
 *  @param $type - The type helps to identify the document e.g) logo, quotation, e.t.c
 *  @param $user - The user uploading the document
 *
 *  @return $document  for upload success
 *  @return false for upload failed
 */
function oq_saveDocument($model, $document, $location, $type, $user)
{
    //  Get the document
    $doc_file = $document;

    //  Store the document file to Amazon s3 and retrieve the new document name
    $doc_file_name = Storage::disk('s3')->putFile($location, $doc_file, 'public');

    if (!empty($doc_file_name)) {
        //  Construct the URL to the new uploaded file
        $doc_url = env('AWS_URL').$doc_file_name;

        //  Record the uploaded doc
        $document = $model->documents()->create([
                        'type' => $type,                                  //  Used to identify from other documents
                        'name' => $doc_file->getClientOriginalName(),     //  e.g) aircon picture
                        'mime' => getimagesize($doc_file)['mime'],        //  e.g) "mime": "image/jpeg"
                        'size' => $doc_file->getClientSize(),             //  e.g) 101936
                        'url' => $doc_url,
                        'who_created_id' => $user->id,
                    ]);

        //  If the document was uploaded successfully
        if ($document) {
            //  Record activity of success document upload
            $documentUploadedActivity = oq_saveActivity($document, $type.' uploaded', $user);
            //  Return the document instance
            return $document;
        } else {
            //  Record activity of failed document upload
            $documentUploadedActivity = oq_saveActivity(null, $type.' failed', $user);
        }
    }

    return false;
}

/**
 *  Gets the file from the request.
 *
 * @param $file - Request Input of the file e.g) $request->only('new_company_logo')
 *
 * @return file for complete success, everything worked!
 */
function oq_getFile($file)
{
    //  return the file if it exists, otherwise return nothing
    return !empty($file) ? $file[array_keys($file)[0]] : [];
}

/**
 *  Create a completely new company.
 *
 * @param $request - The request with parameters for creating company
 * @param $company - The company we want to add to the directory
 * @param $user - The user running this activity
 *
 * @return $company for complete success, everything worked!
 * @return false    for complete/partial fail during execution
 */
function oq_createCompany($request, $user)
{
    //  Lets get the logo file if it exists
    $logoFile = oq_getFile($request->only('new_company_logo'));

    //  Add all uploads for validation
    $fileArray = array_merge(array('new_company_logo' => $logoFile), $request->all());

    //  Get the rules for validating a company on creation
    $rules = oq_company_create_v_rules();

    //  Customized error messages for validating a company on creation
    $messages = oq_company_create_v_msgs();

    // Now pass the input and rules into the validator
    $validator = Validator::make($fileArray, $rules, $messages);

    // Check to see if validation fails or passes
    if ($validator->fails()) {
        //  Notify the user that validation failed
        oq_notify('Couldn\'t create '.$request->input('new_company_type').', check your information!', 'danger');
        //  Return back with errors and old inputs
        return  ['failed_validation' => Redirect::back()->withErrors($validator)->withInput()];
    }

    //  Create the new company
    $company = \App\Company::create([
        'name' => $request->input('new_company_name'),
        'city' => $request->input('new_company_city'),
        'state_or_region' => $request->input('new_company_state_or_region'),
        'address' => $request->input('new_company_address'),
        'email' => $request->input('new_company_email'),
    ]);

    //  If the company was created successfully
    if ($company) {
        // re-retrieve the instance to get all of the fields in the table.
        $company = $company->fresh();

        //  Record activity of a new company created
        $companyCreatedActivity = oq_saveActivity($company, 'created', $user);

        //  Create the branch
        $branch = oq_createBranch(null, $company, Auth::user());

        //  If we have the logo and has been approved, then save it to Amazon S3 bucket
        if ($request->hasFile('new_company_logo')) {
            $document = oq_saveDocument($company, Input::file('new_company_logo'), 'company_logos', 'logo', $user);
        }

        return $company;
    }

    return false;
}

/**
 *  Add the company to company directory.
 *
 * @param $owningCompany_id - The ID of the owning company
 * @param $company - The company we want to add to the directory
 * @param $user - The user we will use to extract the owning company's directory to add the listing company
 * @param $type - The type of company being added e.g) client, contractor, e.t.c
 *
 * @return true  for complete success, everything worked!
 * @return false for complete/partial fail during execution
 */
function oq_addCompanyToDirectory($owningCompany_id, $company, $type, $user)
{
    //  Let us add the company to the owning company as type of client/contractor
    $addedToDirectory = $company->companyDirectory()->attach($owningCompany_id, ['type' => $type]);

    if ($addedToDirectory) {
        return true;
    }

    return false;
}

/**
 *  Add the company to the a jobcard as a client for that jobcard.
 *
 * @param $jobcard_id - The jobcard id
 * @param $company - The company we want to add
 * @param $user - The user running this activity
 *
 * @return true  for complete success, everything worked!
 * @return false for complete/partial fail during execution
 */
function oq_addClientToJobcard($jobcard_id, $company, $user)
{
    //  Get the associated jobca
    $jobcard = \App\Jobcard::find($jobcard_id);

    if ($jobcard) {
        //  Save the company to the jobcard as the current client
        $jobcard->client_id = $company->id;
        $jobcard->save();

        //  Record activity of success client added
        $jobcardActivity = oq_saveActivity([$jobcard, $company], 'client added', $user);

        //  If the activity was saved successfully
        if ($jobcardActivity) {
            return true;
        }
    }

    return false;
}

/**
 *  Add the company to the a jobcard as a potential contractor for that jobcard.
 *  Also save the quotation file and amount for the jobcard.
 *
 * @param $jobcard_id - The jobcard id
 * @param $company - The company we want to add
 * @param $user - The user running this activity
 * @param $quoteData - The quotation details in array format [$file, $amount]
 *
 * @return true  for complete success, everything worked!
 * @return false for complete/partial fail during execution
 */
function oq_addContractorToJobcard($jobcard_id, $company, $user, $quoteData = null)
{
    //  Get the associated jobcard
    $jobcard = \App\Jobcard::find($jobcard_id);

    if ($jobcard) {
        //  If we have the quotation and has been approved, then save it to Amazon S3 bucket
        if ($quoteData != null) {
            //  Lets get the quotation file if it exists. It can exist if this is a contrator
            $quoteFile = oq_getFile($quoteData[0]);

            //  Add all quotaiton for validation
            $fileArray = ['new_company_quote' => $quoteFile];

            //  Get the rules for validating a document on creation
            $rules = oq_document_create_v_rules();

            //  Customized error messages for validating a document on creation
            $messages = oq_document_create_v_msgs();

            // Now pass the file and rules into the validator
            $validator = Validator::make($fileArray, $rules, $messages);

            // Check to see if validation fails or passes
            if ($validator->fails()) {
                //  Notify the user that validation failed
                oq_notify('Something went wrong uploading quotation. Make sure you uploaded a proper file not exceeding upload limits!', 'danger');
                //  Return back with errors and old inputs
                return  ['failed_validation' => Redirect::back()->withErrors($validator)->withInput()];
            }

            $document = oq_saveDocument($company, $quoteFile, 'company_quotes', 'quotation', $user);
        } else {
            $document = null;
        }

        $doc_id = !empty($document) ? $document->id : null;

        //  Add the company as part of the jobcards list of potential contractors
        $jobcard->contractorsList()->attach([$company->id => [
                'amount' => $quoteData[1],
                'quotation_doc_id' => $doc_id,
            ]]);

        //  Record activity of success client client/contractor added
        $jobcardActivity = oq_saveActivity([$jobcard, $company], 'contractor added', $user);

        //  If the activity was saved successfully
        if ($jobcardActivity) {
            return true;
        }
    }

    return false;
}

/**
 *  Check if the validation has passed or failed.
 *
 * @param array $data - An array of the validation response
 *                    - ['failed_validation' => Redirect::back()->withErrors($validator)->withInput()];
 *
 * @return true  for failed validation
 * @return false for passed validation
 */
function oq_failed_validation($data)
{
    //  If the data has a failed_validation key then the validation did not pass
    if (is_array($data) && array_key_exists('failed_validation', $data)) {
        return true;
    } else {
        return false;
    }
}

/**************************************************************
***************************************************************
***************************************************************
    JOBCARD CONTROLLER HELPER FUNCTIONS
***************************************************************
***************************************************************
**************************************************************/
/**
 *  Get the pagination of the company contacts.
 *
 * @param $company - The company we want th contacts from
 *
 * @return $contacts for complete success, everything worked!
 */
function oq_paginateClientContacts($company)
{
    if (!empty($company)) {
        $contacts = $company->contactDirectory()->paginate(5, ['*'], 'contacts');
    } else {
        $contacts = null;
    }

    return $contacts;
}

/**
 *  Calculate how many days until deadline and get the deadline in days
 *  Get the deadline time in seconds then minus current time in seconds and
 *  Divide by 24hrs for days left till deadline.
 *
 * @param $model - The model we want the deadlines from
 *
 * @return true  for complete success, everything worked!
 * @return false for complete/partial fail during execution
 */
/*  Calculate how many days until deadline
*   Get the deadline time in seconds then
*   Minus current time in seconds and
*   Divide by 24hrs for days left till deadline
*/
function oq_jobcardDeadlineArray($model)
{
    if ($model->end_date != null) {
        $deadline = \Carbon\Carbon::parse($model->end_date);
        $now = \Carbon\Carbon::parse(\Carbon\Carbon::now());
        $diff = $deadline->diff($now);

        /*  The invert helps us know if the difference is positive or negative
        *  If the invert is == 1 then it is positive, meaning deadline not reached.
        *  If the invert is == 0 then it is negative, meaning deadline passed.
        */
        $invert = $diff->invert;

        if ($diff->y != 0) {
            return $diff->y == 1 ? [$diff->y, 'year', $invert] : [$diff->y, 'years', $invert];
        } elseif ($diff->m != 0) {
            return $diff->m == 1 ? [$diff->m, 'month', $invert] : [$diff->m, 'months', $invert];
        } elseif ($diff->d != 0) {
            return $diff->d == 1 ? [$diff->d, 'day', $invert] : [$diff->d, 'days', $invert];
        } elseif ($diff->h != 0) {
            return $diff->h == 1 ? [$diff->h, 'hour', $invert] : [$diff->h, 'hours', $invert];
        } elseif ($diff->i != 0) {
            return $diff->i == 1 ? [$diff->i, 'minute', $invert] : [$diff->i, 'minutes', $invert];
        } elseif ($diff->s != 0) {
            return $diff->s == 1 ? [$diff->s, 'second', $invert] : [$diff->s, 'seconds', $invert];
        }
    }

    return null;
}

function oq_jobcardDeadlineWords($deadline)
{
    /*  $deadline[0] = number e.g) 7
     *  $deadline[1] = type e.g) minutes
     *  $deadline[2] = invert e.g) 0 or 1
     * The invert helps us know if the difference is positive or negative
     *  If the invert is == 1 then it is positive, meaning deadline not reached.
     *  If the invert is == 0 then it is negative, meaning deadline passed.
     */
    if ($deadline != null) {
        if ($deadline[2] == 1) {
            return $deadline[0].' '.$deadline[1].' until deadline';
        } elseif ($deadline[2] == 0) {
            return 'Deadline passed '.$deadline[0].' '.$deadline[1].' ago';
        }
    }

    return null;
}

/**
 *  Add a new view for a given model.
 *
 * @param $model - The entity being viewed e.g) jobcard, user, e.t.c
 * @param $user - The user being recorded as a viewer
 *
 * @return $activity for complete success, everything worked!
 * @return false     for complete/partial fail during execution
 */
function oq_addView($model, $user)
{
    // re-retrieve the instance to get all of the fields in the table.
    $model = $model->fresh();

    $recentViews = $model->recentActivities->filter(function ($activity) use ($user) {
        //  If this is a viewing activity
        if ($activity->type == 'viewing') {
            //  And the view belongs to the current authenticated user
            if ($activity->detail['viewer'] == $user->id) {
                return $activity;
            }
        }
    });

    if ($recentViews->count()) {
        collect([$recentViews->first()])->map(function ($activity) use ($model, $user) {
            /*  Calculate how long ago they viewed from now
            *  Get the current time in seconds and
            *  Divide by 60 to give number of minutes since last view
            */
            $last_viewed = (strtotime(\Carbon\Carbon::now()->toDateTimeString()
                                    ) - strtotime($activity->created_at))
                                    / 60;

            // If the user last viewed this atleast 1minute ago then record their new view
            if ($last_viewed > 1) {
                //  Record activity of a new view
                return $viewedActivity = oq_saveActivity($model, 'viewing', $user, ['viewer' => $user->id]);
            }
        });
    } else {
        //  Record activity of a new view
        return $viewedActivity = oq_saveActivity($model, 'viewing', $user, ['viewer' => $user->id]);
    }

    return false;
}

function oq_getArray($data)
{
    //  If we json_decode the $data and return a valid array then we can proceed
    if (is_array(json_decode($data, true)[0])) {
        return json_decode($data, true)[0];
    }
    //  Otherwise return the original $data unchanged
    return $data;
}

function oq_createJobcard($request, $user)
{
    /*  Lets get the priority, cost centers, categories and branches
     *  for this new jobcard. At this point we don't know if the user
     *  is using stored entries from the database or creating new
     *  values. So for now we will store these values in the
     *  following variables for later checking
     */
    $priorityArray = oq_getArray($request->input('priority'));
    $cost_centerArray = oq_getArray($request->input('cost_center'));
    $categoryArray = oq_getArray($request->input('category'));
    $branchArray = oq_getArray($request->input('branch'));

    /*  Lets check if the priority, cost centers, categories and branches
     *  values are custom values.This means they were created using a modal.
     *  values created using a modal contain "_&_" as a separator.
     *  Since strpos may return TRUE (substring present), then we
     *  can check and then extract those values.
     */

    if (is_array($priorityArray)) {
        //  Extract the new priority name and replace the current request value
        $request->merge(['priority' => $priorityArray['name']]);
    }

    if (is_array($cost_centerArray)) {
        //  Extract the cost center name and replace the current request value
        $request->merge(['cost_center' => $cost_centerArray['name']]);
    }

    if (is_array($categoryArray)) {
        //  Extract the new category name and replace the current request value
        $request->merge(['category' => $categoryArray['name']]);
    }

    if (is_array($branchArray)) {
        //  Extract the new company branch name and replace the current request value
        $request->merge(['branch' => $branchArray['name']]);
    }

    //  Lets get the image file if it exists
    $imageFile = oq_getFile($request->only('image'));

    //  Add all uploads for validation
    $fileArray = array_merge(array('image' => $imageFile), $request->all());

    //  Get the rules for validating a jobcard on creation
    $rules = oq_jobcard_create_v_rules(Auth::user());

    //  Customized error messages for validating a jobcard on creation
    $messages = oq_jobcard_create_v_msgs($request);

    // Now pass the input and rules into the validator
    $validator = Validator::make($fileArray, $rules, $messages);

    // Check to see if validation fails or passes
    if ($validator->fails()) {
        //  Notify the user that validation failed
        oq_notify('Couldn\'t create jobcard, check your information!', 'danger');
        //  Return back with errors and old inputs
        return  ['failed_validation' => Redirect::back()->withErrors($validator)->withInput()];
    }

    if (is_array($priorityArray)) {
        //  Save the new priority and assign it back to the request input value
        $priorityCreated = oq_createPriority($priorityArray['name'], $priorityArray['desc'], $priorityArray['color'], Auth::user());
        //  Update request parameter with the priority id
        $request->merge(['priority' => $priorityCreated->id]);
    }

    if (is_array($cost_centerArray)) {
        //  Save the new cost center and assign it back to the request input value
        $costcenterCreated = oq_createCostCenter($cost_centerArray['name'], $cost_centerArray['desc'], Auth::user());
        //  Update request parameter with the cost center id
        $request->merge(['cost_center' => $costcenterCreated->id]);
    }

    if (is_array($categoryArray)) {
        //  Save the new priority and assign it back to the request input value
        $categoryCreated = oq_createCategory($categoryArray['name'], $categoryArray['desc'], Auth::user());
        //  Update request parameter with the category id
        $request->merge(['category' => $categoryCreated->id]);
    }

    if (is_array($branchArray)) {
        //  Save the new branch and assign it back to the request input value
        $branchCreated = oq_createBranch($branchArray['name'], Auth::user()->companyBranch->company, $user);
        //  Extract the new company branch name and replace the current request value
        $request->merge(['branch' => $branchCreated->id]);
    }

    //  Create the jobcard
    $jobcard = \App\Jobcard::create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
        'status_id' => 1,
        'priority_id' => $request->input('priority'),
        'cost_center_id' => $request->input('cost_center'),
        'company_branch_id' => $request->input('branch'),
        'category_id' => $request->input('category'),
        'who_created_id' => Auth::id(),
    ]);

    //  If the jobcard was created successfully
    if ($jobcard) {
        // re-retrieve the instance to get all of the fields in the table.
        $jobcard = $jobcard->fresh();

        //  Record activity of a jobcard created
        $jobcardCreatedActivity = oq_saveActivity($jobcard, 'created', $user);

        /*  Allocate the process form for tracking status
         *
            $process = $jobcard->processInstructions()->create([
                'process_form' => Auth::user()->companyBranch->company->processForms()->where('selected', 1)->first()->instructions,
            ]);
         */

        //  If we have the jobcard image and has been approved, then save it to Amazon S3 bucket
        if ($request->hasFile('new_jobcard_image')) {
            $document = oq_saveDocument($jobcard, Input::file('new_jobcard_image'), 'jobcard_images', 'samples', $user);
        }

        //  Notify the user that the jobcard creation was successful
        oq_notify('Jobcard created successfully!', 'success');
    } else {
        //  Record activity of a failed jobcard during creation
        $jobcardCreatedActivity = oq_saveActivity(null, 'jobcard creation failed', $user);

        //  Notify the user that the jobcard creation was unsuccessful
        oq_notify('Something went wrong creating the jobcard. Please try again', 'warning');
    }

    return $jobcard;
}

function oq_createPriority($name, $desc, $color, $user)
{
    $priorityCreated = $user->companyBranch->company->priorities()->create([
        'name' => $name,
        'description' => $desc,
        'color_code' => $color,
        'who_created_id' => $user->id,
    ]);

    //  If the document was uploaded successfully
    if ($priorityCreated) {
        //  Record activity of a new priority created
        $priorityCreatedActivity = oq_saveActivity($priorityCreated, 'created', $user);
    } else {
        //  Record activity of a failed priority during creation
        $priorityCreatedActivity = oq_saveActivity(null, 'priority creation failed', $user);
    }

    return $priorityCreated;
}

function oq_createCostCenter($name, $desc, $user)
{
    //  Save the new cost center and assign it back to the request input value
    $costcenterCreated = $user->companyBranch->company->costCenters()->create([
        'name' => $name,
        'description' => $desc,
        'who_created_id' => $user->id,
    ]);

    //  If the cost center was created successfully
    if ($costcenterCreated) {
        //  Record activity of a new cost center created
        $costcenterCreatedActivity = oq_saveActivity($costcenterCreated, 'created', $user);
    } else {
        //  Record activity of a failed cost center during creation
        $costcenterCreatedActivity = oq_saveActivity(null, 'cost center creation failed', $user);
    }

    return $costcenterCreated;
}

function oq_createCategory($name, $desc, $user)
{
    //  Save the new category and assign it back to the request input value
    $categoryCreated = $user->companyBranch->company->categories()->create([
        'name' => $name,
        'description' => $desc,
        'who_created_id' => $user->id,
    ]);

    //  If the category was created successfully
    if ($categoryCreated) {
        //  Record activity of a new category created
        $categoryCreatedActivity = oq_saveActivity($categoryCreated, 'created', $user);
    } else {
        //  Record activity of a failed category during creation
        $categoryCreatedActivity = oq_saveActivity(null, 'category creation failed', $user);
    }

    return $categoryCreated;
}

function oq_createBranch($name = null, $company, $user)
{
    //  Create the branch
    if ($name != null) {
        $branchCreated = \App\CompanyBranch::create([
            'name' => $name,
            'company_id' => $company->id,
        ]);
    } else {
        $branchCreated = \App\CompanyBranch::create([
            /*   name field will use default value from migration table   */
            'company_id' => $company->id,
        ]);
    }
    //  If the branch was created successfully
    if ($branchCreated) {
        // re-retrieve the instance to get all of the fields in the table.
        $branchCreated = $branchCreated->fresh();

        //  Record activity of a new branch created
        $branchCreatedActivity = oq_saveActivity($branchCreated, 'created', $user);
    } else {
        //  Record activity of a failed branch during creation
        $branchCreatedActivity = oq_saveActivity(null, 'branch creation failed', $user);
    }

    return $branchCreated;
}

function oq_createdBy($model)
{
    //  Create the branch
    $createdBy = $model->recentActivities->where('type', 'created')->first()->createdBy;

    return $createdBy;
}

function oq_formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
