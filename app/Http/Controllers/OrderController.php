<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Order;
use App\Models\OrderSendDetails;
use \App\Models\Sximo\Module;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Cache;
use PHPMailer;
use PHPMailerOAuth;

class OrderController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'order';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
        $this->modelview = new  \App\Models\Sbinvoiceitem();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);
        $this->module_id = Module::name2id($this->module);
        $this->pass = \FEGSPass::getMyPass($this->module_id);

        $this->data = array(
            'pass' => $this->pass,
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => $this->module,
            'pageUrl' => url($this->module),
            'return' => self::returnUrl()
        );


    }

    public function getExport($t = 'excel')
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-'.$exportId;
            \Session::put($exportSessionID, microtime(true));
        }

        $info = $this->model->makeInfo($this->module);
        //$master  	= $this->buildMasterDetail();

        $sort = (!is_null(Input::get('sort')) ? Input::get('sort') : $this->info['setting']['orderby']);
        $order = (!is_null(Input::get('order')) ? Input::get('order') : $this->info['setting']['ordertype']);

        // Get order_type search filter value and location_id saerch filter values
        $orderTypeFilter = $this->model->getSearchFilters(array('order_type' => 'order_selected', 'location_id' => ''));
        extract($orderTypeFilter);
        // default order type is blank which means all or anything select other other defaults
        if (empty($order_selected)) {
            $order_selected = "";
        }

        // rebuild search query skipping 'order_type' filter // depricated
        $trimmedSearchQuery = $this->model->rebuildSearchQuery(null, array('order_type'));

        // Filter Search for query
        // build sql query based on search filters
        $filter = $this->getSearchFilterQuery();//$filter = is_null(Input::get('search')) ? '' : $this->buildSearch($trimmedSearchQuery);
        // Get assigned locations list as sql query (part)
        $locationFilter = \SiteHelpers::getQueryStringForLocation('orders');
        // if search filter does not have location_id filter
        // add default location filter
        if (empty($location_id)) {
            $filter .= $locationFilter;
        }


        //$filter 	.=  $master['masterFilter'];
        //comment limit
        $params = array(
            'page' => 1,
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
        );

        $minutes = 60;
        $cacheKey = md5($filter . $order_selected . $sort . $order);
        $results = Cache::remember($cacheKey, $minutes, function () use ($params, $order_selected) {
            return $this->model->getExportRows($params, $order_selected);
        });
        //$results = $this->model->getExportRows($params);

        $fields = $info['config']['grid'];
        $rows = $results['rows'];

        //$rows = $this->updateDateInAllRows($rows);

        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
        );

        if ($t == 'word') {

            return view('sximo.module.utility.word', $content);

        } else if ($t == 'pdf') {

            $pdf = PDF::loadView('sximo.module.utility.pdf', $content);
            return view($this->data['pageTitle'] . '.pdf');

        } else if ($t == 'csv') {

            return view('sximo.module.utility.csv', $content);

        } else if ($t == 'print') {

            return view('sximo.module.utility.print', $content);

        } else {

            return view('sximo.module.utility.excel', $content);
        }
    }


    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['sid'] = "";
        $this->data['access'] = $this->access;
        return view('order.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'order')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        $this->getSearchParamsForRedirect();
       // echo \Session::get('searchParams');
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
            \Session::put('config_id', $config_id);
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
            \Session::put('config_id', $config_id);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query

        // Get order_type search filter value and location_id saerch filter values
        $orderTypeFilter = $this->model->getSearchFilters(array('order_type' => 'order_selected', 'location_id' => ''));
        extract($orderTypeFilter);
        // default order type is blank which means all or anything select other other defaults
        if (empty($order_selected)) {
            $order_selected = "";
        }

        // rebuild search query skipping 'order_type' filter // depricated
        $trimmedSearchQuery = $this->model->rebuildSearchQuery(null, array('order_type'));

        // Filter Search for query
        // build sql query based on search filters
        $filter = $this->getSearchFilterQuery();
        // Get assigned locations list as sql query (part)
        $locationFilter = \SiteHelpers::getQueryStringForLocation('orders');
        // if search filter does not have location_id filter
        // add default location filter
        if (empty($location_id)) {
            $filter .= $locationFilter;
        }

        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );
        $isRedirected=\Session::get('filter_before_redirect');
        \Session::put('order_selected',$order_selected);

       // \Session::put('filter_before_redirect',false);
        //\Session::put('params',$params);
         $results = $this->model->getRows($params, $order_selected);
        if (count($results['rows']) == 0 and $page != 1) {
            $params['limit'] = $this->info['setting']['perpage'];
            $results = $this->model->getRows($params, $order_selected);
         }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('order/data');
        $rows = $results['rows'];
        foreach ($rows as $index => $data) {
            $rows[$index]->date_ordered = date("m/d/Y", strtotime($data->date_ordered));
            //$location = \DB::select("Select location_name FROM location WHERE id = " . $data->location_id . "");
           // $rows[$index]->location_id = (isset($location[0]->location_name) ? $location[0]->location_name : '');
            $user = \DB::select("Select username FROM users WHERE id = " . $data->user_id . "");
            $rows[$index]->user_id = (isset($user[0]->username) ? $user[0]->username : '');
            $order_type = \DB::select("Select order_type FROM order_type WHERE id = " . $data->order_type_id . "");
            $rows[$index]->order_type_id = (isset($order_type[0]->order_type) ? $order_type[0]->order_type : '');

            //  $vendor = \DB::table('vendor')->where('id', '=', $data->vendor_id)->get(array('vendor_name'));
            //$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');

            $order_status = \DB::select("Select status FROM order_status WHERE id = " . $data->status_id . "");
            $partial = $data->is_partial == 1 ? ' (Partial)':'';
            $rows[$index]->status_id = (isset($order_status[0]->status) ? $order_status[0]->status.$partial : '');
        }
        $this->data['param'] = $params;
        $this->data['rowData'] = $rows;
        // Build Pagination
        $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = ($page * $params['limit']) - $params['limit'];
        // Grid Configuration
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['colspan'] = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['setting'] = $this->info['setting'];

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        $this->data['order_selected'] = $order_selected;
        // Render into template
        return view('order.table', $this->data);

    }


    function getUpdate(Request $request, $id = 0, $mode = '')
    {
        $fromStore = 0;
        $editmode = $prefill_type = 'edit';
        $where_in_expression = '';
        \Session::put('redirect','order');
        $this->data['setting'] = $this->info['setting'];
        $isRequestApproveProcess = false;
        if ($id != 0 && $mode == '') {
            $mode = 'edit';
        } elseif ($id == 0 && $mode == '') {
            $mode = 'create';
        } elseif (substr($mode, 0, 3) == 'SID') {
            \Session::put('redirect','managefegrequeststore');
            $isRequestApproveProcess = true;
            $mode = $mode;
            $fromStore = 1;
        } elseif ($mode == "clone") {
            $mode = 'clone';
        }
        if ($id == 0) {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        if ($id != 0) {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('orders');
        }

        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['mode'] = $mode;
        $this->data['isRequestApproveProcess'] = $isRequestApproveProcess;
        $this->data['id'] = $id;
        $this->data['data'] = $this->model->getOrderQuery($id, $mode);
        $this->data['relationships'] = $this->model->getOrderRelationships($id);
        $user_allowed_locations = implode(',', \Session::get('user_location_ids'));
        $this->data['games_options'] = $this->model->populateGamesDropdown();
        return view('order.form', $this->data)->with('fromStore',$fromStore);
    }

   public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('orders');
        }
        $this->data['order_data'] = $this->model->getOrderQuery($id, 'edit');

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['relationships'] = $this->model->getOrderRelationships($id);
        return view('order.view', $this->data);
    }
    // Uncomment if Copy functionality is needed for orders
