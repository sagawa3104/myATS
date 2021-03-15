<?php

namespace App\Models;

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

    public function workRecordDetails()
    {
        return $this->hasMany('App\Models\WorkRecordDetail');
    }
}
