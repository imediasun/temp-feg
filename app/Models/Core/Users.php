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
		
		return " SELECT  users.*,  tb_groups.name, users.user_level as group_id, users.user_name as username
FROM users LEFT JOIN tb_groups ON tb_groups.group_id = users.user_level ";
	}	

	public static function queryWhere(  ){
		
		return "    WHERE users.id !=''   ";
	}
	
	public static function queryGroup(){
		return "      ";
	}
	

}
