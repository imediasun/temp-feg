<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class gamesdisposed extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT game.* FROM game  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL AND sold = 1 ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getDownloadDisposedData()
    {
        $data=\DB::select('SELECT V.vendor_name AS Manufacturer,
									 IF(G.test_piece = 1,CONCAT("**TEST** ",T.game_title),T.game_title) AS Game_Title,
									 G.version, G.serial, IF(G.date_in_service = "0000-00-00", "", G.date_in_service) AS "Date In Service",
									 G.id,
									 IF(G.location_id = 0,CONCAT(G.prev_location_id, " | ", L2.location_name_short),CONCAT(G.location_id, " | ", L.location_name_short)) AS LastLocation,
									 L.city,
									 L.state,
									 date_sold AS DateSold,
									 sold_to AS SoldTo,
									 G.sale_price AS Wholesale,
									 IF(G.sale_price >= 1000,
									 	ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
									 	(G.sale_price+100)) AS Retail,
									 G.notes AS Notes
								FROM game G
						   LEFT JOIN game_title T ON G.game_title_id = T.id
						   LEFT JOIN vendor V ON V.id = T.mfg_id
						   LEFT JOIN location L ON L.id = G.location_id
						   LEFT JOIN location L2 ON L2.id = G.prev_location_id
							   WHERE G.sold = 1
						    ORDER BY T.game_title ASC, G.location_id');
        return $data;
    }
	

}
