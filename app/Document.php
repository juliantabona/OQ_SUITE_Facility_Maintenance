<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'user' => 'App\User',
    'company' => 'App\Company',
    'jobcard' => 'App\Jobcard',
]);

class Document extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'description', 'mime', 'size', 'url',
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
