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
		
		return "  SELECT employees.*, users.username,users.loc_1, tb_groups.group_id
FROM employees 
JOIN users ON employees.user_id = users.id
JOIN  tb_groups ON users.group_id = tb_groups.group_id ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE employees.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
