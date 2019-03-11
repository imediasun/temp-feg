<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemEmailConfigNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_email_config_names', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method_name');
            $table->integer('order_type_id');
            $table->boolean('prefer_google_o_auth_mail')->default(false);
            $table->string('from_email_address');
            $table->string('config_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_email_config_names');
    }
}
