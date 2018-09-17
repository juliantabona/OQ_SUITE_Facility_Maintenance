<?php

namespace App\Http\Controllers;

use Auth;
use App\Company;
use App\CompanyBranch;
use Illuminate\Http\Request;
use Session;

class CompanyController extends Controller
{
    public function registerCreate()
    {
        //  Check if the user already has a company
        $UserHasCompany = Auth::user()->company()->count();

        //  If the user does not have a company
        if (!$UserHasCompany) {
            //  Display company creation page
            return view('auth.register-company');
        } else {
            //  Notify the user that they already have a company assigned
            Session::forget('alert');
            Session::flash('alert', array('You already have a company. You can update your company information <a href="">Here</a>', 'icon-exclamation icons', 'warning'));

            //  Go to dashboard
            return redirect()->route('overview');
        }
    }

    public function registerStore(Request $request)
    {
        //  Create the company
        $company = Company::create([
            'name' => $request['company_name'],
        ]);

        //  If company created successfully
        if ($company) {
            //  Create the branch
            $branch = CompanyBranch::create([
                'name' => $request['company_branch_name'],
                'destination' => $request['company_destination'],
                'company_id' => $company->id,
            ]);

            //  If company branch was created successfully
            if ($branch) {
                //  Assign the created branch to the user
                Auth::user()->update([
                    'company_branch_id' => $branch->id,
                ]);

                //  Notify the user that company was not created successfully
                Session::forget('alert');
                $request->session()->flash('alert', array('Account was setup and linked successully. Welcome home!', 'icon-check icons', 'success'));

                //  Go to dashboard
                return redirect()->route('overview');
            }
        }

        //  Notify the user that company was not created successfully
        Session::forget('alert');
        $request->session()->flash('alert', array('Company was not created successully. Please try again', 'danger'));

        return back();
    }
}
