<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\WorkRecord;
use App\Utils\Generator\WorkdayGenerator;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(WorkRecord::class, function (Faker $faker) {

    $users = User::all()->pluck('id');

    $start = Carbon::now()->subQuarter();
    $end = Carbon::now();

    $days = WorkdayGenerator::periodPerDay($start, $end, true);

    $matrix = $users->crossJoin($days);
    $keys = $faker->unique()->randomElement($matrix);

    $times = $faker->randomElement([
        ['10:00', '19:00', 480, 60, 0],
        ['10:00', '20:00', 540, 60, 60],
        ['09:00', '18:30', 510, 60, 30],
        ['10:00', '22:00', 660, 60, 120],
        ['10:00', '19:30', 510, 60, 30],
        ['09:30', '19:00', 510, 60, 30],
        ['10:00', '18:30', 465, 45, -15],
        ['09:00', '18:00', 480, 60, 0],
    ]);

    return [
        'user_id' => $keys[0],
        'workday' => $keys[1],
        'attended_at' => $times[0],
        'left_at' => $times[1],
        'working_time' => $times[2],
        'break_time' => $times[3],
        'overtime' => $times[4],
    ];
});
