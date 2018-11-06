<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\VerifyUser;
use App\Company;
use App\CompanyBranch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

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
     *  After the user is successfully registered,
     *  we hook into the registered() function from "RegistersUsers"
     *  and create the token used during authorization for API calls.
     */
    protected function registered(Request $request, $user)
    {
        return $user->generateToken($request);
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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|max:255',
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
        ]);

        //  If the user was created successfully
        if ($user) {
            $verification = VerifyUser::create([
                'user_id' => $user->id,
                'token' => sha1(time()),
              ]);

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

                    //  Fetch the updated user and details
                    $user = User::find($user->id);

                    //  Record activity of user created
                    $userCreatedActivity = $user->recentActivities()->create([
                        'type' => 'created',
                        'detail' => [
                                        'user' => $user,
                                    ],
                        'who_created_id' => $user->id,
                        'company_branch_id' => $user->company_branch_id,
                    ]);

                    //  Record activity of a new company created
                    $companyCreatedActivity = $company->recentActivities()->create([
                        'type' => 'created',
                        'detail' => [
                                        'company' => $company,
                                    ],
                        'who_created_id' => $user->id,
                    ]);

                    //  Record activity of a new branch created
                    $branchCreatedActivity = $branch->recentActivities()->create([
                        'type' => 'created',
                        'detail' => [
                                        'branch' => $branch,
                                    ],
                        'who_created_id' => $user->id,
                        'company_branch_id' => $user->company_branch_id,
                    ]);

                    //  Notify the user that account was created successfully
                    oq_notify('Account created successfully!', 'success');
                }
            }
        }

        return User::where('id', $user->id)->with([
                        'companyBranch' => function ($query) {
                            $query->with('company');
                        }, ])->first();
    }
}
