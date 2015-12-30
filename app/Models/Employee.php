<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class employee extends Sximo  {
	
	protected $table = 'tb_employees';
	protected $primaryKey = 'employeeNumber';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_employees.* FROM tb_employees  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_employees.employeeNumber IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
