<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'gender', 'date_of_birth', 'bio', 'address', 'phone_ext', 'phone_num', 'email',
        'additional_email', 'username', 'password', 'avatar', 'status', 'verifyToken', 'settings', 'tutorial_status',
        'company_branch_id', 'position', 'country', 'city', 'accessibility', 'who_created_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
     *   Incase the user does not have a profile image, use the default placeholder.
     */
    public function getAvatarAttribute($value)
    {
        //  If the avatar is not empty ('', NULL, false, e.t.c) then return the avatar url
        //  Otherwise return the default avatar placeholder
        return !empty($value) ? $value : '/images/assets/placeholders/profile_placeholder.png';
    }
}
