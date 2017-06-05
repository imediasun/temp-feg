<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ordertyperestrictions extends Sximo  {
	
	protected $table = 'order_type';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT order_type.* FROM order_type  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE order_type.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function isApiable($id) {
        $api = self::where('id', $id)->value('api_restricted');
        return empty($api);
    }

}
