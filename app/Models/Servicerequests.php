<?php namespace App\Models;

use App\Models\SbTicketsTroubleshootingCheckList;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Observers\Observerable;
use App\Models\ticketsetting;
use App\Library\FEG\System\Formatter;
use Log;

class Servicerequests extends Observerable  {

    protected $table = 'sb_tickets';
    protected $primaryKey = 'TicketID';
    public $timestamps = false;
    public $hideGridFieldsTab1 = [
        'functionality_id',
        'issue_type_id',
        'shipping_priority_id',
        'game_realted_date',
        'part_number',
        'cost',
        'qty',
    ];
    public $hideGridFieldsTab2 = [
        'issue_type',
        'functionality_id',
        'game_realted_date',
        'part_number',
        'cost',
        'qty',
        'need_by_date',
    ];
    public $tab2FieldLabels = [
        'fields'=>[
        'Description'
        ],
        'labels'=>[],
    ];
    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){
        $date = date("Y-m-d");
        $sql = "SELECT
                        IF (sbc.UserID=0, sbc.USERNAME, CONCAT(U.first_name, ' ', U.last_name)) AS last_user,
                    IF(ISNULL(sbc.Posted),
                        DATEDIFF('$date', sb_tickets.Created),
                        DATEDIFF('$date', sbc.Posted)) AS last_updated_elapsed_days,
                    sb_tickets.*, D.company as debit_type,
                      CONCAT(L.id,' ',L.location_name) AS location_full_name,
                      L.location_name AS locationname,
                      UC.first_name   AS firstname,
                      UC.last_name    AS lastname,
                      sbc.USERNAME    AS sbcusername,
                      sb_tickets.ticket_type,
                      (SELECT
                     GROUP_CONCAT(sb_ticketcomments.Comments)
                    FROM sb_ticketcomments
                  WHERE sb_ticketcomments.TicketID = sb_tickets.TicketID) AS sbcComments
                FROM sb_tickets

        LEFT JOIN (
            SELECT sb_ticketcomments.TicketID, Posted, UserID, USERNAME,sb_ticketcomments.Comments
				FROM sb_ticketcomments
				LEFT JOIN (SELECT tc.TicketID, max(tc.Posted) as max_posted from sb_ticketcomments tc group by TicketID) tcm
					ON tcm.TicketID = sb_ticketcomments.TicketID
				WHERE tcm.max_posted = sb_ticketcomments.Posted
    ) sbc ON sbc.TicketID = sb_tickets.TicketID

	LEFT JOIN users U ON U.id = sbc.UserID
	LEFT JOIN users UC ON UC.id = sb_tickets.entry_by
	INNER JOIN location L ON ( sb_tickets.location_id = L.id )
    LEFT JOIN debit_type D ON (L.debit_type_id = D.id)
	";

        return $sql;
    }

    public static function queryWhere(  ){
        $table = "sb_tickets";
        $controlField = "$table.TicketID";
        $selected_loc = \SiteHelpers::getCurrentUserLocationsFromSession();//\Session::get('selected_location');
        $isOmniscient = ticketsetting::isUserOmniscient();
        $q = "";
        if (empty($selected_loc)) {
            $selected_loc = null;
        }

        if ($isOmniscient) {
            $q = "  WHERE $controlField IS NOT NULL ";            
        }            
        else {
            if(isset($selected_loc)) {
                $q .= "  WHERE $controlField IS NOT NULL AND $table.location_id IN ($selected_loc)";
            }
            else {
                $q = "  WHERE $controlField IS NULL";
            }
        }
        //exclude office locations
        //$q .= ' AND (D.company IS NOT NULL OR location_id IN (6001,6000,6030))';

        return $q;
    }

    public static function queryGroup(){
        return "  ";
    }



    public static function getComboselect($params, $limit = null, $parent = null) {
        $tableName = $params[0];
        if($tableName == 'location'){
            return parent::getUserAssignedLocation($params,$limit,$parent);

        }
        else{
            return parent::getComboselect($params,$limit,$parent);
        }
    }
    
    public static function getPriorities() {
        return Formatter::getTicketPriorities();
    }
    public static function getStatuses() {
        return Formatter::getTicketStatuses();
    }
    public static function getIssueTypes() {
        return Formatter::getTicketIssueTypes();
    }
	public static function doesTicketExist($id){
        
        $ticket = self::where('TicketID', '=', $id)
                        ->first();

        return !empty($ticket);
	}

    public static function getTicketInitialRequestAsComment($ticketId) {
        $data = self::select(\DB::raw(implode(",", [
                    "0 as CommentID,
                    TicketID,
                    Description as Comments,
                    Created as Posted,
                    entry_by as UserID,
                    concat(users.first_name, ' ',users.last_name)  as `USERNAME`,
                    file_path as Attachments,
                    0 as `imap_read`,
                    '' as `imap_message_id`,
                    '' as `imap_meta`,
                    Created as created_at,
                    updated as updated_at",

                   'users.username',
                   'users.first_name',
                   'users.last_name',
                   'users.email',
                   'users.avatar',
                   'users.active',
                   'users.group_id'
                ]))
            )
            ->leftJoin('users', 'users.id', '=', 'sb_tickets.entry_by')
            ->where('TicketID', '=', $ticketId)
            ->get()->first();
        $comment = null;
        if (!empty($data)) {
            //$comment = $data[0];
            $comment = $data;
        }
        return $comment;
    }
    public static function getRows($args, $cond = null) {
        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1,
            'ticket_type' => 'debit-card-related',
        ), $args));


        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$sort} {$order} " : '';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }

        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }

        if(!empty($ticket_type)){
            $select .= " AND sb_tickets.ticket_type='$ticket_type'";
        }

        Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $total= count($counter_select);
        if($table=="img_uploads")
        {
            $total="";
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        global $result;
        $result = "";
        \DB::transaction(function ()use($select,$params,$orderConditional,$limitConditional) {
            global $result;
            $sql = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ;";
            \DB::statement(\DB::raw("SET SESSION group_concat_max_len = 10000000; "));
            $result = \DB::select(\DB::raw($sql));

            // $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        }, 5);
        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }
        return $results = array('rows' => $result, 'total' => $total);
    }

    public function getGameRelatedfields(){
        $fields = [
            'game_related'=>[
                'TicketID',
                'shipping_priority_id',
                'Created',
                'issue_type',
                'location_id',
                'Description',
                'need_by_date',
                'closed',
                'Status',
                'last_user',
                'last_updated_elapsed_days',
            ],
            ['debit_card_related']=>[
                'TicketID',
                'Created',
                'issue_type',
                'location_id',
                'debit_type',
                'Subject',
                'Description',
                'need_by_date',
                'closed',
                'Status',
                'last_user',
                'last_updated_elapsed_days',
            ],

        ];

        return $fields;
        }

        public function getGames($locationId = 0){
           return game::select('game.id','game_title.game_title')->join('game_title','game.game_title_id','=','game_title.id')->where('game.location_id',$locationId)->get();
        }

    public function displayFieldsByType($tableGrid, $type)
    {

        $updatedGrid = [];

        foreach ($tableGrid as $item) {
            if (in_array($item['field'], $this->hideGridFieldsTab1) && $type == 'debit-card-related') {
                $item['view'] = 0;
            }elseif(in_array($item['field'], $this->hideGridFieldsTab2) && $type == 'game-related'){
                $item['view'] = 0;
            }
            $updatedGrid[] = $item;
        }
        return $updatedGrid;
    }

    public function saveTroubleshootingChecklist($checkList = [],$ticketId){
    $sbTicketsTroubleshootingCheckList = new SbTicketsTroubleshootingCheckList();
        $removeItems = $sbTicketsTroubleshootingCheckList->where('sb_ticket_id',$ticketId)->delete();
        foreach ($checkList as $item){
            $data = ['sb_ticket_id'=>$ticketId,'troubleshooting_check_list_id'=>$item];
            $sbTicketsTroubleshootingCheckList->insertRow($data,null);
        }

    }
    public function prepareDataforGameEmail($data = []){
        $game_id = $data['game_id'];
        $game = game::find($game_id);
        $gameTitle = Gamestitle::find($game->game_title_id);
        $data['game']['game_id'] = $game_id;
        $data['game']['game_title'] = $gameTitle->game_title;

        $data['location'] = location::find($data['location_id']);
        $data['issue_type'] = IssueType::find($data['issue_type_id'])->issue_type_name;
        $data['functionality'] = GameFunctionality::find($data['functionality_id'])->functionality_name;
        if(!empty($data['shipping_priority_id'])) {
            $data['shipping_priority'] = ShippingPriority::find($data['shipping_priority_id'])->priority_name;
        }
        return $data;
    }

}
