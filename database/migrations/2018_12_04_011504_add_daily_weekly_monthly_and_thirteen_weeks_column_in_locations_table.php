<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyWeeklyMonthlyAndThirteenWeeksColumnInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('location', function ($table) {
            $table->string('daily_folder_id')->nullable()->after('fedex_number');
            $table->string('weekly_folder_id')->nullable()->after('daily_folder_id');
            $table->string('monthly_folder_id')->nullable()->after('weekly_folder_id');
            $table->string('thirteen_weeks_folder_id')->nullable()->after('monthly_folder_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location', function ($table) {
            $table->dropColumn('daily_folder_id');
            $table->dropColumn('weekly_folder_id');
            $table->dropColumn('monthly_folder_id');
            $table->dropColumn('thirteen_weeks_folder_id');
        });
    }
}
