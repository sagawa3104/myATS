<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkRecordRequest extends FormRequest
{
    private const SECONDSFORMINUTE =60;
    private const NINEHOURSTOMINUTES = 540;
    private const EIGHTHOURSTOMINUTES = 480;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデータインスタンスの設定
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $attended_at = strtotime($validator->getData('attended_at'));
            $left_at = strtotime($validator->getData('left_at'));
            $attending_minutes = ($left_at - $attended_at) / self::SECONDSFORMINUTE ;
            $break_time = $attending_minutes < self::NINEHOURSTOMINUTES ? 45: 60;
            $working_time = $attending_minutes - $break_time;
            $over_time = $working_time - self::EIGHTHOURSTOMINUTES;
            
            $projects = $validator->getData('project_id');
            $work_times = $validator->getData('work_time');
            $contents = $validator->getData('content');

            $detail_work_time = 0;
            for($i = 0; $i < count($projects; $i++){
                if(is_null($projects[$i])) continue;

                if(is_null($work_times[$i])){
                    $validator->errors()->add('work_time', '作業時間を入力してください');
                }

                if(is_null($contents[$i])){
                    $validator->errors()->add('content', '作業内容を入力してください');
                }

                $detail_work_time += strtotime($work_time[$i]) / self::SECONDSFORMINUTE;
            }

            if($working_time <> $detail_work_time){
                $validator->errors()->add('work_time', '作業内容を入力してください');
            }

        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'workday' => 'required|date',
            'attended_at' => 'required|date_format:H:i|',
            'left_at' => 'required|date_format:H:i|after:attended_at',
            'project_id.*' => 'nullable|exists:projects,id',
            'work_time.*' => 'nullable|date_format:H:i',
            'content.*' => 'nullable|max:255',
        ];
    }
}
