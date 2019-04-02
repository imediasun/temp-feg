<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileNameFieldInGoogleDriveEarningReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('google_drive_earning_reports', function ($table) {
            $table->string('file_name')->after('google_file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_drive_earning_reports', function ($table) {
            $table->dropColumn('file_name');
        });
    }
}
