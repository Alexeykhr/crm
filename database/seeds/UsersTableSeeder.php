<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'nickname' => 'admin',
            'first_name' => 'Alexey',
            'last_name' => 'Khrusch',
            'password' => bcrypt('admin123'),
        ]);
    }
}
