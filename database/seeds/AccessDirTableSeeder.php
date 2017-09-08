<?php

use Illuminate\Database\Seeder;

class AccessDirTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\AccessDir::class, 100)->create();
    }
}
