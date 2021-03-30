<?php

namespace Tests\Feature\app\Models;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @dataProvider getStrIsAdminTestData
     */
    public function is_adminの文字列変換テスト($data, $expected)
    {
        //Arrange
        $project = new Project($data);

        //Act
        $result = $project->getStrIsAdmin();

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
    public function codeのユニークテスト()
    {
        //Arrange
        $data = [
            'name' => 'hoge',
            'code' => 'fuga',
        ];
        $project1 = new Project($data);
        $project1->save();

        $project2 = $project1->replicate();

        //Act
        try {
            $result_1 = $project2->validate();
        } catch (ValidationException $e) {
            $result_1 = false;
        }

        $project2->code = 'unique';
        try {
            $result_2 = $project2->validate();
        } catch (ValidationException $e) {
            $result_2 = false;
        }

        //Assert
        $this->assertFalse($result_1);
        $this->assertTrue($result_2);
    }
}
