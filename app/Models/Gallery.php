<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gallery extends Sximo  {
	
	protected $table = 'img_uploads';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT img_uploads.* FROM img_uploads  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE img_uploads.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
