<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BrokenCaseFieldAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_contents', function ($table) {
            $table->tinyInteger('is_broken_case')->default('0')->after('upc_barcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_contents', function ($table) {
            $table->dropColumn('is_broken_case');
        });
    }
}
