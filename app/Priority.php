<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'company' => 'App\Company',
]);

class Priority extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'color_code', 'who_created_id',
    ];

    /**
     * Get all of the owning priority models.
     */
    public function priority()
    {
        return $this->morphTo();
    }
}
