<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class spareparts extends Sximo  {
	
	protected $table = 'spare_parts';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT spare_parts.* FROM spare_parts  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE spare_parts.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	public static function getComboselect($params, $limit = null, $parent = null) {
		$tableName = $params[0];
		if($tableName == 'location'){
			return parent::getUserAssignedLocation($params,$limit,$parent);
		}
		else{
			return parent::getComboselect($params,$limit,$parent);
		}
	}


}
