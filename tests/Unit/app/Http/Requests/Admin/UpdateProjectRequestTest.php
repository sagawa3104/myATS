<?php

namespace Tests\Unit\app\Http\Requests\Admin;

use App\Http\Requests\Admin\UpdateProjectRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateProjectRequestTest extends TestCase
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
        $request = new UpdateProjectRequest();
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
        $request = new UpdateProjectRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        //Act
        $validator->fails();

        $result = $validator->failed();

        //Assert
        $this->assertEquals($expected, isset($result[$target]));
    }

    public function requestNgDataProvider()
    {
        return [
            'コード 必須エラー1' => [
                [],
                'code',
                'Required',
                true,
            ],
            'コード 必須エラー2' => [
                [
                    'code' => '',
                ],
                'code',
                'Required',
                true,
            ],
            'コード 必須エラー3' => [
                [
                    'code' => null,
                ],
                'code',
                'Required',
                true,
            ],
            'コード 桁数エラー' => [
                [
                    'code' => str_repeat('a', 256),
                ],
                'code',
                'Max',
                true,
            ],
            '名前 必須エラー1' => [
                [],
                'name',
                'Required',
                true,
            ],
            '名前 必須エラー2' => [
                [
                    'name' => '',
                ],
                'name',
                'Required',
                true,
            ],
            '名前 必須エラー3' => [
                [
                    'name' => null,
                ],
                'name',
                'Required',
                true,
            ],
            '名前 桁数エラー' => [
                [
                    'name' => str_repeat('a', 256),
                ],
                'name',
                'Max',
                true,
            ],
        ];
    }
    public function requestOkDataProvider()
    {
        return [
            'コード' => [
                [
                    'code' => str_repeat('a', 255),
                ],
                'code',
                false,
            ],
            '名前' => [
                [
                    'name' => str_repeat('a', 255),
                ],
                'name',
                false,
            ],
        ];
    }
}
