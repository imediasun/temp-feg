<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelsGoogleDriveAuthTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_drive_auth_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('redirect_link')->nullable();
            $table->string('oauth_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('oauth_email')->nullable();
            $table->string('oauth_refreshed_at')->nullable();
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
        Schema::drop('google_drive_auth_tokens');
    }
}
