<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $up = [
        400000,
        600000,
        800000,
        1000000,
        1200000,
        1400000,
        1600000,
    ];
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'is_admin' => false,
        'monthly_unit_price' => $faker->randomElement($up),
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'admin', function ($faker) {
    return [
        'is_admin' => true,
    ];
});
