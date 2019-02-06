<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSenderCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_sender_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('driver')->nullable();
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('from')->nullable();
            $table->string('name')->nullable();
            $table->string('encryption')->nullable();
            $table->string('sendmail')->nullable();
            $table->boolean('pretend')->nullable();
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
        Schema::drop('email_sender_credentials');
    }
}
