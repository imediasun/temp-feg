<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderTypePartySuppliesToMarketingInOrderTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_type', function (Blueprint $table) {

            \DB::table('order_type')
                ->where('id', 17)
                ->update(['order_type'=>'Marketing']);

            \DB::table('order_type')
                ->where('id', 21)
                ->delete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_type', function (Blueprint $table) {

            \DB::table('order_type')
                ->where('id', 17)
                ->update(['order_type'=>'Party Supplies']);

            $twentyFirstOrderType = \DB::table('order_type')
                ->where('id', 21)
                ->first();

            if(!$twentyFirstOrderType)
            {
                \DB::table('order_type')
                    ->insert([
                        'id'                =>  21,
                        'order_type'        =>  'Marketing',
                        'is_merch'          =>  0,
                        'can_request'       =>  0,
                        'api_restricted'    =>  1
                    ]);
            }

        });
    }
}
