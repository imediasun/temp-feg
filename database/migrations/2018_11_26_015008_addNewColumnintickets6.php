<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnintickets6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sb_tickets', function (Blueprint $table) {
            $table->integer('functionality_id')->default(0);
            $table->integer('issue_type_id')->default(0);
            $table->timestamp('game_realted_date')->nullable();
            $table->string('part_number')->nullable();
            $table->integer('issue_type_id')->default(0);
            $table->decimal('cost',10,5)->default(0);
            $table->integer('qty')->default(0);
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
            $table->dropColumn('functionality_id');
            $table->dropColumn('issue_type_id');
            $table->dropColumn('game_realted_date');
            $table->dropColumn('part_number');
            $table->dropColumn('cost');
            $table->dropColumn('qty');
        });
    }
}
