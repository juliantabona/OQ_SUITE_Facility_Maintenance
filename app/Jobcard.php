<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobcard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'step_id', 'priority_id', 'cost_center_id', 'company_branch_id',
        'category_id', 'client_id', 'select_contractor_id', 'img_url', 'who_created_id',
    ];

    public function views()
    {
        return $this->morphMany('App\View', 'viewable');
    }

    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'who_created_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function costCenter()
    {
        return $this->belongsTo('App\CostCenter', 'cost_center_id');
    }

    public function priority()
    {
        return $this->belongsTo('App\Priority', 'priority_id');
    }

    public function processInstructions()
    {
        return $this->morphMany('App\ProcessFormAllocation', 'processable');
    }

    public function owningBranch()
    {
        return $this->belongsTo('App\CompanyBranch', 'company_branch_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Company', 'client_id');
    }

    public function selectedContractor()
    {
        return $this->belongsTo('App\Company', 'select_contractor_id');
    }

    public function contractorsList()
    {
        return $this->belongsToMany('App\Company', 'jobcard_contractors', 'jobcard_id', 'contractor_id')
                    ->withPivot('id', 'jobcard_id', 'contractor_id', 'amount', 'quotation_doc_url')
                    ->withTimestamps();
    }

    public function processFormStep()
    {
        return $this->belongsTo('App\ProcessFormSteps', 'step_id');
    }

    /*
        public function progressSteps()
        {
            return $this->morphToMany('App\ProgressStatusSteps', 'progressable', 'progress_status', 'progressable_id', 'step_id');
        }

    */

    /*

    public function category()
    {
        return $this->belongsTo('App\JobcardCategory', 'category_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function clientContacts()
    {
        return $this->belongsToMany('App\ClientContact', 'jobcard_contacts', 'jobcard_id', 'contact_id');
    }
    */
}
