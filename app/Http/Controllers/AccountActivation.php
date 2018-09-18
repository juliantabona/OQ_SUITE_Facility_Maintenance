<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountActivation extends Controller
{
    public function index()
    {
        return view('auth.activate-account');
    }

    public function resend(Request $request)
    {
        if (Auth::user()->email) {
            //  Send email to the user to activate account
            Mail::to($request->input('email'))->send(new ActivateAccount(Auth::user()));

            //  Notify the user that email was sent successfully
            Session::forget('alert');
            Session::flash('alert', array('Account activation email sent successfully!', 'icon-check icons', 'success'));
        }

        return view('auth.activate-account');
    }

    public function activate()
    {
    }
}
