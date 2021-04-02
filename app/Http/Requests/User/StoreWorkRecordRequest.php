<?php

namespace App\Http\Requests\User;

use App\Utils\StrtotimeConverter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkRecordRequest extends FormRequest
{
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

            $workRecordDetails = $data['workRecordDetails'];

            //作業合計時間を算定
            $detail_work_time = 0;
            foreach ($workRecordDetails as $index => $workRecordDetail) {
                if (is_null($workRecordDetail['project_code'])) continue;

                if (!isset($workRecordDetail['work_time'])) {
                    $validator->errors()->add('workRecordDetail.' . $index . '.work_time', '作業時間を入力してください');
                }

                if (!isset($workRecordDetail['content'])) {
                    $validator->errors()->add('workRecordDetail.' . $index . '.content', '作業内容を入力してください');
                }

                $detail_work_time += isset($workRecordDetail['work_time']) ? StrtotimeConverter::strHourToIntMinute($workRecordDetail['work_time']) : 0;
            }

            //作業合計時間に基づき、休憩時間、超過時間を算定
            $break_time = $detail_work_time < self::EIGHTHOURSTOMINUTES ? self::BREAKTIME_S : self::BREAKTIME_L;
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
        $unique = isset($this->workrecord) ? $unique->ignore($this->workrecord->id) : $unique;
        return [
            'workday' => ['required', 'date_format:Y-m-d'],
            'attended_at' => ['required', 'date_format:H:i'],
            'left_at' => ['required', 'date_format:H:i', 'after:attended_at'],
            'workRecordDetail.*.project_code' => [],
            'workRecordDetail.*.work_time' => ['date_format:H:i'],
            'workRecordDetail.*.content' => ['max:255'],
        ];
    }
}
