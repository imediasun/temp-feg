<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewTableShippingPriority extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_priorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('priority_name');
            $table->integer('is_active')->default(1);
        });
        $entries = ['Ground','2-Day','Next Day','Next Day Priority','Next Day Saturday'];
        foreach ($entries as $entry){
            $sql = "INSERT INTO shipping_priorities(`priority_name`) VALUES ('".$entry."');";
            \DB::statement(\DB::raw($sql));
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipping_priorities');
    }
}
