<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
    'jobcard' => 'App\Jobcard',
    //  'company' => 'App\Company',
    //  'document' => 'App\Document',
]);

class View extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'who_viewed_id',
    ];

    /**
     * Get all of the owning documentable models.
     */
    public function viewable()
    {
        return $this->morphTo();
    }
}
