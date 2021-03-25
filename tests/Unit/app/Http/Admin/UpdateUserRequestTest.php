<?php

namespace Tests\Unit\app\Http\Admin;

use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateUserRequestTest extends TestCase
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
        $request = new UpdateUserRequest();
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
        $request = new UpdateUserRequest();
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
            'email 必須エラー1' => [
                [],
                'email',
                'Required',
                true,
            ],
            'email 必須エラー2' => [
                [
                    'email' => '',
                ],
                'email',
                'Required',
                true,
            ],
            'email 必須エラー3' => [
                [
                    'email' => null,
                ],
                'email',
                'Required',
                true,
            ],
            'email 桁数エラー' => [
                [
                    'email' => str_repeat('a', 256),
                ],
                'email',
                'Max',
                true,
            ],
            'email 形式エラー' => [
                [
                    'email' => 'hoge',
                ],
                'email',
                'Email',
                true,
            ],
            'パスワード 桁数エラー' => [
                [
                    'password' => str_repeat('a', 7),
                ],
                'password',
                'Min',
                true,
            ],
            '管理者権限 非Bool値' => [
                [
                    'is_admin' => 999,
                ],
                'is_admin',
                'Boolean',
                true,
            ],
        ];
    }

    public function requestOkDataProvider()
    {
        return [
            '名前' => [
                [
                    'name' => str_repeat('a', 255),
                ],
                'name',
                false,
            ],
            'Email' => [
                [
                    'email' => str_repeat('a', 243) . '@example.com',
                ],
                'email',
                false,
            ],
            'パスワード NULL許可' => [
                [],
                'password',
                false,
            ],
            'パスワード NULL許可' => [
                [
                    'password' => null,
                ],
                'password',
                false,
            ],
            'パスワード' => [
                [
                    'password' => str_repeat('a', 8),
                ],
                'password',
                false,
            ],
            '管理者権限 NULL許可' => [
                [],
                'is_admin',
                false,
            ],
            '管理者権限 NULL許可' => [
                [
                    'is_admin' => null,
                ],
                'is_admin',
                false,
            ],
            '管理者権限' => [
                [
                    'is_admin' => 0,
                ],
                'is_admin',
                false,
            ],
        ];
    }
}
