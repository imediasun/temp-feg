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
    public function setExcludedData($rows){
        $returnData = [];
        foreach ($rows as $row){
            $locationData = FEGDBRelationHelpers::getCustomRelationRecords($row->id,location::class,self::class,0)->pluck('location_id')->toArray();
            $productData = FEGDBRelationHelpers::getCustomRelationRecords($row->id,product::class,self::class,1)->pluck('product_id')->toArray();
            $productType = FEGDBRelationHelpers::getCustomRelationRecords($row->id,Ordertyperestrictions::class,self::class,1)->pluck('ordertyperestrictions_id')->toArray();

            $locations = location::select(\DB::raw("GROUP_CONCAT(DISTINCT CONCAT(location.id,' ',location.location_name) ORDER BY location.id SEPARATOR '<br>') AS location_names"))->whereIn('id',$locationData)->where('active',1)->get()->pluck('location_names')->toArray();
            $productTypeData = Ordertyperestrictions::select(\DB::raw('group_concat(order_type ORDER BY order_type ASC) as product_types'))->whereIn('id', $productType)->get()->pluck('product_types')->toArray();
            $productsData = product::select(\DB::raw('group_concat(vendor_description ORDER BY vendor_description ASC) as product_name'))->whereIn('id', $productData)->get()->pluck('product_name')->toArray();
            if(!empty($productTypeData[0])){
                $productTypes = str_replace(",",",<br>",$productTypeData[0]);
                $row->excluded_product_type_ids = $productTypes;
            }
            if(!empty($productsData[0])){
                $productName= str_replace(",",",<br>",$productsData[0]);
                $row->excluded_product_ids = $productName;
            }
            if(!empty($locations[0])){
                $locationName= str_replace(",",",<br>",$locations[0]);
                $row->location_ids = $locationName;
            }
            $returnData[] = $row;
        }
        return $returnData;
    }
}
