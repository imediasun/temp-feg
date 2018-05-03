<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class pendingrequest extends Sximo  {
	
	protected $table = 'requests';
	protected $primaryKey = 'id';
    public $timestamps = false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

        return " SELECT
  requests.*,
  products.unit_price,
  products.case_price,
  merch_request_status.status,
  vendor.id                   AS vendor_id
FROM requests
  LEFT JOIN merch_request_status
    ON (requests.status_id = merch_request_status.id)
  LEFT OUTER JOIN products
    ON requests.product_id = products.id
  LEFT OUTER JOIN vendor
    ON products.vendor_id = vendor.id
  LEFT JOIN location
    ON location.id = requests.location_id
  LEFT JOIN users RU
    ON RU.id = requests.request_user_id
  LEFT JOIN users PU
    ON PU.id = requests.process_user_id ";
	}	

	public static function queryWhere(  )
    {
        $selectedLocations = \SiteHelpers::getCurrentUserLocationsFromSession();
		return "  WHERE requests.id IS NOT NULL AND requests.location_id IN ($selectedLocations) ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRow( $id )
    {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;

        $result =  \DB::select(
           'select  requests.*,U1.username as request_name,merch_request_status.status ,U2.username process_name,l1.location_name as location,p.vendor_description  FROM requests
LEFT JOIN merch_request_status ON (requests.status_id = merch_request_status.id)
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

    public function getPendingRequests($location_id, $vendor_id){
        $requests = self::with('product')
            ->where('location_id', $location_id)
            ->where('status_id', 1)
            ->whereHas('product', function($q) use($vendor_id) {
                $q->where('vendor_id', $vendor_id);
            })
            ->get();

        return $requests;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo("App\Models\product");
    }
}
