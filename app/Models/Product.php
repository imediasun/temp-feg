<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class product extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT products.* FROM products  ";
	}	

	public static function queryWhere($product_type_id=null){
		$return="WHERE products.id IS NOT NULL";
        if($product_type_id!= null)
        {
        $return.=" AND products.prod_type_id=".$product_type_id;
        }
        return $return;
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
