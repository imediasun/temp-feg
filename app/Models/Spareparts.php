<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class spareparts extends Sximo  {
	
	protected $table = 'spare_parts';
	protected $primaryKey = 'id';
	public static $AVAILABLE = 2;
	public static $CLAIMED = 1;
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

        return "  SELECT
  spare_parts.*
FROM spare_parts
  LEFT JOIN location L
    ON l.id = spare_parts.loc_id
  LEFT JOIN location CL
    ON CL.id = spare_parts.claimed_location_id
  LEFT JOIN game_title
    ON game_title.id = spare_parts.game_title_id
  LEFT JOIN spare_status
    ON spare_status.id = spare_parts.status_id
  LEFT JOIN users
    ON users.id = spare_parts.claimed_by
  ";
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
