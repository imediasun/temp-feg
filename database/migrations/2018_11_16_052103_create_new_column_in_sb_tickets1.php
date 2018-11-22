<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewColumnInSbTickets1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sb_tickets', function (Blueprint $table) {
            $table->integer('shipping_priority_id')->nullable();
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
                $table->dropColumn('shipping_priority_id');
            });

    }
}
