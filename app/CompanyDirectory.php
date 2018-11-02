<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

Relation::morphMap([
    'company' => 'App\Company',
    'user' => 'App\User',
]);

class CompanyDirectory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'type',
    ];

    /**
     * Get all of the owning cost center models.
     */
    public function directable()
    {
        return $this->morphTo();
    }
}
