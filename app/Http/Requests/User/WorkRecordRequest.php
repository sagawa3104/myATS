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
            $data = $this->validationData();

            $projects = $data['project_id'];
            $work_times = $data['work_time'];
            $contents = $data['content'];

            //作業合計時間を算定
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

            //作業合計時間に基づき、休憩時間、超過時間を算定
            $break_time = $detail_work_time < self::NINEHOURSTOMINUTES ? self::BREAKTIME_S : self::BREAKTIME_L;
            $overtime = $detail_work_time - self::EIGHTHOURSTOMINUTES;

            //出勤時刻・退勤時刻から算定した時間と作業合計時間の検証            
            $attending_time = StrtotimeConverter::strHourToIntMinute($data['left_at']) - StrtotimeConverter::strHourToIntMinute($data['attended_at']);
            if ($attending_time <> $detail_work_time + $break_time) {
                $validator->errors()->add('sum_work_time', '勤務時間と作業合計時間が一致しません');
            }

            //導出項目をリクエストに追加
            $this->merge([
                'working_time' => $detail_work_time,
                'break_time' => $break_time,
                'overtime' => $overtime,
            ]);
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

}
