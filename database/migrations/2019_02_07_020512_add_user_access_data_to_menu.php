<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAccessDataToMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_menu', function (Blueprint $table) {
            $table->mediumText('user_access_data')->nulllable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_menu', function (Blueprint $table) {
            $table->dropColumn('user_access_data')->nulllable();
        });
    }
}
