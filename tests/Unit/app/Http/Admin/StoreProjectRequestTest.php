<?php

namespace Tests\Unit\app\Http\Admin;

use App\Http\Requests\Admin\StoreProjectRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreProjectRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @param array
     * @param boolean
     * @dataProvider requestDataProvider
     */
    public function 登録時のリクエスト(array $data, $expected)
    {
        $request = new StoreProjectRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);
        $result = $validator->fails();

        $this->assertEquals($expected, $result);
    }

    public function requestDataProvider()
    {
        return [
            '必須エラー1' => [
                [
                    'name' => 'hoge',
                    'code' => null,
                ],
                true,
            ],
            '必須エラー2' => [
                [
                    'name' => null,
                    'code' => 'hoge',
                ],
                true,
            ],
            '必須エラー3' => [
                [
                    'name' => 'hoge',
                    'code' => '',
                ],
                true,
            ],
            '必須エラー4' => [
                [
                    'name' => '',
                    'code' => 'hoge',
                ],
                true,
            ],
            '桁数エラー1' => [
                [
                    'name' => str_repeat('a', 256),
                    'code' => 'hoge',
                ],
                true,
            ],
            '桁数エラー2' => [
                [
                    'name' => 'hoge',
                    'code' => str_repeat('a', 256),
                ],
                true,
            ],
            '正常1' => [
                [
                    'name' => str_repeat('a', 255),
                    'code' => 'hoge',
                ],
                false,
            ],
            '正常2' => [
                [
                    'name' => 'hoge',
                    'code' => str_repeat('a', 255),
                ],
                false,
            ],

        ];
    }
}
