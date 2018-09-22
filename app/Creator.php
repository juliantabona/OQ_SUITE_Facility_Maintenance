<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get all of the owning priority models.
     */
    public function created()
    {
        return $this->morphTo();
    }
}
