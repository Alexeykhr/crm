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
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('photo')->nullable();
            $table->integer('role_id');
            $table->integer('job_id')->nullable();
            $table->string('email')->nullable();
            $table->string('work_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->timestamp('hire')->nullable();
            $table->timestamp('birth')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('delete')->default(0);
            $table->timestamps();
        });

        DB::table('users')->insert([
            'nick'     => 'admin',
            'name'     => 'Адміністратор',
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
