<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewLocationSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_location_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->string('vendor_id')->nullable();
            $table->string('teamviewer_id');
            $table->string('teamviewer_passowrd');
            $table->tinyInteger('is_server_locked')->default(0);
            $table->string('windows_user')->nullable();
            $table->string('windows_user_password')->nullable();
            $table->tinyInteger('is_remote_desktop')->default(0);
            $table->string('rdp_computer_name')->nullable();
            $table->string('rdp_computer_user')->nullable();
            $table->string('rdp_computer_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('new_location_setups');
    }
}
