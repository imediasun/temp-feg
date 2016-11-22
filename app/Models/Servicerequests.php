<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Observers\Observerable;
class Servicerequests extends Observerable  {

    protected $table = 'sb_tickets';
    protected $primaryKey = 'TicketID';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT sb_tickets.* FROM sb_tickets  ";
    }

    public static function queryWhere(  ){

        return "  WHERE sb_tickets.TicketID IS NOT NULL ";
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
}
