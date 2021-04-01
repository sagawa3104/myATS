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
