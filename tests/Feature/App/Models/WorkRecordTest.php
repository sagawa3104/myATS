<?php

namespace Tests\Feature\app\Models;

use App\Models\User;
use App\Models\WorkRecord;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WorkRecordTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        factory(User::class)->states('admin')->create();
        factory(User::class, 3)->create();

        // $wr = factory(WorkRecord::class, 260)->create();
    }

    /**
     * @test
     */
    public function user_idとworkdayのユニークテスト()
    {
        //Arrange
        $workRecord1 = factory(WorkRecord::class)->create();
        $workday = new Carbon($workRecord1->workday);

        $workRecord2 = $workRecord1->replicate();

        //Act
        try {
            $result_1 = $workRecord2->validate();
        } catch (ValidationException $e) {
            $result_1 = false;
        }

        $workRecord2->workday = $workday->addDay()->format('Y-m-d');
        try {
            $result_2 = $workRecord2->validate();
        } catch (ValidationException $e) {
            $result_2 = false;
        }

        //Assert
        $this->assertFalse($result_1);
        $this->assertTrue($result_2);
    }
}
