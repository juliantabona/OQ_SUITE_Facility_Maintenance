<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AdvancedFilter\Dataviewer;

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
    use Dataviewer;

    protected $table = 'recent_activities';

    protected $casts = [
        'detail' => 'array',
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

    protected $allowedFilters = [
        'id', 'type', 'company_branch_id', 'created_at',
    ];

    protected $orderable = [
        'id', 'type', 'company_branch_id', 'created_at',
    ];

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
