<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDataInIssueTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'issue_types';
        /**
         * Troubleshooting Assistance
        Parts Approval
        Advance Request
        Repair Request
         *
         */
        $rows = ['Advance Request','Parts Approval','Repair Request','Troubleshooting Assistance'];
        foreach ($rows as $row){
            \DB::statement("insert into issue_types(issue_type_name) values('".$row."')");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('truncate issue_types');
    }
}
