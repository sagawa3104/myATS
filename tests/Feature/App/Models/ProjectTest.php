<?php

namespace Tests\Feature\app\Models;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     * @dataProvider selectListTestData
     */
    public function セレクトボックス格納用配列のテスト($data, $expected)
    {
        //Arrange
        foreach ($data as $project) {
            Project::create($project);
        }

        //Act
        $result = Project::selectList();
        //Assert
        $this->assertEquals($expected, $result);
    }

    public function selectListTestData()
    {
        return [
            'OK' => [
                [
                    [
                        'name' => 'テスト1',
                        'code' => 'test1',
                    ],
                    [
                        'name' => 'テスト2',
                        'code' => 'test2',
                    ],
                    [
                        'name' => 'テスト3',
                        'code' => 'test3',
                    ],
                ],
                [
                    'test1' => 'test1:テスト1',
                    'test2' => 'test2:テスト2',
                    'test3' => 'test3:テスト3',
                    '' => '選択してください',
                ]
            ],
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
