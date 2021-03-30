<?php

namespace Tests\Feature\app\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
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

    /**
     * @test
     */
    public function emailのユニークテスト()
    {
        //Arrange
        $data = [
            'name' => 'hoge',
            'email' => 'test@example.com',
            'password' => 'password',
            'is_admin' => false,
        ];
        $user1 = new User($data);
        $user1->password = Hash::make($data['password']);
        $user1->save();

        $user2 = $user1->replicate();

        //Act
        try {
            $result_1 = $user2->validate();
        } catch (ValidationException $e) {
            $result_1 = false;
        }

        $user2->email = 'unique@example.com';
        try {
            $result_2 = $user2->validate();
        } catch (ValidationException $e) {
            $result_2 = false;
        }

        //Assert
        $this->assertFalse($result_1);
        $this->assertTrue($result_2);
    }
}
