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
        'desc' => $faker->text(255),
    ];
});

$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->jobTitle,
        'level' => mt_rand(0, 9),
        'color' => $faker->rgbColor,
    ];
});

$factory->define(\App\Folder::class, function (Faker\Generator $faker) {
    $job = mt_rand(1, 5) == 4 ? \App\Folder::inRandomOrder()->first()->id : null;

    return [
        'parent_id' => $job,
        'title' => $faker->title,
        'desc' => $faker->text(255),
    ];
});

$factory->define(\App\AccessDir::class, function (Faker\Generator $faker) {
    $folder = \App\Folder::inRandomOrder()->first();
    $rnd = mt_rand(0, 2);
    $id = null;

    switch ($rnd) {
        case 0:
            $id = \App\User::inRandomOrder()->first()->id;
            break;

        case 1:
            $id = \App\Role::inRandomOrder()->first()->id;
            break;

        case 2:
            $id = \App\Job::inRandomOrder()->first()->id;
            break;
    }

    return [
        'folder_id' => $folder->id,
        'user_id' => $rnd == 0 ? $id : null,
        'role_id' => $rnd == 1 ? $id : null,
        'job_id' => $rnd == 2 ? $id : null,
        'access' => mt_rand(1, 15),
    ];
});
