<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Project;
use App\Models\WorkRecordDetail;
use Faker\Generator as Faker;

$factory->define(WorkRecordDetail::class, function (Faker $faker) {
    $project = Project::all()->random();
    return [
        //
        'work_record_id' => 1,
        'project_id' => $project->id,
        'work_time' => 480,
        'content' => 'aaa',
    ];
});
