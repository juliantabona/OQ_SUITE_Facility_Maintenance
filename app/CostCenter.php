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
        'name', 'description', 'who_created_id',
    ];

    /**
     * Get all of the owning cost center models.
     */
    public function costcenter()
    {
        return $this->morphTo();
    }
}
