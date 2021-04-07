<?php

use App\Models\Project;
use App\Models\User;
use App\Models\WorkRecord;
use App\Models\WorkRecordDetail;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(User::class)->state('admin')->create([
            'email' => 'admin@example.com'
        ]);
        factory(User::class, 2)->create();

        factory(Project::class, 10)->create();

        factory(WorkRecord::class, 190)->create()->each(function ($wr) {
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
}
