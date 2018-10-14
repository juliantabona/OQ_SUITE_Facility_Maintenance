<?php

namespace App\Http\Controllers;

//use Request;
use Auth;
use App\Jobcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $model_id = Input::get('id', false);
        $model_type = Input::get('type', false);

        if (!empty($model_id) && $model_type == 'jobcard') {
            $model = Jobcard::find($model_id);
        }

        //  Params for document upload
        $files = Input::file('files');
        $user = Auth::user();

        $uploadedFiles = [];

        foreach ($files as $file) {
            $document = oq_saveDocument($model, $file, 'jobcard_images', 'samples', $user);
            array_push($uploadedFiles, $document);
        }

        return $uploadedFiles;
    }
}
