<?php

namespace App\Models;

use App\Utils\StrtotimeConverter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class WorkRecordDetail extends Model
{
    use Validatable;
    //
    protected $fillable = [
        'work_record_id',
        'project_id',
        'work_time',
        'content',
    ];

    private function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'work_time' => ['required', 'numeric',],
            'content' => ['required', 'max:255'],
        ];
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function workRecord()
    {
        return $this->belongsTo('App\Models\WorkRecord');
    }

    public function intWorkTimeToStrHour()
    {
        return StrtotimeConverter::intMinuteToStrHour($this->work_time);
    }
}
