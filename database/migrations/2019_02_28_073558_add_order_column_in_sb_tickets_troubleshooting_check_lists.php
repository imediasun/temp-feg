<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderColumnInSbTicketsTroubleshootingCheckLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sb_tickets_troubleshooting_check_lists', function (Blueprint $table) {
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sb_tickets_troubleshooting_check_lists', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
