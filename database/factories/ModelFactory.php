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

    return [
        'nick' => $faker->unique()->firstName,
        'name' => $faker->name,
        'email' => $faker->email,
        'work_email' => $faker->email,
        'role_id' => mt_rand(1, 3),
        'job_id' => mt_rand(1, 20),
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
    ];
});

$factory->define(App\Log::class, function (Faker\Generator $faker) {
    return [
        'user_id' => mt_rand(1, 200),
        'module'  => 'auth',
        'action'  => 'Авторизація',
        'desc'    => 'Користувач увійшов в систему',
        'date'    => $faker->dateTime,
    ];
});
