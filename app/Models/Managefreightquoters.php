<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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
        $status=isset($_GET['status'])?$_GET['status']:'manage';
        if($status=='manage')
        {
            $statusqry='IF(F.status = 0, "<b style=\"color:red\">Quote Requested</b>", "<b style=\"color:green\">Freight Booked</b>")';
        }
        else
        {
            $statusqry='"<b style=\"color:darkblue\">Invoice Paid</b>"';
        }
        return 'SELECT F.*,F.date_submitted,F.date_paid,GROUP_CONCAT(company_name) AS company_name,
                IF(F.vend_to = 0 AND F.loc_to_1=0, CONCAT(F.to_add_name," (",F.to_add_state,")"),
                IF(F.vend_to = 0,CONCAT("",GROUP_CONCAT(L2.location_name_short)), V2.vendor_name)) AS vend_from,
                IF(F.vend_from = 0 AND F.loc_from = 0, CONCAT(F.from_add_name,"(",F.from_add_state,")"),IF(F.vend_from = 0, L.location_name_short, V.vendor_name)) AS vend_to,
                ' .$statusqry. ' AS status
                FROM freight_orders F LEFT JOIN
                freight_location_to FLT ON F.id=FLT.freight_order_id LEFT JOIN freight_companies FC ON FLT.freight_company=FC.id
                LEFT JOIN vendor V ON V.id=F.vend_from
                LEFT JOIN vendor V2 ON V2.id=F.vend_to
                LEFT JOIN location L ON L.id=F.loc_from
                LEFT JOIN location L2 ON L2.id=FLT.location_id';
    }

    public static function queryWhere($cond = 'manage')
    {
        $where = "";
        if ($cond == "manage") {
            $where .= " WHERE F.status!=2 ";
        } else {
            $where .= " WHERE F.status = 2 ";
        }
        $where .= " AND  F.id IS NOT NULL ";
        return $where;
    }

    public static function queryGroup()
    {
        return " GROUP BY F.id";
    }

    public static function getSearchFilters()
    {
        $finalFilter = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach ($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $finalFilter[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
        return $finalFilter;
    }

    function getDescription($freight_order_id)
    {
        $description = \DB::select("SELECT freight_orders.id,GROUP_CONCAT(description) as description FROM freight_pallet_details LEFT JOIN freight_orders ON freight_orders.id=freight_pallet_details.freight_order_id where freight_orders.id=" . $freight_order_id . " GROUP BY freight_orders.id");
        return $description;
    }
}
