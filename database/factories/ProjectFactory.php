<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        //
        'code' => $faker->unique()->regexify('[A-Z]{3,5}[0-9]{3,5}'),
        'name' => $faker->word,
    ];
});
