<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductSubTypeName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::update("update product_type set product_type='win everytime crane', type_description ='Win Everytime Crane' where id = 31");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::update("update product_type set product_type='crane', type_description ='Crane' where id = 31");

    }
}
