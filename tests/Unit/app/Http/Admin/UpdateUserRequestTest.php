<?php

namespace Tests\Unit\app\Http\Admin;

use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateUserRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     * @param array
     * @param string
     * @param boolean
     * @dataProvider requestDataProvider
     */
    public function testProjectRequest(array $data, $target, $rule, $expected)
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

    public function requestDataProvider()
    {
        return [
            '名前 必須エラー1' => [
                [
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'name',
                'Required',
                true,
            ],
            '名前 必須エラー2' => [
                [
                    'name' => '',
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'name',
                'Required',
                true,
            ],
            '名前 必須エラー3' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'name',
                'Required',
                true,
            ],
            '名前 桁数エラー' => [
                [
                    'name' => str_repeat('a', 256),
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'name',
                'Max',
                true,
            ],
            'email 必須エラー1' => [
                [
                    'name' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'email',
                'Required',
                true,
            ],
            'email 必須エラー2' => [
                [
                    'name' => null,
                    'email' => '',
                    'password' => null,
                    'id_admin' => null,
                ],
                'email',
                'Required',
                true,
            ],
            'email 必須エラー3' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'email',
                'Required',
                true,
            ],
            'email 桁数エラー' => [
                [
                    'name' => null,
                    'email' => str_repeat('a', 256),
                    'password' => null,
                    'id_admin' => null,
                ],
                'email',
                'Max',
                true,
            ],
            'email 形式エラー' => [
                [
                    'name' => null,
                    'email' => 'hoge',
                    'password' => null,
                    'id_admin' => null,
                ],
                'email',
                'Email',
                true,
            ],
            'パスワード NULL許可' => [
                [
                    'name' => null,
                    'email' => null,
                    'id_admin' => null,
                ],
                'password',
                null,
                false,
            ],
            'パスワード NULL許可' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'id_admin' => null,
                ],
                'password',
                null,
                false,
            ],
            'パスワード 桁数エラー' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => str_repeat('a', 7),
                    'id_admin' => null,
                ],
                'password',
                'Min',
                true,
            ],
            '管理者権限 NULL許可' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'is_admin' => null,
                ],
                'is_admin',
                null,
                false,
            ],
            '管理者権限 NULL許可' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'is_admin' => null,
                ],
                'is_admin',
                null,
                false,
            ],
            '管理者権限 非Bool値' => [
                [
                    'name' => null,
                    'email' => null,
                    'password' => null,
                    'is_admin' => 999,
                ],
                'is_admin',
                'Boolean',
                true,
            ],

        ];
    }
}
