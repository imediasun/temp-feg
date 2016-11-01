<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class VendorAPI extends Sximo  {
	
	protected $table = 'vendor';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT vendor.* FROM vendor ";
	}	

	public static function queryWhere( ){
		
		return "  WHERE id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public static function processApiData($json,$param=null)
    {
        //loop over all records and check if website is not empty then add http:// prefix for it
        $data = array();
        foreach($json as $record){
            if(!empty($record['website'])){
                if(strpos($record['website'],'http') === false){
                    $record['website'] = 'http://'.$record['website'];
                }
            }
            $data[] = $record;
        }
        return $data;
    }


}
