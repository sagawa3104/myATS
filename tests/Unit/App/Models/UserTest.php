<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     * 
     * @test
     * @param array $data
     * @return void
     */
    public function パスワードの非fillableテスト()
    {
        //Arrange
        $data = [
            'is_admin' => false,
        ];

        //Act
        $user = new User($data);

        //Assert
        $this->assertFalse(isset($user->password));
    }

    /**
     * @test
     * @dataProvider getStrIsAdminTestData
     */
    public function is_adminの文字列変換テスト($data, $expected)
    {
        //Arrange
        $user = new User($data);

        //Act
        $result = $user->getStrIsAdmin();

        //Assert
        $this->assertEquals($expected, $result);
    }

    public function getStrIsAdminTestData()
    {
        return [
            '×' => [
                ['is_admin' => false],
                '×'
            ],
            '〇' => [
                ['is_admin' => true],
                '〇'
            ]
        ];
    }
}
