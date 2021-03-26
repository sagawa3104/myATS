<?php

namespace App\Models;

use App\Utils\StrtotimeConverter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class WorkRecord extends Model
{
    use Validatable;
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

    private function rules()
    {
        $unique = Rule::unique('work_records', 'workday');
        $unique = isset($this->id) ? $unique->ignore($this->id) : $unique;
        return [
            'workday' => ['required', 'date_format:Y-m-d', $unique],
            'attended_at' => ['required', 'date_format:H:i'],
            'left_at' => ['required', 'date_format:H:i', 'after:attended_at'],
            'working_time' => ['numeric'],
            'break_time' => ['numeric'],
            'overtime' => ['numeric'],
        ];
    }

    public function user()
    {
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

    public function hisAttendedAtToHiFormat()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->attended_at);
    }
    public function hisLeftAtToHiFormat()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->left_at);
    }
}
