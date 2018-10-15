<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CompanyController extends Controller
{
    public function show($id)
    {
        /*  Check if the user requested for the company contacts
         *  If the contacts input is set to true, retrieve the contacts
         */
        $contacts = Input::get('contacts', false);  //  true or false

        $additional = array('logo');

        if ($contacts) {
            array_push($additional, 'contactDirectory');
        }

        //  Get the company
        $company = Company::with($additional)->where('id', $id)->first();

        //  If the company exists
        if ($company) {
            //  Return the company
            return response($company, 200);
        }

        return false;
    }
}
