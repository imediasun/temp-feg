<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUsersAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_users_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->mediumText('user_access_data')->nullable();
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
        Schema::drop('tb_users_access');
    }
}
