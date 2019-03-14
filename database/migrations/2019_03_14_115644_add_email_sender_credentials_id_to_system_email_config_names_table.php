<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailSenderCredentialsIdToSystemEmailConfigNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_email_config_names', function (Blueprint $table) {
            $table->dropColumn('from_email_address');
            $table->integer('email_sender_credentials_id');
        });
        \App\SystemEmailConfigName::where('config_name', 'Request Invoice - Debit Card')->update(['email_sender_credentials_id'=>1]);
        \App\SystemEmailConfigName::where('config_name', 'Request Invoice - Games')->update(['email_sender_credentials_id'=>2]);
        \App\SystemEmailConfigName::where('config_name', 'Request Invoice - Graphics')->update(['email_sender_credentials_id'=>3]);
        \App\SystemEmailConfigName::where('config_name', 'Request Invoice - Merchandise')->update(['email_sender_credentials_id'=>4]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_email_config_names', function (Blueprint $table) {
            $table->dropColumn('email_sender_credentials_id');
            $table->string('from_email_address');
        });
    }
}
