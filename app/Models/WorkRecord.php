<?php

namespace App\Models;

use App\Utils\StrtotimeConverter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkRecord extends Model
{
    //
    protected $fillable = [
        'user_id',
        'workday',
        'attended_at',
        'left_at',
        'working_time',
        'break_time',
        'overtime',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function workRecordDetails()
    {
        return $this->hasMany('App\Models\WorkRecordDetail');
    }

    public function intWorkingTimeToStrHour()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->working_time);
    }
    public function intBreakTimeToStrHour()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->break_time);
    }
    public function intOverTimeToStrHour()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->overtime);
    }
}
