<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertGameFunctionalities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rows = ['Game Down','Still Operational'];
        foreach ($rows as $row) {
            \DB::statement("insert into game_functionalities(functionalty_name) values('" . $row . "')");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("truncate game_functionalities");
    }
}
