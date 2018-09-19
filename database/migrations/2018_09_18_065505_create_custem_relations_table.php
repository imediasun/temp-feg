<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustemRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custem_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('related_id');
            $table->integer('related_to');
            $table->string('related_type');
            $table->string('related_type_to');
            $table->integer('is_excluded')->default('0');
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
        Schema::drop('custem_relations');
    }
}
