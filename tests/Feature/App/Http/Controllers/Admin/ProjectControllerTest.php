<?php

namespace Tests\Feature\App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $user;
    private $project;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->states('admin')->create();
        $this->user = factory(User::class)->create();
        $this->project = factory(Project::class)->create();
    }

    /**
     * @test
     * @return void
     */
    public function 未認証状態での一覧画面アクセスはログイン画面へリダイレクトされること()
    {
        //Act
        $response = $this->get(route('admin.project.index'));

        //Assert
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * @return void
     */
    public function 非管理者で認証状態での一覧画面アクセスは403されること()
    {
        //Arrange
        $this->actingAs($this->user);

        //Act
        $response = $this->get(route('admin.project.index'));

        //Assert
        $response->assertStatus(403);
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での一覧画面アクセスは200されること()
    {
        //Arrange
        $this->actingAs($this->admin);

        //Act
        $response = $this->get(route('admin.project.index'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.project.index');
        $response->assertViewHas('projects');
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での登録画面アクセスは200されること()
    {
        //Arrange
        $this->actingAs($this->admin);

        //Act
        $response = $this->get(route('admin.project.create'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.project.form');
        $response->assertViewHas('formOptions', [
            'route' => ['admin.project.store',],
            'method' => 'post',
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での登録処理が成功されること()
    {
        //Arrange
        $data = factory(Project::class)->raw();
        $this->actingAs($this->admin);
        $this->assertDatabaseMissing('projects', ['code' => $data['code']]);

        //Act
        $response = $this->post(route('admin.project.store'), $data);

        //Assert
        $this->assertDatabaseHas('projects', ['code' => $data['code']]);
        $response->assertRedirect(route('admin.project.index'));
        $response->assertSessionHas('success');
    }


    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での編集画面アクセスは200されること()
    {
        //Arrange
        $this->actingAs($this->admin);

        //Act
        $response = $this->get(route('admin.project.edit', [$this->project->id]));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.project.form');
        $response->assertViewHas('formOptions', [
            'route' => ['admin.project.update', [$this->project->id]],
            'method' => 'put',
        ]);
        $response->assertViewHas('project', $this->project);
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での更新処理が成功されること()
    {
        //Arrange
        $data = $this->project->toArray();
        $testname = 'hoge';
        $this->assertDatabaseMissing('projects', ['name' => $testname, 'code' => $data['code']]);
        $this->actingAs($this->admin);

        //Act
        $data['name'] = $testname;
        $response = $this->put(route('admin.project.update', [$this->project->id]), $data);

        //Assert
        $this->assertDatabaseHas('projects', ['name' => $testname, 'code' => $data['code']]);
        $response->assertRedirect(route('admin.project.index'));
        $response->assertSessionHas('success');
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での削除処理が成功されること()
    {
        //Arrange
        $project = factory(Project::class)->create();
        $data = $project->toArray();
        $this->assertDatabaseHas('projects', ['code' => $data['code']]);
        $this->actingAs($this->admin);

        //Act
        $response = $this->delete(route('admin.project.destroy', [$data['id']]));

        //Assert
        $this->assertSoftDeleted('projects', ['code' => $data['code']]);
        $response->assertRedirect(route('admin.project.index'));
        $response->assertSessionHas('success');
    }
}
