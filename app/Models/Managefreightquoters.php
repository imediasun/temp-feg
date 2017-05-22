<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\FEG\System\FEGSystemHelper;

class managefreightquoters extends Sximo
{

    protected $table = 'freight_orders';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        $status = \Session::get('freight_status');
        if ($status == 'requested') {
            $statusqry = '"<b style=\"color:red\">Quote Requested</b>"';
        }
        elseif($status == 'booked')
        {
            $statusqry =  '"<b style=\"color:green\">Freight Booked</b>"';
        }
        else {
            $statusqry = '"<b style=\"color:darkblue\">Invoice Paid</b>"';
        }
        return 'SELECT freight_orders.*,IF(freight_orders.loc_to_1 = 0,"",freight_orders.loc_to_1) AS loc_to_1,freight_orders.date_submitted,freight_orders.date_paid,GROUP_CONCAT(company_name) AS company_name,
                (select c.company_name from freight_companies c where c.id=freight_orders.freight_company_1) as company_name_1,
                IF(freight_orders.vend_to = 0 AND freight_orders.loc_to_1=0, CONCAT(freight_orders.to_add_name," (",freight_orders.to_add_state,")"),
                IF(freight_orders.vend_to = 0,CONCAT("",GROUP_CONCAT(L2.location_name)), V2.vendor_name)) AS vend_to,
                IF(freight_orders.vend_from = 0 AND freight_orders.loc_from = 0, CONCAT(freight_orders.from_add_name,"(",freight_orders.from_add_state,")"),IF(freight_orders.vend_from = 0, L.location_name, V.vendor_name)) AS vend_from,
                ' . $statusqry . ' AS status
                FROM freight_orders  LEFT JOIN
                freight_location_to FLT ON freight_orders.id=FLT.freight_order_id LEFT JOIN freight_companies FC ON FLT.freight_company=FC.id
                LEFT JOIN vendor V ON V.id=freight_orders.vend_from
                LEFT JOIN vendor V2 ON V2.id=freight_orders.vend_to
                LEFT JOIN location L ON L.id=freight_orders.loc_from
                LEFT JOIN location L2 ON L2.id=FLT.location_id';
    }

    public static function queryWhere()
    {
        $status=\Session::get('freight_status');
        $where = "";
        if ($status == "requested") {
            $where .= " WHERE freight_orders.status = 0 ";
        }
        elseif ($status == "booked") {
            $where .= " WHERE freight_orders.status = 1 ";
        }
        else {
            $where .= " WHERE freight_orders.status = 2 ";
        }
        $where .= " AND  freight_orders.id IS NOT NULL ";
        return $where;
    }

    public static function queryGroup()
    {
        return " GROUP BY freight_orders.id";
    }

    function getDescription($freight_order_id)
    {
        $description = \DB::select("SELECT freight_orders.id,GROUP_CONCAT(description SEPARATOR ', ') as description FROM freight_pallet_details LEFT JOIN freight_orders ON freight_orders.id=freight_pallet_details.freight_order_id where freight_orders.id=" . $freight_order_id . " GROUP BY freight_orders.id");
        return $description;
    }

    public function sendFreightQuotes($data)
    {
        $id = "";
        if (isset($data['freight_orders']) && is_array($data['freight_orders'])) {
            $id = \DB::table('freight_orders')->insertGetId($data['freight_orders']);

        }
        if (isset($data['freight_location_to']) && !empty($data['freight_location_to'])) {
            $row = array();
            $row['freight_order_id'] = $id;
            foreach ($data['freight_location_to'] as $location) {
                $row['location_id'] = $location;
                \DB::table('freight_location_to')->insert($row);
            }
        }
        if (isset($data['pallte_details']) && is_array($data['pallte_details'])) {
            //$pallet_data = array();
            $row = array();
            $row['freight_order_id'] = $id;
            for ($i = 0; $i < count($data['pallte_details']['description']); $i++) {
                $row['description'] = $data['pallte_details']['description'][$i];
                $row['dimensions'] = $data['pallte_details']['dimensions'][$i];
                \DB::table('freight_pallet_details')->insert($row);
            }

        }


    }

    public static function getRow($id)
    {
        $freightOrder = \DB::select('SELECT F.id AS freight_order_id,F.freight_company_1, IF(F.date_submitted = "0000-00-00", "N/A",F.date_submitted) AS date_submitted,
						IF(F.date_booked = "0000-00-00", "N/A",F.date_booked) AS date_booked, IF(F.date_paid = "0000-00-00", "N/A",F.date_paid) AS date_paid,
                        F.vend_from, V.vendor_name AS vend_from_name, V.street1 AS vend_from_street, V.city AS vend_from_city, V.state AS vend_from_state,
						V.zip AS vend_from_zip, V.games_contact_name AS vend_from_contact_name, V.games_contact_email AS vend_from_contact_email,
						V.games_contact_phone AS vend_from_contact_phone, F.vend_to, group_concat(FLT.description) as description,
						group_concat(FLT.dimensions) as dimensions, group_concat(FLT.ship_exception) as shipping_exception,group_concat(FLT.id) as freight_pallet_id,
						V2.vendor_name AS vend_to_name, V2.street1 AS vend_to_street, V2.city AS vend_to_city, V2.state AS vend_to_state, V2.zip AS vend_to_zip, V2.games_contact_name AS vend_to_contact_name,
						V2.games_contact_email AS vend_to_contact_email, V2.games_contact_phone AS vend_to_contact_phone, F.loc_from AS loc_from_id,
						CONCAT(F.loc_from, " | ", L.location_name) AS loc_from,F.from_add_name,F.from_add_street,F.from_add_city,F.from_add_state,F.loc_to_1,
						F.from_add_zip,F.from_contact_name,F.from_contact_email,F.from_contact_phone,F.from_loading_info,F.to_add_name,
						F.to_add_street,F.to_add_city,F.to_add_state,F.to_add_zip,F.to_contact_name,F.to_contact_email,
						F.to_contact_phone,F.to_loading_info,F.external_ship_quote,F.external_ship_trucking_co,
						F.external_ship_pro, F.to_add_name,F.to_add_state,F.notes,F.num_games_per_destination,F.email_notes,
						If(F.status = 0, "<b style=\"color:red; font-size:12px\">Quote Requested</b>",
						If(F.status = 1, "<b style=\"color:green; font-size:12px;\">Freight Booked</b>", "<b style=\"color:darkblue; font-size:12px;\">Invoice Paid</b>")) AS status,
						F.status AS status_id FROM freight_orders F LEFT JOIN freight_pallet_details FLT on F.id=FLT.freight_order_id
						LEFT JOIN vendor V ON V.id = F.vend_from LEFT JOIN vendor V2 ON V2.id = F.vend_to  LEFT JOIN location L ON L.id = F.loc_from
						WHERE F.id = ' . $id);

        if (count($freightOrder) == 1) {
            $data['freight_order_id'] = $freightOrder[0]->freight_order_id;
            $data['date_submitted'] = $freightOrder[0]->date_submitted;
            $data['date_booked'] = $freightOrder[0]->date_booked;
            $data['date_paid'] = $freightOrder[0]->date_paid;
            $data['loc_1'] = $freightOrder[0]->loc_to_1;
            $data['freight_loc_info'] = self::getFreightLocations($id);
            $data['companies_dropdown'] = self::companiewDropdown();
            $data['description'] = explode(',', $freightOrder[0]->description);
            $data['freight_pallet_id'] = explode(',', $freightOrder[0]->freight_pallet_id);
            $data['dimensions'] = explode(',', $freightOrder[0]->dimensions);
            $data['ship_exception'] = is_array($freightOrder[0]->shipping_exception) ? explode(',', $freightOrder[0]->shipping_exception) : "N/A";
            $vend_from = $freightOrder[0]->vend_from;
            $loc_from = $freightOrder[0]->loc_from;
            $loc_from_id = $freightOrder[0]->loc_from_id;
            $data['loc_from_id'] = $loc_from_id;
            $from_add_name = $freightOrder[0]->from_add_name;
            $from_add_street = $freightOrder[0]->from_add_street;
            $from_add_city = $freightOrder[0]->from_add_city;
            $from_add_state = $freightOrder[0]->from_add_state;
            $from_add_zip = $freightOrder[0]->from_add_zip;
            $from_contact_name = $freightOrder[0]->from_contact_name;
            $from_contact_email = $freightOrder[0]->from_contact_email;
            $from_contact_phone = $freightOrder[0]->from_contact_phone;
            $from_loading_info = $freightOrder[0]->from_loading_info;
            $vend_to = $freightOrder[0]->vend_to;
            $data['vend_to'] = $vend_to;
            $to_add_name = $freightOrder[0]->to_add_name;
            $data['to_add_street'] = $freightOrder[0]->to_add_street;
            $to_add_city = $freightOrder[0]->to_add_city;
            $to_add_state = $freightOrder[0]->to_add_state;
            $to_add_zip = $freightOrder[0]->to_add_zip;
            $data['to_contact_name'] = $freightOrder[0]->to_contact_name;
            $data['to_contact_email'] = $freightOrder[0]->to_contact_email;
            $to_contact_phone = $freightOrder[0]->to_contact_phone;
            $to_loading_info = $freightOrder[0]->to_loading_info;
            $vend_from_name = $freightOrder[0]->vend_from_name;
            $vend_from_street = $freightOrder[0]->vend_from_street;
            $vend_from_city = $freightOrder[0]->vend_from_city;
            $vend_from_state = $freightOrder[0]->vend_from_state;
            $vend_from_zip = $freightOrder[0]->vend_from_zip;
            $vend_from_contact_name = $freightOrder[0]->vend_from_contact_name;
            $data['vend_from_contact_email'] = $freightOrder[0]->vend_from_contact_email;
            $vend_from_contact_phone = $freightOrder[0]->vend_from_contact_phone;
            $vend_to_name = $freightOrder[0]->vend_to_name;
            $vend_to_street = $freightOrder[0]->vend_to_street;
            $vend_to_city = $freightOrder[0]->vend_to_city;
            $vend_to_state = $freightOrder[0]->vend_to_state;
            $vend_to_zip = $freightOrder[0]->vend_to_zip;
            $vend_to_contact_name = $freightOrder[0]->vend_to_contact_name;
            $data['vend_to_contact_email'] = $freightOrder[0]->vend_to_contact_email;
            $vend_to_contact_phone = $freightOrder[0]->vend_to_contact_phone;
            $data['num_games_per_destination'] = $freightOrder[0]->num_games_per_destination;

            $data['external_ship_quote'] = $freightOrder[0]->external_ship_quote;
            $data['external_ship_trucking_co'] = $freightOrder[0]->external_ship_trucking_co;
            $data['external_ship_pro'] = $freightOrder[0]->external_ship_pro;
            $data['notes'] = $freightOrder[0]->notes;
            $data['email_notes'] = $freightOrder[0]->email_notes;
            // $data['shipping_exceptions'] = $freightOrder[0]->ShippingExceptions;
            $data['status'] = $freightOrder[0]->status;
            $data['current_status_id'] = $freightOrder[0]->status_id;

        }
        if ($vend_from == 0 && $loc_from == 0) {
            $data['from_address'] = $from_add_name . "<br>" .
                $from_add_street . "<br>" .
                $from_add_city . ", " . $from_add_state . " " . $from_add_zip . "<br>" .
                $from_contact_name . "<br>" .
                $from_contact_email . "<br>" .
                $from_contact_phone . "<br>" .
                $from_loading_info . "<br>";
        } else {
            if ($vend_from == 0) {
                $data['from_address'] = $loc_from;
            } else {
                $data['from_address'] = $vend_from_name . "<br>" .
                    $vend_from_street . "<br>" .
                    $vend_from_city . ", " . $vend_from_state . " " . $vend_from_zip . "<br>" .
                    $vend_from_contact_name . "<br>" .
                    $data['vend_from_contact_email'] . "<br>" .
                    $vend_from_contact_phone . "<br>";
            }
        }
        if ($vend_to == 0) {
            $data['to_address'] = $to_add_name . "<br>" .
                $data['to_add_street'] . "<br>" .
                $to_add_city . ", " . $to_add_state . " " . $to_add_zip . "<br>" .
                $data['to_contact_name'] . "<br>" .
                $data['to_contact_email'] . "<br>" .
                $to_contact_phone . "<br>" .
                $to_loading_info . "<br>";

            $data['contact_email'] = $data['to_contact_email'];
        } else {
            $data['to_address'] = $vend_to_name . "<br>" .
                $vend_to_street . "<br>" .
                $vend_to_city . ", " . $vend_to_state . " " . $vend_to_zip . "<br>" .
                $vend_to_contact_name . "<br>" .
                $data['vend_to_contact_email'] . "<br>" .
                $vend_to_contact_phone . "<br>";

            $data['contact_email'] = $data['vend_to_contact_email'];
        }

        if ($data['loc_1'] == 0) {
            $data['ship_to_type'] = 'external';
        } else {
            $data['ship_to_type'] = 'internal';
        }
        $data['freight_company_1']=isset($freightOrder[0]->freight_company_1)?$freightOrder[0]->freight_company_1:0;
        $data['loc_from_id'] = $loc_from_id;
        $data['game_drop_down'] = self::populateGamesDropDownInFreightQuote();
        return $data;
    }

    public static function getFreightLocations($id)
    {
        $query = \DB::select('SELECT l.id as freight_loc_to_id,
                            l.location_id,
                            lll.location_name,
                            l.location_pro,
                            l.location_quote,
                            l.location_trucking_co,
                            l.freight_company,GROUP_CONCAT(ll.game_id) as game_ids,group_concat(ll.id) as freight_loc_game_ids
                            FROM freight_location_to l
                            LEFT JOIN location lll ON l.location_id=lll.id
                            LEFT JOIN freight_order_location_games ll ON l.id=ll.freight_loc_to_id
                            WHERE l.freight_order_id=' . $id . ' GROUP BY l.id');
        $data = array();

        foreach ($query as $row) {

            $data['location'][] = $row->location_id;
            $data['location_name'][] = $row->location_name;
            $data['location_pro'][] = $row->location_pro;
            $data['location_quote'][] = $row->location_quote;
            $data['location_trucking_co'][] = $row->location_trucking_co;
            $data['freight_company'][] = $row->freight_company;
            $data['loc_game'][] = explode(',', $row->game_ids);
            $data['freight_loc_to_id'][] = $row->freight_loc_to_id;
            $data['freight_loc_game_id'][] = explode(',', $row->freight_loc_game_ids);
        }

        return $data;
    }

    public static function populateGamesDropDownInFreightQuote()
    {
        $concat = 'CONCAT(IF(G.location_id = 0, "IN TRANSIT", G.location_id), " | ",T.game_title," | ",G.id, IF(G.notes = "","", CONCAT(" (",G.notes,")")))';
        $where="AND L.active = 1";
        $orderBy = 'L.id,T.game_title';
        $query = \DB::select('SELECT G.id AS id, IFNULL(' . $concat . ',"") AS text  FROM game G
							Inner JOIN game_title T ON T.id = G.game_title_id
							Inner JOIN location L ON L.id = G.location_id
                            WHERE G.sold = 0 ' . $where . '  ORDER BY ' . $orderBy);
        $query=json_decode(json_encode($query),true);
        return $query;
    }

    public function updateFreightOrder($data)
    {

        $from_loc = $data['request']['from_loc'];
        $freight_order_id = $data['request']['freight_order_id'];
        $ship_to_type = $data['request']['ship_to_type'];
        if(isset($data['request']['freight_company_1']))
        {
            $freight_company_1 = $data['request']['freight_company_1'];
        }
        elseif(isset($data['request']['company'][0]))
        {
            $freight_company_1 = $data['request']['company'][0];
        }
        else{
            $freight_company_1=0;
        }
        $email = $data['request']['email'];
        $email_notes = $data['request']['email_notes'];
        $today = date('Y-m-d');
        $current_status_id = $data['request']['current_status_id']; // 0 = Quote Requested, 1  Freight Booked, Used to Make Sure Not to Overwrite Booking Dates
        $external_ship_quote = isset($data['request']['external_ship_quote']) ? $data['request']['external_ship_quote'] : "";
        $external_ship_trucking_co = isset($data['request']['external_ship_trucking_co']) ? $data['request']['external_ship_trucking_co'] : "";
        $external_ship_pro = isset($data['request']['external_ship_pro']) ? $data['request']['external_ship_pro'] : "";
        $description = $data['request']['description'];
        $dimensions = $data['request']['dimension'];
        $notes = $data['request']['notes'];
        $freight_pallet_update = array();
        if ($current_status_id == 0 && (!empty($freight_company_1 ))) {
            $status = 1;
            $update = array(
                'notes' => $notes,
                'external_ship_quote' => $external_ship_quote,
                'external_ship_trucking_co' => $external_ship_trucking_co,
                'external_ship_pro' => $external_ship_pro,
                'freight_company_1' => $freight_company_1,
                'email_notes' => $email_notes,
                'date_booked' => $today,
                'status' => $status
            );
            $row['freight_order_id'] = $freight_order_id;
            for ($i = 0; $i < count($data['request']['freight_pallet_id']); $i++) {
                $pallet_id=$data['request']['freight_pallet_id'][$i];
                $freight_pallet_update['description'] = isset($data['request']['description'][$i])?$data['request']['description'][$i]:"";
                $freight_pallet_update['dimensions'] = isset($data['request']['dimension'][$i])?$data['request']['dimension'][$i]:"";
                if(isset($pallet_id) && !empty($pallet_id)) {

                    \DB::table('freight_pallet_details')->where('id', $pallet_id)->update($freight_pallet_update);
                }
                else{
                    $freight_pallet_update['freight_order_id']=$freight_order_id;
                    \DB::table('freight_pallet_details')->insert($freight_pallet_update);
                }
            }
        } else {
            $status = 0;
            $update = array(
                'notes' => $notes,
                'external_ship_quote' => $external_ship_quote,
                'external_ship_trucking_co' => $external_ship_trucking_co,
                'external_ship_pro' => $external_ship_pro,
                'freight_company_1' => $freight_company_1,
                'email_notes' => $email_notes
            );
            $row['freight_order_id'] = $freight_order_id;
            for ($i = 0; $i < count($data['request']['freight_pallet_id']); $i++) {
                $pallet_id=$data['request']['freight_pallet_id'][$i];
                $freight_pallet_update['description'] = isset($data['request']['description'][$i])?$data['request']['description'][$i]:"";
                $freight_pallet_update['dimensions'] = isset($data['request']['dimension'][$i])?$data['request']['dimension'][$i]:"";
                if(isset($pallet_id) && !empty($pallet_id)) {

                    \DB::table('freight_pallet_details')->where('id', $pallet_id)->update($freight_pallet_update);
                }
                else{
                    $freight_pallet_update['freight_order_id']=$freight_order_id;
                    \DB::table('freight_pallet_details')->insert($freight_pallet_update);
                }
            }
        }
        \DB::table('freight_orders')->where('id', $freight_order_id)->update($update);

        $freight_contents = $data['request']['freight_contents'];
        if ($ship_to_type == 'internal') {
            $loc = array();
            $loc_1_pro = '';
            $loc_1_quote = 0;
            $loc_1_trucking_co = '';
            $num_games_per_destination = $data['request']['num_games_per_destination'];
            $exception_num = 1;
            $num_allowable_exceptions = 5;
            $num_allowable_locations = count($data['request']['loc']); //SET LIMITATION
            $freight_location_to_update = array();
            $freight_location_game_update = array();
            for ($i = 0; $i < $num_allowable_locations; $i++) {
                $loc[] = $data['request']['loc'][$i];
                $freight_location_to_update['location_id'] = $data['request']['loc'][$i];
                if ($data['request']['loc'] != 0) { //IF SHIP TO LOCATION SELECTED, DO GAMES CALCULATION
                    for ($n = 0; $n < $num_games_per_destination; $n++) {
                        $loc_game[$i][$n] = isset($data['request']['loc_game'][$i][$n]) ? $data['request']['loc_game'][$i][$n] : 0;
                        if (!empty($data['request']['loc_game'][$i][$n])) // UPDATE GAME_SETUP_STATUS TO 1 -> GAME HAS BEEN SHIPPED
                        {
                            // ${'shipping_exception_'.$i.'_'.$n} = $this->input->post('shipping_exception_'.$i.'_'.$n);

                            // if(${'shipping_exception_'.$i.'_'.$n} == 1)
                            // {
                            // 	$ship_date = $this->input->post('new_ship_date_'.$i.'_'.$n);
                            // 	$ship_delay_reason = $this->input->post('new_ship_reason_'.$i.'_'.$n);

                            // 	if($exception_num <= $num_allowable_exceptions)
                            // 	{
                            // 		${'ship_exception_'.$exception_num} = ${'loc_'.$i.'_game_'.$n};
                            // 		${'new_ship_date_'.$exception_num} = $ship_date;
                            // 		${'new_ship_reason_'.$exception_num} = $ship_delay_reason;
                            // 	}
                            // 	$exception_num++;

                            // 	$updateGame = array(
                            // 		'ship_delay_reason' => $ship_delay_reason,
                            // 		'date_shipped' => $ship_date
                            // 	);

                            // 	$this->db->where('id', ${'loc_'.$i.'_game_'.$n});
                            // 	$this->db->update('game', $updateGame);
                            // }
                            // else
                            // {
                            $freight_location_game_update['game_id'] = isset($data['request']['loc_game'][$i][$n]) ? $data['request']['loc_game'][$i][$n] : 0;
                            $freight_loc_game_id = isset($data['request']['freight_loc_game_id'][$i][$n])?$data['request']['freight_loc_game_id'][$i][$n]:"";
                            $game_id=isset($data['request']['loc_game'][$i][$n])?$data['request']['loc_game'][$i][$n]:0;
                            // Update freight_order_location_game table


                            if (isset($freight_loc_game_id) && !empty($freight_loc_game_id)) {
                                $update = \DB::update("UPDATE freight_order_location_games set game_id=" . $game_id . " WHERE id=" . $freight_loc_game_id);
                            } else {

                                $freight_location_game_update['freight_loc_to_id'] = $data['request']['freight_loc_to_id'][$i];
                                \DB::table('freight_order_location_games')->insert($freight_location_game_update);
                            }

                            $updateGame = array(
                                'game_setup_status_id' => 1,
                                'freight_order_id' => $freight_order_id,
                            );
                            if ($current_status_id == 0) {
                                $updateGame['date_shipped'] = $today;
                            }
                            if (!empty($data['request']['loc'][$i])) {
                                $updateGame['intended_first_location'] = $data['request']['loc'][$i];
                            }
                            if (!empty($from_loc)) {
                                $updateGame['prev_location_id'] = $from_loc;
                            }

                            \DB::table('game')->where('id', $data['request']['loc_game'][$i][$n])->update($updateGame);

                            //}
                        }
                    }
                    $freight_location_to_update['location_pro'] = isset($data['request']['pro_number'][$i])?$data['request']['pro_number'][$i]:0;
                    $freight_location_to_update['location_quote'] = isset($data['request']['quoted_price'][$i])?$data['request']['quoted_price'][$i]:0;
                    $freight_location_to_update['location_trucking_co'] = isset($data['request']['trucking_line'][$i])?$data['request']['trucking_line'][$i]:"";
                    $freight_location_to_update['freight_company'] = isset($data['request']['company'][$i])?$data['request']['company'][$i]:"";
                    // Update freight_location_to table
                    $update = \DB::table('freight_location_to')->where('id', $data['request']['freight_loc_to_id'][$i])->update($freight_location_to_update);

                }
            }
            $updateFreight = array(

                'email_notes' => $email_notes,
                'status' => $status,

            );

            //  $this->db->where('id', $freight_order_id);
            // $this->db->update('freight_orders', $updateFreight);

            if ($email == 1) {
                for ($i = 0; $i < $num_allowable_locations; $i++) {
                    if ($data['request']['loc'][$i] != 0) {
                        $game_links = '';
                        for ($n = 0; $n < $num_games_per_destination; $n++) {
                            $game_asset_id = isset($data['request']['loc_game'][$i][$n]) ? $data['request']['loc_game'][$i][$n] : "";

                            if (!empty($game_asset_id)) {
                                $gameTitle = $this->get_game_info_by_id($game_asset_id, 'game_title');
                                $game_links = $game_links . 'Game #' . $n . ': <b>' . $gameTitle . '</b>
														    <br>
														    Web Page #' . $n . ': '.url().'/managefreightquoters/gamedetails/' . $game_asset_id . '<br>';

                            }
                        }


                        $locationName = $this->get_location_info_by_id($data['request']['loc'][$i], 'location_name_short');


                        //  $data = $this->get_user_data();

                        $pallet_info = '';
                        if (isset($data['request']['description']) && !empty($data['request']['description'])) {
                            for ($j = 0; $j < count($data['request']['description']); $j++) {
                                $pallet_info = $pallet_info .
                                    $data['request']['description'][$j] . '(' . $data['request']['dimension'][$j] . ') <br/>';
                            }
                        }

                        $from = \Session::get('eid');
                        $to = $this->get_user_emails('users_plus_district_and_field_managers', $data['request']['loc'][$i]);
                        //$to =  \FEGHelp::getSystemEmailRecipients('USERS PLUS DISTINCT AND FIELD MANAGER', $data['request']['loc'][$i]);
                        $cc = 'freight-notifications@fegllc.com';
                        $bcc = 'support@fegllc.com';
                        $subject = ((int)$num_games_per_destination == 0)?('Scheduled for delivery to ' . $locationName . '!'):('('.(int)$num_games_per_destination.')'.' Game[s] scheduled for delivery to ' . $locationName . '!');
                        $message = '<p>
										' . $email_notes . '
										<br>
									</p>
									<p>
										<table style="margin:0px auto; width:100%;">
			            					<tr style="color:black; border:thin black solid;">
			            						<td colspan="2" style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold; font-style:italic; text-align:center;">
			            							Freight Details
			            						</td>
			            					</tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding-right:3px; border:thin black solid; text-align:right; width:15%"">
													Game Details:
												</td>
						    					<td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold;">
						    						' . $game_links . '
						    					</td>
								            </tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding-right:3px; border:thin black solid; text-align:right; width:15%"">
													Pallet Info:
												</td>
						    					<td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold;">
						    						' . $pallet_info . '
						    					</td>
								            </tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding-right:3px; border:thin black solid; text-align:right; width:15%"">
													Destination:
												</td>
						    					<td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold;">
						    						' . $data['request']['loc'][$i] . ' || ' . $locationName . '
						    					</td>
								            </tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding-right:3px; border:thin black solid; text-align:right; width:15%"">
													Trucking Line:
												</td>
								                <td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold;">
													' . $data['request']['trucking_line'][$i] . '
						    					</td>
								            </tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding-right:3px; border:thin black solid; text-align:right; width:15%">
													Pro Number:
												</td>
								                <td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold;">
													' . $data['request']['pro_number'][$i] . '	<em>(visit Trucking Line website for Shipment Tracking)</em>
						    					</td>
								            </tr>
								        </table>
									</p>
									<p style="font-weight:bold; color:black;">
										<table style="margin:0px auto; width:100%;">
			            					<tr style="color:black; border:thin black solid;">
			            						<td style="padding-right:3px; border:thin black solid; text-align:left; font-weight:bold; font-style:italic; text-align:center;">
			            							Instructions
			            						</td>
			            					</tr>
			            					<tr style="color:black; border:thin black solid;">
								                <td style="padding:3px; border:thin black solid; text-align:left;">
													<b style="color:red; text-decoration:underline;">**PRE-ARRIVAL**</b>
													<br>
													1.) Warn the Lodge / Park / Resort Dock Personnel that you are expecting this shipment and ask that they notify you upon games\' arrival.
													<br>
													2.) DO NOT SIGN THE FREIGHT RECEIPT UNTIL YOU HAVE <b style="text-decoration:underline;">FULLY INSPECTED THE FREIGHT FOR DAMAGE</b>. If unboxing is needed
														to perform your physical inspection, ask the driver to wait until you\'ve done so. If there is any damage whatsoever, you <b style="text-decoration:underline;">MUST WRITE A DETAILED
														DESCRIPTION OF THE DAMAGE ON THE RECEIPT YOU\'RE ASKED TO SIGN</b> AND <b style="text-decoration:underline;">GET A COPY OF THAT RECEIPT FOR OUR RECORDS</b>
														records. <b style="color:red;">If the damage is excessive, call Rich Pankey at (623)-385-9330
														immediately</b> (if you cannot reach Rich for some reason, contact your direct supervisor) for determination as to whether you should
														accept or refuse the shipment.
													<br>
													<br>
													<b style="color:red; text-decoration:underline;">**ONCE RECEIVED**</b>
													<br>
												    1). Click Game Web Page Above to Receive Game Online
													<br>
													2). Get <em style="text-decoration:underline;">Asset ID</em> and Official <em style="text-decoration:underline;">Game Title</em> from
														Website for Embed/Sacoa setup (if applicable)
												    </em>
						    					</td>
								            </tr>
								        </table>
									</p>';
                        //$headers = "CC: " . $cc . PHP_EOL;
                        //$headers .= "BCC:" . $bcc . PHP_EOL;
                        //$headers .= "From: " . $from . "\r\n" . "X-Mailer: php";
                        //$headers .= "MIME-Version: 1.0\r\n";
                        //$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        // echo $message;
                       //mail($to, $subject, $message, $headers);
                        if(!empty($to)){
                            FEGSystemHelper::sendSystemEmail(array(
                                'to' => $to,
                                'subject' => $subject,
                                'message' => $message,
                                'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                                'from' => $from,
                                'cc' => $cc,
                                'bcc' => $bcc,
                                'configName' => 'UPDATE FREIGHT TOOL EMAIL'
                            ));
                        }
                    } else {

                    }
                }
            }
        } else {
            $contact_email = $data['request']['contact_email'];
            if ($email == 1 && !empty($contact_email)) {
                $contents_message = '';
                if (isset($data['request']['description']) && !empty($data['request']['description'])) {
                    for ($i = 0; $i < count($data['request']['description']); $i++) {
                        $contents_message = $contents_message . 'Pallet ' . ($i + 1) . ':
									' . $data['request']['description'][$i] . '
									<br>
									Dimensions:
									' . $data['request']['dimension'][$i];
                    }
                }
                if(env('APP_ENV', 'development') == 'production')
                {
                    $to = $contact_email;
                    $cc = 'rich.pankey@fegllc.com';
                }
                else
                {
                    $to = "stanlymarian@gmail.com";//hardcoded email for testing
                    $cc = 'jdanial710@gmail.com';
                }
                $from = 'support@fegllc.com';
                $bcc = '';
                $subject = 'FEG has scheduled a Freight Shipment to you!';
                $message = '<p style="font-size:1em;">
							' . $email_notes . '
							<br>
						</p>
						<p style="font-size:1.1em;">
							Freight Contents:
							<br>
							' . $contents_message . '
							<br>
							<br>
							Trucking Line: ' . $external_ship_trucking_co . '
							<br>
							Pro Number: <b>' . $external_ship_pro . '</b>
							<br>
							<em>visit Trucking Line website for Shipment Tracking</em>
							<br>
						</p>
						<p style="font-size:1em; font-weight:bold; color:black;">
							<b style="color:red; text-decoration:underline;">**NOTE**</b>
							<br>
							1.) DO NOT SIGN THE FREIGHT RECEIPT UNTIL YOU HAVE <b style="text-decoration:underline;">FULLY INSPECTED THE FREIGHT FOR DAMAGE</b>. If unboxing is needed
								to perform your physical inspection, ask the driver to wait until you\'ve done so. If there is any damage whatsoever, you <b style="text-decoration:underline;">MUST WRITE A DETAILED
								DESCRIPTION OF THE DAMAGE ON THE RECEIPT YOU\'RE ASKED TO SIGN</b> AND <b style="text-decoration:underline;">GET A COPY OF THAT RECEIPT FOR OUR RECORDS</b>
								records. <b style="color:red;">If the damage is excessive, call Rich Pankey at (623)-385-9330
								immediately</b> for determination as to whether you should accept or refuse the shipment.
							<br>
						</p>';
                //$headers = "CC: " . $cc . PHP_EOL;
                //$headers .= "BCC:" . $bcc . PHP_EOL;
                //$headers .= "From: " . $from . "\r\n" . "X-Mailer: php";
                //$headers .= "MIME-Version: 1.0\r\n";
                //$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                // echo $message;
                //DO NOT uncomment below code
                //mail($to, $subject, $message, $headers);
                if(!empty($to)){
                    FEGSystemHelper::sendSystemEmail(array(
                        'to' => $to,
                        'subject' => $subject,
                        'message' => $message,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'from' => $from,
                        'cc' => $cc,
                        'bcc' => $bcc,
                        'configName' => 'UPDATE FREIGHT ORDER EMAIL'
                    ));
                }
            }
        }
        return true;

    }

    public static function companiewDropdown()
    {
        $row = \DB::select('select id,company_name as text from freight_companies where active=1');
        $query=json_decode(json_encode($row),true);
       return $query;
    }

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
