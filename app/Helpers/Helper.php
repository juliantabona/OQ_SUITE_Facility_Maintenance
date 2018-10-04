<?php
/*  Checks if the client already exists in the company directory
*   If yes, return true, otherwise false
*/
function oq_checkClientExists($company_name)
{
    $result = Auth::user()->companyBranch->company->clients
                    ->where('name', $company_name)->first();

    return (!empty($result)) ? true : false;
}

/*  Checks if the contractor already exists in the company directory
*   If yes, return true, otherwise false
*/
function oq_checkContractorExists($company_name)
{
    $result = Auth::user()->companyBranch->company->contractors
                    ->where('name', $company_name)->first();

    return (!empty($result)) ? true : false;
}

/*  Returns back with the old inputs and custom errors
 *  Error Format ['field_name', 'Associated Error Message']
 *  e.g) ['new_company_name' => 'A company with that name already exists.']
 */
function oq_backWithCustomErrors($errors)
{
    // Return error with old input fields
    return Redirect::back()->withInput()->withErrors($errors);
}

/*  Creates a flash notification to display to the user`
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
 */
function oq_saveActivity($model, $type, $user)
{
    if ($model != null) {
        $model_1 = is_array($model) ? $model[0] : $model;
        $model_2 = is_array($model) ? $model[1] : $model;

        $details = [
            '\''.strtolower(snake_case(class_basename($model_2))).'\'' => $model_2,    //  'document' => [Document Object]
        ];

        $model_1->recentActivities()->create([
            'type' => $type,
            'detail' => $details,
            'who_created_id' => $user->id,
            'company_branch_id' => $user->company_branch_id,
        ]);
    } else {
        \App\RecentActivity::create([
            'type' => $type,
            'detail' => '',
            'who_created_id' => $user->id,
            'company_branch_id' => $user->company_branch_id,
        ]);
    }
}

function oq_saveDocument($model, $document, $location, $type)
{
    //  Get the document
    $doc_file = $document;

    //  Store the document file to Amazon s3 and retrieve the new document name
    $doc_file_name = Storage::disk('s3')->putFile($location, $doc_file, 'public');

    if ($doc_file_name) {
        //  Construct the URL to the new uploaded file
        $doc_url = env('AWS_URL').$doc_file_name;

        //  Record the uploaded doc
        $document = $model->documents()->create([
                        'type' => $type,                                  //  Used to identify from other documents
                        'name' => $doc_file->getClientOriginalName(),     //  e.g) aircon picture
                        'mime' => getimagesize($doc_file)['mime'],        //  e.g) "mime": "image/jpeg"
                        'size' => $doc_file->getClientSize(),             //  e.g) 101936
                        'url' => $doc_url,
                        'who_created_id' => Auth::user()->id,
                    ]);

        if ($document) {
            return $document;
        }
    }

    return false;
}

function oq_getFile($file)
{
    //  return the file if it exists, otherwise return nothing
    return !empty($file) ? $file[array_keys($file)[0]] : [];
}
