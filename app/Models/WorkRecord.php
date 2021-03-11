<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkRecord extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'workday',
        'attended_at',
        'left_at',
        'working_time',
        'break_time',
        'overtime',
    ];
}
