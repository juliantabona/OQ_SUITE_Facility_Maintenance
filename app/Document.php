<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AdvancedFilter\Dataviewer;

Relation::morphMap([
    'user' => 'App\User',
    'company' => 'App\Company',
    'jobcard' => 'App\Jobcard',
]);

class Document extends Model
{
    use SoftDeletes;
    use Dataviewer;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'description', 'mime', 'size', 'url',
    ];

    protected $allowedFilters = [
        'name', 'type', 'description', 'mime', 'size', 'url', 'created_at', 'documentable_id', 'documentable_type',
    ];

    protected $orderable = [
        'name', 'type', 'description', 'mime', 'size', 'url', 'created_at',
    ];

    public function creator()
    {
        return $this->morphMany('App\Creator', 'creatable');
    }

    /**
     * Get all of the owning documentable models.
     */
    public function documentable()
    {
        return $this->morphTo();
    }

    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }
}
