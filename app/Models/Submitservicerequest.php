<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Groups;

class submitservicerequest extends Sximo
{

    protected $table = 'game';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return 'SELECT G.id,G.game_name,G.prev_game_name,G.version,G.version_id,G.players,G.monitor_size,G.dba,G.sacoa,G.embed,G.rfid,G.notes,G.freight_order_id,G.location_id,
									  CONCAT(G.location_id," | ",L.location_name_short) AS locationFull,
									  V.vendor_name,V.phone AS vendor_phone,V.contact AS vendor_contact,V.email AS vendor_email,V.website AS vendor_website,
									  G.serial,G.date_in_service,G.status_id,G.game_setup_status_id,G.intended_first_location,
									  U.username AS last_edited_by,G.last_edited_on,G.prev_location_id,
									  CONCAT(G.prev_location_id," | ",L2.location_name_short) AS prevLocationFull,
									  G.sold, G.date_sold,G.sold_to, G.game_move_id,G.game_service_id,G.test_piece,
									  IF(G.test_piece =1,CONCAT("**TEST**",T.game_title),T.game_title) AS game_title,
									  T.id AS game_title_id,Y.game_type,P.vendor_description AS product_description,
									  T.has_manual,T.has_servicebulletin,GS.game_status
								      FROM game G
						    LEFT JOIN users U ON U.id = G.last_edited_by
						    LEFT JOIN game_title T ON T.id = G.game_title_id
						    LEFT JOIN vendor V ON V.id = T.mfg_id
						    LEFT JOIN game_type Y ON Y.id = T.game_type_id
						    LEFT JOIN products P ON P.id = G.product_id_1
						    LEFT JOIN game_status GS ON GS.id = G.status_id
						    LEFT JOIN location L ON L.id = G.location_id
						    LEFT JOIN location L2 ON L2.id = G.prev_location_id';
    }

    public static function queryWhere($id)
    {

        return " WHERE G.id=$id";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public function create_game_options($customField, $customWhere, $customOrderBy, $customBlankField)
    {
        $query = \DB::select('SELECT game.id AS gid, ' . $customField . ' AS game_title FROM game
							LEFT JOIN game_title ON game.game_title_id = game_title.id ' . $customWhere . ' ' . $customOrderBy);
        foreach ($query as $row) {
            $game[$row->gid] = $row->game_title;
        }
        if (strpos($customBlankField, 'Inventory') !== FALSE) {
            $game[''] = 'Add to ' . $customBlankField;
        } else {
            $game[''] = 'Select Game';
        }
        return $game;
    }

    function getSubmitServiceRequestInfo($var1 = null, $var2 = null)
    {
        if (substr($var1, 0, 3) == 'GID') {

            $var1 = substr($var1, 3);
            $GID = $var1;
        } else if (substr($var2, 0, 3) == 'GID') {
            $var2 = substr($var2, 3);
            $GID = $var2;
        } else {
            $GID = '';
        }
        $data['GID'] = $GID;

        if (substr($var1, 0, 3) == 'LID') {
            $var1 = substr($var1, 3);
            $LID = $var1;
        } else if (substr($var2, 0, 3) == 'LID') {
            $var2 = substr($var2, 3);
            $LID = $var2;
        } else {

            $user_level = \Session::get('gid');
            if ($user_level == Groups::USER || $user_level == Groups::PARTNER || $user_level == Groups::DISTRICT_MANAGER || $user_level == Groups::PARTNER_PLUS || $user_level == Groups::TECHNICAL_MANAGER) {

                $user_locations = \Session::get('user_locations');
                $LID = $user_locations[0]->id;
            } else {

                $LID = '';
            }
        }
        $data['LID'] = $LID;

        if (!empty($GID)) {

            $data['game_details'] = \DB::select(SELF::querySelect() . " " . Self::queryWhere($GID));
        }
        // $data['loc_options'] = $this->populateLocationsDropdown();
        $data['game_options'] = $this->create_game_options('CONCAT(game.location_id," | ",game_title.game_title," | ",game.id)', 'WHERE game.location_id = "' . $LID . '" AND game.sold=0', 'ORDER BY game_title.game_title', '');

        return $data;
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
}
