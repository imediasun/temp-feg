<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class order extends Sximo
{

    protected $table = 'orders';
    protected $primaryKey = 'id';
    const OPENID1 = 1, OPENID2 = 3, OPENID3 = 4, FIXED_ASSET_ID = 9, PRO_IN_DEV = 18, CLOSEID1 = 2, CLOSEID2 = 5;

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "  SELECT orders.*,order_type.is_merch FROM orders left outer join order_type on orders.order_type_id=order_type.id  ";
    }

    public static function queryWhere($cond = null)
    {
        $return = " Where";
        switch ($cond) {
            case 'ALL':
                $return .= " orders.id IS NOT NULL";
                break;
            case 'OPEN':
                $return .= " orders.status_id IN(" . self::OPENID1 . "," . self::OPENID2 . "," . self::OPENID3 . ") AND orders.order_type_id !=" . self::FIXED_ASSET_ID . " AND orders.order_type_id !=" . self::PRO_IN_DEV;
                break;
            case 'FIXED_ASSET':
                $return .= " orders.order_type_id = " . self::FIXED_ASSET_ID;
                break;
            case 'PRO_IN_DEV':
                $return .= "  orders.order_type_id = " . self::PRO_IN_DEV;
                break;
            case 'CLOSED':
                $return .= "  orders.status_id IN(" . self::CLOSEID1 . "," . self::CLOSEID2 . ")";
                break;
            default:
                $return .= " orders.id IS NOT NULL";
        }

        return $return;
    }

    public static function queryGroup()
    {
        return "GROUP BY orders.id  ";
    }

    public function getOrderQuery($order_id, $mode = null)
    {
        $data['requests_item_count'] = 0;
        $data['order_loc_id'] = '0';
        $data['order_vendor_id'] = '';
        $data['order_type'] = '';
        $data['order_company_id'] = '';
        $data['order_freight_id'] = '';
        $data['orderDescriptionArray'] = '';
        $data['orderPriceArray'] = '';
        $data['orderQtyArray'] = '';
        $data['orderProductIdArray'] = '';
        $data['itemNameArray']="";
        $data['itemCasePrice']="";
        $data['orderRequestIdArray'] = '';
        $data['requests_item_count'] = '';
        $data['today'] = $this->get_local_time();
        $data['order_total'] = '0.00';
        $data['alt_address'] = "";
        $data['po_1'] = '0';
        $data['po_2'] = date('dmy');
        $data['po_3'] = $this->increamentPO();
        $data['po_notes'] = '';
        $data['prefill_type'] = "";
        $where_in_expression = "";
        $data['alt_name'] = $data['alt_street'] = $data['alt_city'] = $data['alt_state'] = $data['alt_zip'] = $data['shipping_notes'] = "";
        if ($order_id != 0 && $mode != (substr($mode, 0, 3) == 'SID')) {
            $order_query = \DB::select('SELECT location_id,vendor_id, date_ordered,order_total,alt_address,order_type_id,company_id,freight_id,po_notes,po_number FROM orders WHERE id = ' . $order_id);
            if (count($order_query) == 1) {
                $data['order_loc_id'] = $order_query[0]->location_id;
                $data['order_vendor_id'] = $order_query[0]->vendor_id;
                $data['order_type'] = $order_query[0]->order_type_id;
                $data['order_company_id'] = $order_query[0]->company_id;
                $data['order_freight_id'] = $order_query[0]->freight_id;
                $data['today'] = $order_query[0]->date_ordered;
                $data['order_total'] = $order_query[0]->order_total;
                $data['alt_address'] = $order_query[0]->alt_address;
            }
            $data['prefill_type'] = 'clone';
            $content_query = \DB::select('SELECT IF(O.product_id = 0, O.product_description, P.vendor_description) AS description,O.price AS price,O.qty AS qty, O.product_id,O.item_name,O.case_price
												 FROM order_contents O LEFT JOIN products P ON P.id = O.product_id WHERE O.order_id = ' . $order_id);
            if ($content_query) {

                foreach ($content_query as $row) {
                    $data['requests_item_count'] = $data['requests_item_count'] + 1;
                    $orderDescriptionArray[] = $row->description;
                    $orderPriceArray[] = $row->price;
                    $orderQtyArray[] = $row->qty;
                    $orderProductIdArray[]=$row->product_id;
                    $orderitemnamesArray[]=$row->item_name;
                    $orderitemcasepriceArray[]=$row->case_price;
                  //  $prod_data[]=$this->productUnitPriceAndName($orderProductIdArray);
                }
                $data['orderDescriptionArray'] = $orderDescriptionArray;
                $data['orderPriceArray'] = $orderPriceArray;
                $data['orderQtyArray'] = $orderQtyArray;
                $data['orderProductIdArray']=$orderProductIdArray;
           /*     if(count($prod_data)!=0) {
                    foreach ($prod_data as $d) {
                        $item_name_array[] = $d['vendor_description'];
                        $item_case_price[] = $d['case_price'];
                    }
                }*/
                $data['itemNameArray']=$orderitemnamesArray;
                $data['itemCasePrice']=$orderitemcasepriceArray;
                $poArr = array("", "", "");
                if (isset($data['po_number'])) {
                    $poArr = explode("-", $data['po_number']);
                    $data['po_1'] = $poArr[0];
                }
            }
            if ($mode == 'edit') {
                $data['today'] = $order_query[0]->date_ordered;
                $data['po_notes'] = $order_query[0]->po_notes;
                $data['po_number'] = $order_query[0]->po_number;

                if (isset($data['po_number'])) {
                    $poArr = explode("-", $data['po_number']);
                    $data['po_1'] = $poArr[0];
                    $data['po_2'] = isset($poArr[1]) ? $poArr[1] : "";
                    $data['po_3'] = isset($poArr[2]) ? $poArr[2] : "";
                }
                if (isset($data['alt_address'])) {
                    $altAddr = explode('|', $data['alt_address']);
                    $data['alt_name'] = isset($altAddr[0])?$altAddr[0]:"";
                    $data['alt_street'] = isset($altAddr[1])?$altAddr[1]:"";
                    $data['alt_city'] = isset($altAddr[2])?$altAddr[2]:"";
                    $data['alt_state'] = isset($altAddr[3])?$altAddr[3]:"";
                    $data['alt_zip'] = isset($altAddr[4])?$altAddr[4]:"";
                    $data['shipping_notes'] = isset($altAddr[5])?$altAddr[5]:"";
                }

            $data['prefill_type'] = 'edit';
        }
        $data['today'] = ($mode) ? $order_query[0]->date_ordered : $this->get_local_time('date');
    }
elseif (substr($mode, 0, 3) == 'SID')
{
$item_count = substr_count($mode, '-');

$SID_string = $mode;
$data['SID_string'] = $SID_string;
for ($i = 1;$i < $item_count;$i++)
{
$pos1 = strpos($SID_string, '-');
$SID_string = substr($SID_string, $pos1+1);
$pos2 = strpos($SID_string, '-');
${
'SID' . $i
} = substr($SID_string, 0, $pos2);

$where_in_expression = $where_in_expression . ${'SID' . $i} . ',';

$query = \DB::select('SELECT R.qty,
											  P.case_price,
											  P.vendor_id,
											  R.product_id,
											  R.location_id,
											  L.company_id,
											  P.prod_type_id,
										  SUM(R.qty*P.case_price) AS total,
									   CONCAT(P.vendor_description," (SKU-",P.sku,")",IF(R.notes = "", "", CONCAT(" **note: ",R.notes,"**"))) AS description
										 FROM requests R
									LEFT JOIN products P ON P.id = R.product_id
									LEFT JOIN location L ON L.id = R.location_id
										WHERE R.id = ' . ${'SID' . $i} . '');

    if (count($query) == 1) {
    $data['order_loc_id'] = $query[0]->location_id;
    $data['order_company_id'] = $query[0]->company_id;
    $data['order_vendor_id'] = $query[0]->vendor_id;
    $data['order_type'] = $query[0]->prod_type_id;
    $data['order_total'] = $query[0]->total;
    $data['po_2'] = date('dmy');
    $data['po_3'] = $this->increamentPO();
    $data['po_notes'] = $query[0]->description;
    //$this->data['id']=1806;
    $data['prefill_type'] = "";
    $data['order_freight_id'] = "";

    $orderDescriptionArray[] = $query[0]->description;
    $orderPriceArray[] = $query[0]->case_price;
    $orderQtyArray[] = $query[0]->qty;
    $orderProductIdArray[] = $query[0]->product_id;
    $prod_data=$this->productUnitPriceAndName($query[0]->product_id);
        $item_name_array[]=$query[0]->item_name;;
        $item_case_price[]=$query[0]->case_price;;
    $orderRequestIdArray[] = ${'SID' . $i};
}

$data['orderDescriptionArray'] = $orderDescriptionArray;
$data['orderPriceArray'] = $orderPriceArray;
$data['orderQtyArray'] = $orderQtyArray;
$data['orderProductIdArray'] = $orderProductIdArray;
$data['orderRequestIdArray'] = $orderRequestIdArray;
$data['itemNameArray']=$item_name_array;
$data['itemCasePrice']=$item_case_price;
$data['requests_item_count'] = $item_count;
$data['today'] = date('Y-m-d');
}
}
return $data;
}

function getPoNumber($po_full)
{
    $query = \DB::select('SELECT po_number FROM orders WHERE po_number = "' . $po_full . '"');
    if (count($query) > 0) {
        $po_message = 'taken';
    } else {
        $po_message = 'available';
    }
    return $po_message;
}

public
function get_local_time($type = null)
{
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $dayText = date('D');

    $yearmonthday = $year . '-' . $month . '-' . $day;
    if ($type = 'date') {
        return $yearmonthday;
    }
}

function getOrderReceipt($order_id)
{
    $where_in_expression = '';
    $order_description = '';
    $total = '';
    $data['order_vendor_name'] = '';
    $data['order_id'] = $order_id;
    $data['location_id'] = '';
    $data['user_id'] = \Session::get('uid');
    if (!empty($order_id)) {
        $query = \DB::select('SELECT O.order_type_id,O.order_description,O.request_ids,O.po_number,O.location_id,O.order_total,O.status_id,
                     O.notes,O.added_to_inventory,V.vendor_name,U.username FROM orders O LEFT JOIN vendor V ON V.id = O.vendor_id
                     LEFT JOIN users U ON U.id = O.user_id WHERE O.id = ' . $order_id . '');
        if (count($query) == 1) {
            $data['requestIds'] = $query[0]->request_ids;
            $data['order_type'] = $query[0]->order_type_id;
            $data['po_number'] = $query[0]->po_number;
            $data['location_id'] = $query[0]->location_id;
            $data['order_status_id'] = $query[0]->status_id;
            $data['order_notes'] = $query[0]->notes;
            $data['added_to_inventory'] = $query[0]->added_to_inventory;
            $data['order_total'] = $query[0]->order_total;
            $data['order_user_name'] = $query[0]->username;
            $order_description = $query[0]->order_description;
            $data['description'] = str_replace(' | ', "<br>", $order_description);
            $data['vendor_name'] = $query[0]->vendor_name;
            $data['item_count'] = '';
        }
        if (!empty($data['requestIds']) && ($data['order_type'] == 7 || $data['order_type'] == 8)) //INSTANT WIN AND REDEMPTION PRIZES
        {
            $item_count = substr_count($data['requestIds'], ',') + 1;
            $data['item_count'] = $item_count;
            $requestIdString = $data['requestIds'];
            for ($i = 1; $i <= $item_count; $i++) {
                $comma = strpos($requestIdString, ',');

                if (!empty($comma)) {
                    $id = substr($requestIdString, 0, $comma);
                    $requestIdString = substr($requestIdString, $comma + 1);
                } else {
                    $id = $requestIdString;
                }

                $query = \DB::select('SELECT R.product_id,R.qty,P.case_price,P.prod_type_id,R.location_id,CONCAT(P.vendor_description," (SKU-",P.sku,")") AS description FROM requests R
                           LEFT JOIN products P ON P.id = R.product_id WHERE R.id = ' . $id);
                if (count($query) == 1) {
                    $data['product_id_' . $i] = $query[0]->product_id;
                    $data['order_qty_' . $i] = $query[0]->qty;
                    $data['order_description_' . $i] = $query[0]->description;
                    $data['order_price_' . $i] = $query[0]->case_price;
                }
            }
        }
        //  $data['status_options'] = $this->create_all_options_list('order_status','id','status','','id','YES','');
        $data['game_options'] = $this->create_game_options('CONCAT("Add to ",game_title.game_title," | ",game.id)', 'WHERE game.location_id = "' . $data['location_id'] . '" AND game.sold=0 AND game_title.game_type_id = 3', 'ORDER BY game_title.game_title', 'Inventory for Loc. #' . $data['location_id']);
        $data['today'] = $this->get_local_time('date');
        $data['title'] = 'Order Receipt';
        return $data;
    } else {
        Redirect::to('orders');
    }
}

public
function create_game_options($customField, $customWhere, $customOrderBy, $customBlankField)
{
    $query = \DB::select('SELECT game.id AS gid, ' . $customField . ' AS game_title FROM game LEFT JOIN game_title ON game.game_title_id = game_title.id ' . $customWhere . ' ' . $customOrderBy);

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

function receiveOrder($request)
{


}

function increamentPO()
{
    $po = \DB::select('select po_number from orders where id=(select max(id) from orders)');
    $po = explode('-', $po[0]->po_number);
    return ++$po[2];
}
    function getVendorEmail($vendor_id)
    {
        $vendor_email=\DB::select("SELECT email from vendor WHERE id=".$vendor_id);
        return $vendor_email[0]->email;
    }
    function productUnitPriceAndName($prod_id)
    {
        $row=\DB::select('SELECT vendor_description,case_price from products where id='.$prod_id);
        if($row) {
            $data = array('vendor_description' => $row[0]->vendor_description, 'case_price' => $row[0]->case_price);
            return $data;
        }
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


