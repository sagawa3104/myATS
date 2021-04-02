<?php

namespace Tests\Feature\App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->states('admin')->create();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     * @return void
     */
    public function 未認証状態での一覧画面アクセスはログイン画面へリダイレクトされること()
    {
        //Act
        $response = $this->get(route('admin.user.index'));

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
        $response = $this->get(route('admin.user.index'));

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
        $response = $this->get(route('admin.user.index'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.user.index');
        $response->assertViewHas('users');
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
        $response = $this->get(route('admin.user.create'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.user.form');
        $response->assertViewHas('formOptions', [
            'route' => ['admin.user.store',],
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
        $userdata = factory(User::class)->raw(['password' => 'password']);
        $this->actingAs($this->admin);
        $this->assertDatabaseMissing('users', ['email' => $userdata['email']]);

        //Act
        $response = $this->post(route('admin.user.store'), $userdata);

        //Assert
        $this->assertDatabaseHas('users', ['email' => $userdata['email']]);
        $response->assertRedirect(route('admin.user.index'));
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
        $response = $this->get(route('admin.user.edit', [$this->user->id]));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.user.form');
        $response->assertViewHas('formOptions', [
            'route' => ['admin.user.update', [$this->user->id]],
            'method' => 'put',
        ]);
        $response->assertViewHas('user', $this->user);
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での更新処理が成功されること()
    {
        //Arrange
        $userdata = $this->user->toArray();
        $testname = 'hoge';
        $this->assertDatabaseMissing('users', ['name' => $testname, 'email' => $userdata['email']]);
        $this->actingAs($this->admin);

        //Act
        $userdata['name'] = $testname;
        $response = $this->put(route('admin.user.update', [$this->user->id]), $userdata);

        //Assert
        $this->assertDatabaseHas('users', ['name' => $testname, 'email' => $userdata['email']]);
        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas('success');
    }

    /**
     * @test
     * @return void
     */
    public function 管理者で認証状態での削除処理が成功されること()
    {
        //Arrange
        $user = factory(User::class)->create();
        $userdata = $user->toArray();
        $this->assertDatabaseHas('users', ['email' => $userdata['email']]);
        $this->actingAs($this->admin);

        //Act
        $response = $this->delete(route('admin.user.destroy', [$userdata['id']]));

        //Assert
        $this->assertSoftDeleted('users', ['email' => $userdata['email']]);
        $response->assertRedirect(route('admin.user.index'));
        $response->assertSessionHas('success');
    }
}
