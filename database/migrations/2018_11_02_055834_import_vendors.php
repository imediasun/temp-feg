<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->timestamp('email_recieved_at')->nullable();
            $table->integer('is_imported')->default(0);
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
        Schema::drop('import_vendors');
    }
}
