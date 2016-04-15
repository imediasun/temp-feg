<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class pendingrequest extends Sximo  {
	
	protected $table = 'requests';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT requests.* from requests";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE requests.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getRow( $id )
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        $result =  \DB::select(
           'select  requests.*,U1.username as request_name,U2.username process_name,l1.location_name as location,p.vendor_description  FROM requests
left outer join users U1 on requests.request_user_id = U1.id
left outer join users U2 on requests.process_user_id=U2.id
left outer join location l1 on requests.location_id=l1.id
left outer join products p on requests.product_id=p.id'.
            self::queryWhere().
            " AND ".$table.".".$key." = '{$id}' ".
            self::queryGroup()
        );
        if(count($result) <= 0){
            $result = array();
        } else {

            $result = $result[0];
        }
        return $result;
    }


}
