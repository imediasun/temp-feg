<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class sbticketsetting extends Sximo  {

    protected $table = 'sbticket_setting';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT sbticket_setting.* FROM sbticket_setting  ";
    }

    public static function queryWhere(  ){

        return "  WHERE sbticket_setting.id IS NOT NULL ";
    }

    public static function queryGroup(){
        return "  ";
    }


}
