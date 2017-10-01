<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname', 40)->unique();
            $table->string('password', 60);
            $table->string('first_name', 20);
            $table->string('middle_name', 20)->nullable();
            $table->string('last_name', 20);
            $table->string('image')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_work', 30)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('email_work', 50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        DB::table('users')->insert([
            'first_name' => 'Name',
            'last_name' => 'Family',
            'nickname' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
