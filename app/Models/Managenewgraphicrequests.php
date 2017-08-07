<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class managenewgraphicrequests extends Sximo
{

    protected $table = 'new_graphics_request';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {

        return "SELECT new_graphics_request.*,if(new_graphics_request.aprrove_user_id = 0,'',new_graphics_request.aprrove_user_id)as aprrove_user_id,u1.username,location.location_name_short,new_graphics_request_status.status FROM new_graphics_request LEFT JOIN users u1 ON (new_graphics_request.request_user_id = u1.id)
                LEFT JOIN location ON (new_graphics_request.location_id = location.id)
                LEFT JOIN new_graphics_request_status ON (new_graphics_request.status_id = new_graphics_request_status.id)";

    }

    public static function queryWhere($cond = null)
    {

        $where = " WHERE new_graphics_request.id IS NOT NULL ";
        if ($cond['view'] == "open") {
            $where .= " AND new_graphics_request.status_id IN(1,2,3,4)";
        } elseif ($cond['view'] == "archive") {
            $where .= " AND new_graphics_request.status_id IN(0,5)";
        }
        return $where;
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public static function getManageGraphicsRequestsInfo($var1 = null, $var2 = null)
    {

        if (substr($var1, 0, 3) == 'LID') {
            $var1 = substr($var1, 3);
            $LID = $var1;
        } else if (substr($var2, 0, 3) == 'LID') {
            $var2 = substr($var2, 3);
            $LID = $var2;
        }

        if (empty($LID)) {
            $data['LID'] = '';
            $data['search_name'] = '';
            //$data['vendor_options'] = $this->create_vendor_options('vendor_name','WHERE requests.status_id=1','ORDER BY vendor.vendor_name');
        } else {
            $data['LID'] = $LID;
            //  $data['vendor_options'] = $this->create_vendor_options('vendor_name','WHERE requests.status_id=1 AND requests.location_id="'.$LID.'"','ORDER BY vendor.vendor_name');

                // $query = $this->db->query('SELECT location_name_short FROM location WHERE id = "'.$LID.'"');
                // if ($query->num_rows() == 1)
                //  {
                //      $row = $query->row();
                //      $data['search_name'] = $row->location_name_short;
                //   }
            }
            if (substr($var1, 0, 3) == 'VID') {
                $var1 = substr($var1, 3);
                $VID = $var1;
            } else if (substr($var2, 0, 3) == 'VID') {
                $var2 = substr($var2, 3);
                $VID = $var2;
            }
            if (empty($VID)) {
                $data['VID'] = '';
            } else {
                $data['VID'] = $VID;
            }
            //  $data['loc_options'] = $this->create_location_options('CONCAT(requests.location_id," | ",location.location_name_short)','WHERE requests.status_id=1','ORDER BY requests.location_id');
            /*
             * *************** Code deprecated no longer in use *********************
             * Added By : Arslan
             * Date : 10-June-2017
             * ***********************************************************************
             */
            /*
            $query = \DB::select('SELECT COUNT(R.id) AS request_count  FROM requests R
								LEFT JOIN products P ON P.id = R.product_id WHERE R.status_id = 1 AND (P.prod_type_id = 6 OR P.prod_type_id = 10)');
            if (count($query) == 1) {
                $data['number_existing_requests'] = $query[0]->request_count;
            }*/
            $query = \DB::select('SELECT COUNT(id) AS request_count FROM new_graphics_request WHERE status_id IN(1,2,3,4)');
            if (count($query) == 1) {
                $data['number_new_requests'] = $query[0]->request_count;
            }
            /*
             * *************** Code deprecated no longer in use *********************
             * Added By : Arslan
             * Date : 10-June-2017
             * ***********************************************************************
             */
            /*
            $query = \DB::select('SELECT GROUP_CONCAT(order_type) AS order_types
									 FROM order_type
									WHERE (id = 6
									    OR id = 10)');
            if (count($query) == 1) {
                $data['order_types'] = $query[0]->order_types;
            }
            */
            return $data;

    }


}
