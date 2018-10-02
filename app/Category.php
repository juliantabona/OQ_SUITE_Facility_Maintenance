<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'company' => 'App\Company',
]);

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function creator()
    {
        return $this->morphMany('App\Creator', 'creatable');
    }

    /**
     * Get all of the owning category models.
     */
    public function category()
    {
        return $this->morphTo();
    }

    /**
     *   Get the recent activities that belong to the user.
     */
    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }
}
