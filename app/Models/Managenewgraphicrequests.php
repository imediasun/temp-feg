<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Search\Searchable;
use App\Repositories\Managenewgraphicrequests\ManagenewgraphicrequestsRepository;
use App\Repositories\Managenewgraphicrequests\ElasticsearchManagenewgraphicrequestsRepository;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class managenewgraphicrequests extends Sximo
{
    use Searchable;
    protected $table = 'new_graphics_request';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public function location()
    {
        return $this->hasOne('App\Models\Location','id','location_id');
    }

    public function status(){
        return $this->hasOne('App\Models\Newgraphicrequestsstatus','id','status_id');
    }

    public function receiveUser(){
        return $this->hasOne('App\Models\Core\Users','id','request_user_id')->select(['id','first_name','last_name']);
    }

    public static function getRows($args, $cond = null) {

        $table = with(new static)->table;
        $key = with(new static)->primaryKey;
        extract(array_merge(array(
            'page' => '0',
            'limit' => '0',
            'sort' => '',
            'extraSorts' => [],
            'customSorts' => [],
            'order' => '',
            'params' => '',
            'global' => 1
        ), $args));

        //dump($args);

        if(isset($_SESSION['managenewgraphicrequests_search'])&& !empty($_SESSION['managenewgraphicrequests_search'])){
            $explode_string=explode('|',$_SESSION['managenewgraphicrequests_search']);
            $second_explode=explode(':',$explode_string[0]);
            $elastic = function (ManagenewgraphicrequestsRepository $repository)  {
                if(isset($_SESSION['managenewgraphicrequests_search'])){
                    $explode_string=explode('|',$_SESSION['managenewgraphicrequests_search']);
                    //dump($explode_string);
                    $result['status_id']=null;
                    $result['is_api_visible']=null;
                    $result['invoice_verified']=null;
                    foreach($explode_string as $k=>$param){
                        $second_explode=explode(':',$param);
                        switch($second_explode[0]){
                            case 'search_all_fields':

                                $main_search=$second_explode[2];
                                break;
                            case 'active':
                                $result['active']=$second_explode[2];
                                break;
                            case 'id':
                                $result['id']=$second_explode[2];
                                break;
                            case 'invoice_verified':
                                $result['invoice_verified']=$second_explode[2];
                                break;

                        }
                        //dump($second_explode);
                    }
                    //$second_explode=explode(':',$explode_string[0]);

                }

                if(empty($main_search)){
                    unset($_SESSION['managenewgraphicrequests_search']);
                }
                else{
                    $result['managenewgraphicrequests'] = $repository->search((string) $main_search);
                    // dump('orders=>',$result['location']);
                    return $result;
                }
                return false;
            };
            $client = ClientBuilder::create()->setHosts(config('services.search.hosts'))->build();
            $el=new ElasticsearchManagenewgraphicrequestsRepository($client);
            $pre_products=$elastic($el);

            if($pre_products['managenewgraphicrequests']!=null){


                if($pre_products['invoice_verified']) {
                    //dump('intvalinvoice_verified',intval($pre_products['invoice_verified']));
                    $pre_products['managenewgraphicrequests'] = $pre_products['managenewgraphicrequests']->where('invoice_verified', intval($pre_products['invoice_verified']));
                }


                $products=$pre_products['managenewgraphicrequests'];
                $total=count($products/*$pre_products['orders']*/);
                $search_total=$total;
                //dump('total1=>',$total);

                $offset = ($page - 1) * $limit;
                if ($offset >= $total && $total != 0 && $limit != 0) {
                    $page = ceil($total/$limit);
                    $offset = ($page-1) * $limit ;
                }




                //dump('offset',$offset);
     /*           foreach($products as $pr){
                    $string=$pr->description;
                    //dump($pr->description);

                    if($string != strip_tags($string)) {

                        $tmp=
                        $num_simbols_before_html_tags=strlen(explode('<b style=\'color:#da4f49\'>',$string)[0]);
                        $pr->index_description=$num_simbols_before_html_tags;
                        dump($pr->index_description);
                    }
                    else{
                        $pr->index_description=1000;
                    }




                    $string_request_user=$pr->request_user;


                    if($string_request_user != strip_tags($string_request_user)) {

                        $tmp=
                        $num_simbols_before_html_tags_request_user=strlen(explode('<b style=\'color:#da4f49\'>',$string_request_user)[0]);
                        $pr->index_request_user=$num_simbols_before_html_tags_request_user;
                        dump($pr->index_request_user);
                    }
                    else{
                        $pr->index_description=1000;
                    }



                }

                $products=$products->sortBy('index')->sortBy('index_request_user');*/

                if($total>0){
                    $products = $products->chunk($limit);/*$pre_products['orders']*/
                    $products=$products[$page - 1];
                }
                dump('products',$products);


            }
        }



        $orderConditional1 = '';
        if (!empty($customSorts)) {
            $customOrderConditionals = [];
            foreach($customSorts as $customSort => $customSortType) {
                $customSortItem = '`'.$customSort.'` '.$customSortType;
                $customOrderConditionals[] = $customSortItem;
            }
            $orderConditional1 = implode(', ', $customOrderConditionals);
            $orderConditional1 = !empty($orderConditional1) ? $orderConditional1.", ":$orderConditional1;
        }

        $orderConditional = ($sort != '' && $order != '') ? " ORDER BY {$orderConditional1} {$sort} {$order} " : '';
        if (!empty($extraSorts)) {
            if (empty($orderConditional)) {
                $orderConditional = " ORDER BY ";
            }
            else {
                $orderConditional .= ", ";
            }
            $extraOrderConditionals = [];
            foreach($extraSorts as $extraSortItem) {
                $extraSortItem[0] = '`'.$extraSortItem[0].'`';
                $extraOrderConditionals[] = implode(' ', $extraSortItem);
            }
            $orderConditional .= implode(', ', $extraOrderConditionals);
        }



        // Update permission global / own access new ver 1.1
        $table = with(new static)->table;
        if ($global == 0)
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        // End Update permission global / own access new ver 1.1

        $rows = array();
        $select = self::querySelect();

        /*

        */
        $createdFlag = false;

        if ($cond != null) {
            $select .= self::queryWhere($cond);
        }
        else {
            $select .= self::queryWhere();
        }

        if(!empty($createdFrom)){
            if($cond != 'only_api_visible')
            {
                $select .= " AND created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            else
            {
                $select .= " AND api_created_at BETWEEN '$createdFrom' AND '$createdTo'";
            }
            $createdFlag = true;
        }

        if(!empty($updatedFrom)){

            if($createdFlag){
                if($cond != 'only_api_visible')
                {
                    $select .= " OR updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " OR api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }
            else{
                if($cond != 'only_api_visible')
                {
                    $select .= " AND updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
                else
                {
                    $select .= " AND api_updated_at BETWEEN '$updatedFrom' AND '$updatedTo'";
                }
            }

        }

        if(!empty($order_type_id)){
            $select .= " AND order_type_id in($order_type_id)";
        }
        if(!empty($status_id)){
            $select .= " AND status_id='$status_id'";
        }
        if(!empty($active)){//added for location
            $select .= " AND location.active='$active'";
        }

        //Log::info("Total Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}");
        $counter_select =\DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}");


        if(!isset($search_total)) {
            $total = count($counter_select);
            if ($table == "img_uploads") {
                $total = "";
            }
        }else{
            //dump('here');
            $total = $search_total;
        }

        $offset = ($page - 1) * $limit;
        if ($offset >= $total && $total != 0 && $limit != 0) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }

        $limitConditional = ($page != 0 && $limit != 0) ? "LIMIT  $offset , $limit" : '';
        // echo $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";
        //Log::info("Query : ".$select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        self::$getRowsQuery = $select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ";


        if(isset($_SESSION['managenewgraphicrequests_search']) && !empty($_SESSION['managenewgraphicrequests_search'])){
            //var_dump('ses=>',$_SESSION['order_search']);
            if(isset($products)){
                $result=$products;}
            unset($_SESSION['managenewgraphicrequests_search']);
        }
        else {
            $result = \DB::select($select . " {$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");
        }
        if ($key == '') {
            $key = '*';
        } else {
            $key = $table . "." . $key;
        }

        //dump('total=>',$total);
        return $results = array('rows' => $result, 'total' => $total);
    }

    public static function querySelect()
    {

        return " SELECT
  new_graphics_request.*,
  IF(new_graphics_request.aprrove_user_id = 0,'',new_graphics_request.aprrove_user_id) AS aprrove_user_id,
  u1.username,
  location.location_name_short,
  new_graphics_request_status.status
FROM new_graphics_request
  LEFT JOIN users u1
    ON (new_graphics_request.request_user_id = u1.id)
   LEFT JOIN users u2
    ON (new_graphics_request.aprrove_user_id = u2.id)
  LEFT JOIN location
    ON (new_graphics_request.location_id = location.id)
  LEFT JOIN new_graphics_request_status
    ON (new_graphics_request.status_id = new_graphics_request_status.id) ";

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
