<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
            if ($user_level == 1 || $user_level == 2 || $user_level == 6 || $user_level == 8 || $user_level == 11) {

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

    public function get_user_emails($user_level = null, $loc_id = null)
    {
        $comma_separated_emails = '';
        $location_statement = '';
        $region_statement = '';

        if (!empty($loc_id)) {

            $location_statement = 'AND UL.location_id=' . $loc_id;
            $query_table = 'users';
        }

        if ($user_level == 'all_users') {
            $user_level_statement = ' AND U.group_id IN(1,2,3,4,5,6,7,8,9,10)';
            $query_table = 'users';
        }
        if ($user_level == 'all_employees') {
            $user_level_statement = ' AND U.group_id IN(1,3,4,5,6,7,10)';
            $query_table = 'users';
        }
        if ($user_level == 'all_managers') {
            $user_level_statement = ' AND U.group_id IN(3,4,5,6,7,10)';
            $query_table = 'users';
        }
        if ($user_level == 'technical_contact') {
            $user_level_statement = ' AND U.is_tech_contact = 1';
            $location_statement = ' AND L.id = ' . $loc_id;
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_managers') {
            $user_level_statement = ' AND U.group_id IN(1,5,6)';
            $location_statement = ' L.id=' . $loc_id . ' OR U.reg_id = (SELECT L.region_id FROM location L WHERE L.id = ' . $loc_id . '))';
            $query_table = 'users';
        }
        if ($user_level == 'users_plus_district_and_field_managers') {
            $user_level_statement = ' AND U.group_id IN(1,4,5,6)';
            $location_statement = ' AND (UL.location_id = ' . $loc_id . ' OR U.id = (SELECT L.field_manager_id FROM location L WHERE L.id = ' . $loc_id . ') OR U.reg_id = (SELECT L.region_id FROM location L WHERE L.id = ' . $loc_id . '))';
            $query_table = 'users';
        }
        if ($user_level == 'location_manager_and_field_manager') {
            $location_statement = ' AND L.id = ' . $loc_id;
            $query_table = 'location';
        }
        // 1	User	user
        // 2	Partner	partner
        // 3	Merch Manager	merchmgr
        // 4	Field Manager	fieldmgr
        // 5	Office Manager	officemgr
        // 6	District Manager	distmgr
        // 7	Finance Manager	financemgr
        // 8	Partner Plus	partnerplus
        // 9	Guest	guest
        // 10	Super Admin	superadmin


        if ($query_table == 'users') {
            $sql = 'SELECT GROUP_CONCAT(DISTINCT U.email separator ",") as Emails
								 FROM users U
								LEFT JOIN user_locations UL ON UL.user_id=U.id
								LEFT JOIN location L ON L.id=UL.location_id
								LEFT JOIN region R ON R.id = L.region_id
                               WHERE U.active = 1'
                . $user_level_statement
                . $location_statement;
            $query =\DB::select($sql);
            foreach ($query as $row) {
                $comma_separated_emails = $row->Emails;
            }

        } else if ($query_table == 'location') {

            $query = \DB::select('SELECT CONCAT(Umain.email,", ",Ufield.email,", ",Uregion.email) as Emails
									 FROM location L
								LEFT JOIN region R ON R.id = L.region_id
								LEFT JOIN users Umain ON Umain.id = L.contact_id
								LEFT JOIN users Ufield ON Ufield.id = L.field_manager_id
								LEFT JOIN users Uregion ON Uregion.id = R.dist_mgr_id
									WHERE Umain.active = 1
									  AND Ufield.active = 1
									  AND Uregion.active = 1
									    ' . $location_statement);


            foreach ($query as $row) {
                $comma_separated_emails = $row->Emails;
            }
        }

        return $comma_separated_emails;
    }


}
