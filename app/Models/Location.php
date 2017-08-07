<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;

class location extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

    public static function getQuery( ) {
        $roleSQL = \SiteHelpers::getUniqueLocationUserAssignmentMeta('sql');
        $locationFields = "
            location.id,
            location.store_id,
            location.location_name,
            location.location_name_short, 
            location.mail_attention,
            location.street1,
            location.city, 
            location.state,
            location.zip,
            location.attn,
            location.company_id,
            location.self_owned, 
            location.loading_info,
            location.post_add_action_done,
            location.date_added, 
            location.date_opened,
            location.date_closed,
            location.region_id, 
            location.loc_group_id,
            location.debit_type_id,
            location.can_ship, 
            location.loc_ship_to,
            location.phone,
            location.bestbuy_store_number,
            location.bill_debit_type,
            location.bill_debit_amt,
            location.bill_debit_detail,
            location.bill_ticket_type,
            location.bill_ticket_amt,
            location.bill_ticket_detail,
            location.bill_thermalpaper_type,
            location.bill_thermalpaper_amt,
            location.bill_thermalpaper_detail,
            location.bill_token_type, 
            location.bill_token_amt,
            location.bill_token_detail,
            location.bill_license_type, 
            location.bill_license_amt,
            location.bill_license_detail,
            location.bill_attraction_type,
            location.bill_attraction_amt,
            location.bill_attraction_detail,
            location.bill_redemption_type,
            location.bill_redemption_amt,
            location.bill_redemption_detail,
            location.bill_instant_type,
            location.bill_instant_amt,
            location.bill_instant_detail,
            location.no_games,
            location.liftgate,
            location.ipaddress,
            location.reporting,
            location.active";
        $sql = "SELECT ".$roleSQL['select'] . ", $locationFields 
            FROM location " .$roleSQL['join'];
        return $sql;
    }
	public static function querySelect(  ){        
		return self::getQuery();
	}	

	public static function queryWhere(  ){
		
		return " Where location.id is not null  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRow($id)
    {       
        if (empty($id)) {
            return false;
        }
        $sql = self::querySelect();
        $rows = \DB::select($sql." WHERE location.id='$id'");
        return $rows;
    }

}
