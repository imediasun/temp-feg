<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropLocationStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_location_setups', function (Blueprint $table) {
            $table->dropColumn('location_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_location_setups', function (Blueprint $table)  {
            $table->boolean('location_status')->default(0);

        });
    }
}
