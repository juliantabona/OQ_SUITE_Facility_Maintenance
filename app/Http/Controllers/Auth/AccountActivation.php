<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\VerifyUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountActivation extends Controller
{
    public function resend()
    {
        if (!empty(request('user_id'))) {
            $user = User::find(request('user_id'));
        } else {
            return oq_api_notify_error('include user_id', null, 404);
        }

        if (!$user) {
            //  No resource found
            return oq_api_notify_no_resource();
        }

        if ($user->email) {
            //  Send email to the user to activate account
            Mail::to($user->email)->send(new ActivateAccount($user));

            //  Notify the user that email was sent successfully

            //  API Response
            if (oq_viaAPI($request)) {
                return oq_api_notify(['data' => 'Account activation email sent successfully to "'.$user->email.'"!'], 200);
            } else {
                //  Notify the user to activate their account
                oq_notify('Account activation email sent successfully to "'.$user->email.'"!', 'success');
                //  Go to login page
                return redirect()->route('login');
            }
        }

        //  Redirect to activate account page
        return redirect()->route('activate-show');
    }

    public function activate(Request $request)
    {
        if (empty(request('token'))) {
            return oq_api_notify([
                'message' => 'Activation token not provided',
            ], 400);
        } else {
            $token = request('token');
        }

        $verifyUser = VerifyUser::where('token', $token)->first();

        if (isset($verifyUser)) {
            $userExists = count($verifyUser->user);

            if (!$userExists) {
                //  API Response
                if (oq_viaAPI($request)) {
                    return oq_api_notify([
                        'message' => 'Account does not exist',
                    ], 200);
                } else {
                    //  Notify the user that the account associated to the token does not exist
                    oq_notify('Account does not exist');
                    //  Go to login page
                    return redirect()->route('login');
                }
            }

            $user = $verifyUser->user;

            if (!$user->verified) {
                $user->verified = 1;
                $user->save();

                //  API Response
                if (oq_viaAPI($request)) {
                    return oq_api_notify([
                        'message' => 'Account verified',
                        'user' => $user,
                    ], 200);
                } else {
                    //  Notify the user that their account is verified
                    oq_notify('Account verified. You can now login.', 'success');
                    //  Go to login page
                    return redirect()->route('login');
                }
            } else {
                //  API Response
                if (oq_viaAPI($request)) {
                    return oq_api_notify([
                        'message' => 'Account verified',
                        'user' => $user,
                    ], 200);
                } else {
                    //  Notify the user that their account is already verified
                    oq_notify('Account already verified. You can login.', 'success');
                    //  Go to login page
                    return redirect()->route('login');
                }
            }
        } else {
            //  API Response
            if (oq_viaAPI($request)) {
                return oq_api_notify([
                    'message' => 'Incorrent Token',
                ], 200);
            } else {
                //  Notify the user that their account is already verified
                oq_notify('Incorrent Token', 'warning');
                //  Go to login page
                return redirect()->route('login');
            }
        }
    }
}
