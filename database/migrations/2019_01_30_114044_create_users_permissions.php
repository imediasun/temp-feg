<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_users_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id');
            $table->integer('user_id');
            $table->mediumText('user_access_data');
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
