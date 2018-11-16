<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewColumnInSbTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sb_tickets', function (Blueprint $table) {
            $table->string('ticket_type')->default('debit-card-related');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sb_tickets', function (Blueprint $table) {
            $table->dropColumn('ticket_type');
        });
    }
}
