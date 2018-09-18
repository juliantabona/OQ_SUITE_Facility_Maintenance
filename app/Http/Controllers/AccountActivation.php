<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Session;
use App\User;
use App\Mail\ActivateAccount;
use Illuminate\Support\Facades\Input;

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
            Session::flash('alert', array('Account activation email sent successfully to "'.Auth::user()->email.'"!', 'icon-check icons', 'success'));
        }

        //  Redirect to activate account page
        return redirect()->route('activate-show');
    }

    public function activate()
    {
        $email = Input::get('email', false);    //  Users email
        $token = Input::get('token', false);    //  VerifyToken

        if (!empty($email) && !empty($token)) {
            /*
             *  Lets get the requested user trying to verify their account.
             *  A valid account must be a status of 0 and also have a
             *  token assigned from initial registration
             */
            $user = User::where('email', $email)
                        ->where('status', 0)
                        ->whereNotNull('verifyToken')
                        ->first();

            //  If the user account does exist
            if (count(collect($user)->toArray())) {
                //  This far the user exists and has not been verified. Lets verify now
                if ($user->email == $email && $user->verifyToken == $token) {
                    //  Verify Acccount
                    $verified = $user->update([
                                        'status' => 1,
                                        'verifyToken' => null,
                                    ]);
                    if ($verified) {
                        //  Clear all active sessions
                        Session::flush();
                        //  Get the verified user
                        $user = User::where('email', $client_email)->first();
                        //  Login the user
                        Auth::login($user);

                        //  Send account verified email
                        Mail::to($email)->send(new AccountActivated($user));

                        //  Notify the user that account was verified successfully
                        Session::forget('alert');
                        Session::flash('alert', array('Account verified successfully! Welcome home', 'icon-check icons', 'success'));

                        //  Redirect to overview
                        return redirect()->route('overview');
                    } else {
                        //  Notify the user that account verification failed
                        Session::forget('alert');
                        Session::flash('alert', array('Something went wrong trying to verify account "'.$email.'". Try resending the activation link again!', 'icon-exclamation icons', 'warning'));

                        //  Redirect to activate account page
                        return redirect()->route('activate-show');
                    }
                } else {  // Wrong verification link provided
                    //  Notify the user that account verification failed
                    Session::forget('alert');
                    Session::flash('alert', array('Verification Link expired or incorrect! Resend activation email.', 'icon-exclamation icons', 'warning'));

                    //  Redirect to activate account page
                    return redirect()->route('activate-show');
                }

                //  If the user account does not exist
            } else {
                //  Notify the user that the account they are trying to verify does not exist
                Session::forget('alert');
                Session::flash('alert', array('Could not verify '.$email.'. Account does not exist!', 'icon-exclamation icons', 'warning'));
            }
        }

        //  Redirect to login page
        return redirect()->route('login');
    }
}
