<?php

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
            $table->string('nick')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('role')->default('user');
            $table->string('phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('delete')->default(0);
            $table->timestamp('hire')->nullable();
            $table->timestamp('birth')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'nick'     => 'admin',
            'name'     => 'Admin',
            'role'     => 'administrator',
            'password' => bcrypt('admin'),
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
