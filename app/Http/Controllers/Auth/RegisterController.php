<?php

namespace App\Http\Controllers\Auth;

use Mail;
use App\User;
use App\Company;
use App\CompanyBranch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\ActivateAccount;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/activate-account';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verifyToken' => Str::random(40),
        ]);

        //  If the user was created successfully
        if ($user) {
            //  Create the company
            $company = Company::create([
                'name' => $data['company_name'],
            ]);

            //  If company was created successfully
            if ($company) {
                //  Create the branch
                $branch = CompanyBranch::create([
                    'name' => $data['company_branch_name'],
                    'destination' => $data['company_destination'],
                    'company_id' => $company->id,
                ]);

                //  If company branch was created successfully
                if ($branch) {
                    //  Assign the created branch to the user
                    User::find($user->id)->update([
                        'company_branch_id' => $branch->id,
                    ]);

                    //  Send email to the user to activate account
                    Mail::to($data['email'])->send(new ActivateAccount($user));

                    //  Notify the user that account was created successfully
                    Session::forget('alert');
                    Session::flash('alert', array('Account created successfully!', 'icon-check icons', 'success'));
                }
            }
        }

        return $user;
    }
}
