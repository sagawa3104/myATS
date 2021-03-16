<?php

namespace App\Http\Requests\User;

use App\Utils\StrtotimeConverter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkRecordRequest extends FormRequest
{
    private const NINEHOURSTOMINUTES = 540;
    private const EIGHTHOURSTOMINUTES = 480;
    private const BREAKTIME_L = 60;
    private const BREAKTIME_S = 45;
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
        if ($validator->fails()) return;

        $validator->after(function ($validator) {
            $data = $this->ComplementHeader($validator->getData());

            $projects = $data['project_id'];
            $work_times = $data['work_time'];
            $contents = $data['content'];

            $detail_work_time = 0;
            for ($i = 0; $i < count($projects); $i++) {
                if (is_null($projects[$i])) continue;

                if (is_null($work_times[$i])) {
                    $validator->errors()->add('work_time.' . $i, '作業時間を入力してください');
                }

                if (is_null($contents[$i])) {
                    $validator->errors()->add('content.' . $i, '作業内容を入力してください');
                }

                $detail_work_time += StrtotimeConverter::strHourToIntMinute($work_times[$i]);
            }

            if ($data['working_time'] <> $detail_work_time) {
                $validator->errors()->add('sum_work_time', '勤務時間と合計時間が一致しません');
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
        $unique = Rule::unique('work_records', 'workday');
        $unique = isset($this->workrecord)? $unique->ignore($this->workrecord->id):$unique;
        return [
            'workday' => ['required','date',$unique],
            'attended_at' => 'required|date_format:H:i|',
            'left_at' => 'required|date_format:H:i|after:attended_at',
            'project_id.*' => 'nullable|exists:projects,id',
            'work_time.*' => 'nullable|date_format:H:i',
            'content.*' => 'nullable|max:255',
        ];
    }


    private function calcAttendingTime($data)
    {

        $attended_at = StrtotimeConverter::strHourToIntMinute($data['attended_at']);
        $left_at = StrtotimeConverter::strHourToIntMinute($data['left_at']);

        return $left_at - $attended_at;
    }

    private function ComplementHeader($data)
    {

        $attending_time = $this->calcAttendingTime($data);
        $break_time = $attending_time < self::NINEHOURSTOMINUTES ? self::BREAKTIME_S : self::BREAKTIME_L;
        $working_time = $attending_time - $break_time;
        $overtime = $working_time - self::EIGHTHOURSTOMINUTES;

        $this->merge([
            'working_time' => $working_time,
            'break_time' => $break_time,
            'overtime' => $overtime,
        ]);

        return $this->validationData();
    }
}
