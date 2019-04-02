<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedProdSubTypeIdToOrderContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_contents', function (Blueprint $table) {
            $table->integer('updated_prod_sub_type_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_contents', function (Blueprint $table) {
            $table->dropColumn('updated_prod_sub_type_id');
        });
    }
}
