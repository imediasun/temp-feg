<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInSbTicketsTroubleshootingCheckListName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //troubleshooting_check_lists
     //sb_tickets_troubleshooting_check_lists
        $rows = \DB::table('troubleshooting_check_lists')->get();
        foreach ($rows as $row){
            \DB::table('sb_tickets_troubleshooting_check_lists')->where('troubleshooting_check_list_id',$row->id)->update(['check_list_name'=>$row->check_list_name]);
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('sb_tickets_troubleshooting_check_lists')->update(['check_list_name'=>null]);
    }
}
