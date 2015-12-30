<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class calendar extends Sximo  {
	
	protected $table = 'tb_calendar';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_calendar.* FROM tb_calendar  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_calendar.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
