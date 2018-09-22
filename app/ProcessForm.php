<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessForm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $casts = [
        'form_structure' => 'collection',
        'doc_structure' => 'collection',
    ];

    protected $table = 'process_forms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'form_structure', 'doc_structure', 'type', 'selected', 'deletable', 'company_id', 'who_created_id',
    ];

    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }
}
