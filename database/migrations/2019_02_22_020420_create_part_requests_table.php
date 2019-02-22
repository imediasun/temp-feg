<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->string('part_number');
            $table->integer('qty');
            $table->double('cose');
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
        Schema::drop('part_requests');
    }
}
