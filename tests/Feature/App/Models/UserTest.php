<?php

namespace Tests\Feature\app\Models;

use App\Models\Project;
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
        $user1 = factory(User::class)->create();

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

    /**
     * @test
     */
    public function ユーザーにアサインされたプロジェクトのリスト取得のテスト()
    {
        //Arrange
        $user = factory(User::class, 1)->create();
        $projcets = factory(Project::class, 10)->create();
        $random = $projcets->random(3);
        $expect = array("" => "選択してください");
        foreach ($random as $p) {
            $expect += array($p->code => $p->code . ":" . $p->name);
        }
        $col = $random->pluck('id');
        $user->projects->sync($col);

        $res = $user->assignedProjectList();

        //Assert
        $this->assertEquals($expect, $res);
    }
}
