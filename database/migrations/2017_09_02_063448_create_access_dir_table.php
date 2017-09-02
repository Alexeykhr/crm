<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessDirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_dir', function (Blueprint $table) {
            $table->integer('folder_id');
            $table->integer('user_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->smallInteger('access');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_dir');
    }
}