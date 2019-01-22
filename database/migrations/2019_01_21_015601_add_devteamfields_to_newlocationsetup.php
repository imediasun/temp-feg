<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDevteamfieldsToNewlocationsetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_location_setups', function (Blueprint $table) {
            $table->string('vm_url', 200)->nullable();
            $table->string('vm_user', 100)->nullable();
            $table->string('vm_password', 100);
            $table->boolean('sync_install')->nullable();
            $table->time('sync_time')->nullable();
            $table->string('sync_time_zone', 20)->nullable();
            $table->time('sync_difference')->nullable();
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
            $table->dropColumn('vm_url');
            $table->dropColumn('vm_user');
            $table->dropColumn('vm_password');
            $table->dropColumn('sync_install');
            $table->dropColumn('sync_time');
            $table->dropColumn('sync_time_zone');
            $table->dropColumn('sync_difference');
        });
    }
}
