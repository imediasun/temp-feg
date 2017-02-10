<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class excludedreaders extends Sximo  {
	
	protected $table = 'reader_exclude';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " SELECT * from reader_exclude ";
	}	

	public static function queryWhere(  ){
		
		return " WHERE id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
