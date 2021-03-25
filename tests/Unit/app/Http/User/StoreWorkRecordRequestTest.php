<?php

namespace Tests\Unit\app\Http\User;

use App\Http\Requests\User\StoreWorkRecordRequest;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreWorkRecordRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @param array
     * @param string
     * @param boolean
     * @dataProvider requestNgDataProvider
     */
    public function 単体データテスト_NG(array $data, $target, $rule, $expected)
    {
        //Arrange
        $request = new StoreWorkRecordRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        //Act
        $validator->fails();
        $result = $validator->failed();

        //Assert
        $this->assertEquals($expected, isset($result[$target][$rule]));
    }
    /**
     * A basic unit test example.
     * @test
     * @param array
     * @param string
     * @param boolean
     * @dataProvider requestOkDataProvider
     */
    public function 単体データテスト_OK(array $data, $target, $expected)
    {
        //Arrange
        $request = new StoreWorkRecordRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        //Act
        $validator->fails();
        $result = $validator->failed();

        //Assert
        $this->assertEquals($expected, isset($result[$target]));
    }

    /**
     * A basic unit test example.
     * @test
     * @param array
     * @param boolean
     * @dataProvider requestNgRelationnalDataProvider
     */
    public function 関係テスト_NG(array $data, $expected)
    {

        $this->assertTrue($this->validate($data));
    }

    protected function validate($data)
    {
        //Arrange
        $this->app->resolving(StoreWorkRecordRequest::class, function ($resolved) use ($data) {
            $resolved->merge($data);
        });

        //Act
        try {
            app(StoreWorkRecordRequest::class);

            return false;
        } catch (ValidationException $e) {

            return true;
        }
    }

    public function requestNgDataProvider()
    {
        return [
            '勤務日 必須エラー1' => [
                [],
                'workday',
                'Required',
                true,
            ],
            '勤務日 必須エラー2' => [
                [
                    'workday' => '',
                ],
                'workday',
                'Required',
                true,
            ],
            '勤務日 必須エラー3' => [
                [
                    'workday' => null,
                ],
                'workday',
                'Required',
                true,
            ],
            '勤務日 形式エラー' => [
                [
                    'workday' => '20200101',
                ],
                'workday',
                'DateFormat',
                true,
            ],
            '開始時間 必須エラー1' => [
                [],
                'attended_at',
                'Required',
                true,
            ],
            '開始時間 必須エラー2' => [
                [
                    'attended_at' => '',
                ],
                'attended_at',
                'Required',
                true,
            ],
            '開始時間 必須エラー3' => [
                [
                    'attended_at' => null,
                ],
                'attended_at',
                'Required',
                true,
            ],
            '開始時間 必須エラー3' => [
                [
                    'attended_at' => null,
                ],
                'attended_at',
                'Required',
                true,
            ],
            '開始時間 形式エラー' => [
                [
                    'attended_at' => '0900',
                ],
                'attended_at',
                'DateFormat',
                true,
            ],
            '終了時間 必須エラー1' => [
                [],
                'left_at',
                'Required',
                true,
            ],
            '終了時間 必須エラー2' => [
                [
                    'left_at' => '',
                ],
                'left_at',
                'Required',
                true,
            ],
            '終了時間 必須エラー3' => [
                [
                    'left_at' => null,
                ],
                'left_at',
                'Required',
                true,
            ],
            '終了時間 必須エラー3' => [
                [
                    'left_at' => null,
                ],
                'left_at',
                'Required',
                true,
            ],
            '終了時間 形式エラー' => [
                [
                    'left_at' => '1800',
                ],
                'left_at',
                'DateFormat',
                true,
            ],
            '終了時間 関係エラー' => [
                [
                    'attended_at' => '20:00',
                    'left_at' => '18:00',
                ],
                'left_at',
                'After',
                true,
            ],
            'プロジェクトID 形式エラー' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'project_id' => 'a',
                        ]
                    ]
                ],
                'workRecordDetail.0.project_id',
                'Numeric',
                true,
            ],
            '作業時間 形式エラー' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'work_time' => '0800',
                        ]
                    ]
                ],
                'workRecordDetail.0.work_time',
                'DateFormat',
                true,
            ],
            '作業内容 桁数エラー' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'content' => str_repeat('a', 256),
                        ]
                    ]
                ],
                'workRecordDetail.0.content',
                'Max',
                true,
            ],
        ];
    }
    public function requestOkDataProvider()
    {
        return [
            '勤務日' => [
                [
                    'workday' => '2020-01-01',
                ],
                'workday',
                false,
            ],
            '開始時間' => [
                [
                    'attended_at' => '09:00',
                ],
                'attended_at',
                false,
            ],
            '終了時間' => [
                [
                    'attended_at' => '09:00',
                    'left_at' => '10:00',
                ],
                'left_at',
                false,
            ],
            'プロジェクトID' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'project_id' => 1,
                        ]
                    ]
                ],
                'workRecordDetail.0.project_id',
                false,
            ],
            '作業時間' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'work_time' => '08:00',
                        ]
                    ]
                ],
                'workRecordDetail.0.work_time',
                false,
            ],
            '作業内容' => [
                [
                    'workRecordDetail' => [
                        0 => [
                            'content' => str_repeat('a', 255),
                        ]
                    ]
                ],
                'workRecordDetail.0.content',
                false,
            ],
        ];
    }

    public function requestNgRelationnalDataProvider()
    {
        return [
            'test' => [
                [
                    'workday' => '2020-01-01',
                    'attended_at' => '10:00',
                    'left_at' => '19:00',
                    'workRecordDetails' => [
                        0 => [
                            'project_id' => 1,
                            'work_time' => '04:00',
                            'content' => 'content1',
                        ],
                        1 => [
                            'project_id' => 2,
                            'work_time' => '05:00',
                            'content' => 'content2',
                        ],
                    ],
                ],
                true,
            ],
        ];
    }
}
