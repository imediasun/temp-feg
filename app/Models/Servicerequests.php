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
                    sb_tickets.*

                FROM sb_tickets

        LEFT JOIN (
            SELECT sb_ticketcomments.TicketID, Posted, UserID, USERNAME
				FROM sb_ticketcomments
				LEFT JOIN (SELECT tc.TicketID, max(tc.Posted) as max_posted from sb_ticketcomments tc group by TicketID) tcm
					ON tcm.TicketID = sb_ticketcomments.TicketID
				WHERE tcm.max_posted = sb_ticketcomments.Posted
    ) sbc ON sbc.TicketID = sb_tickets.TicketID

	LEFT JOIN users U ON U.id = sbc.UserID";

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
}
