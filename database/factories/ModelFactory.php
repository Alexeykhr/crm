<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $role = \App\Role::inRandomOrder()->first();
    $job = \App\Job::inRandomOrder()->first();

    return [
        'nick' => $faker->unique()->firstName,
        'name' => $faker->name,
        'email' => $faker->email,
        'work_email' => $faker->email,
        'role_id' => $role->id,
        'job_id' => $job->id,
        'active' => mt_rand(0, 1),
        'delete' => mt_rand(0, 1),
        'phone' => $faker->phoneNumber,
        'hire' => $faker->dateTime,
        'birth' => $faker->dateTime,
        'work_phone' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});

$factory->define(App\Job::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->jobTitle,
        'active' => mt_rand(0, 1),
        'delete' => mt_rand(0, 1),
    ];
});

$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->jobTitle,
        'level' => mt_rand(0, 9),
        'color' => $faker->hexColor,
        'active' => mt_rand(0, 1),
        'delete' => mt_rand(0, 1),
    ];
});