// it need testing afer commenting.
/*
    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM orders ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        // $toCopy = implode(",", $request->input('ids'));
        $toCopy=$request->input('ids');
        foreach($toCopy as $to)
        {

            $sql = " SELECT " . implode(",", $columns) . " FROM orders WHERE id = " . $to . "";
            $order_data=\DB::select($sql);
            $order_data_arr="";
            foreach($order_data as $od)
            {

                $po_3=$this->validatePO($od->po_number,$od->po_number,$od->location_id);
                $po_number=$od->location_id.'-'. date("mdy", strtotime(date('mdy'))).'-'.$po_3;
                $order_data_arr=array(
                    'user_id'=> $od->user_id,
                    'company_id' => $od->company_id,
                    'date_ordered' => $od->date_ordered,
                    'order_total' => $od->order_total,
                    'warranty' => $od->warranty,
                    'location_id' => $od->location_id,
                    'vendor_id'=>$od->vendor_id,
                    'order_description'=>$od->order_description,
                    'status_id'=>$od->status_id,
                    'order_type_id'=>$od->order_type_id,
                    'game_id'=>$od->game_id,
                    'freight_id'=>$od->freight_id,
                    'po_number'=>$po_number,
                    'po_notes' => $od->po_notes,
                    'notes' => $od->notes,
                    'date_received'=> $od->date_received,
                    'received_by'=> $od->received_by,
                    'quantity'=> $od->quantity,
                    'alt_address'=> $od->alt_address,
                    'request_ids'=>$od->request_ids,
                    'game_ids' => $od->game_ids,
                    'tracking_number'=>$od->tracking_number,
                    'added_to_inventory'=>$od->added_to_inventory,
                    'order_content'=>$od->order_content,
                    'new_format'=>$od->new_format,
                    'is_partial'=>$od->is_partial,
                );
            }
            \DB::table('orders')->insert($order_data_arr);
            $new_order_id = \DB::getPdo()->lastInsertId();
            $contents_sql="select request_id,product_id,product_description,price,qty,game_id,item_name,case_price,total,item_received,sku from order_contents where order_id=$to";
            $order_contents=\DB::select($contents_sql);

            foreach($order_contents as $oc)
            {
                $contents_arr=array(
                    'order_id'=>$new_order_id,
                    'request_id'=>$oc->request_id,
                    'product_description'=>$oc->product_description,
                    'price'=>$oc->price,
                    'qty'=>$oc->qty,
                    'game_id'=>$oc->game_id,
                    'item_name'=>$oc->item_name,
                    'case_price'=>$oc->case_price,
                    'total'=>$oc->total,
                    'item_received'=>$oc->item_received,
                    'sku'=>$oc->sku
                );
                \DB::table('order_contents')->insert($contents_arr);
            }
        }

        //$sql = "INSERT INTO orders (" . implode(",", $columns) . ") ";
        //$sql .= " SELECT " . implode(",", $columns) . " FROM orders WHERE id IN (" . $toCopy . ")";

        //\DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }
  */
    function postSave(Request $request, $id = 0)
    {
        $query = \DB::select('SELECT R.id FROM requests R LEFT JOIN products P ON P.id = R.product_id WHERE R.location_id = "' . (int)$request->location_id . '"  AND P.vendor_id = "' . (int)$request->vendor_id . '" AND R.status_id = 1');

        /*$productIdArray = $request->get('product_id');
        $query = \DB::select('select id from requests where location_id = "' . (int)$request->location_id . '" AND status_id = 1 AND product_id IN ('.implode(',',$productIdArray).')');
*/
        if (count($query) < 1 && $request->from_sid == 1) {
            return response()->json(array(
                'message' => 'Someone has already ordered these products',
                'status' => 'error',

            ));
        }
        $rules = array(
              //  'location_id' => "required",
                'vendor_id' => 'required',
                'order_type_id' => "required",
                'freight_type_id' => 'required',
                'date_ordered' => 'required',
             //   'po_3' => 'required'
            );
        $validator = Validator::make($request->all(), $rules);
        $order_data = array();
        $order_contents = array();
        $data = array_filter($request->all());
        $redirect_link = "order";
        if ($validator->passes()) {
            $order_id = $request->get('order_id');
            $editmode = $request->get('editmode');
            $where_in = $request->get('where_in_expression');
            //$where_in = implode(',',$query);
            $SID_string = $request->get('SID_string');
            $company_id = $request->get('company_id');
            $location_id = $request->get('location_id');
            $order_type = $request->get('order_type_id');
            $vendor_id = $request->get('vendor_id');
            $vendor_email = $this->model->getVendorEmail($vendor_id);
            $freight_type_id = $request->get('freight_type_id');
            $date_ordered = date("Y-m-d", strtotime($request->get('date_ordered')));
            $total_cost = $request->get('order_total');
            $notes = $request->get('po_notes');
            $is_freehand = $request->get('is_freehand') == "1" ?1:0;
            $po_1 = $request->get('po_1');
            $po_2 = $request->get('po_2');
            $po_3 = $request->get('po_3');
            $po = $po_1 . '-' . $po_2 . '-' . $po_3;
            $altShipTo = $request->get('alt_ship_to');
            $alt_address = '';
            $order_description = '';
            if (!empty($altShipTo)) {
                $rules = array(
                        'to_add_name' => 'required|max:60',
                        'to_add_street' => 'required|min:5',
                        'to_add_city' => 'required|min:5',
                        'to_add_state' => 'required|max:2',
                        'to_add_zip' => 'required|max:10'
                    );
                $validator = Validator::make($request->all(), $rules);
                $to_add_name = $request->get('to_add_name');
                $to_add_street = $request->get('to_add_street');
                $to_add_city = $request->get('to_add_city');
                $to_add_state = $request->get('to_add_state');
                $to_add_zip = $request->get('to_add_zip');
                $to_add_notes = $request->get('to_add_notes');
                $alt_address = $to_add_name . '|' . $to_add_street .
                        '|' . $to_add_city . '| ' . $to_add_state .
                        '| ' . $to_add_zip . '|' . $to_add_notes;
            }
            $itemsArray = $request->get('item');
            $itemNamesArray = $request->get('item_name');
            $skuNumArray = $request->get('sku');
            $casePriceArray = $request->get('case_price');
            $priceArray = $request->get('price');
            $qtyArray = $request->get('qty');
            $productIdArray = $request->get('product_id');
            $requestIdArray = $request->get('request_id');
            $games = $request->get('game');
            $num_items_in_array = count($itemsArray);

            for ($i = 0; $i < $num_items_in_array; $i++) {
                $j = $i + 1;
                if($order_type == 20 || $order_type == 10 || $order_type== 17 || $order_type == 1 )
                {
                    $itemsPriceArray[] = $priceArray[$i];
                }
                elseif($order_type  == 7 || $order_type  == 8 || $order_type == 6)
                {
                    $itemsPriceArray[] = $casePriceArray[$i];
                }
                elseif($order_type  == 4)
                {
                    $itemsPriceArray[] = ($priceArray[$i] == 0.00)?$casePriceArray[$i]:$priceArray[$i];
                }
                $order_description .= ' | item' . $j . ' - (' . $qtyArray[$i]
                        . ') ' . $itemsArray[$i] . ' @ $' .
                        $itemsPriceArray[$i] . ' ea.';
            }
            if ($editmode == "edit") {
                $orderData = array(
                    'company_id' => $company_id,
                    'order_type_id' => $order_type,
                    'vendor_id' => $vendor_id,
                    'order_description' => $order_description,
                    'order_total' => $total_cost,
                    'freight_id' => $freight_type_id,
                    'alt_address' => $alt_address,
                    'request_ids' => $where_in,
                    'po_notes' => $notes
                );
                $this->model->insertRow($orderData, $order_id);
                $last_insert_id = $order_id;
                \DB::table('order_contents')->where('order_id', $last_insert_id)->delete();
            } else {
                $orderData = array(
                    'user_id' => \Session::get('uid'),
                    'company_id' => $company_id,
                    'location_id' => $location_id,
                    'order_type_id' => $order_type,
                    'date_ordered' => $date_ordered,
                    'vendor_id' => $vendor_id,
                    'order_description' => $order_description,
                    'status_id' => 1,
                    'order_total' => $total_cost,
                    'freight_id' => $freight_type_id,
                    'po_number' => $po,
                    'alt_address' => $alt_address,
                    'request_ids' => $where_in,
                    'new_format' => 1,
                    'is_freehand' => $is_freehand,
                    'po_notes' => $notes
                );
                if ($editmode == "clone") {
                    $id = 0;
                }
                $this->model->insertRow($orderData, $id);
                $order_id = \DB::getPdo()->lastInsertId();
            }
            for ($i = 0; $i < $num_items_in_array; $i++) {

                if (empty($productIdArray[$i])) {
                    $product_id = '0';
                } else {
                    $product_id = $productIdArray[$i];
                }
                if (empty($skuNumArray[$i])) {
                    $sku_num = '0';
                } else {
                    $sku_num = $skuNumArray[$i];
                }

                if (empty($requestIdArray[$i])) {
                    $request_id = '0';
                } else {
                    $request_id = $requestIdArray[$i];
                }

                if ($order_type == 1) {
                    $game_id = $games[$i];
                } else {
                    $game_id = '0';
                }
                $contentsData = array(
                    'order_id' => $order_id,
                    'request_id' => $request_id,
                    'product_id' => $product_id,
                    'product_description' => $itemsArray[$i],
                    'price' => $priceArray[$i],
                    'qty' => $qtyArray[$i],
                    'game_id' => $game_id,
                    'item_name' => $itemNamesArray[$i],
                    'case_price' => $casePriceArray[$i],
                    'sku' => $sku_num,
                    'total' => $itemsPriceArray[$i] * $qtyArray[$i]
                );

                \DB::table('order_contents')->insert($contentsData);
                if ($order_type == 18) //IF ORDER TYPE IS PRODUCT IN-DEVELOPMENT, ADD TO PRODUCTS LIST WITH STATUS IN-DEVELOPMENT
                {
                    $productData = array(
                        'vendor_id' => $vendor_id,
                        'vendor_description' => $itemsArray[$i],
                        'case_price' => $priceArray[$i],
                        'num_items' => $qtyArray[$i],
                        'in_development' => 1,
                    );
                    \DB::table('products')->insert($productData);
                }
                if (!empty($where_in)) {
                    $redirect_link = "managefegrequeststore";
                    //// UPDATE STATUS TO APPROVED AND PROCESSED
                    $now = $this->model->get_local_time('date');

                    \DB::update('UPDATE requests
							 SET status_id = 2,
							 	 process_user_id = ' . \Session::get('uid') . ',
								 process_date = "' . $now . '",
								 blocked_at = null 
						   WHERE id IN(' . $where_in . ')');
                    //// SUBTRACT QTY OF RESERVED AMT ITEMS
                    $item_count = substr_count($SID_string, '-') - 1;
                    $SID_new = $SID_string;
                    $this->updateRequestAndProducts($item_count, $SID_new);
                } else {
                    $redirect_link = "order";
                }
            }
            // $mailto = $vendor_email;
            $from = \Session::get('eid');
            //send product order as email to vendor only if sendor and reciever email is available
            // if(!empty($mailto) && !empty($from))
            // {
            // $this->getPo($order_id, true,$mailto,$from);
            //}
            //$result = Mail::send('submitservicerequest.test', $message, function ($message) use ($to, $from, $full_upload_path, $subject) {
//
//        if (isset($full_upload_path) && !empty($full_upload_path)) {
//            $message->attach($full_upload_path);
//        }
//        $message->subject($subject);
//        $message->to($to);
//        $message->from($from);
//
//    });
            \Session::put('send_to', $vendor_email);
            \Session::put('order_id', $order_id);
            \Session::put('redirect', $redirect_link);
            $saveOrSendView = $this->getSaveOrSendEmail("pop")->render();
            return response()->json(array(
                'saveOrSendContent' => $saveOrSendView,
                'status' => 'success',
                'message' => \Lang::get('core.note_success'),

            ));

        }
        elseif($id != 0){
            $data = $this->validatePost('orders',true);
            $this->model->insertRow($data, $id);
            \Session::put('order_id', $id);
            $saveOrSendView = $this->getSaveOrSendEmail("pop")->render();
            return response()->json(array(
                'saveOrSendContent' => $saveOrSendView,
                'status' => 'success',
                'message' => \Lang::get('core.note_success'),

            ));
        }
        else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error',

            ));
        }

    }

    public function getSaveOrSendEmail($isPop = null)
    {
        $order_id=\Session::get('order_id');
        $order_type = \DB::select('SELECT order_type_id FROM orders WHERE id='.$order_id);
        $order_type_id = $order_type[0]->order_type_id;
        $is_test=env('APP_ENV', 'development') !== 'production'?true:false;
        if($is_test) {
            $receipts = FEGSystemHelper::getSystemEmailRecipients("send PO copy",null,true);
        }
        else{
            $receipts = FEGSystemHelper::getSystemEmailRecipients("send PO copy");
        }
        extract($receipts);
        $cc1="";
       // for Instant Win, Redemption Prize, Tickets, Uniforms and Office Supply categories send a copy of PO to
        // marissa sexton,mandee cook,lisa price
        if(($order_type_id == 7 || $order_type_id == 8 || $order_type_id == 4 || $order_type_id == 6))// && CNF_MODE != "development" )
        {
            $cc1=$cc;

        }
        else{
            $cc1="";
        }
        $viewName = empty($isPop) ? 'order.saveorsendemail' : 'order.pop.saveorsendemail';
        return view($viewName, array('cc'=>$cc1, "pageUrl" => $this->data['pageUrl']));
    }

    function postSaveorsendemail(Request $request)
    {
        $type = $request->get('type');
        $from=$request->get('from');
        $from=!empty($from)?$from:env('MAIL_USERNAME');
        $order_id = $request->get('order_id');
        if($type == "send") {
            $to = $request->get('to');
            $to = $this->getMultipleEmails($to);
            $cc = $request->get('cc');
            $cc = $this->getMultipleEmails($cc);
            $bcc = $request->get('bcc');
            $bcc = $this->getMultipleEmails($bcc);
            $message = $request->get('message');
        } else {
            $to = $request->get('to1');
            $to = $this->getMultipleEmails($to);
            $cc = $request->get('cc1');
            $cc = $this->getMultipleEmails($cc);
            $bcc = $request->get('bcc1');
            $bcc = $this->getMultipleEmails($bcc);
            $message = $request->get('message');
        }
        /*$order_type = \DB::select('SELECT order_type_id FROM orders WHERE id='.$order_id);
        $order_type_id = $order_type[0]->order_type_id;
        // for Instant Win, Redemption Prize, Tickets, Uniforms and Office Supply categories send a copy of PO to
        // marissa sexton,mandee cook,lisa price
        if(($order_type_id == 7 || $order_type_id == 8 || $order_type_id == 4 || $order_type_id == 6))// && CNF_MODE != "development" )
        {
            //uncomment after testing email sending
           /* $to[] = "marissa.sexton@fegllc.com";
            $to[] = "mandee.cook@fegllc.com";
            $to[] = "lisa.price@fegllc.com";*/
            // remove these lines after testing email sending
          /*  $to[] = "stanlymarian@gmail.com";
            $to[] = "jdanial710@gmail.com";
            $to[] = "daynaedvin@gmail.com";
        }*/
        $opt = $request->get('opt');
        $redirect_module=\Session::get('redirect');
        \Session::put('filter_before_redirect','no');

        if (count($to) == 0) {
            //\Session::put('filter_before_redirect',false);
            return response()->json(array(
                'message' => \Lang::get('core.email_missing_error'),
                'status' => 'error',

            ));
            } else {

            // Store email recipients for future use through auto-complete suggestion
            OrderSendDetails::saveDetails($order_id, ['emails' =>
                ["TO" => $to, "CC" => $cc, "BCC" => $bcc]]);

            \Session::put('filter_before_redirect','redirect');
            $status = $this->getPo($order_id, true, $to, $from, $cc, $bcc, $message);

            if ($status == 1)
            {
                return response()->json(array(
                    'message' => \Lang::get('core.mail_sent_success'),
                    'status' => 'success',

                ));
               }
            elseif ($status == 2)
            {
                return response()->json(array(
                    'message' => \Lang::get('core.google_account_not_exist'),
                    'status' => 'error',

                ));
               }
            elseif ($status == 3)
            {
                return response()->json(array(
                    'message' => \Lang::get('core.gmail_smtp_connect_failed'),
                    'status' => 'error',

                ));
              }
            elseif ($status == 4)
            {
                return response()->json(array(
                    'message' => \Lang::get('core.error_sending_mail'),
                    'status' => 'error',

                ));
            }


        }
    }

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));


        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_delete')
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));

        }

    }

    function getRemovalrequest($po_number = null)
    {
        $this->data['po_number'] = $po_number;
        return view('order.removalexplain', $this->data);
    }

    function postRemovalrequest(Request $request)
    {
        $configName = 'Order Request removal';
        $po_number = $request->get('po_number');
        $explanation = $request->get('explaination');
        $user = \Session::get('uid');
        $userName = \FEGFormat::userToName($user);
        $isTest = env('APP_ENV', 'development') !== 'production' ? true : false;
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName,null,$isTest);

        $messageData = [
            'userName' => $userName,
            'poNumber' => $po_number,
            'url' => url().'/order/removeorder/'.$po_number,
            'reason' => $explanation,
        ];
        $message = view('order.email.removal-request', $messageData)->render();
        $from = \Session::get('eid');
        $subject = 'Order Removal Request';
        $message = $message;

        FEGSystemHelper::sendSystemEmail(array_merge($receipts, array(
            'subject' => $subject,
            'message' => $message,
//            'preferGoogleOAuthMail' => true,
            'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
            'configName' => $configName,
            'from' => $from,
            'replyTo' => $from,

        )));

        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.request_sent_success')
        ));
    }

    function getRemoveorder($poNumber = "")
    {

        \DB::table('orders')->where('po_number', $poNumber)->delete();
        \Session::flash('success', 'Po  deleted successfully!');
        return Redirect::to('order')->with('messagetext', \Lang::get('core.note_block'))->with('msgstatus', 'success');

    }

    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '']);
        $skipFilters = ['search_all_fields'];
        $mergeFilters = [];
        extract($globalSearchFilter); //search_all_fields

        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        if (!empty($search_all_fields)) {
            $searchFields = [
                'orders.id',
                'U.username',
                'L.location_name',
                'V.vendor_name',
                'orders.order_total',
                'orders.order_description',
                'OS.status',
                'OT.order_type',
                'orders.po_number',
                'orders.po_notes',
                'orders.notes',
                'orders.is_partial',
                'YN.yesno'
            ];
            $dateSearchFields = [
                'orders.date_ordered',
                'orders.created_at',
                'orders.updated_at',
            ];
            $dates = FEGSystemHelper::probeDatesInSearchQuery($search_all_fields);
            $searchInput = ['query' => $search_all_fields, 'dateQuery' => $dates,
                'fields' => $searchFields, 'dateFields' => $dateSearchFields];

        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);


        return $filter;
    }

    function getPo($order_id = null, $sendemail = false, $to = null, $from = null, $cc = null, $bcc = null, $message = null)
    {
        $mode = "";
        if (isset($_GET['mode']) && !empty($_GET['mode'])) {
            $mode = $_GET['mode'];
        }
        $data = $this->model->getOrderData($order_id);
        if (empty($data)) {

        } else {
            if (empty($data[0]['po_for_location'])) {
                $data[0]['for_location'] = '';
            } else {
                $data[0]['for_location'] = '(for ' . $data[0]['po_for_location'] . ')';
            }

            if ($data[0]['freight_type'] == 'Employee Pickup') {
                $data[0]['po_location'] = '**WILL PICKUP FROM ' . $data[0]['vendor_name'] . '**' . "\n" . $data[0]['po_location'];
            }

            if (!empty($data[0]['loading_info']) && ($data[0]['order_type_id'] == 4 || $data[0]['order_type_id'] == 9)) //IF ORDER TYPE IS TICKTS/TOKENS OR FIXED ASSET -- AKA LARGE ITEMS
            {
                $data[0]['freight_type'] = $data[0]['freight_type'] . "\n" . 'DELIVERY NOTES: **' . $data[0]['loading_info'] . '**';
            }

            if (!empty($data[0]['loc_merch_contact_email']) && ($data[0]['order_type_id'] == 7 || $data[0]['order_type_id'] == 8)) {
                $data[0]['loc_contact_email'] = $data[0]['loc_merch_contact_email'];
            }

            if ($data[0]['email'] != $data[0]['loc_contact_email']) {
                $data[0]['loc_contact_email'] = ' AND ' . $data[0]['loc_contact_email'];
            } else {
                $data[0]['loc_contact_email'] = '';
            }
            if ($data[0]['order_type_id'] == 3 || $data[0]['order_type_id'] == 4) {
                $data[0]['cc_email'] = ', lisa.price@fegllc.com';
            } else {
                $data[0]['cc_email'] = '';
            }
            if (!empty($data[0]['po_attn'])) {
                $data[0]['po_location'] = $data[0]['po_location'] . "\n" . $data[0]['po_attn'];
            }
            if (empty($data[0]['po_notes'])) {
                $data[0]['po_notes'] = " NOTE: **TO CONFIRM ORDER RECEIPT AND PRICING, SEND EMAILS TO " . $data[0]['email'] . $data[0]['cc_email'] . $data[0]['loc_contact_email'] . "**";
            } else {
                $data[0]['po_notes'] = " NOTE: " . $data[0]['po_notes'] . " (Email Questions to " . $data[0]['email'] . $data[0]['cc_email'] . $data[0]['loc_contact_email'] . ")";
            }
            $order_description = $data[0]['order_description'];
            if (substr($order_description, 0, 3) === ' | ') {
                $order_description = substr($order_description, 3);

            }
            $order_description = str_replace(' | ', "\n", $order_description);
            if ($data[0]['new_format'] == 1) {
                $item_description_string = '';
                $sku_num_string = '';
                $item_price_string = '';
                $item_qty_string = '';
                $item_total_string = '';
                $item_total = '';
                $order_total_cost = 0;
                $numLenghtyDescItems = 0;
                for ($i = 0; $i < $data[0]['requests_item_count']; $i++) {
                    $j = $i + 1;
                    $item_total = $data[0]['orderItemsPriceArray'][$i] * $data[0]['orderQtyArray'][$i];
                    $item_total_string = "$ " . number_format($item_total, Order::ORDER_PERCISION);
                    $item_description_string = "Item #" . $j . ": " . $data[0]['orderDescriptionArray'][$i];
                    if (isset($data[0]['skuNumArray'])) {
                        $sku_num_string = $data[0]['skuNumArray'][$i];
                    }
                    $item_qty_string = $data[0]['orderQtyArray'][$i];
                    $item_price_string = $data[0]['orderItemsPriceArray'][$i];
                    $descriptionLength = strlen($item_description_string);
                    $order_total_cost = $order_total_cost + $item_total;
                }
                $data[0]['item_description_string'][$i] = $item_description_string;
                $data[0]['item_price_string'][$i] = $item_price_string;
                $data[0]['sku_num_string'][$i] = $sku_num_string;

                $data[0]['item_qty_string'][$i] = $item_qty_string;
                $data[0]['item_total_string'][$i] = $item_total_string;
                $data[0]['order_total_cost'] = $order_total_cost;
                $data[0]['company_name_long'] = 'Family Entertainment Group';

                $data[0]['relationships'] = implode("<br/>", $this->model->getOrderRelationships($order_id));
                
                //$item_total_string = $item_total_string."-----------------\n"."$ ".number_format($order_total_cost,3)."\n";
            }
            $pdf = \PDF::loadView('order.po', ['data' => $data, 'main_title' => "Purchase Order"]);
            if ($mode == "save") {
                $po_file_name = $data[0]['company_name_short'] . "_PO_" . $data[0]['po_number'] . '.pdf';
                $po_file_path =  'orders/' . $po_file_name;
              //  echo $po_file_path;
                if (\File::exists($po_file_path)) {
                    \File::delete($po_file_path);
                }
                $pdf->save($po_file_path);
                $data = array('file_name' => $po_file_name, 'url' => url());
                return $data;
            }
            if ($sendemail) {
                if (isset($to) && count($to) > 0) {
                    $filename = 'PO_' . $order_id . '.pdf';
                    $subject = "Purchase Order # {$data[0]['po_number']}";
                    $output = $pdf->output();
                    $file_to_save = public_path() . '/orders/' . $filename;
                    file_put_contents($file_to_save, $output);
                    $message = $message;
                    if(is_array($cc))
                    {
                        $cc = implode(',',$cc);
                    }
                    if(is_array($bcc))
                    {
                        $bcc = implode(',',$bcc);
                    }


                /* current user */
                    $google_acc = \DB::table('users')->where('id', \Session::get('uid'))->first();
                    $options = [
                        'cc'=>$cc,
                        'bcc'=>$bcc,
                        'attach'=>$file_to_save,
                        'filename'=>$filename,
                        'encoding'=>'base64',
                        'type'=>'application/pdf',
                        'preferGoogleOAuthMail'=>true
                    ];
                    if (!empty($google_acc->oauth_token) && !empty($google_acc->refresh_token)) {

                        $sent = FEGSystemHelper::sendEmail(implode(',',$to),$subject,$message,$google_acc->email,$options);
                        if (!$sent) {
                            return 3;
                        } else {
                            return 1;
                        }
                    }
                     else {
                      $sent= $this->sendPhpEmail($message,$to,$from,$subject,$pdf,$filename,$cc,$bcc);
                        return $sent;
                    }
                }
            } else {
                return $pdf->download($data[0]['company_name_short'] . "_PO_" . $data[0]['po_number'] . '.pdf');
            }
        }
    }

    function sendPhpEmail($message,$to,$from,$subject,$pdf,$filename,$cc,$bcc)
    {
        $result = \Mail::raw($message, function ($message) use ($to, $from, $subject, $pdf, $filename,$cc,$bcc) {
                            $message->subject($subject);
                            $message->from($from);
                            $message->to($to);

                            if(!empty($cc))
                            {
                               $message->cc($cc);
                            }
                            if(!empty($bcc))
                            {
                               $message->bcc($bcc);
                            }
                            $message->replyTo($from, $from);
                            $message->attachData($pdf->output(), $filename);
                        });
        if($result)
        {
           return 1;
        }
        else{
            return 2;
        }

    }

    function getClone($id)
    {
        if ($id == '') {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != '') {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('order');
        }
        $this->data['setting'] = $this->info['setting'];
        //  $this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['data'] = $this->model->getOrderQuery($id);
        return view('order.clonenew', $this->data);
    }
    function getInstaClone(Request $request, $eId, $voidify = null)
    {
        $now = date("Y-m-d");
        $nowStamp = date("Y-m-d H:i:s");
        $nowPOPart = date("mdy");
        $response = ['status' => 'error', 'message' => \Lang::get('core.note_restric')];
        if ($this->access['is_add'] == 0) {
            return response()->json($response);
        }
        $id = \SiteHelpers::encryptID($eId, true);
        $response['message'] = \Lang::get('core.order_missing_id');
        if (empty($id)) {
            return response()->json($response);
        }
        $row = $this->model->find($id)->toArray();
        if (empty($row)) {
            return response()->json($response);
        }
        $newID = Order::cloneOrder($id, $row, ['resetDate' => $nowStamp]);
        $response['message'] = \Lang::get('core.order_clone_error');
        if (empty($newID)) {
            return response()->json($response);
        }

        $response['status'] = 'success';
        $response['editUrl'] = url('/order/update/'.$newID);
        $response['viewUrl'] = url('/order/show/'.$newID);
        $response['poUrl'] = url('/order/po/'.$newID);
        $response['receiptUrl'] = url('/order/orderreceipt/'.$newID);
        
        $response['message'] = \Lang::get('core.order_clone_successful');
        if (strtolower($voidify) == 'voided') {
            Order::voidify($id);
            Order::relateOrder('replace', $newID, $id);
            $response['message'] = \Lang::get('core.order_clone_void_successful');
        }

        return response()->json($response);
    }

    function postValidateponumber(Request $request)
    {
        $po_1 = $request->get('po_1');
        $po_2 = $request->get('po_2');
        $po_3 = $request->get('po_3');
        $location_id = $request->get('location_id');
        $po = $request->get('po');
        $po_full = $po_1 . '-' . $po_2 . '-' . $po_3;
        return $this->validatePO($po,$po_full,$location_id);

    }
    function validatePO($po,$po_full,$location_id)
    {
        if($po !=0)
        {

            if($this->model->isPOAvailable($po_full))
            {
                $this->model->createPOTrack($po_full,$location_id);
                $po_3=explode('-',$po_full);
                $msg= $po_3[2];
            }
            else
            {
               //die('po not available');
                $msg=$this->model->increamentPO($location_id);
            }
        }
        else{
            $msg = $this->model->increamentPo($location_id);
        }
        return $msg;
    }

    function getOrderreceipt($order_id = null)
    {

        $this->data['data'] = $this->model->getOrderReceipt($order_id);
        $this->data['data']['order_items'] = \DB::select('SELECT * , g.game_name, O.id as id  FROM order_contents O LEFT JOIN game g ON g.id = O.game_id WHERE order_id = ' . $order_id);

        return view('order.order-receipt', $this->data);
    }

    function postReceiveorder(Request $request, $id = null)
    {
        $received_part_ids = array();
        $order_id = $request->get('order_id');
        $item_count = $request->get('item_count');
        $notes = $request->get('notes');
        $order_status = $request->get('order_status');
        $added_to_inventory = $request->get('added_to_inventory');
        $user_id = $request->get('user_id');
        $added = 0;
        if (!empty($request->get('receivedInParts'))) {
            $received_part_ids = $request->get('receivedInParts');
        } else {
            // close order
            $order_status = 2;
        }
        $received_qtys = $request->get('receivedQty');
        $item_ids = $request->get('itemsID');
        $received_item_qty = $request->get('receivedItemsQty');
        $date_received = date("Y-m-d", strtotime($request->get('date_received')));
        for ($i = 0; $i < count($item_ids); $i++) {
            $status = 1;
            if (in_array($item_ids[$i], $received_part_ids))

                $status = 2;
            \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_id . ',' . $item_ids[$i] . ',' . $received_qtys[$i] . ',' . $user_id . ',' . $status . ', "' . $date_received . '" , "' . $notes . '" )');
            \DB::update('UPDATE order_contents
								 	 	 SET item_received = ' . $received_item_qty[$i] . '+' . $received_qtys[$i] . '
							   	   	   WHERE id = ' . $item_ids[$i]);
        }
        $rules = array();
        if (empty($notes)) {
            $rules['order_status'] = "required:min:2";
        }
        if ($order_status == 5) // Advanced Replacement Returned.. require tracking number
        {
            $rules['tracking_number'] = "required|min:3";
            $tracking_number = $request->get('tracking_number');
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if (!empty($item_count) && $added_to_inventory == 0) {

                ///////APPLY PRIZES TO THE PROPER GAMES / LOCATIONS
                for ($i = 1; $i <= $item_count; $i++) {
                    $product_id = $request->get('product_id_' . $i);
                    $order_qty = $request->get('order_qty_' . $i);
                    $game = $request->get('game_' . $i);

                    // IF NO GAME SELECTED, INSERT INTO INVENTORY FOR USER'S LOCATION
                    if (!empty($game)) {
                        // IF ALL AVAILABLE QUANTITIES ARE ALLOCATED TO THE GAME
                        $allGame = array('product_id' => $product_id);
                        \DB::table('game')->where('id', $game)->update($allGame);
                    }

                    $location_id = $request->get('location_id');

                    $query = \DB::select('SELECT id
											 FROM merch_inventory
											WHERE product_id = ' . $product_id . '
								   	 		  AND location_id = ' . $location_id . '');

                    if (count($query) == 1) {
                        \DB::update('UPDATE merch_inventory
								 	 	 SET product_qty = product_qty + ' . $order_qty . '
							   	   	   WHERE product_id = ' . $product_id . '
								   	     AND location_id = ' . $location_id);
                    } else {
                        \DB::insert('INSERT INTO merch_inventory (`location_id`,`product_id`,`product_qty`,`user_id`)
							 	  		   VALUES (' . $location_id . ',' . $product_id . ',' . $order_qty . ',' . $user_id . ')');
                    }
                }
                $added = 1;
            }
            $date_received = $request->get('date_received');
            // $date_received = \DateHelpers::formatDate($date_received);
            $date_received = date("Y-m-d", strtotime($date_received));
            $partial = 0;
            $record = \DB::select('SELECT  SUM(qty) as total_items,(SUM(qty)-SUM(item_received)) as remaining_items FROM order_contents WHERE order_id ='.$request->get('order_id'));

            if($record[0]->remaining_items > 0 && $record[0]->remaining_items < $record[0]->total_items)
            {
                $partial = 1;
            }
            $data = array('date_received' => $date_received,
                'status_id' => $order_status,
                'notes' => $notes,
                'tracking_number' => $request->get('tracking_number'),
                'received_by' => $request->get('user_id'),
                'is_partial' => $partial,
                'added_to_inventory' => $added);
            \DB::table('orders')->where('id', $request->get('order_id'))->update($data);
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }
    }

    public function getSubmitorder($SID)
    {
        $this->data['sid'] = $SID;
        $this->data['access'] = $this->access;
        return view('order.index', $this->data);
    }

    public function getProduct()
    {
        $rows = \DB::select('select vendor_description,sku from products where id is not null');
        $json = array();
        foreach ($rows as $row) {
            $json[] = array('label' => $row->vendor_description, 'sku' => $row->sku);
        }
        return json_encode($json);
    }

    public function getAutocomplete()
    {
        $term = Input::get('term');
        $vendorId = Input::get('vendor_id',0);
        $whereWithVendorCondition = "";
        //get products related to selected vendor only
        if(!empty($vendorId)){
            $whereWithVendorCondition = " AND products.vendor_id = $vendorId";
        }
        $results = array();
        //fixing for https://www.screencast.com/t/vwFYE3AlF
        $queries = \DB::select("SELECT *,LOCATE('$term',vendor_description) AS pos
                                FROM products
                                WHERE vendor_description LIKE '%$term%' AND products.inactive=0 $whereWithVendorCondition
                                GROUP BY vendor_description
                                ORDER BY pos, vendor_description
                                 Limit 0,10");
        if (count($queries) != 0) {
            foreach ($queries as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->vendor_description];
            }
            echo json_encode($results);
        } else {
            echo json_encode(array('id' => 0, 'value' => "No Match"));
        }
    }

    public function getProductdata()
    {
        $vendor_description = Input::get('product_id');
        $row = \DB::select("select id,sku,item_description,unit_price,case_price,retail_price from products WHERE vendor_description='" . $vendor_description . "'");
        $row = Order::hydrate($row);
        $json = array('sku' => $row[0]->sku, 'item_description' => $row[0]->item_description, 'unit_price' => $row[0]->unit_price, 'case_price' => $row[0]->case_price, 'retail_price' => $row[0]->retail_price, 'id' => $row[0]->id);
        echo json_encode($json);
    }


    function getTestEmail()
    {
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587; // or 587
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
        $mail->SMTPAuth = true; // authentication enabled

        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only

        //$mail->IsHTML(true);
        $mail->Username = 'dev2@shayansolutions.com';          // SMTP username
        $mail->Password = '&b%Dd9Kr';
        $mail->SetFrom('dev2@shayansolutions.com');
        $mail->Subject = "Test";
        $mail->Body = "hello";
        $mail->AddAddress("dev3@shayansolutions.com");
        $mail->addCC('shayansolutions@gmail.com');
        $mail->addBCC('dev2@shayansolutions.com');
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message has been sent";
        }
        die;
    }

    function updateRequestAndProducts($item_count, $SID_new)
    {

        for ($i = 1; $i <= $item_count; $i++) {
            $pos1 = strpos($SID_new, '-');
            $SID_new = substr($SID_new, $pos1 + 1);
            $pos2 = strpos($SID_new, '-');
            ${'SID' . $i} = substr($SID_new, 0, $pos2);
            \DB::update('UPDATE products
                 LEFT JOIN requests ON requests.product_id = products.id
                        SET products.reserved_qty = (products.reserved_qty - requests.qty)
                        WHERE requests.id = ' . ${'SID' . $i} . ' AND products.is_reserved = 1');
        }
    }

    public function getDownloadPo($file_name)
    {

        $file = "orders/" . $file_name;
       // echo $file;
        $headers = array('Content-Type: application/pdf',);
        return \Response::download($file, $file_name, $headers);
    }

    public function getGamesDropdown()
    {
        $location = $_GET['location'];
        //$user_allowed_locations=implode(',',\Session::get('user_location_ids'));
        $games_options = $this->model->populateGamesDropdown($location);
        return $games_options;
    }

    public function getBillAccount()
    {
        $vendor_id = @$_GET['vendor'];
        if (empty($vendor_id)) {
            $vendor_id = 0;
        }
        return \DB::table('vendor')->select('bill_account_num')->where('id', $vendor_id)->get();
    }

    function getComboselect(Request $request)
    {

        if ($request->ajax() == true && \Auth::check() == true) {
            $param = explode(':', $request->input('filter'));
            $parent = (!is_null($request->input('parent')) ? $request->input('parent') : null);
            $limit = (!is_null($request->input('limit')) ? $request->input('limit') : null);
            //for order type Advance Replacement
            if (isset($param[3]) && !empty($param[3]) && isset($param[4])) {
                if ($param[3] == "order_type_id" && $param[4] == 0) {
                    $rows = \DB::table("order_status")->where('id', '=', '1')->orWhere('id', '=', '6')->orderBy('status', 'asc')->get();
                } //for ordet type other than Advance Replacement
                elseif ($param[3] == "order_type_id" && $param[4] == 1) {
                    $rows = \DB::table("order_status")->where('id', '=', '1')->orWhere('id', '=', '2')->orderBy('status', 'asc')->get();
                }
            } else {
                $rows = $this->model->getComboselect($param, $limit, $parent);
            }
            $items = array();

            $fields = explode("|", $param[2]);

            foreach ($rows as $row) {
                $value = "";
                foreach ($fields as $item => $val) {
                    if ($val != "") $value .= $row->$val . " ";
                }
                $items[] = array($row->$param['1'], $value);

            }

            return json_encode($items);
        } else {
            return json_encode(array('OMG' => " Ops .. Cant access the page !"));
        }
    }

    function getMultipleEmails($email)
    {
        if(!empty($email))
        {
            if (strpos($email, ',') != FALSE) {
                $email = explode(',', trim($email,","));
            }
            else
            {
                $email = array($email);
            }
            foreach($email as $index => $record){
                $record = trim($record);
                if(!filter_var($record, FILTER_VALIDATE_EMAIL)){
                    unset($email[$index]);
                }
                else{
                    $email[$index] = $record;
                }

            }
            return empty($email)?false:$email;
        }
        return false;
    }

    function getExposeApi(Request $request, $eId) {
        $id = \SiteHelpers::encryptID($eId, true);
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $status = Order::apified($id);
            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? \Lang::get('core.order_api_not_exposable') : \Lang::get('core.order_api_exposed');
        }
        return response()->json($response);
    }

    function getCheckEditable(Request $request, $id) {
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $orderData = Order::find($id)?Order::find($id)->toArray():null;
            $freeHand = Order::isFreehand($id, $orderData);
            $apified = Order::isApified($id, $orderData);
            $voided = Order::isVoided($id, $orderData);
            $closed = Order::isClosed($id, $orderData);
            $partial = Order::isPartiallyReceived($id);
            
            $status = true;

            if ($freeHand) {
                $response['status'] = 'success';
                $response['message'] = 'Ready for edit';
                return response()->json($response);
            }

            if ($apified) {
                $message = \Lang::get('core.order_api_exposed_edit_alert');
                $status = false;
            }
            if ($apified && $partial) {
                $message = \Lang::get('core.order_api_edit_partial_alert');
                $status = false;
            }
            if ($closed) {
                $message = \Lang::get('core.order_closed_edit_alert');
                $status = false;
            }
            if ($voided) {
                $message = \Lang::get('core.order_voided_edit_alert');
                $status = false;
            }

            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? $message : 'Ready for edit';

            $isClone = $apified && (!$partial && !$voided && !$closed);

            if ($isClone) {
                $response['url'] = url('/order/insta-clone/'.\SiteHelpers::encryptID($id).'/voided');
                $response['action'] = 'clone';
            }
        }
        return response()->json($response);
    }
    function getCheckReceivable(Request $request, $eId) {
        $id = \SiteHelpers::encryptID($eId, true);
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $orderData = Order::find($id)->toArray();
            $freeHand = Order::isFreehand($id, $orderData);
            $apiable = Order::isApiable($id, $orderData);
            $apified = Order::isApified($id, $orderData);
            $voided = Order::isVoided($id, $orderData);
            $closed = Order::isClosed($id, $orderData);
            $status = !$voided && !$closed && ($freeHand || !$apiable || $apified);

            if (!$apified) {
                $message = \Lang::get('core.order_receive_error_api_not_exposed');
            }
            if ($closed) {
                $message = \Lang::get('core.order_closed_receipt_alert');
            }
            if ($voided) {
                $message = \Lang::get('core.order_voided_receipt_alert');
            }

            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? $message : 'Ready to receive';

            if ($status) {
                $response['url'] = url('/order/orderreceipt/'.$id);
            }
        }
        return response()->json($response);

    }
    function getCheckClonable(Request $request, $eId) {

    }

    public function getEmailHistory(Request $request) {
        
        $returnSelf = !empty($request->input('returnSelf'));

        $searchFor = !is_null($request->input('search')) ? trim($request->input('search')) : '';
        $searchFor = empty($searchFor) || $searchFor == '@' ? '' : $searchFor;
        
        $startAt = !is_null($request->input('start')) ? trim($request->input('start')) : '';
        $endAt = !is_null($request->input('end')) ? trim($request->input('end')) : '';

        $query = OrderSendDetails::distinct();
        
        if (!empty($searchFor)) {
            $query->where('email', 'LIKE', "%$searchFor%");
        }
        if (!empty($startAt)) {
            $query->where('created_at', '>=', $startAt);
        }
        if (!empty($endAt)) {
            $query->where('created_at', '<=', $endAt);
        }

        $dataList = [];
        if (!empty($searchFor) || !empty($startAt) || !empty($endAt)) {
            $dataList = $query->lists('email');
        }
        
        if($returnSelf && !empty($searchFor)) {
            $dataList[] = $searchFor;
        }
        
        return response()->json($dataList);
    }

}
