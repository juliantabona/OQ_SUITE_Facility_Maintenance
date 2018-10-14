<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use Storage;
use Redirect;
use App\View;
use App\Jobcard;
use App\Company;
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
         *  the jobcard client, contractors, documents and recent activity which
         *  holds information about all views, downloads, authourizations, assigns,
         *  updates, deletes, emails sent, and requests.
         */

        $jobcard = Jobcard::with(array('documents', 'category', 'priority', 'costCenter', 'client',
                                       'recentActivities' => function ($query) {
                                           $query->where('type', '!=', 'viewing');
                                       }, ))->where('id', $jobcard_id)->first();

        $createdBy = oq_createdBy($jobcard);

        //  Get the pagination of the clients contacts
        $contactsList = oq_paginateClientContacts($jobcard->client);

        //  Get the pagination of the jobcard contractors
        $contractorsList = $jobcard->contractorsList()->paginate(5, ['*'], 'contractors');

        //  If we have a jobcard
        if ($jobcard) {
            //  Update the viewing status and add a recent view if necessary
            $recentViews = oq_addView($jobcard, Auth::user());

            //  Get the jobcard deadline
            $deadline = oq_jobcardDeadlineArray($jobcard);

            $processForm = \App\ProcessForm::where('company_id', Auth::user()->companyBranch->company->id)
                            ->where('type', 'jobcard')
                            ->where('selected', 1)
                            ->first();

            return view('dashboard.pages.jobcard.show', compact('jobcard', 'createdBy', 'contractorsList', '$contactsList', 'deadline', 'processForm'));
        } else {
            return view('dashboard.pages.jobcard.no_jobcard');
        }
    }

    public function edit()
    {
    }

    public function store(Request $request)
    {
        $jobcard = oq_createJobcard($request, Auth::user());

        //  If the validation did not pass
        if (oq_failed_validation($jobcard)) {
            //  Return back with the alert and failed validation rules
            return $jobcard['failed_validation'];
        }

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
