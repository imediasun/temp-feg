<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_import_products', function (Blueprint $table) {
            $table->integer('is_updated')->default(0);
            $table->integer('is_new')->default(0);
            $table->integer('is_deleted')->default(0);
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
            $table->dropColumn('is_updated');
            $table->dropColumn('is_new')->default(0);
            $table->dropColumn('is_deleted')->default(0);
        });
    }
}
