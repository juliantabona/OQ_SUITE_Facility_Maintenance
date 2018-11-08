<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        //  Get all and trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $documents = Document::withTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only trashed
        } elseif (request('onlytrashed') == 1) {
            try {
                //  Run query
                $documents = Document::onlyTrashed()->orderBy(oq_getOrder()[0], oq_getOrder()[1])->paginate(oq_getLimit());
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get all except trashed
        } else {
            try {
                //  Run query
                $documents = Document::advancedFilter();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($documents)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $documents->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($documents, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function show($document_id)
    {
        //  Get one, even if trashed
        if (request('withtrashed') == 1) {
            try {
                //  Run query
                $document = Document::withTrashed()->where('id', $document_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
            //  Get only if not trashed
        } else {
            try {
                //  Run query
                $document = Document::where('id', $document_id)->first();
            } catch (\Exception $e) {
                return oq_api_notify_error('Query Error', $e->getMessage(), 404);
            }
        }

        if (count($document)) {
            //  Eager load other relationships wanted if specified
            if (request('connections')) {
                $document->load(oq_url_to_array(request('connections')));
            }

            //  Action was executed successfully
            return oq_api_notify($document, 200);
        }

        //  No resource found
        return oq_api_notify_no_resource();
    }

    public function store(Request $request)
    {
        //  Current authenticated user
        $user = auth('api')->user();

        //  If we have the model and id specified
        if (!empty(request('model')) && !empty(request('id'))) {
            //  Get the associated model responsible fo the upload
            $DynamicModel = '\\App\\'.request('model');
            if (class_exists($DynamicModel)) {
                //  Run query
                $model = $DynamicModel::find(request('id'));
            } else {
                //  No such class, the user provided incorrect details
                return oq_api_notify_error('Class does not exist', null, 404);
            }
        } else {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  Specify the location of storage
        if (!empty(request('location'))) {
            $location = request('location');
        } else {
            //  No location specified error
            return oq_api_notify_error('include storage location', null, 404);
        }

        /*  Specify the type/group/class the upload belongs to.
         *  This helps us classify related documents e.g logo, quotation, e.t.c
         */
        if (!empty(request('type'))) {
            $type = request('type');
        } else {
            //  No document type specified error
            return oq_api_notify_error('include document type', null, 404);
        }

        //  Verify if we have documents to upload
        if (Input::hasFile('files')) {
            $documents = Input::file('files');
            $uploadedFiles = [];
        } else {
            //  No such class, the user provided incorrect details
            return oq_api_notify_error('Upload File', null, 404);
        }

        //  Foreach document
        foreach ($documents as $document) {
            /*  Validate and upload document to Amazom s3
             *  Also make relation to belonging model e.g) User, Jobcard, e.t.c
             *  Update recent activities
             *
             *  @param $request - The request parameters used to validate each document
             *  @param $model - The model the document belongs to e.g) User, Jobcard, e.t.c
             *  @param $document - The file we want to upload
             *  @param $location - The location in Amazon bucket we want  to save to
             *  @param $type - The type/group/class the upload belongs to, e.g) logo, quotation, e.t.c
             *  @param $user - The user uploading the file
             *
             *  @return Validator - If validation failed
             *  @return document - If successful
             */

            $response = oq_saveDocument($request, $model, $document, $location, $type, $user);

            //  If the validation did not pass during upload
            if (oq_failed_validation($response)) {
                //  Return validation errors with an alert or json response if API request
                return oq_failed_validation_return($request, $response, $uploadedFiles);
            }

            array_push($uploadedFiles, $response);
        }

        //  return created document
        return oq_api_notify($uploadedFiles, 201);
    }

    public function update(Request $request, $document_id)
    {
        //  Current authenticated user
        $user = auth('api')->user();

        //  Get the document, even if trashed
        $document = Document::withTrashed()->where('id', $document_id)->first();

        //  Check if we don't have a resource
        if (!count($document)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        /*  Validate and Update the document and upload related documents
         *  [e.g document images or files]. Update recent activities
         *
         *  @param $request - The request with all the parameters to update
         *  @param $document - The document we are updating
         *  @param $user - The user we will use to extract the owning company branch and also point all activity towards
         *
         *  @return Validator - If validation failed
         *  @return document - If successful
         */

        $response = oq_updateDocument($request, $document, $user);

        //  If the validation did not pass
        if (oq_failed_validation($response)) {
            //  Return validation errors with an alert or json response if API request
            return oq_failed_validation_return($request, $response);
        }

        //  return updated document
        return oq_api_notify($response, 200);
    }

    public function delete($document_id)
    {
        //  Get the document, even if trashed
        $document = Document::withTrashed()->find($document_id);

        //  Check if we have a resource
        if (!count($document)) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        //  If the param "permanent" is set to true
        if (request('permanent') == 1) {
            $doc_path = str_replace(env('AWS_URL'), '', $document->url);

            if (!empty($doc_path)) {
                Storage::disk('s3')->delete($doc_path);
            }

            //  Permanently delete document
            $document->forceDelete();
        } else {
            //  Soft delete (trash) the document
            $document->delete();
        }

        return response()->json(null, 204);
    }
}
