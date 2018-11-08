<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\URL;
use App\AdvancedFilter\Dataviewer;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use Dataviewer;

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'bio', 'address', 'phone_ext', 'phone_num', 'email',
        'additional_email', 'username', 'password', 'verifyToken', 'settings', 'tutorial_status',
        'company_branch_id', 'position', 'country', 'city', 'accessibility',
    ];

    protected $allowedFilters = [
        'id', 'first_name', 'last_name', 'gender', 'date_of_birth', 'bio', 'address', 'phone_ext', 'phone_num', 'email',
        'additional_email', 'position', 'country', 'city', 'accessibility', 'created_at',
    ];

    protected $orderable = [
        'id', 'first_name', 'last_name', 'gender', 'date_of_birth', 'bio', 'address', 'phone_ext', 'phone_num', 'email',
        'additional_email', 'position', 'country', 'city', 'accessibility', 'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function verification()
    {
        return $this->hasOne('App\VerifyUser');
    }

    /**
     *   Get the users company. A user must belong to a company to access more
     *   information. This can be branches, jobcards, staff, contractors, clients,
     *   quotations, invoices, receipts, documents, e.t.c related to the company.
     */
    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    /**
     *   Get the users company branch. This is the branch that the user belongs to.
     *   A user must belong to a company branch to access more information for that
     *   specific branch. This can be jobcards, staff, contractors, clients,
     *   quotations, invoices, receipts, documents, e.t.c related to the branch.
     */
    public function companyBranch()
    {
        return $this->belongsTo('App\CompanyBranch', 'company_branch_id');
    }

    public function documents()
    {
        return $this->morphMany('App\Document', 'documentable');
    }

    /**
     *   Get the recent activities that belong to the user.
     */
    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }

    /**
     *   Incase the user does not have a profile image, use the default placeholder.
     */
    public function getAvatarAttribute($value)
    {
        //  If the avatar is not empty ('', NULL, false, e.t.c) then return the avatar url
        //  Otherwise return the default avatar placeholder
        return !empty($value) ? $value : '/images/assets/placeholders/profile_placeholder.png';
    }

    public function generateToken($request)
    {
        $http = new \GuzzleHttp\Client();

        $response = $http->post(URL::to('/').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'U7xlr123YP2FPzakHTX66Kp1xncnmkQNDHbuMelQ',
                'username' => $this->email,
                'password' => $request->input('password'),
                'scope' => '',
            ],
        ]);

        //  Lets get an array instead of a stdObject so that we can return without errors
        $response = json_decode($response->getBody(), true);
        /*
        return response()->json(
                [
                    'auth' => $response,            //  API ACCESS TOKEN
                    'user' => $this->toArray(),      //  NEW REGISTERED USER
                ], 201);                            //  201 STATUS  - Resource Created
        */
        return oq_api_notify([
                    'auth' => $response,            //  API ACCESS TOKEN
                    'user' => $this->toArray(),      //  NEW REGISTERED USER
                ], 201);
    }
}
