<?php namespace App\Models\Core;

use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Users extends Sximo  {
	
	protected $table = 'users';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " SELECT  users.*,  tb_groups.name, users.group_id, users.username
FROM users LEFT JOIN tb_groups ON tb_groups.group_id = users.group_id ";
	}	

	public static function queryWhere(  ){
		
		return "    WHERE users.id !=''   ";
	}
	
	public static function queryGroup(){
		return "      ";
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
			$locations = \DB::table('location')
				->select('location.*')
				->where('location.active', 1)->orderBy('location.location_name')
				->get();
			return $locations;
		} else {
			return parent::getComboselect($params, $limit, $parent);
		}
	}
}
