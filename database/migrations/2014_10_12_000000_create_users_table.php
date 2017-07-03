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
            $table->string('work_email')->nullable();
            $table->string('photo')->nullable();
            $table->string('password');
            $table->integer('role_id')->default(2);
            $table->string('phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('position')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('delete')->default(0);
            $table->timestamp('hire')->nullable();
            $table->timestamp('birth')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'nick'     => 'admin',
            'name'     => 'Admin',
            'role_id'  => 1,
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
