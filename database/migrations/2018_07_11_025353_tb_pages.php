<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TbPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tb_pages','page_content')) {
            Schema::table('tb_pages', function (Blueprint $table) {
                $table->text('page_content')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_pages',function(Blueprint $table){
            $table->dropColumn('page_content');
        });
    }
}
