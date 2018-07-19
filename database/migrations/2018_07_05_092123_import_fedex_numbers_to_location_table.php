<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportFedexNumbersToLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('location', 'created_at') && !Schema::hasColumn('location', 'updated_at')) {

            Schema::table('location', function (Blueprint $table) {
                $table->timestamps();
            });

        }



        $fedex_number = [
            ['fedex_number'=>882641163,'id'=>6000],
            ['fedex_number'=>884838282,'id'=>2007],
            ['fedex_number'=>884134005,'id'=>2008],
            ['fedex_number'=>882966844,'id'=>2009],
            ['fedex_number'=>882966984,'id'=>2012],
            ['fedex_number'=>362304970,'id'=>2017],
            ['fedex_number'=>883100867,'id'=>2022],
            ['fedex_number'=>362306370,'id'=>2023],
            ['fedex_number'=>361676270,'id'=>2030],
            ['fedex_number'=>882881245,'id'=>2031],
            ['fedex_number'=>883353641,'id'=>2035],
            ['fedex_number'=>883945123,'id'=>2017],
            ['fedex_number'=>883101227,'id'=>2038],
            ['fedex_number'=>883101367,'id'=>2039],
            ['fedex_number'=>882755169,'id'=>2040],
            ['fedex_number'=>882755509,'id'=>6002],
            ['fedex_number'=>882755649,'id'=>6003],
            ['fedex_number'=>883101707,'id'=>6004],
            ['fedex_number'=>882881725,'id'=>6005],
            ['fedex_number'=>883353781,'id'=>6006],
            ['fedex_number'=>882640183,'id'=>6007],
            ['fedex_number'=>882967484,'id'=>6008],
            ['fedex_number'=>882640663,'id'=>6009],
            ['fedex_number'=>361676750,'id'=>6010],
            ['fedex_number'=>883946383,'id'=>6011],
            ['fedex_number'=>884879922,'id'=>6012],
            ['fedex_number'=>882968324,'id'=>6014],
            ['fedex_number'=>882641023,'id'=>6015],
            ['fedex_number'=>883102347,'id'=>6020],
            ['fedex_number'=>883386108,'id'=>6021],
            ['fedex_number'=>362325790,'id'=>6022],
            ['fedex_number'=>883386248,'id'=>7001],
            ['fedex_number'=>290389925,'id'=>6001],
            ['fedex_number'=>859590853,'id'=>6023],
            ['fedex_number'=>858424259,'id'=>2041]

        ];

        foreach ($fedex_number as $location){

            \App\Models\location::where('id', $location['id'])->update([
                'fedex_number'=>$location['fedex_number']
            ]);

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
}
