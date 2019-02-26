<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInSbticketSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sbticket_setting', function (Blueprint $table) {
            $table->string('user_permission_groups')->nullable();
            $table->string('user_permission')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sbticket_setting', function (Blueprint $table) {
            $table->dropColumn('user_permission_groups');
            $table->dropColumn('user_permission');
        });
    }
}
