<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSbTicketsTroubleshootingCheckListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sb_tickets_troubleshooting_check_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sb_ticket_id');
            $table->integer('troubleshooting_check_list_id');
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
        Schema::drop('sb_tickets_troubleshooting_check_lists');
    }
}
