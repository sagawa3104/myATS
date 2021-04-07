<?php

namespace Tests\Feature\App\Http\Controllers\User;

use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use App\Utils\Generator\WorkdayGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): Void
    {
        parent::setUp();

        factory(User::class)->states('admin')->create();
        factory(User::class, 2)->create();
        factory(Project::class, 10)->create();
        factory(WorkRecord::class, 10)->create()->each(function ($wr) {
            $working_time = $wr->working_time;
            //5以下の約数をランダムに選出し、その数だけ明細を作成する。
            $divisors = collect();
            for ($i = 1; $i < 6; $i++) {
                if ($working_time % $i == 0) {
                    $divisors->push($i);
                }
            }
            $detail_number = $divisors->random();
            factory(WorkRecordDetail::class, $detail_number)->create([
                'work_record_id' => $wr->id,
                'work_time' => $wr->working_time / $detail_number,
            ]);
        });
    }

    /**
     * @test
     * @return void
     */
    public function 未認証状態での一覧画面アクセスはログイン画面へリダイレクトされること()
    {
        //Arrange
        $user = User::inRandomOrder()->first();
        //Act
        $response = $this->get(route('user.workrecord.index', [$user->id]));

        //Assert
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * @return void
     */
    public function 認証状態での他ユーザーへの一覧画面アクセスは403されること()
    {
        //Arrange
        $users = User::where('is_admin', false)->get();
        $user = $users->random();
        $other = $users->filter(function ($value) use ($user) {
            return $value <> $user;
        })->random();
        $this->actingAs($user);

        //Act
        $response = $this->get(route('user.workrecord.index', [$other->id]));

        //Assert
        $response->assertStatus(403);
    }
    /**
     * @test
     * @return void
     */
    public function 認証状態での本人の一覧画面アクセスは200されること()
    {
        //Arrange
        $users = User::where('is_admin', false)->get();
        $user = $users->random();
        $this->actingAs($user);

        //Act
        $response = $this->get(route('user.workrecord.index', [$user->id]));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.workrecord.index');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('workrecords');
    }
    /**
     * @test
     * @return void
     */
    public function 認証状態での他ユーザーの一覧画面アクセスは、管理者の場合200されること()
    {
        //Arrange
        $admin = User::where('is_admin', true)->first();
        $user = User::where('is_admin', false)->get()->random();
        $this->actingAs($admin);

        //Act
        $response = $this->get(route('user.workrecord.index', [$user->id]));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.workrecord.index');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('workrecords');
    }

    /**
     * @test
     * @return void
     */
    public function 認証状態での登録画面アクセスは200されること()
    {
        //Arrange
        $user = User::where('is_admin', false)->get()->random();
        $this->actingAs($user);
        $projectlist = Project::selectList();
        $workrecord = new WorkRecord([
            'attended_at' => '10:00',
            'left_at' => '19:00',
        ]);

        //Act
        $response = $this->get(route('user.workrecord.create', ['user' => $user->id, 'workday' => '']));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.workrecord.form');
        $response->assertViewHas('formOptions', [
            'route' => ['user.workrecord.store', [$user->id,]],
            'method' => 'post',
        ]);
        $response->assertViewHas('user', $user);
        $response->assertViewHas('projects', $projectlist);
        $response->assertViewHas('workrecord', $workrecord);
    }
    /**
     * @test
     * @return void
     */
    public function 認証状態での登録画面アクセスは日付を指定していた場合、反映されること()
    {
        //Arrange
        $user = User::where('is_admin', false)->get()->random();
        $this->actingAs($user);
        $projectlist = Project::selectList();
        $workday = '2021-04-01';
        $workrecord = new WorkRecord([
            'workday' => $workday,
            'attended_at' => '10:00',
            'left_at' => '19:00',
        ]);

        //Act
        $response = $this->get(route('user.workrecord.create', ['user' => $user->id, 'workday' => $workday]));

        //Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.workrecord.form');
        $response->assertViewHas('formOptions', [
            'route' => ['user.workrecord.store', [$user->id,]],
            'method' => 'post',
        ]);
        $response->assertViewHas('user', $user);
        $response->assertViewHas('projects', $projectlist);
        $response->assertViewHas('workrecord', $workrecord);
    }

    /**
     * @test
     * @return void
     */
    public function 認証状態での登録処理が成功されること()
    {
        //Arrange
        $user = User::where('is_admin', false)->get()->random();
        $this->actingAs($user);
        $wr = WorkRecord::where('user_id', $user->id)->get();
        $st = Carbon::now()->subWeekdays(3);
        $ed = Carbon::now();
        $wd = WorkdayGenerator::periodPerDay($st, $ed, true);

        $data =
            [
                'workday' => '2020-01-01',
                'attended_at' => '10:00',
                'left_at' => '19:00',
                'workRecordDetails' => [
                    0 => [
                        'project_code' => Project::inRandomOrder()->first()->code,
                        'work_time' => '04:00',
                        'content' => 'content1',
                    ],
                    1 => [
                        'project_code' => Project::inRandomOrder()->first()->code,
                        'work_time' => '04:00',
                        'content' => 'content2',
                    ],
                ],
            ];

        //Act
        $response = $this->post(route('user.workrecord.store', [$user->id]), $data);

        //Assert
        $this->assertDatabaseHas('work_records', [
            'user_id' => $user->id,
            'workday' => '2020-01-01',
            'attended_at' => '10:00',
            'left_at' => '19:00',
        ]);
        $response->assertRedirect(route('user.workrecord.index', [$user->id]));
        $response->assertSessionHas('success');
    }
}
