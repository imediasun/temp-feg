<?php namespace App\Models;

use App\Library\FEGDBRelationHelpers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Locationgroups extends Sximo  {
	
	protected $table = 'l_groups';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT l_groups.*, '' as location_ids, '' as excluded_product_ids, '' as excluded_product_type_ids FROM l_groups  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE l_groups.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public function locations(){
	    $locationIds = FEGDBRelationHelpers::getCustomRelationRecords($this->id, Locationgroups::class, location::class, 0, true)->pluck('location_id')->toArray();
	    return location::whereIn('id', $locationIds);
    }

	public function excludedProductTypes(){
	    $excludedProductTypeIds = FEGDBRelationHelpers::getCustomRelationRecords($this->id, self::class, Ordertyperestrictions::class, 1, true)->pluck('ordertyperestrictions_id')->toArray();
	    return Ordertyperestrictions::whereIn('id', $excludedProductTypeIds);
    }

	public function excludedProducts(){
	    $excludedProductIds = FEGDBRelationHelpers::getCustomRelationRecords($this->id, self::class, Product::class, 1, true)->pluck('product_id')->toArray();
	    return Product::whereIn('id', $excludedProductIds);
    }
}
