<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): Void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        factory(Project::class, 10)->create();
        factory(WorkRecord::class, 60)->create()->each(function ($wr) {
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

        //Act
        $response = $this->get(route('home'));

        //Assert
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * @return void
     */
    public function 認証状態での一覧画面アクセスは200されること()
    {
        //Arrange
        $this->actingAs($this->user);
        $today = Carbon::now()->format('Y-m-d');

        //Act
        $response = $this->get(route('home'));

        //Assert
        $response->assertStatus(200);
        $response->assertViewHas('today', $today);
        $response->assertViewHas('wdcnt');
        $response->assertViewHas('wt');
        $response->assertViewHas('ot');
        $response->assertViewHas('wt_per_project');
    }
}
