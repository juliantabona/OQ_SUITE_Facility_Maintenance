<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

Relation::morphMap([
    'user' => 'App\User',
    'company' => 'App\Company',
    'companybranch' => 'App\CompanyBranch',
    'jobcard' => 'App\Jobcard',
    'category' => 'App\Category',
    'priority' => 'App\Priority',
    'costcenter' => 'App\CostCenter',
    'document' => 'App\Document',
]);

class RecentActivity extends Model
{
    use SoftDeletes;

    protected $table = 'recent_activities';

    protected $casts = [
        'detail' => 'collection',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'detail', 'who_created_id', 'company_branch_id',
    ];

    public function creator()
    {
        return $this->morphMany('App\Creator', 'creatable');
    }

    /**
     * Get all of the owning documentable models.
     */
    public function trackable()
    {
        return $this->morphTo();
    }

    public function jobcard()
    {
        return $this->belongsTo('App\Jobcard', 'trackable_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'who_created_id');
    }
}
