<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class managefegrequeststore extends Sximo
{

    protected $table = 'requests';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "SELECT requests.*,u1.username,products.img,IF(product_id = 0, requests.description, products.vendor_description) as description,
                products.sku,products.case_price,products.retail_price,products.case_price*requests.qty,products.ticket_value,location.location_name_short,
                merch_request_status.status,products.size,concat(V1.vendor_name,if(V1.status=0,' (Inactive)','')) as vendor_name,order_type.order_type,If(products.reserved_qty = 0, 'No Data' , products.reserved_qty) as reserved_qty,
                (products.reserved_qty - requests.qty) as reserved_difference, products.vendor_id,products.prod_type_id  FROM requests
                LEFT JOIN users u1 ON (requests.request_user_id = u1.id)
			    LEFT JOIN products ON (requests.product_id = products.id)
			LEFT JOIN vendor V1 ON (products.vendor_id = V1.id)
			LEFT JOIN location ON (requests.location_id = location.id)
			LEFT JOIN merch_request_status ON (requests.status_id = merch_request_status.id)
			LEFT JOIN order_type ON (order_type.id = products.prod_type_id)";
    }

    public static function queryWhere($cond = null)
    {
        $order_type_id = isset($cond['order_type_id']) ? $cond['order_type_id'] : "";
        $location_id = isset($cond['location_id']) ? $cond['location_id'] : "";
        $vendor_id = isset($cond['vendor_id']) ? $cond['vendor_id'] : "";
        $where = "  WHERE requests.id IS NOT NULL ";
        if ($cond['view'] == 'manage') {
            if (!empty($order_type_id)) {
                if (strpos($order_type_id, '-')) {
                    $order_type_id = str_replace('-', ',', $order_type_id);
                }

                if (!empty($location_id)) {
                    if (!empty($vendor_id)) {
                        $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")
						            AND requests.location_id = " . $location_id . "
						            AND V1.id =" . $vendor_id;
                    } else {
                        $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")
						            AND requests.location_id = " . $location_id;
                    }
                } else {
                    $where .= " AND requests.status_id IN(1)
						            AND products.prod_type_id IN(" . $order_type_id . ")";
                }
            } else {
                $where .= " AND requests.status_id IN(1)";
            }
        } elseif ($cond['view'] == 'archive') {

            $where .= " AND requests.status_id IN(2,3)";

        }
        return $where;

    }

    public static function queryGroup()
    {
        return "  ";
    }

    public static function getManageRequestsInfo($v1 = null, $v2 = null, $v3 = null,$filter=null)
    {
        if (substr($v1, 0, 1) == 'T') {
            $v1 = substr($v1, 1);
            $TID = $v1;
        } else if (substr($v2, 0, 1) == 'T') {
            $v2 = substr($v2, 1);
            $TID = $v2;
        } else if (substr($v3, 0, 1) == 'T') {
            $v3 = substr($v3, 1);
            $TID = $v3;
        } else {
            $TID = 0;
        }
        if (substr($v1, 0, 1) == 'L') {
            $v1 = substr($v1, 1);
            $LID = $v1;
        } else if (substr($v2, 0, 1) == 'L') {
            $v2 = substr($v2, 1);
            $LID = $v2;
        } else if (substr($v3, 0, 1) == 'L') {
            $v3 = substr($v3, 1);
            $LID = $v3;
        } else {
            $LID = 0;
        }
        if (substr($v1, 0, 1) == 'V') {
            $v1 = substr($v1, 1);
            $VID = $v1;
        } else if (substr($v2, 0, 1) == 'V') {
            $v2 = substr($v2, 1);
            $VID = $v2;
        } else if (substr($v3, 0, 1) == 'V') {
            $v3 = substr($v3, 1);
            $VID = $v3;
        } else {
            $VID = 0;
        }
        $order_dropdown_data = self::getOrdersDropDownData();
        $data['order_dropdown-data'] = $order_dropdown_data;
        if (!empty($TID)) {
            if (strpos($TID, '-')) {
                $TID_comma_replaced = str_replace('-', ',', $TID);
            } else {
                $TID_comma_replaced = $TID;
            }
            $loc_where = 'WHERE requests.status_id=1 AND products.prod_type_id IN (' . $TID_comma_replaced . ') '.$filter;
            $data['loc_options'] = self::getLocationDropDownData('CONCAT(requests.location_id," | ",location.location_name_short)', $loc_where, 'ORDER BY requests.location_id');
            if (!empty($LID)) {
                $vendor_where='WHERE requests.status_id=1 AND requests.location_id=' . $LID . ' AND products.prod_type_id IN (' . $TID_comma_replaced . ')'.$filter;
                $data['vendor_options'] = self::getVendorDropDownData('CONCAT(vendor_name,IF(vendor.status=0," (Inactive)",""))',$vendor_where, 'ORDER BY vendor.vendor_name');
            } else {
                $data['vendor_options'] = array('' => '<-- Select');
            }
            $order_type_where = "AND P.prod_type_id IN (" . $TID_comma_replaced . ")";
        } else {
            $data['loc_options'] = array('' => '<-- Select');
            $data['vendor_options'] = array('' => '<-- Select');

            $order_type_where = "";
        }
        $data['TID'] = $TID;
        $data['LID'] = $LID;
        $data['VID'] = $VID;
        $number_requests = '';
        $order_type_where =$order_type_where." ". \SiteHelpers::getQueryStringForLocation('requests');

        $query = \DB::select('SELECT COUNT(requests.id) as count,O.order_type AS request_count FROM requests
								LEFT JOIN products P ON P.id = requests.product_id LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE requests.status_id = 1 AND O.order_type IS NOT NULL ' . $order_type_where . ' GROUP BY P.prod_type_id');

        foreach ($query as $index => $row) {
       //     $number_requests = $number_requests ." ".." | <em>". $row->request_count .":</em>";
            if($index == count($query) -1 )
                $number_requests = $number_requests ." ".$row->request_count. $row->count ;
            else
                $number_requests = $number_requests ." ".$row->request_count. $row->count  ;

        }
        $data['number_requests'] = substr($number_requests, 0, -2);
        $query = \DB::select('SELECT GROUP_CONCAT(order_type) AS order_types  FROM order_type
							  WHERE id != 6 AND id != 10');
        if (count($query) == 1) {
            $data['order_types'] = $query[0]->order_types;
        }
        $data['title'] = 'Manage Requests';
        $data['subtitle1'] = 'Merch Requests';
        $data['subtitle2'] = 'Office Requests';
        $data['subtitle3'] = 'Other Requests';
        return $data;
    }

    public static function getOrdersDropDownData()
    {

        $query = \DB::select('SELECT O.id,O.order_type FROM order_type O
							  LEFT JOIN products P ON P.prod_type_id = O.id
							  LEFT JOIN requests R ON R.product_id = P.id
							  WHERE R.status_id = 1
							  GROUP BY O.id
                              ORDER BY O.order_type');

        $orderTypesArray = array();
        foreach ($query as $row) {
            if($row->order_type!= 7 && $row->order_type != 8) {
                $row = array(
                'id' => $row->id,
                'text' => $row->order_type
            );
                $orderTypesArray[] = $row;
               // Removing 'Instant Wind Prizes' and 'Redemption Prizes' from order type array
                /*if($row['id'] != 7 && $row['id'] != 8) {
                    $orderTypesArray[] = $row;
                }*/
            }
        }

        // Combining 'Instant Win','Redemption' and 'Party' order types in a single category
        /*$customArray[] = array(
            'id' => '7-8-17',
            'text' => 'Instant Win, Redemption, Party (Combined)'
        );*/

        //$array = array_merge($orderTypesArray, $customArray);
        $array = array_merge($orderTypesArray);

        return $array;
    }

    public static function getLocationDropDownData($customField, $customWhere, $customOrderBy)
    {
        $data[''] = 'Select Location';

        $query = \DB::select('SELECT location.id AS lid, ' . $customField . 'AS location_name FROM location
							LEFT JOIN requests ON location.id = requests.location_id
							LEFT JOIN products ON products.id = requests.product_id ' . $customWhere . ' ' . $customOrderBy);
        $location_ids = array();
        $locations = self::getUserAssignedLocation();
        foreach($locations  as $location)
            $location_ids[] =  $location->id;

        foreach ($query as $row) {
            if(in_array($row->lid, $location_ids))
                $data[$row->lid] = $row->location_name;
        }

        return $data;
    }

    public static function getVendorDropDownData($customField, $customWhere, $customOrderBy)
    {
        $data[''] = 'Select Vendor';
        $query = \DB::select('SELECT vendor.id AS vid, ' . $customField . ' AS vendor_name FROM vendor
							LEFT JOIN products ON vendor.id = products.vendor_id
							LEFT JOIN requests ON requests.product_id = products.id ' . $customWhere . ' ' . $customOrderBy);
        foreach ($query as $row) {
            $data[$row->vid] = $row->vendor_name;
        }

        return $data;
    }
    function manageRequests($v1 = null, $v2 = null, $v3 = null)
    {
        $user_lever=\Session::get('gid');
        if ($user_lever == 2)
        {
            redirect('dashboard');
        }
        else
        {

            if(substr($v1, 0, 1) == 'T')
            {
                $v1 = substr($v1, 1);
                $TID = $v1;
            }
            else if(substr($v2, 0, 1) == 'T')
            {
                $v2 = substr($v2, 1);
                $TID = $v2;
            }
            else if(substr($v3, 0, 1) == 'T')
            {
                $v3 = substr($v3, 1);
                $TID = $v3;
            }
            else
            {
                $TID = 0;
            }

            if(substr($v1, 0, 1) == 'L')
            {
                $v1 = substr($v1, 1);
                $LID = $v1;
            }
            else if(substr($v2, 0, 1) == 'L')
            {
                $v2 = substr($v2, 1);
                $LID = $v2;
            }
            else if(substr($v3, 0, 1) == 'L')
            {
                $v3 = substr($v3, 1);
                $LID = $v3;
            }
            else
            {
                $LID = 0;
            }

            if(substr($v1, 0, 1) == 'V')
            {
                $v1 = substr($v1, 1);
                $VID = $v1;
            }
            else if(substr($v2, 0, 1) == 'V')
            {
                $v2 = substr($v2, 1);
                $VID = $v2;
            }
            else if(substr($v3, 0, 1) == 'V')
            {
                $v3 = substr($v3, 1);
                $VID = $v3;
            }
            else
            {
                $VID = 0;
            }

            $data['order_type_options'] = self::getOrdersDropDownData();

            if(!empty($TID))
            {
                if(strpos($TID,'-'))
                {
                    $TID_comma_replaced = str_replace('-',',',$TID);
                }
                else
                {
                    $TID_comma_replaced = $TID;
                }

                $data['loc_options'] = self::getLocationDropDownData('CONCAT(requests.location_id," | ",location.location_name_short)','WHERE requests.status_id=1 AND products.prod_type_id IN ('.$TID_comma_replaced.')','ORDER BY requests.location_id');

                if(!empty($LID))
                {
                    $data['vendor_options'] = self::getVendorDropDownData('vendor_name','WHERE requests.status_id=1 AND requests.location_id='.$LID.' AND products.prod_type_id IN ('.$TID_comma_replaced.')','ORDER BY vendor.vendor_name');
                }
                else
                {
                    $data['vendor_options'] = array('' => '<-- Select');
                }

                $order_type_where = "AND P.prod_type_id IN (".$TID_comma_replaced.")";
            }
            else
            {
                $data['loc_options'] = array('' => '<-- Select');
                $data['vendor_options'] = array('' => '<-- Select');

                $order_type_where = "";
            }
            $data['TID'] = $TID;
            $data['LID'] = $LID;
            $data['VID'] = $VID;
          $number_requests = '';
            $query = \DB::select('SELECT CONCAT("(",COUNT(R.id),") <em>",O.order_type,"</em>, ") AS request_count
									 FROM requests R
								LEFT JOIN products P ON P.id = R.product_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
									WHERE R.status_id = 1
										'.$order_type_where.'
								 GROUP BY P.prod_type_id HAVING P.prod_type_id IS NOT NULL');

            foreach ($query as $row)
            {
                $number_requests = $number_requests.$row->request_count;
            }
            $data['number_requests'] = substr($number_requests, 0, -2);

            $query = \DB::select('SELECT GROUP_CONCAT(order_type) AS order_types
									 FROM order_type
									WHERE id != 6
									  AND id != 10');
            if (count($query) == 1)
            {
                $data['order_types'] = $query[0]->order_types;
            }

            $data['title'] = 'Manage Requests';
            $data['subtitle1'] = 'Merch Requests';
            $data['subtitle2'] = 'Office Requests';
            $data['subtitle3'] = 'Other Requests';
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
