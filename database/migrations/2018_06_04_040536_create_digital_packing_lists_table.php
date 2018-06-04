<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDigitalPackingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_packing_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('name');
            $table->integer('location_id');
            $table->integer('type_id');
            $table->timestamps();
            $table->timestamp('downloaded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('digital_packing_lists');
    }
}
