<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertListInPartRequestStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $table = 'part_request_statuses';
    public function up()
    {
        $data = ['Open','Approved','Denied'];
        foreach ($data as $value){
            $sql = "insert into ".$this->table." (status_text) values('".$value."')";
            \DB::statement($sql);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("truncate ".$this->table);
    }
}
