<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class employee extends Sximo  {
	
	protected $table = 'employees';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT employees.*, users.user_name,users.loc_1, user_level.user_level
FROM employees 
JOIN users ON employees.user_id = users.id
JOIN  user_level ON users.user_level = user_level.id ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE employees.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
