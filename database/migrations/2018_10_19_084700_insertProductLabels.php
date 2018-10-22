<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertProductLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\ProductLabel::insert([
            ['label_text'=>'Hot','label_color'=>'label-warning','active'=>1],
            ['label_text'=>'New','label_color'=>'label-success','active'=>1],
            ['label_text'=>'Back in Stock','label_color'=>'label-default','active'=>1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\ProductLabel::truncate();
    }
}
