<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessForm extends Model
{
    use SoftDeletes;

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
        'name', 'description', 'form_structure', 'doc_structure', 'type', 'selected', 'deletable', 'company_id',
    ];

    public function creator()
    {
        return $this->morphMany('App\Creator', 'creatable');
    }

    public function recentActivities()
    {
        return $this->morphMany('App\RecentActivity', 'trackable')
                    ->orderBy('created_at', 'desc');
    }
}
