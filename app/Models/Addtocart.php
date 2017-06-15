<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Groups;

class addtocart extends Sximo
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
                merch_request_status.status,products.size,V1.vendor_name,order_type.order_type,If(products.reserved_qty = 0, '' , products.reserved_qty) as reserved_qty,
                (products.reserved_qty - requests.qty) as reserved_difference FROM requests
                LEFT JOIN users u1 ON (requests.request_user_id = u1.id)
			    LEFT JOIN products ON (requests.product_id = products.id)
			LEFT JOIN vendor V1 ON (products.vendor_id = V1.id)
			LEFT JOIN location ON (requests.location_id = location.id)
			LEFT JOIN merch_request_status ON (requests.status_id = merch_request_status.id)
			LEFT JOIN order_type ON (order_type.id = products.prod_type_id)";
    }

    public static function queryWhere()
    {
        $where="WHERE requests.id IS NOT NULL ";
        $data['user_level'] = \Session::get('gid');


        if ($data['user_level'] == Groups::MERCH_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN) {
            $where.= " AND requests.location_id = " . \Session::get('selected_location') . " AND requests.status_id = 9"; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
        } else {
            $where.= " AND requests.location_id = " . \Session::get('selected_location') . " AND requests.status_id = 4";
        }
        return $where ;
    }

    public static function queryGroup()
    {
        return "  ";
    }
    

    function popupCartData($productId=null,$v1=null,$qty=0)
    {

        $data['user_level']=\Session::get('gid');



        if (false && $data['user_level'] == Groups::PARTNER)
        {
            //redirect('./dashboard', 'refresh');
            return false;
        }
        else
        {

            $locationId = \Session::get('selected_location');

            if(empty($locationId)){
                return false;
            }

            if ($data['user_level'] == Groups::MERCH_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN)
            {
                $statusId = 9; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
            }
            else
            {
                $statusId = 4;
            }
            if(!empty($productId) &&!empty($qty))
            {


               // $qty = 1;



                $query = \DB::select('SELECT id FROM requests WHERE product_id = "'.$productId.'" AND status_id = "'.$statusId.'" AND location_id = "'.$locationId.'"');

                /// TO AVOID ADDITNG THE SAME PRODUCT IN TWO PLACES
                if (count($query) == 0)
                {

                    $now = date('Y-m-d');
                    $insert = array(
                        'product_id' => $productId,
                        'location_id' => $locationId,
                        'request_user_id' => \Session::get('uid'),
                        'request_date' => $now,
                        'qty' => $qty,
                        'status_id' => $statusId
                    );
                    \DB::table('requests')->insert($insert);
                }
            }
            $location_id = \Session::get('selected_location');

            $data['selected_location'] = $location_id;


            // SHOPPING CART TOTALS (SHOWN ABOVE CART) START
            $data['shopping_cart_total'] = '';
            $data['amt_short'] = '';
            $data['amt_short_message'] = '';

                                       $select='SELECT V.vendor_name,  V.id AS vendor_id, V.min_order_amt, SUM(R.qty*P.case_price) AS total,
                                       V.min_order_amt - SUM(R.qty*P.case_price) AS amt_short FROM requests R
                                       LEFT JOIN products P ON P.id = R.product_id
								       LEFT JOIN vendor V ON V.id = P.vendor_id
									   WHERE R.status_id = "' . $statusId . '" AND V.vendor_name !="null"
									   AND R.location_id = "' . $location_id . '"
                                       GROUP BY V.vendor_name';
            if($v1)
            {
                $select .= ' HAVING V.vendor_name="'.$v1.'"';
            }

                $query = \DB::select($select);


            $amt_short_message="";
            foreach ($query as $row)
            {
                $row = array(
                    'vendor_name' => $row->vendor_name,
                    'vendor_id' => $row->vendor_id,
                    'vendor_min_order_amt' => $this->parseNumber($row->min_order_amt),
                    'vendor_total' => $this->parseNumber($row->total),
                    'amt_short' => $this->parseNumber($row->amt_short)
                );

                $array[] = $row;


                if($row['amt_short'] > 0)
                {
                    $amt_short_message  .= $data['amt_short_message'].$row['vendor_name'].' order is short by $'.$row['amt_short'].'. ';
                }

                $data['shopping_cart_total'] = $this->parseNumber($data['shopping_cart_total'] + $row['vendor_total']);
            }
            $data['amt_short_message']=$amt_short_message;
            if(isset($array))
            {
                $data['subtotals'] = $array;
            }
            else
            {
                $data['empty']="";
            }
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) END

            // NEW PRODUCTS (SHOWN ABOVE STORE) START
            $today = date('Y-m-dd');
            //$this->load->library('dateoperations');

            /// USE SELECTED LOCATION TO GET LOCATION NAME
            $query = \DB::select('SELECT location_name_short FROM location WHERE id='.$location_id.'');
            if (count($query) == 1)
            {
                $data['title_2'] = 'Cart - '.$query[0]->location_name_short;
            }

            return $data;
        }

    }
    public static function destroy($ids)
    {

        // We'll initialize a count here so we will return the total number of deletes
        // for the operation. The developers can then check this number as a boolean
        // type value or get this total count of records deleted for logging, etc.
        $count = 0;



        // We will actually pull the models from the database table and call delete on
        // each of them individually so that their events get fired properly with a

        $selected_location=\Session::get('selected_location');
        $update=array('status_id' => '2');

        foreach ($ids as $rid) {
            \DB::update("update  requests set status_id=2 where id='".$rid."' AND location_id =".$selected_location);

        $count++;
        }


        return $count;
    }


}
