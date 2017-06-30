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
        'active' => mt_rand(0, 1),
        'delete' => mt_rand(0, 1),
        'phone' => $faker->phoneNumber,
        'work_phone' => $faker->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
    ];
});
