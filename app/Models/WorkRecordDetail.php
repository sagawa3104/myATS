<?php

namespace App\Models;

use App\Utils\StrtotimeConverter;
use Illuminate\Database\Eloquent\Model;

class WorkRecordDetail extends Model
{
    //
    protected $fillable = [
        'work_record_id',
        'project_id',
        'work_time',
        'content',
    ];

    public function workRecord()
    {
        return $this->belongsTo('App\Models\workRecord');
    }

    public function intWorkTimeToStrHour()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->work_time);
    }
}
