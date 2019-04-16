<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInTrubleshootingCheckListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'troubleshooting_check_lists';
        $rows = [
            'Checked AC line voltage present',
        'Checked fuses and power module/EMI filter',
        'Checked ALL connections',
        'Checked power supply voltages withing range from a logic board',
        'Ran self-tests from game program',
        'Swapped board or CPU with similar game and problem persisted',
        'Video signal present',
        'Ran restore disks, if applicable',
        'Ticket dispenser swapped',
        'Calibrated controls and settings',
        'Fans are working',
        'Searched the FEG website for troubleshooting info (training, technical, SOPs, forum, etc.)',
        'Consulted with the manufacturer\'s technical support department',
        ];

        foreach ($rows as $row){
            \App\Models\Troubleshootingchecklist::forceCreate(['check_list_name'=>$row]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("truncate troubleshooting_check_lists");
    }
}
