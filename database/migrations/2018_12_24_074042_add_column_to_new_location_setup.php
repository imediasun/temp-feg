<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToNewLocationSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_location_setups', function (Blueprint $table) {
            $table->boolean('is_location_synced')->default(0);
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
            $table->dropColumn('is_location_synced');
        });
    }
}
