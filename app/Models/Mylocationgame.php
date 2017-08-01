<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class mylocationgame extends Sximo  {
	
	protected $table = 'game';
	protected $primaryKey = 'id';
    public $timestamps = false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "SELECT game.*,game_title.img,game.location_id as location_num FROM game LEFT OUTER JOIN game_title ON game.game_title_id=game_title.id  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE game.id IS NOT NULL AND sold=0";
	}
	
	public static function queryGroup(){
		return "  ";
	}
  /*  public static function getRow( $id )
    {
        $row= \DB::table('game')
            ->leftJoin('game_status', 'game.status_id', '=', 'game_status.id')
            ->leftJoin('game_type','game.game_type_id','=','game_type.id')
            ->leftJoin('game_title','game.game_title_id','=','game_title.id')
            ->leftJoin('game_version','game.version_id','=','game_version.id')
            ->leftJoin('location','game.location_id','=','location.id')
            ->leftJoin('vendor','game.mfg_id','=','vendor.id')
            ->leftJoin('users','game.last_edited_by','=','users.id')
            ->leftJoin('location as l2','game.prev_location_id','=','l2.id')
            ->select('game.*','game_status.game_status','game_title.*','game_type.game_type','game_version.version','game_version.id as version_id','location.location_name','vendor.*','game.id as asset_number','users.first_name','users.last_name','l2.location_name as previous_location')
            ->where('game.id','=',$id)
            ->get();
        return $row;
    }*/
    public static function getRow($id=null)
    {
        $row=\DB::select('SELECT G.id as asset_number,
									  G.game_name,
									  G.prev_game_name,
									  G.version,
									  G.version_id,
									  G.players,
									  G.monitor_size,
									  G.dba,
									  G.sacoa,
									  G.embed,
									  G.rfid,
									  G.notes,
                                      G.freight_order_id,
									  G.location_id,
									  CONCAT(G.location_id," | ",L.location_name_short) AS location_name,
									  V.vendor_name,
									  V.phone AS vendor_phone,
									  V.contact AS vendor_contact,
									  V.email AS vendor_email,
									  V.website AS vendor_website,
									  G.serial,
									  G.date_in_service,
									  G.status_id,
									  G.game_setup_status_id,
									  G.intended_first_location,
									  U.username AS last_edited_by,
									  G.last_edited_on,
									  G.prev_location_id,
									  CONCAT(G.prev_location_id," | ",L2.location_name_short) AS previous_location,
									  G.sold,
									  G.date_sold,
									  G.sold_to,
									  G.game_move_id,
									  G.game_service_id,
									  G.test_piece,
									  G.exclude_from_reports,
									  IF(G.test_piece =1,CONCAT("**TEST** ",T.game_title),T.game_title) AS game_title,
									  T.id AS game_title_id,
									  Y.game_type,
									  G.product_id AS product_ids_json,
									  P.vendor_description AS product_description,
									  T.has_manual,
									  T.has_servicebulletin,
									  GS.game_status
								 FROM game G
						    LEFT JOIN users U ON U.id = G.last_edited_by
						    LEFT JOIN game_title T ON T.id = G.game_title_id
						    LEFT JOIN vendor V ON V.id = T.mfg_id
						    LEFT JOIN game_type Y ON Y.id = T.game_type_id
						    LEFT JOIN products P ON P.id = G.product_id_1
						    LEFT JOIN game_status GS ON GS.id = G.status_id
						    LEFT JOIN location L ON L.id = G.location_id
						    LEFT JOIN location L2 ON L2.id = G.prev_location_id
								WHERE G.id='.$id);
      return $row;
    }
	/**
	 * override location drop down menu
	 * @param $params
	 * @param null $limit
	 * @param null $parent
	 * @return mixed
	 */
	public static function getComboselect($params, $limit = null, $parent = null)
	{
		$tableName = $params[0];
		if ($tableName == 'location') {
			return parent::getUserAssignedLocation($params, $limit, $parent);
		} else {
			return parent::getComboselect($params, $limit, $parent);
		}

	}

	public static function dispose($ids)
	{
        $result = true;
        $user = \Session::get('uid');
        $userName = \FEGFormat::userToName($user);
        if (is_string($ids)) {
            $ids = explode(",". $ids);
        }
        $now = date("Y-m-d");
        $nowstamp = date("Y-m-d H:i:s");

        $noDisposeCount = 0;
        $totalIds = count($ids);

        \DB::beginTransaction();
        foreach ($ids as $id) {
            $location = \DB::table('game')->where('id' , $id)->first();
            $locationID = $location->id;
            $status = $location->status_id;
            $sold = $location->sold;
            if ($status == 3 || $sold == 1) {
                if (is_bool($result)) {
                    $result = [];
                }
                $result[] = "Game #$id is alerady in-transit or sold.";
                $noDisposeCount++;
                continue;
            }
            $moveId = \DB::table('game_move_history')->insertGetId(
                [   
                    'game_id' => $id,
                    'from_loc' => $locationID,
                    'from_by' => $user,
                    'from_date' => $nowstamp,
                ]
            );
            self::where('id', $id)->update([
                'prev_location_id' => $locationID,
                'location_id' => 0,
                'intended_first_location' => 0,
                'status_id' => '3',
                'sold' => '1',
                'date_sold' => $now,
                'sold_to' => "Disposed by user: $userName",
                'game_move_id' => $moveId,
                'date_last_move' => $now,
                'last_edited_on' => $nowstamp,
                'last_edited_by' => $user,
            ]);            
        }
        \DB::commit();
        
        if ($totalIds <= $noDisposeCount) {
            $result = false;
        }

		return $result;
	}

}
