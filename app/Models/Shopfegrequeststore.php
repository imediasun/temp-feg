<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Log;

class shopfegrequeststore extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';
	public function __construct() {
		parent::__construct();
	}
	public static function querySelect(  ){
		
		return "SELECT products.*,vendor.vendor_name,O.order_type,T.product_type FROM products
                LEFT JOIN vendor ON (products.vendor_id = vendor.id)
                LEFT JOIN order_type O ON (O.id = products.prod_type_id)
                LEFT JOIN product_type T ON (T.id = products.prod_sub_type_id)";
	}
   public  function queryWhere($cond=null){
       $return=" WHERE products.id IS NOT NULL";
		if(is_array($cond))
        {
            $cond= array_filter($cond);
            if(!empty($cond))
            {
                $prodType=isset($cond['order_type'])?$cond['order_type']:"";
                $prodSubType=isset($cond['product_type'])?$cond['product_type']:"";
              if($cond['type'] == "store")
              {
                      $group_ids = $this->get_location_group_ids(\SiteHelpers::getLocationDetails(\Session::get('uid')));
                       if(is_numeric($prodType) && !empty($prodType) && empty($prodSubType))
                      {
                          $return.= " AND products.prod_type_id = ".$prodType."
							AND products.vendor_description != ''
							AND IF(products.limit_to_loc_group_id = 0, products.limit_to_loc_group_id = 0, products.limit_to_loc_group_id IN(".$group_ids."))
                            AND products.in_development = 0";
                      }
                      else if(is_numeric($prodType) && !empty($prodSubType) && !empty($prodSubType))
                      {
                         $return.=" AND products.prod_type_id = ".$prodType."
							AND products.prod_sub_type_id = ".$prodSubType."
							AND products.vendor_description != ''
							AND products.in_development = 0";
                      }
                      else
                      {
                          $return.=" AND products.sku != ''
                          AND products.in_development = 0";

                      }
              }
                if(array_search("active",$cond))
                {
                    $return.= " AND products.inactive=0";
                }
                elseif(array_search("inactive",$cond))
                {
                    $return.= " AND products.inactive=1";
                }
                elseif(array_search("all",$cond)){

                    $return.= " ";
                }
                else{
                    $return.=" AND products.inactive=0";
                }

            }
        }
        return $return;

	}
	
	public static function queryGroup(){
		return "  ";
	}
    function get_location_group_ids($reg_loc_ids)
    {
        $ids=array();
        foreach($reg_loc_ids as $loc_id)
        {
            $ids[]=$loc_id->id;
        }
        $reg_loc_ids=implode(',',$ids);
        $query =\DB::select('SELECT GROUP_CONCAT(DISTINCT loc_group_id) AS group_ids FROM location WHERE id IN('.$reg_loc_ids.')');
       if (count($query) == 1)
        {
            $data['loc_group_ids'] = $query[0]->group_ids;
        }
        return $data['loc_group_ids'];
    }
    function shoppingCart($active_inactive = null, $prodType = null, $prodSubType = null)
    {
      if(false)
      {

      }else
        {
            $location_id = \Session::get('selected_location');
            $data['selected_location'] = $location_id;
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) START
            $data['shopping_cart_total'] = '';
            $data['amt_short'] = '';
            $data['amt_short_message'] = '';
            $query = \DB::select('SELECT V.vendor_name,V.id AS vendor_id,V.min_order_amt,SUM(R.qty*P.case_price) AS total,
                                V.min_order_amt - SUM(R.qty*P.case_price) AS amt_short FROM requests R
								LEFT JOIN products P ON P.id = R.product_id
								LEFT JOIN vendor V ON V.id = P.vendor_id
									WHERE R.status_id = 0
									  AND R.location_id = "'.$location_id .'"
								 GROUP BY V.vendor_name');
            foreach ($query as $row)
            {
                $row = array(
                    'vendor_name' => $row->vendor_name,
                    'vendor_id' => $row->vendor_id,
                    'vendor_min_order_amt' => $row->min_order_amt,
                    'vendor_total' => $row->total,
                    'amt_short' => $row->amt_short
                );

                $array[] = $row;

                if($row['amt_short'] > 0)
                {
                    $data['amt_short_message'] = 'Your '.$data['amt_short_message'].$row['vendor_name'].' order is short by $'.$row['amt_short'].'. ';
                }

                $data['shopping_cart_total'] = $data['shopping_cart_total'] + $row['vendor_total'];
            }
            if(isset($array))
            {
                $data['items_in_cart'] = 'yes';
                //$data['subtotals'] = $array;
            }
            else
            {
                $data['items_in_cart'] = 'no';
            }
            // SHOPPING CART TOTALS (SHOWN ABOVE CART) END

            // NEW PRODUCTS (SHOWN ABOVE STORE) START
           // $today = $this->get_local_time('date');

            $newQuery = \DB::select('SELECT CONCAT(V.vendor_name," - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case <br />") AS item,
										  P.id as PID
									 FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
									WHERE P.date_added > subdate(current_date, 14)
									  AND P.vendor_description != ""
								 ORDER BY RAND()
									LIMIT 4');


            foreach ($newQuery as $row)
            {
                $row = array(
                    'item' => $row->item,
                    'new_product_id' => $row->PID
                );

                $newArray[] = $row;
            }
            if(isset($newArray))
            {
                $data['new_products'] = $newArray;
            }
            // NEW PRODUCTS (SHOWN ABOVE STORE) END
            /// TEMPORARILY LIMIT REQUESTABLE TYPES -- WHERE id IN(2,3)///
                $data['title_2'] = 'Cart - '.\Session::get('selected_location_name');
            $data['title'] = 'Store';
            return $data;
        }
    }
    function getRecentlyAddedProducts()
    {
        $location_id = \Session::get('selected_location');
        $data['selected_location'] = $location_id;

        $newQuery = \DB::select('SELECT CONCAT(O.order_type," - </b><i>",V.vendor_name,"</i> - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case") AS item,
								P.id as PID  FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE P.date_added > subdate(current_date, 10)
                                AND P.vendor_description != ""');


        foreach ($newQuery as $row)
        {
            $row = array(
                'item' => $row->item,
                'new_product_id' => $row->PID
            );

            $newArray[] = $row;
        }
        if(isset($newArray))
        {
            $data['new_products'] = $newArray;
        }
        // NEW PRODUCTS (SHOWN ABOVE STORE) END
        $data['title'] = 'Recently Added Products';
        return $data;
    }

    function getRecentlyAddedProduct()
    {
        $location_id = \Session::get('selected_location');
        $data['selected_location'] = $location_id;

        $newQuery = \DB::select('SELECT CONCAT(O.order_type," - </b><i>",V.vendor_name,"</i> - <b>",P.vendor_description," </b> ($",P.case_price," / ", P.num_items," items) per case") AS item,
								P.id as PID  FROM products P
								LEFT JOIN vendor V ON V.id = P.vendor_id
								LEFT JOIN order_type O ON O.id = P.prod_type_id
                                WHERE P.date_added > subdate(current_date, 10)
                                AND P.vendor_description != ""
                                LIMIT 1');


        foreach ($newQuery as $row)
        {
            $row = array(
                'item' => $row->item,
                'new_product_id' => $row->PID
            );

            $newArray[] = $row;
        }
        if(isset($newArray))
        {
            $data['new_products'] = $newArray;
        }
        // NEW PRODUCTS (SHOWN ABOVE STORE) END
        $data['title'] = 'Recently Added Products';
        return $data;
    }


    function newGraphicRequest($data)
    {

        $last_inserted_id=\DB::table('new_graphics_request')->insertGetId($data);
        $locationName = $this->get_location_info_by_id($data['location_id'], 'location_name_short');
        $game_info=explode('-',$data['description']);
        $mangeGraphicRequestURL = url("managenewgraphicrequests");
        $graphicApproveLink = "http://{$_SERVER['HTTP_HOST']}/managenewgraphicrequests/approve/$last_inserted_id";
        $graphicDenyLink = "http://{$_SERVER['HTTP_HOST']}/managenewgraphicrequests/deny/$last_inserted_id";
        $message = '<b>Date Requested:</b> '.\DateHelpers::formatDate($data['request_date']).'<br>
					<b>Requestor:</b> '.\Session::get('fid').'<br>
					<b>Location:</b> '.$data['location_id'].' | '.$locationName.'<br>
					<b>For Game:</b> '.$game_info[0]  .'<br>
					<b>Description:</b> '.$data['description'].'<br>
					<b>Quantity:</b> '.$data['qty'].'<br>
					<b>Need By Date:</b> '.$data['need_by_date'].'<br><br>

					<em>**Mark/Tom, please click on <a href="'.$graphicApproveLink.'">Approval</a> or <a href="'.$graphicDenyLink.'">Denial</a> <br>
					to Approve/Deny this graphic request <br><br>
					&nbsp;&nbsp;&nbsp; 2.) Set Priority Level at <b>'.$mangeGraphicRequestURL.'</b><br><br>
					**All cc\'d, please Reply to All <b> only if you wish to deny or modify request</b> and explain why.</em><br>';
                    $from = \Session::get('eid');
                    $to = CNF_GRAPHIC_MANAGER;
                    $cc = '';
                    $bcc = '';
                    $subject = 'New Graphics Request for '.$locationName;
                    $message = $message;
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    mail($to, $subject, $message, $headers);
                    Log::info("**Send Graphic Request Email => ",[$to, $subject, $message, $headers]);
                   return $last_inserted_id;
    }

    /**
     * override location drop down menu
     * @param $params
     * @param null $limit
     * @param null $parent
     * @return mixed
     */
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
