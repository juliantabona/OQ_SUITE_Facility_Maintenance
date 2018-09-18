<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Session;
use App\Mail\ActivateAccount;

class AccountActivation extends Controller
{
    public function index()
    {
        return view('auth.activate-account');
    }

    public function resend()
    {
        if (Auth::user()->email) {
            //  Send email to the user to activate account
            Mail::to(Auth::user()->email)->send(new ActivateAccount(Auth::user()));

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
