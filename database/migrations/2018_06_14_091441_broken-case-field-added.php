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
        DB::statement("ALTER TABLE `order_contents` ADD COLUMN `is_broken_case` TINYINT(1) DEFAULT 0 NOT NULL AFTER `upc_barcode`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `order_contents` DROP COLUMN `is_broken_case`; ");
    }
}
