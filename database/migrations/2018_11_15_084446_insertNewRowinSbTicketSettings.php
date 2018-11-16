<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNewRowinSbTicketSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql ="INSERT INTO `sbticket_setting` (`role1`, `role2`, `role3`, `role4`, `role5`, `individual1`, `individual2`, `individual3`, `individual4`, `individual5`, `updated_at`, `setting_type`) VALUES('','','','','','15137','15021','','','','2016-02-17 04:12:38','game-related');" ;
        \DB::statement(\DB::raw($sql));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement(\DB::raw('delete from sbticket_setting where id=2;'));
    }
}
