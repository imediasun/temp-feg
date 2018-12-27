<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorIdUseTvColumnToLocationsetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_location_setups', function (Blueprint $table)  {
            $table->boolean('use_tv')->default(0);
            $table->boolean('location_status')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_location_setups', function (Blueprint $table) {
            $table->dropColumn('use_tv');
            $table->dropColumn('location_status');
        });
    }
}
