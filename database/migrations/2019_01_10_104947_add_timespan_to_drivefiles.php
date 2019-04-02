<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimespanToDrivefiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('location', function (Blueprint $table) {
            $table->dateTime('daily_script_time')->nullable();
            $table->dateTime('weekly_script_time')->nullable();
            $table->dateTime('13weeks_script_time')->nullable();
            $table->dateTime('monthly_script_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location', function (Blueprint $table) {
            $table->dropColumn('daily_script_time');
            $table->dropColumn('weekly_script_time');
            $table->dropColumn('13weeks_script_time');
            $table->dropColumn('monthly_script_time');
        });
    }
}
