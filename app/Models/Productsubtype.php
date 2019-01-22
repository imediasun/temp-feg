<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class productsubtype extends Sximo  {
	
	protected $table = 'product_type';
	protected $primaryKey = 'id';
	use SoftDeletes;
    protected $dates = ['deleted_at'];

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT * FROM product_type  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE product_type.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
