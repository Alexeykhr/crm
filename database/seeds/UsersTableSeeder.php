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
            'phone' => '+38 (480) 684-89-25',
            'phone_work' => '556-84-21',
            'email' => 'admin@gmail.com',
            'email_work' => 'info@example.com',
            'password' => bcrypt('admin123'),
        ]);

        User::insert([
            'nickname' => 'user',
            'first_name' => 'Second',
            'last_name' => 'User',
            'password' => bcrypt('admin123'),
        ]);
    }
}
