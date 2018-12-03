<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleDriveEarningReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_drive_earning_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('google_file_id');
            $table->string('web_view_link');
            $table->string('icon_link');
            $table->dateTime('modified_time');
            $table->dateTime('created_time');
            $table->string('mime_type');
            $table->string('parent_id');
            $table->string('location_name');
            $table->integer('loc_id');
            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('google_drive_earning_reports', function (Blueprint $table) {
            Schema::drop('google_drive_earning_reports');
        });
    }
}
