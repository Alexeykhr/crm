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
            $table->string('title', 60)->unique();
            $table->string('desc', 255)->nullable();
            $table->tinyInteger('level')->default(1);
            $table->string('color', 11)->default('218,218,218');
            $table->tinyInteger('acs_user')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_role')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_job')->default(1); // [0, ..,  15]
            $table->tinyInteger('acs_calendar')->default(0); // [0, 1] TODO: on future [0, .., 15]
            $table->boolean('acs_profile')->default(0); // [0, 1]
            $table->boolean('acs_log')->default(0); // [0, 1]
            $table->timestamps();
        });

        DB::table('roles')->insert([
            'title' => 'Адміністратор',
            'level' => 10,
            'color' => '255,5,0',
            'acs_user' => 15,
            'acs_role' => 15,
            'acs_job' => 15,
            'acs_calendar' => 15,
            'acs_profile' => 1,
            'acs_log' => 1,
        ]);

        DB::table('roles')->insert([
            'title' => 'Користувач',
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
