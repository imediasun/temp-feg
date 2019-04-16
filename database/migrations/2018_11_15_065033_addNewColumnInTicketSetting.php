<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInTicketSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sbticket_setting', function (Blueprint $table) {
            $table->string('setting_type')->default('debit-card-related');
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
            $table->dropColumn('setting_type');
        });
    }
}
