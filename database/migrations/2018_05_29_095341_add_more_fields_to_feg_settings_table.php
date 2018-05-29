<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToFegSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feg_system_options', function (Blueprint $table) {
            $table->string('option_title')->after('notes')->nullable();
            $table->longText('option_description')->after('option_title')->nullable();
            $table->longText('option_form_element_details')->after('option_description')->nullable();
        });

        \App\Models\Feg\System\Options::addOption('order_receipt_reminder_days_threshold', 10, [
            'option_title' => 'Order Receipt Reminder',
            'option_description' => '# of days to wait after invoice = verified before sending order.',
            'option_form_element_details' => json_encode([
                "element" => "input",
                "type" => "number",
                "attr" => 'min="0"',
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feg_system_options', function (Blueprint $table) {
            $table->dropColumn('option_title');
            $table->dropColumn('option_description');
            $table->dropColumn('option_form_element_details');
        });
    }
}
