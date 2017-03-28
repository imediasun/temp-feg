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
        $sql = "SELECT concat(U.first_name, ' ', U.last_name) as last_user, sb_tickets.* FROM (
                SELECT
                    c.last_user_id,
                    IF (
                        ISNULL(updated),
                            DATEDIFF('$date', Created),
                            DATEDIFF('$date', updated)
                        ) as last_updated_elapsed_days,
                    sb_tickets.*
                FROM sb_tickets
                LEFT JOIN (SELECT
                        TicketID,
                        UserID AS last_user_id
                    FROM sb_ticketcomments
                    ORDER BY Posted DESC
                ) c ON c.TicketID = sb_tickets.TicketID
                GROUP BY sb_tickets.TicketID) sb_tickets
                LEFT JOIN users U ON U.id = sb_tickets.last_user_id
            ";

        return $sql;
    }

    public static function queryWhere(  ){
        $table = "sb_tickets";
        $controlField = "$table.TicketID";
        $selected_loc = \SiteHelpers::getCurrentUserLocationsFromSession();//\Session::get('selected_location');
        $isOmniscient = ticketsetting::isUserOmniscient();
        $q = "";

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
}
