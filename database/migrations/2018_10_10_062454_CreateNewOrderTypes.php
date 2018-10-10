<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewOrderTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            [
                'id' => 27,
                'order_type' => 'Retail',
                'is_merch' => 0,
                'can_request' => 1,
                'api_restricted' => 1,
            ],
            [
                'id' => 28,
                'order_type' => 'Food and Beverage',
                'is_merch' => 0,
                'can_request' => 1,
                'api_restricted' => 1,
            ]
        ];
        foreach($data as $item) {
            \DB::table('order_type')->insert($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            \DB::table('order_type')->whereIn('id',[27,28])->delete();
    }
}
