<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'company' => 'App\Company',
]);

class CostCenter extends Model
{
    protected $table = 'costcenters';
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
     * Get all of the owning cost center models.
     */
    public function costcenter()
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
