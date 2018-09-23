<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'jobcard' => 'App\Jobcard',
    'company_branch' => 'App\CompanyBranch',
    'user' => 'App\User',
    'document' => 'App\Document',
]);

class RecentActivity extends Model
{
    protected $table = 'recent_activities';

    protected $casts = [
        'activity' => 'collection',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity', 'company_id', 'who_created_id',
    ];

    /**
     * Get all of the owning documentable models.
     */
    public function trackable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'who_created_id');
    }

    public function jobcard()
    {
        return $this->belongsTo('App\Jobcard', 'trackable_id');
    }
}
