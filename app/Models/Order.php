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
        $data['order_loc_id'] = '0';
        $data['order_vendor_id'] = '';
        $data['order_type'] = '';
        $data['order_company_id'] = '';
        $data['order_freight_id'] = '';
        $data['orderDescriptionArray'] = '';
        $data['orderPriceArray'] = '';
        $data['orderQtyArray'] = '';
        $data['orderProductIdArray'] = '';
        $data['orderRequestIdArray'] = '';
        $data['requests_item_count'] = '';
        $data['today'] = $this->get_local_time();
        $data['order_total'] = '0.00';
        $data['po_1'] = '0';
        $data['po_2'] = date('d') . date('m') . date('y');
        $data['po_3'] = 0;
        $data['po_notes'] = '';
        $data['prefill_type'] = "";
        $data['requests_item_count'] = 0;
        if ($order_id != 0) {
            $order_query = \DB::select('SELECT location_id,vendor_id, date_ordered,order_total,order_type_id,company_id,freight_id,po_notes,po_number FROM orders WHERE id = ' . $order_id);
            if (count($order_query) == 1) {
                $data['order_loc_id'] = $order_query[0]->location_id;
                $data['order_vendor_id'] = $order_query[0]->vendor_id;
                $data['order_type'] = $order_query[0]->order_type_id;
                $data['order_company_id'] = $order_query[0]->company_id;
                $data['order_freight_id'] = $order_query[0]->freight_id;
                $data['today'] = $order_query[0]->date_ordered;
                $data['order_total'] = $order_query[0]->order_total;
            }
            $data['prefill_type'] = 'clone';
            $content_query = \DB::select('SELECT IF(O.product_id = 0, O.product_description, P.vendor_description) AS description,O.price AS price,O.qty AS qty
												 FROM order_contents O LEFT JOIN products P ON P.id = O.product_id WHERE O.order_id = ' . $order_id);
            if ($content_query) {

                foreach ($content_query as $row) {
                    $data['requests_item_count'] = $data['requests_item_count'] + 1;
                    $orderDescriptionArray[] = $row->description;
                    $orderPriceArray[] = $row->price;
                    $orderQtyArray[] = $row->qty;
                }
                $data['orderDescriptionArray'] = $orderDescriptionArray;
                $data['orderPriceArray'] = $orderPriceArray;
                $data['orderQtyArray'] = $orderQtyArray;
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
                $data['prefill_type'] = 'edit';
            }
            $data['today'] = ($mode) ? $order_query[0]->date_ordered : $this->get_local_time('date');
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

    public function get_local_time($type = null)
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
                        $data['product_id_'. $i] = $query[0]->product_id;
                        $data['order_qty_'. $i] = $query[0]->qty;
                        $data['order_description_' . $i] = $query[0]->description;
                        $data['order_price_'. $i] = $query[0]->case_price;
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

    public function create_game_options($customField, $customWhere, $customOrderBy, $customBlankField)
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

}
