<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessDirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_dirs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('folder_id');
            $table->integer('user_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->boolean('is_public')->default(0);
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
        Schema::dropIfExists('access_dirs');
    }
}
