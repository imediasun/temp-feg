<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnInVendorImportProductsMissingItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_import_products', function (Blueprint $table) {
            $table->integer('is_missing_in_file')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_import_products', function (Blueprint $table) {
            $table->dropColumn('is_missing_in_file');
        });
    }
}
