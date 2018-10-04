<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use Image;
use Session;
use Storage;
use Redirect;
use Validator;
use App\View;
use App\Jobcard;
use App\Priority;
use App\Company;
use App\CompanyBranch;
use Illuminate\Http\Request;
use App\ProcessFormAllocation;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

class JobcardController extends Controller
{
    public function index()
    {
        //  Get branch related jobcards
        $jobcards = Auth::user()->companyBranch->jobcards()->paginate(10);

        return view('dashboard.pages.jobcard.index', compact('jobcards'));
    }

    public function create()
    {
        //  Show the jobcard creation page
        return view('dashboard.pages.jobcard.create');
    }

    public function show($jobcard_id)
    {
        /*  Get the jobcard along with its relations including
         *  the jobcard client, contractors, docuemtns and recent activity which
         *  holds information about all views, downloads, authourizations, assigns,
         *  updates, deletes, emails sent, and requests.
         */

        $jobcard = Jobcard::with(array(
                                'documents',
                                'recentActivities',
                                'client',
                            ))->where('id', $jobcard_id)
                            ->first();

        if ($jobcard->client) {
            $contacts = $jobcard->client->contactDirectory()->paginate(5, ['*'], 'contacts');
        } else {
            $contacts = null;
        }

        $contractorsList = $jobcard->contractorsList()->paginate(5, ['*'], 'contractors');

        //  If we have a jobcard
        if ($jobcard) {
            /*  Calculate how many days until deadline
            *  Get the deadline time in seconds then
            *  Minus current time in seconds and
            *  Divide by 24hrs for days left till deadline
            */
            $deadline = round((strtotime($jobcard->end_date)
                            - strtotime(\Carbon\Carbon::now()->toDateTimeString()))
                            / (60 * 60 * 24));

            /*  Lets go through the last viewing activity and add a viewing status if necessary
            */
            $recentViews = $jobcard->recentActivities->filter(function ($activity) {
                //  If this is a viewing activity
                if ($activity->type == 'viewing') {
                    //  And the view belongs to the current authenticated user
                    if ($activity->detail['viewer'] == Auth::id()) {
                        return $activity;
                    }
                }
            });

            if ($recentViews->count()) {
                collect([$recentViews->first()])->map(function ($activity) use ($jobcard) {
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
                        $jobcardViewedActivity = $jobcard->recentActivities()->create([
                                    'type' => 'viewing',
                                    'detail' => [
                                                    'viewer' => Auth::id(),
                                                ],
                                    'who_created_id' => Auth::id(),
                                    'company_branch_id' => Auth::user()->company_branch_id,
                                ]);
                    }
                });
            } else {
                //  Record activity of a new view
                $jobcardViewedActivity = $jobcard->recentActivities()->create([
                    'type' => 'viewing',
                    'detail' => [
                                    'viewer' => Auth::id(),
                                ],
                    'who_created_id' => Auth::id(),
                    'company_branch_id' => Auth::user()->company_branch_id,
                ]);
            }

            $processForm = \App\ProcessForm::where('company_id', Auth::user()->companyBranch->company->id)
                            ->where('type', 'jobcard')
                            ->where('selected', 1)
                            ->first();

            return view('dashboard.pages.jobcard.show', compact('jobcard', 'contractorsList', '$contacts', 'deadline', 'processForm'));
        } else {
            return view('dashboard.pages.jobcard.no_jobcard');
        }
    }

    public function edit()
    {
    }

    public function store(Request $request)
    {
        //  If the user uploaded an image
        if ($request->hasFile('image')) {
            //  Lets get the image file
            $imageFile = $request->only('image')['image'];
        } else {
            //  Otherwise set the image file and URL to nothing
            $imageFile = [];
            $image_url = null;
        }

        /*  Lets get the priority, cost centers, categories and branches
         *  for this new jobcard. At this point we don't know if the user
         *  is using stored entries from the database or creating new
         *  values. So for now we will store these values in the
         *  following variables for later checking
         */
        $priority_raw = $request->input('priority');
        $cost_center_raw = $request->input('cost_center');
        $category_raw = $request->input('category');
        $branch_raw = $request->input('branch');

        /*  Lets check if the priority, cost centers, categories and branches
         *  values are custom values.This means they were created using a modal.
         *  values created using a modal contain "_&_" as a separator.
         *  Since strpos may return TRUE (substring present), then we
         *  can check and then extract those values.
         */
        if (strpos($priority_raw, '_&_') !== false) {
            //  Extract the new priority level and replace the current request value
            $priority = explode('_&_', $priority_raw);
            $request->merge(['priority' => $priority[0]]);
        }

        if (strpos($cost_center_raw, '_&_') !== false) {
            //  Extract the cost center and replace the current request value
            $cost_center = explode('_&_', $cost_center_raw);
            $request->merge(['cost_center' => $cost_center[0]]);
        }

        if (strpos($category_raw, '_&_') !== false) {
            //  Extract the new category and replace the current request value
            $category = explode('_&_', $category_raw);
            $request->merge(['category' => $category[0]]);
        }

        if (strpos($branch_raw, '_&_') !== false) {
            //  Extract the new company branch and replace the current request value
            $branch = explode('_&_', $branch_raw);
            $request->merge(['branch' => $branch[0]]);
        }

        // Add all uploads for validation
        $fileArray = array_merge(array('image' => $imageFile), $request->all());

        // Rules for form data
        $rules = array(
            /*  General Validation
             *
             *  Simple and straight forward form validation
             */
            'title' => 'required|max:255|min:3',
            'description' => 'required|max:255|min:3',
            'start_date' => 'date_format:"Y-m-d"|required',
            'end_date' => 'date_format:"Y-m-d"|required|after:today',

            /*  Advanced Validation
             *
             *  Note that when creating new priorities, cost centers, categories and branches,
             *  we want to make sure we do not make any repeated values since they will confuse
             *  users e.g) having priority Low, Medium, Medium, High. The "medium" values is duplicated
             *  and undesirable outcome. To prevent this we must first check and confirm that for the
             *  current company the user does not already have records using the same names.
             */

            //  The priority (name & company_id) must be unique per row
            'priority' => 'required|unique:priorities,name,null,who_created_id,priority_id,'.Auth::user()->companyBranch->company->id.',priority_type,company',
            //  The cost center (name & company_id) must be unique per row
            'cost_center' => 'required|unique:costcenters,name,null,who_created_id,costcenter_id,'.Auth::user()->companyBranch->company->id.',costcenter_type,company',
            //  The category (name & company_id) must be unique per row
            'category' => 'required|unique:categories,name,null,who_created_id,category_id,'.Auth::user()->companyBranch->company->id.',category_type,company',
            //  The branch (name & company_id) must be unique per row
            'branch' => 'required|unique:company_branches,destination,null,who_created_id,company_id,'.Auth::user()->companyBranch->company->id,
        );

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
            //  Form field related
            'title.required' => 'Enter your title',
            'title.max' => 'Title name cannot be more than 255 characters',
            'title.min' => 'Title name must be atleast 3 characters',
            'description.required' => 'Enter your description',
            'description.max' => 'Description cannot be more than 255 characters',
            'description.min' => 'Description must be atleast 3 characters',
            'start_date.required' => 'Enter job start date',
            'start_date.date' => 'Enter a valid start date',
            'end_date.required' => 'Enter job end date',
            'end_date.date' => 'Enter a valid end date',
            'end_date.after' => 'End date must be a future date',
            'priority.required' => 'Select or create a new priority level',
            'priority.unique' => 'The new priority ('.$request->input('priority').') you tried to create already exists',
            'cost_center.required' => 'Select or create a new cost center',
            'cost_center.unique' => 'The new cost center ('.$request->input('cost_center').') you tried to create already exists',
            'category.required' => 'Select or create a new category',
            'category.unique' => 'The new category ('.$request->input('category').') you tried to create already exists',
            'branch.required' => 'Select or create a new company branch',
            'branch.unique' => 'The new company branch ('.$request->input('branch').') you tried to create already exists',

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
        }

        //  If we have a new custom priority, lets save it
        if (strpos($priority_raw, '_&_') !== false) {
            //  Save the new priority and assign it back to the request input value
            $priorityCreated = Auth::user()->companyBranch->company->priorities()->create([
                                    'name' => $priority[0],
                                    'description' => $priority[1],
                                    'color_code' => $priority[2],
                                    'who_created_id' => Auth::id(),
                                ]);
            //  Update request parameter
            $request->merge([
                    'priority' => $priorityCreated->id,
            ]);

            if ($priorityCreated) {
                //  Record activity of a new priority created
                $priorityCreatedActivity = $priorityCreated->recentActivities()->create([
                    'type' => 'created',
                    'detail' => [
                                    'priority' => $priorityCreated,
                                ],
                    'who_created_id' => Auth::id(),
                    'company_branch_id' => Auth::user()->company_branch_id,
                ]);
            }
        }

        //  If we have a new custom cost center, lets save it
        if (strpos($cost_center_raw, '_&_') !== false) {
            //  Save the new cost center and assign it back to the request input value
            $costcenterCreated = Auth::user()->companyBranch->company->costCenters()->create([
                                    'name' => $cost_center[0],
                                    'description' => $cost_center[1],
                                    'who_created_id' => Auth::id(),
                                ]);
            //  Update request parameter
            $request->merge([
                    'cost_center' => $costcenterCreated->id,
            ]);

            if ($costcenterCreated) {
                //  Record activity of a new cost center created
                $costcenterCreatedActivity = $costcenterCreated->recentActivities()->create([
                    'type' => 'created',
                    'detail' => [
                                    'costcenter' => $costcenterCreated,
                                ],
                    'who_created_id' => Auth::id(),
                    'company_branch_id' => Auth::user()->company_branch_id,
                ]);
            }
        }

        //  If we have a new custom category, lets save it
        if (strpos($category_raw, '_&_') !== false) {
            //  Save the new category and assign it back to the request input value
            $categoryCreated = Auth::user()->companyBranch->company->categories()->create([
                                    'name' => $category[0],
                                    'description' => $category[1],
                                    'who_created_id' => Auth::id(),
                                ]);
            //  Update request parameter
            $request->merge([
                    'category' => $categoryCreated->id,
            ]);

            if ($categoryCreated) {
                //  Record activity of a new category created
                $categoryCreatedActivity = $categoryCreated->recentActivities()->create([
                    'type' => 'created',
                    'detail' => [
                                    'category' => $categoryCreated,
                                ],
                    'who_created_id' => Auth::id(),
                    'company_branch_id' => Auth::user()->company_branch_id,
                ]);
            }
        }

        //  If we have a new custom company branch, lets save it
        if (strpos($branch_raw, '_&_') !== false) {
            //  Save the new branch and assign it back to the request input value
            $branchCreated = CompanyBranch::create([
                                'name' => $branch[0],
                                'company_id' => Auth::user()->companyBranch->company->id,
                                'who_created_id' => Auth::id(),
                            ]);
            //  Update request parameter
            $request->merge([
                    'branch' => $branchCreated->id,
            ]);

            if ($branchCreated) {
                //  Record activity of a new category created
                $branchCreatedActivity = $branchCreated->recentActivities()->create([
                    'type' => 'created',
                    'detail' => [
                                    'branch' => $branchCreated,
                                ],
                    'who_created_id' => Auth::id(),
                    'company_branch_id' => $branchCreated->id,
                ]);
            }
        }

        //  Create the jobcard
        $jobcard = Jobcard::create([
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
            /*  Allocate the process form for tracking status
            *

                $process = $jobcard->processInstructions()->create([
                    'process_form' => Auth::user()->companyBranch->company->processForms()->where('selected', 1)->first()->instructions,
                ]);

             */

            //  Record activity of jobcard created
            $jobcardCreatedActivity = $jobcard->recentActivities()->create([
                'type' => 'created',
                'detail' => [
                                'jobcard' => $jobcard,
                            ],
                'who_created_id' => Auth::id(),
                'company_branch_id' => Auth::user()->company_branch_id,
            ]);

            //  If we have the image and has been approved, then save it to Amazon S3 bucket
            if ($request->hasFile('image')) {
                //  Get the image
                $image_file = Input::file('image');

                //  Store the image file to Amazon s3 and retrieve the new image name
                $image_file_name = Storage::disk('s3')->putFile('jobcard_images', $image_file, 'public');

                //  Construct the URL to the new uploaded file
                $image_url = env('AWS_URL').$image_file_name;

                //  Record the uploaded file
                $document = $jobcard->documents()->create([
                                'type' => 'samples',                                //  Used to identify from other documents
                                'name' => $image_file->getClientOriginalName(),     //  e.g) aircon picture
                                'mime' => getimagesize($image_file)['mime'],        //  e.g) "mime": "image/jpeg"
                                'size' => $image_file->getClientSize(),             //  e.g) 101936
                                'url' => $image_url,
                                'who_created_id' => Auth::user()->id,
                            ]);

                //  If the document was created successfully
                if ($document) {
                    //  Record activity of document created
                    $documentActivity = $document->recentActivities()->create([
                        'type' => 'created',
                        'detail' => [
                                        'document' => $document,
                                    ],
                        'who_created_id' => Auth::id(),
                        'company_branch_id' => Auth::user()->company_branch_id,
                    ]);
                }
            }

            //  Record activity of creator as the first viewer
            $jobcardViewedActivity = $jobcard->recentActivities()->create([
                'type' => 'viewing',
                'detail' => [
                                'viewer' => Auth::id(),
                            ],
                'who_created_id' => Auth::id(),
                'company_branch_id' => Auth::user()->company_branch_id,
            ]);
        }

        //  Notify the user that the jobcard was created successfully
        Session::forget('alert');
        Session::flash('alert', array('Jobcard created successfully!', 'icon-check icons', 'success'));

        return redirect()->route('jobcard-show', $jobcard->id);
    }

    public function update(Request $request, $user_id)
    {
    }

    public function delete()
    {
    }

    public function downloadPdf($jobcard_id)
    {
        $jobcard = Jobcard::find($jobcard_id);
        $pdf = PDF::loadView('pdf_file', array('jobcard' => $jobcard));

        return $pdf->stream('jobcard_'.$jobcard_id.'.pdf');

        return view('pdf_file', compact('jobcard'));
    }

    public function removeClient(Request $request, $jobcard_id, $client_id)
    {
        $jobcard = Jobcard::find($jobcard_id);
        $company = Company::find($client_id);

        if ($jobcard) {
            $jobcard->client_id = null;
            $jobcard->save();
            //Alert update success
            $request->session()->flash('alert', array('Client removed successfully!', 'icon-trash icons', 'success'));

            $jobcardActivity = $jobcard->recentActivities()->create([
                'activity' => [
                    'type' => 'client_removed',
                    'company' => $company,
                ],
                'who_created_id' => Auth::id(),
                'company_branch_id' => Auth::user()->company_branch_id,
            ]);
        }

        return redirect()->route('jobcard-show', [$jobcard_id]);
    }

    public function selectContractor(Request $request, $jobcard_id, $contractor_id)
    {
        $jobcard = Jobcard::find($jobcard_id);
        $company = Company::find($contractor_id);

        if ($request->input('selected_contractor') == 'on') {
            $value = $contractor_id;
        } else {
            $value = null;
        }

        $updated = $jobcard->update([
            'select_contractor_id' => $value,
        ]);

        if ($updated) {
            $jobcardActivity = $jobcard->recentActivities()->create([
                'activity' => [
                                'type' => 'contractor_selected',
                                'jobcard' => $jobcard,
                                'company' => $company,
                            ],
                'who_created_id' => Auth::id(),
                'company_branch_id' => Auth::user()->company_branch_id,
            ]);

            //Alert update success
            $request->session()->flash('alert', array('Changes saved successfully!', 'icon-check icons', 'success'));
        } else {
            //Alert update success
            $request->session()->flash('alert', array('Something went wrong updating. Try again', 'icon-check icons', 'danger'));
        }

        return redirect()->route('jobcard-show', [$jobcard_id]);
    }

    public function removeContractor(Request $request, $jobcard_id, $contractor_id, $pivot_id)
    {
        $jobcard = Jobcard::find($jobcard_id);
        $company = Company::find($contractor_id);

        if ($jobcard) {
            $deleted = DB::table('jobcard_contractors')->where('id', $pivot_id)->delete();
            //Alert update success
            $request->session()->flash('alert', array('Contractor removed successfully!', 'icon-trash icons', 'success'));

            $jobcardActivity = $jobcard->recentActivities()->create([
                'activity' => [
                    'type' => 'contractor_removed',
                    'company' => $company,
                ],
                'who_created_id' => Auth::id(),
                'company_branch_id' => Auth::user()->company_branch_id,
            ]);
        }

        return redirect()->route('jobcard-show', [$jobcard_id]);
    }

    public function updateProgress(Request $request, $jobcard_id)
    {
        $updatedProcessInstructions = (array) json_decode(rawurldecode($request->input('updated_process_instructions')), true);
        $processFormData = $updatedProcessInstructions[0]['process_form'];
        $pluginComponents = $updatedProcessInstructions[0]['process_form'][$request->input('plugin_step')]['plugin'];

        return $request->all();

        //  Foreach plugin component in the process step being updated
        foreach ($pluginComponents as $position => $field) {
            //  Check if the component field is of an attachable file
            //  Component must also be updated with a new file to replace the old one
            if ($field['tag'] == 'attach' && $field['update']['done']) {
                //  If the component has the specific file
                if ($request->hasFile('upload_'.$field['id'])) {
                    //  Get the file
                    $doc_file = Input::file('upload_'.$field['id']);

                    //  Store the file to Amazon s3 and retrieve the new file name
                    $doc_file_name = Storage::disk('s3')->putFile('process_status_docs', $doc_file, 'public');

                    //  Construct the URL to the new uploaded file
                    $doc_file_name = env('AWS_URL').$doc_file_name;

                    //  Store the new file URL to the updated process instructions at the specific component response area
                    $updatedProcessInstructions[0]['process_form'][$request->input('plugin_step')]['plugin'][$position]['update']['response'] = $doc_file_name;
                }
            }
        }

        //  Change the form step status from not updated[false ]to updated[true]
        $updatedProcessInstructions[0]['process_form'][$request->input('plugin_step')]['updated'] = true;

        //  Update the new instructions in the database
        $jobcardProcessInstructions = ProcessFormAllocation::find($updatedProcessInstructions[0]['id']);

        $update = $jobcardProcessInstructions->update([
            'process_form' => $updatedProcessInstructions[0]['process_form'],
        ]);

        //  Record new activity
        $jobcard = Jobcard::find($jobcard_id);
        $jobcardActivity = $jobcard->recentActivities()->create([
            'activity' => [
                'type' => 'status_changed',
                'old_status' => $request->input('old_step_name'),
                'new_status' => $request->input('new_step_name'),
            ],
            'who_created_id' => Auth::id(),
            'company_branch_id' => Auth::user()->company_branch_id,
        ]);

        //Return to the jobcard

        return redirect()->route('jobcard-show', $jobcard_id);
    }

    public function showStepJobcard($step_id)
    {
        $jobcards = Jobcard::where('step_id', $step_id)->paginate(10);

        return view('jobcard/index', compact('jobcards'));
    }
}
