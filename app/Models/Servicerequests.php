<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Observers\Observerable;
use App\Models\ticketsetting;
use App\Library\FEG\System\Formatter;

class Servicerequests extends Observerable  {

    protected $table = 'sb_tickets';
    protected $primaryKey = 'TicketID';
    public $timestamps = false;
    
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
            'global' => 1
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
}
