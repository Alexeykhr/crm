<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->tinyInteger('level')->default(1);
            $table->string('background')->default('#e0e0e0');
            $table->string('color')->default('#000');
            $table->tinyInteger('acs_user')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_role')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_job')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_folder')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_file')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_profile')->default(1); // [0, 1]
            $table->tinyInteger('acs_birthday')->default(1); // [0, 1]
            $table->tinyInteger('acs_log')->default(0); // [0, 1]
            $table->timestamps();
        });

        DB::table('roles')->insert([
            'title' => 'Адміністратор',
            'level' => 10,
            'background' => '#ef5350',
            'color' => '#fff',
            'acs_user' => 15,
            'acs_role' => 15,
            'acs_job' => 15,
            'acs_folder' => 15,
            'acs_file' => 15,
            'acs_log' => 1,
        ]);

        DB::table('roles')->insert([
            'title' => 'Модератор',
            'level' => 6,
            'background' => '#4caf50',
            'color' => '#fff',
        ]);

        DB::table('roles')->insert([
            'title' => 'Користувач',
            'background' => '#78909c',
            'color' => '#fff',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
