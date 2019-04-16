<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInSbTicketsTroubleshootingCheckLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sb_tickets_troubleshooting_check_lists', function (Blueprint $table) {
            $table->string('check_list_name')->nulable();
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
            $table->dropColumn('check_list_name');
        });
    }
}
