<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorImportProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableQeury = 'CREATE TABLE vendor_import_products like products';

        $addColumnQuerys = [
            'ALTER TABLE vendor_import_products ADD import_vendor_id int(11) DEFAULT 0;',
            'ALTER TABLE vendor_import_products ADD product_id int(11) DEFAULT 0;',
            'ALTER TABLE vendor_import_products ADD is_imported int(11) DEFAULT 0;',
            'ALTER TABLE vendor_import_products ADD imported_by int(11) DEFAULT NULL;',
            'ALTER TABLE vendor_import_products ADD imported_at TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;',
            'ALTER TABLE vendor_import_products ADD is_omitted int(11) DEFAULT 0;',
        ];

       \DB::statement($tableQeury);

        foreach($addColumnQuerys as $addColumnQuery) {
            \DB::statement($addColumnQuery);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendor_import_products');
    }
}
