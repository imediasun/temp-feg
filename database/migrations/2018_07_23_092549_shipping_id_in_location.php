<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShippingIdInLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('location', 'freight_id')) {

            Schema::table('location', function (Blueprint $table) {
                $table->integer('freight_id')->nullable();
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('location', function (Blueprint $table) {
            $table->dropColumn('freight_id');
        });
    }
}
