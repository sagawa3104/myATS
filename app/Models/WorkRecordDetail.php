<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkRecordDetail extends Model
{
    //
    protected $fillable = [
        'work_record_id',
        'project_id',
        'work_time',
    ];

    public function workRecord()
    {
        return $this->belongsTo('App\Models\workRecord');
    }
}
