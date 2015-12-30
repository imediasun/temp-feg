<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class vendor extends Sximo  {
	
	protected $table = 'tb_vendor';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_vendor.* FROM tb_vendor ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_vendor.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
