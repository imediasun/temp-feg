<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class manageservicerequests extends Sximo  {
	
	protected $table = 'service_requests';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT service_requests.*,u1.username,location.location_name_short FROM service_requests LEFT JOIN users u1 ON (service_requests.requestor_id = u1.id)
			      LEFT JOIN location ON (service_requests.location_id = location.id)" ;
	}

	public static function queryWhere(  ){
		
		return "  WHERE service_requests.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	/**
	 * override location drop down menu
	 * @param $params
	 * @param null $limit
	 * @param null $parent
	 * @return mixed
	 */
	public static function getComboselect($params, $limit = null, $parent = null)
	{
		$tableName = $params[0];
		if ($tableName == 'location') {
			return parent::getUserAssignedLocation($params, $limit, $parent);
		} else {
			return parent::getComboselect($params, $limit, $parent);
		}
	}

}
