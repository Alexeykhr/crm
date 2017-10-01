<?php

use Illuminate\Support\Facades\DB;
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
            $table->string('nickname')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('email')->nullable();
            $table->string('email_work')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('api_token')->unique()->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'first_name' => 'Name',
            'last_name' => 'Family',
            'nickname' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
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
