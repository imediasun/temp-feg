<?php namespace App\Http\Controllers;

use App\Events\ordersEvent;
use App\Events\PostEditOrderEvent;
use App\Events\PostOrdersEvent;
use App\Events\Event;
use App\Events\PostSaveOrderEvent;
use App\Http\Controllers\controller;
use App\Http\Controllers\Feg\System\SystemEmailReportManagerController;
use App\Library\FEG\System\Email\Report;
use App\Library\FEG\System\Email\ReportGenerator;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\location;
use App\Models\managefegrequeststore;
use App\Models\DigitalPackingList;
use App\Models\Order;
use App\Models\product;
use App\Models\OrderSendDetails;
use App\Models\Sximo;
use \App\Models\Sximo\Module;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Library\SximoDB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Validator, Input, Redirect, Cache;
use PHPMailer;
use PHPMailerOAuth;
use App\Models\OrdersettingContent;
use App\Models\ReservedQtyLog;
use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
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

        // "calculate price according to case price" and "use case price if unit price is 0.00" these two permissions will be visible to all users
        $case_price_permission = \FEGSPass::getPasses($this->module_id, 'module.order.special.calculatepriceaccordingtocaseprice', false);
        $case_unit_price_permission = \FEGSPass::getPasses($this->module_id, 'module.order.special.usecasepriceifunitpriceis0.00', false);
        $this->pass['calculate price according to case price'] = $case_price_permission['calculate price according to case price'];
        $this->pass['use case price if unit price is 0.00'] = $case_unit_price_permission['use case price if unit price is 0.00'];

        $this->data = array(
            'pass' => $this->pass,
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => $this->module,
            'pageUrl' => url($this->module),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['status_id' => 'OS.status', 'vendor_id' => 'V.vendor_name', 'user_id' => 'U.username', 'order_type_id' => 'OT.order_type', 'location_id' => 'L.location_name'];
        $this->sortUnMapping = ['OS.status' => 'status_id', 'V.vendor_name' => 'vendor_id', 'U.username' => 'user_id', 'OT.order_type' => 'order_type_id', 'L.location_name' => 'location_id'];

    }

    /**
     * @param string $t
     * @return \Illuminate\View\View
     */
    public function getExport($t = 'excel')
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-' . $exportId;
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

        foreach($results['rows'] as  &$rs){
            $result = $this->model->getProductInfo($rs->id);
            $infoString = '';
            foreach($result as $r){
                if(!isset($r->sku)){
                    $sku = " (SKU: No Data) ";
                }else{
                    $sku = " (SKU: ".$r->sku.")";
                }

                $infoString = $infoString . '(' . $r->qty . ') ' . $r->item_name . ' ' . \CurrencyHelpers::formatPrice($r->total, 2, true, ',', '.', true) . $sku . '; ';
            }
            $rs->productInfo = rtrim($infoString,'; ');
        }


        $fields = $info['config']['grid'];
        $rows = $results['rows'];

        //$rows = $this->updateDateInAllRows($rows);
        $rowss=[];
        foreach($rows as $row1) {
            $row1 = (array) $row1;
            $rowss[] = (array)self::array_move('created_at', 3, (array)$row1);
        }
        $rowsobjects = [];
        foreach($rowss as $rowobj){
            $rowsobjects[] = (object)$rowobj;
        }

        $out = array_splice($fields, 27, 1);
        array_splice($fields, 3, 0, $out);



        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rowsobjects,
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

        /*\App\Library\FEG\System\Sync::transferEarnings();
        \App\Library\FEG\System\Sync::retryTransferMissingEarnings();
        \App\Library\FEG\System\Sync::generateDailySummary();
        \App\Library\FEG\System\Email\Report::daily();
        \App\Library\FEG\System\Email\Report::missingDataReport();
        echo "done transfer";
        exit;*/
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
        if(Session::get('redirect') != "managefegrequeststore") {

            $this->getSearchParamsForRedirect();
        }

        session_start();
        $_SESSION['searchParamsForOrder'] = \Session::get('searchParams');

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

        $this->data['typeRestricted'] = ['isTypeRestricted' => false ,'displayTypeOnly' => ''];

        if($this->model->isTypeRestrictedModule($this->module)){
            if($this->model->isTypeRestricted()){
                $this->data['typeRestricted'] = [
                    'isTypeRestricted' => $this->model->isTypeRestricted(),
                    'displayTypeOnly' => $this->model->getAllowedTypes(),
                ];
            }
        }
        if($this->model->isTypeRestricted()){
            $filter .= " AND orders.order_type_id IN(".$this->model->getAllowedTypes().") ";
        }
        $page = $request->input('page', 1);

        $sort = !empty($this->sortMapping) && isset($this->sortMapping[$sort]) ? $this->sortMapping[$sort] : $sort;

        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );

        $isRedirected = \Session::get('filter_before_redirect');
        \Session::put('order_selected', $order_selected);

        // \Session::put('filter_before_redirect',false);
        //\Session::put('params',$params);
        $results = $this->model->getRows($params, $order_selected);

        foreach ($results['rows'] as &$rs) {
            $result = $this->model->getProductInfo($rs->id);
            $info = '';
            foreach ($result as $r) {
                if (!isset($r->sku)) {
                    $sku = " (SKU: No Data) ";
                } else {
                    $sku = " (SKU: " . $r->sku . ")";
                }

                $info = $info . '(' . $r->qty . ') ' . $r->item_name . ' ' . \CurrencyHelpers::formatPrice($r->total, 2, true, ',', '.', true) . $sku . '; ';
            }
            $rs->productInfo = rtrim($info, '; ');
        }

        if (count($results['rows']) == 0 and $page != 1) {
            $params['limit'] = $this->info['setting']['perpage'];
            $results = $this->model->getRows($params, $order_selected);
        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('order/data');
        $rows = $results['rows'];
        foreach ($rows as $index => $data) {
            if ($data->date_ordered == '0000-00-00')
            {
                $rows[$index]->date_ordered = $data->date_ordered;
            }else{
                $rows[$index]->date_ordered = date("m/d/Y", strtotime($data->date_ordered));
            }
            //$location = \DB::select("Select location_name FROM location WHERE id = " . $data->location_id . "");
            // $rows[$index]->location_id = (isset($location[0]->location_name) ? $location[0]->location_name : '');
            $user = \DB::select("Select username FROM users WHERE id = '" . $data->user_id . "'");
            $rows[$index]->user_id = (isset($user[0]->username) ? $user[0]->username : '');
            $order_type = \DB::select("Select order_type FROM order_type WHERE id = '" . $data->order_type_id . "'");
            $rows[$index]->order_type_id = (isset($order_type[0]->order_type) ? $order_type[0]->order_type : '');

            //  $vendor = \DB::table('vendor')->where('id', '=', $data->vendor_id)->get(array('vendor_name'));
            //$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');

            $order_status = \DB::select("Select status FROM order_status WHERE id = '" . $data->status_id . "'");
            //  $partial = $data->status_id == 10 ? ' ' : ' (Partial)';
            $partial =  '';
            if ($data->is_partial == 1 && $data->status_id == Order::OPENID1)
            {
                $partial = ' (Partial)';
            }else{
                $partial = '';
            }
            $rows[$index]->status_value = $rows[$index]->status_id;
            $rows[$index]->status_id = (isset($order_status[0]->status) ? $order_status[0]->status . $partial : '');

            $order  = Order::find($data->id);
            $rows[$index]->isFullyReceived = !is_null($order) ? $order->isOrderReceived():false;
        }

        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

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
        /*$this->data['set_removed'] = "others";
        if (strpos($_SESSION['searchParamsForOrder'], 'status_id:equal:removed|') > 0) {
            // $is_removed_flag = true;
            $this->data['set_removed'] = 'set_removed';
        }*/

        return view('order.table', $this->data);

    }

    function getUpdate(Request $request, $id = 0, $mode = '')
    {
        $fromStore = 0;
        $editmode = $prefill_type = 'edit';
        $where_in_expression = '';
        \Session::put('redirect', 'order');
        $this->data['setting'] = $this->info['setting'];
        $isRequestApproveProcess = false;
        $requestId = [$id];
        if ($id != 0 && $mode == '') {
            $mode = 'edit';
        } elseif ($id == 0 && $mode == '') {
            $mode = 'create';
        } elseif (substr($mode, 0, 3) == 'SID') {
            \Session::put('redirect', 'managefegrequeststore');
            $isRequestApproveProcess = true;
            $mode = $mode;
            $requestId = explode("-",rtrim(str_replace("SID-",'',$mode),"-"));
            $requestId = empty($requestId[0])  ? [0]: $requestId;
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
        $row = null;
        if(substr($mode, 0, 3) == 'SID'){
            $manageFegRequestStore = new managefegrequeststore();
            $row = $manageFegRequestStore->with([
                'location' => function($query){
                    return $query->select('id', 'fedex_number','freight_id as location_freight_id');
                }
            ])->whereIn("id",$requestId)->first();
        }else{
            $row = $this->model->with([
                'location' => function($query){
                    return $query->select('id', 'fedex_number','freight_id as location_freight_id');
                }
            ])->find($id);
        }

        if ($row) {
            $row->fedex_number =  $row->location ? $row->location->fedex_number ? $row->location->fedex_number : 'No Data' : 'No Data';
           if($row->freight_id) {
               $row->order_freight_id = $row->freight_id ? $row->freight_id : '';
           }else{
               $row->order_freight_id = $row->location->location_freight_id ? $row->location->location_freight_id : '';
           }
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('orders');
        }

        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['mode'] = $mode;
        $this->data['isRequestApproveProcess'] = $isRequestApproveProcess;
        $this->data['id'] = $id;
        $this->data['data'] = $this->model->getOrderQuery($id, $mode, $this->data['pass']);
        $this->data['relationships'] = $this->model->getOrderRelationships($id);
        $user_allowed_locations = implode(',', \Session::get('user_location_ids'));
        $this->data['games_options'] = $this->model->populateGamesDropdown();
        $this->data['isTypeRestricted'] = $this->model->isTypeRestricted();
        $this->data['displayTypesOnly'] = $this->model->getAllowedTypes();
        return view('order.form', $this->data)->with('fromStore',$fromStore);
    }

    public function getShow($id = null)
    {
        $this->data['case_price_permission'] = $this->pass['calculate price according to case price'];
        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
//------  Removed this query and Added the following lines used in these eloquent
//        $row = $this->model->getRow($id);

        $row = $this->model->with([
            'location' => function($query){
                return $query->select('id', 'fedex_number');
            }
        ])->find($id);

        $row->fedex_number = $row->location ? $row->location->fedex_number ? $row->location->fedex_number : 'No Data' : 'No Data';

        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('orders');
        }
        $this->data['order_data'] = $this->model->getOrderQuery($id, 'edit', $this->data['pass']);
        $this->data['typesUsingCasePrice'] = !empty($this->data['pass']['calculate price according to case price']->data_options) ? explode(",",$this->data['pass']['calculate price according to case price']->data_options) : [];
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['nodata'] = \SiteHelpers::isNoData($this->info['config']['grid']);
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
    public function getCheckreceived($id)
    {
        $ds = \DB::table('order_received')->where('order_line_item_id', $id)->get();
        if (!empty($ds)) {
            return response()->json(array(
                'available' => 'true'
            ));
        } else {
            return response()->json(array(
                'available' => 'false'
            ));
        }

    }

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
        $case_price_categories = [];
        if (isset($this->data['pass']['calculate price according to case price'])) {
            $case_price_categories = explode(',', $this->data['pass']['calculate price according to case price']->data_options);
        }
        $case_price_if_no_unit_categories = [];
        if (isset($this->data['pass']['use case price if unit price is 0.00'])) {
            $case_price_if_no_unit_categories = explode(',', $this->data['pass']['use case price if unit price is 0.00']->data_options);
        }
        if ($validator->passes()) {
            $order_id = ($request->get('editmode') == "clone") ? 0:$request->get('order_id');
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
            $is_freehand = $request->get('is_freehand') == "1" ? 1 : 0;
            $po_1 = $request->get('po_1');
            $po_2 = $request->get('po_2');
            $po_3 = $request->get('po_3');
            $po = $po_1 . '-' . $po_2 . '-' . $po_3;
            $altShipTo = $request->get('alt_ship_to');
            $alt_address = '';
            $order_description = '';
            if ($editmode != "clone") {
                $totalQuanity = \DB::select("SELECT SUM(qty) AS total_quantity FROM order_contents WHERE order_id=$order_id")[0]->total_quantity;
                $orderQuantity = array_sum($request->qty);
                $orderQuantity = $orderQuantity - $totalQuanity;
                $newOrderedQty = $totalQuanity + $orderQuantity;
                //When order quantity will be increase then order status will be updated to open (Partial)

                $received_quantity = \DB::select("SELECT SUM(quantity) as total_received_qty FROM order_received WHERE  order_id=$order_id")[0]->total_received_qty;
                $current_order = \DB::select("SELECT * FROM orders WHERE id=$order_id");
                if ($received_quantity and $newOrderedQty != $received_quantity) {
                    \DB::update('update orders set status_id=1, is_partial=1 where id="' . $order_id . '"');
                } elseif (!$received_quantity or ($newOrderedQty == $received_quantity and $current_order[0]->status_id != '2')) {
                    \DB::update('update orders set status_id=1, is_partial=0 where id="' . $order_id . '"');
                } elseif ($newOrderedQty == $received_quantity and $current_order and $current_order[0]->status_id != '1') {
                    \DB::update('update orders set status_id=2, is_partial=0 where id="' . $order_id . '"');
                }

                $itemReceivedcount = \DB::select("SELECT COUNT(*) AS itemReceivedcount FROM order_contents WHERE order_id=$order_id AND item_received>0")[0]->itemReceivedcount;

                if ($itemReceivedcount == 0) {
                    \DB::update('update orders set status_id=1, is_partial=0 where id="' . $order_id . '"');
                }
            }
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
            $brokenCaseArray = $request->has('broken_case_value') ? $request->get('broken_case_value'):array_fill(0,count($itemNamesArray),0);
            $productIdArray = $request->get('product_id');
            $requestIdArray = $request->get('request_id');
            $order_content_id = $request->get('order_content_id');
            $force_remove_items = $request->get('force_remove_items');
            $games = $request->get('game');
            $item_received = $request->get('item_received');
            $item_received = $request->get('item_received');
            $denied_SIDs = $request->get('denied_SIDs');
            $po_notes_additionaltext = $request->get('po_notes_additionaltext');
            $num_items_in_array = count($itemsArray);



            for ($i = 0; $i < $num_items_in_array; $i++) {
                $j = $i + 1;

                $isBrokenCase = isset($brokenCaseArray[$i]) ? $brokenCaseArray[$i] : 0;
                if($isBrokenCase) {
                    $itemsPriceArray[] = $priceArray[$i];
                } else if (in_array($order_type, $case_price_categories)) {
                    $itemsPriceArray[] = $casePriceArray[$i];
                } elseif (in_array($order_type, $case_price_if_no_unit_categories)) {
                    $itemsPriceArray[] = ($priceArray[$i] == 0.00) ? $casePriceArray[$i] : $priceArray[$i];
                } else {
                    $itemsPriceArray[] = $priceArray[$i];
                }
                $order_description .= ' | item' . $j . ' - (' . $qtyArray[$i]
                    . ') ' . $itemsArray[$i] . ' @ $' .
                    $itemsPriceArray[$i] . ' ea. (SKU: ' . $skuNumArray[$i] . ')';
            }
            if ($is_freehand == 0) {
                $validationResponse = $this->validateProductForReserveQty($request);

                if (!empty($validationResponse) && $validationResponse['error'] == true) {
                    return response()->json(array(
                        'message' => $validationResponse['message'],
                        'status' => 'error',
                        'adjustQty' => $validationResponse['adjustQty']
                    ));
                }
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
                    'po_notes' => $notes,
                    'po_notes_additionaltext' => $po_notes_additionaltext,
                );
                $this->model->insertRow($orderData, $order_id);
                $last_insert_id = $order_id;
                //$productIdArray

                $orderContent = Order::find($last_insert_id);
                $supperSetofProducts = $orderContent->orderedContent->pluck('product_id')->toArray();
                $supperSetofProducts = array_diff($supperSetofProducts,$productIdArray);
                $removedProducts = $orderContent->orderedContent()->whereIn("product_id",$supperSetofProducts)->get();
                foreach($removedProducts as $removedProduct){
                    $product = product::find($removedProduct->product_id);
                    if($product->is_reserved == 1) {
                        $productVariations = $product->getProductVariations();
                        $product->reserved_qty += $removedProduct->qty;
                        $product->updateProduct(['reserved_qty' => $product->reserved_qty]);
                        $product->save();
                        $reservedLogData = [
                            "product_id" => $product->id,
                            "order_id" => $last_insert_id,
                            "adjustment_amount" => $removedProduct->qty,
                            "adjustment_type" => 'positive',
                            "variation_id" => $product->variation_id,
                            "adjusted_by" => \AUTH::user()->id,
                        ];
                        $reservedQtyLog = new ReservedQtyLog();
                        $reservedQtyLog->insert($reservedLogData);
                    }
                }
                $force_remove_items = explode(',', $force_remove_items);
                \DB::table('order_contents')->where('order_id', $last_insert_id)->where('item_received', '0')->delete();
                \DB::table('order_contents')->whereIn('id', $force_remove_items)->delete();
                \DB::table('order_received')->whereIn('order_line_item_id', $force_remove_items)->delete();
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
                    'po_notes' => $notes,
                    'po_notes_additionaltext' => $po_notes_additionaltext,
                );
                if ($editmode == "clone") {
                    $id = 0;
                    Sximo::insertLog('Order', 'Clone', 'OrderController', 'An order with po : ' . $po . ' is cloned', json_encode($orderData));
                }
                $this->model->insertRow($orderData, $id);
                $order_id = \DB::getPdo()->lastInsertId();
            }
            //// UPDATE STATUS TO APPROVED AND PROCESSED
            //don't put this code in loop below
            $now = $this->model->get_local_time('date');
            if (!empty($where_in)) {
                \DB::update('UPDATE requests
							 SET status_id = 2,
							 	 process_user_id = ' . \Session::get('uid') . ',
								 process_date = "' . $now . '",
								 blocked_at = null
						   WHERE id IN(' . $where_in . ')');
            }
            for ($i = 0; $i < $num_items_in_array; $i++) {

                if (empty($productIdArray[$i])) {
                    $product_id = 0;
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

                $isBrokenCase = isset($brokenCaseArray[$i]) ? $brokenCaseArray[$i] : 0;

                if (empty($item_received[$i])) {
                    $items_received_qty = '0';
                } else {
                    $items_received_qty = $item_received[$i];
                }
                if ($product_id != 0) {
                    $prodData = \DB::select("SELECT * from products where id =$product_id");
                    if(!empty($prodData)){
                        $prodType = $prodData[0]->prod_type_id;
                        $prodSubtype = $prodData[0]->prod_sub_type_id;
                        $qty_per_case = $prodData[0]->num_items;
                        $prodTicketValue = $prodData[0]->ticket_value;
                        $prodVendorId = $prodData[0]->vendor_id;
                        $upc_barcode = ($prodData[0]->upc_barcode == 'null' || empty($prodData[0]->upc_barcode)) ? '':$prodData[0]->upc_barcode;
                    }
                } else {
                    $prodType = $order_type;
                    $prodSubtype = 0;
                    $qty_per_case = 1;
                    $prodTicketValue = '';
                    $prodVendorId = $vendor_id;
                }

                $contentsData = array(
                    'order_id' => $order_id,
                    'request_id' => $request_id,
                    'product_id' => $product_id,
                    'price' => $priceArray[$i],
                    'qty' => $qtyArray[$i],
                    'game_id' => $game_id,
                    'item_name' => $itemNamesArray[$i],
                    'case_price' => $casePriceArray[$i],
                    'item_received' => $items_received_qty,
                    'sku' => $sku_num,
                    'prod_type_id' => $prodType,
                    'prod_sub_type_id' => $prodSubtype,
                    'qty_per_case' => $qty_per_case,
                    'ticket_value' => $prodTicketValue,
                    'vendor_id' => $prodVendorId,
                    'is_broken_case' => $isBrokenCase,
                    'total' => $itemsPriceArray[$i] * $qtyArray[$i]
                );


                if(!empty($upc_barcode)){
                    $contentsData['upc_barcode'] = $upc_barcode;
                }
                if (!empty($itemsArray[$i])) {
                    $contentsData['product_description'] = $itemsArray[$i];
                }

                if ($editmode == "clone") {
                    $items_received_qty = 0;
                }
                if ($items_received_qty == '0') {
                    \DB::table('order_contents')->insert($contentsData);
                } else {
                    \DB::table('order_contents')->where('id', $order_content_id[$i])->update($contentsData);
                }

                $contentsData['prev_qty'] = $request->input('prev_qty')[$i];
                if ($is_freehand == 0) {
                    event(new PostSaveOrderEvent($contentsData));
                }

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
                    $request_qty = \DB::select('SELECT qty FROM requests WHERE id=' . $request_id);
                    empty($request_qty) ? $request_qty = 0 : $request_qty = $request_qty[0]->qty;
                    $restore_qty = $request_qty - $qtyArray[$i];

                    if ($restore_qty > 0) {
                        \DB::update('UPDATE requests
                         SET status_id = 3,
                             qty = ' . $restore_qty . ',
                             blocked_at = null
                       WHERE id=' . $request_id);
                    } else {
                        \DB::update('UPDATE requests
                         SET status_id = 2,
                             process_user_id = ' . \Session::get('uid') . ',
                             process_date = "' . $now . '",
                             blocked_at = null
                       WHERE id=' . $request_id);
                    }
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

            //Deny Denied SID's
            if ($editmode == 'SID' && !empty($denied_SIDs)) {
                //$denied_SIDs = explode('-', $denied_SIDs);
                //array_pop($denied_SIDs);
                //array_shift($denied_SIDs)
                $denied_SIDs = ltrim($denied_SIDs, ',');
                \DB::update('UPDATE requests
                         SET status_id = 3
                       WHERE id IN(' . $denied_SIDs . ')');
            }

            //Updating PO Track table
            if (isset($orderData['po_number'])) {
                \DB::table('po_track')->where('po_number', $orderData['po_number'])->update(['enabled' => '1']);
            }



            \Session::put('send_to', $vendor_email);
            \Session::put('order_id', $order_id);
            if(!empty($denied_SIDs) && empty($where_in)){
                $redirect_link = "managefegrequeststore";
            }
            \Session::put('redirect', $redirect_link);

            $saveOrSendView = $this->getSaveOrSendEmail("pop")->render();

            if (!empty($where_in)) {
                \DB::update('DELETE FROM requests WHERE id IN(' . $where_in . ')');
            }

            return response()->json(array(
                'saveOrSendContent' => $saveOrSendView,
                'status' => 'success',
                'message' => \Lang::get('core.note_success'),

            ));

        } elseif ($id != 0) {
            $data = $this->validatePost('orders', true);
            $orderTotal = \CurrencyHelpers::formatPrice(Order::find($id)->order_total,2, false);
            if (isset($data['order_type_id'])) {
                $order_contents = \DB::table('order_contents')->where('order_id', $id)->get();
                $orderTotal = 0;
                $order_type = $data['order_type_id'];
                foreach ($order_contents as $content) {
                    if (in_array($order_type, $case_price_categories)) {
                        $sum = $content->qty * $content->case_price;
                    } elseif (in_array($order_type, $case_price_if_no_unit_categories)) {
                        $sum = $content->qty * (($content->price == 0.00) ? $content->case_price : $content->price);
                    } else {
                        $sum = $content->qty * $content->price;
                    }
                    if ($sum != $content->total) {
                        \DB::table('order_contents')->where('id', $content->id)->update(['total' => $sum]);
                    }
                    $orderTotal += $sum;
                }
                $data['order_total'] = $orderTotal;
            }
            $this->model->insertRow($data, $id);

            \Session::put('order_id', $id);
            $saveOrSendView = $this->getSaveOrSendEmail("pop")->render();

            return response()->json(array(
                'saveOrSendContent' => $saveOrSendView,
                'status' => 'success',
                'total' => $orderTotal,
                'message' => \Lang::get('core.note_success'),

            ));
        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error',

            ));
        }

    }

    public function validateProductForReserveQty($request)
    {
        $item_names = $request->input('item_name');
        $productInformation = [];
        for ($i = 0; $i < count($item_names); $i++) {
            $product = \DB::table('products')->where(['id' => $request->input('product_id')[$i], 'is_reserved' => 1])->first();
            if (!empty($product)) {
                $product->item_name = $item_names[$i];
                $product->qty = $request->input('qty')[$i];
                $product->prev_qty = $request->input('prev_qty')[$i];
                $product->order_product_id = ($request->input('product_id')[$i] == $product->id) ? $request->input('product_id')[$i] : 0;
                $productInformation[] = $product;
            }
        }

        $collect = collect($productInformation);
        $groups = $collect->groupBy('id');

        $productInformationCombined = [];
        //TODO: This functionality don't needed when double product restriction will be applied.
        //This loop will combine duplicate products
        foreach ($groups as $key => $group) {
            $group[0]->qty = $group->sum('qty');
            $group[0]->prev_qty = $group->sum('prev_qty');
            $productInformationCombined[] = $group[0];
        }

        return event(new ordersEvent($productInformationCombined, $request->order_id))[0];
    }

    public function getSaveOrSendEmail($isPop = null)
    {
        $order_id = \Session::get('order_id');
        $order_type = \DB::select('SELECT order_type_id FROM orders WHERE id=' . $order_id);
        $order_type_id = $order_type[0]->order_type_id;
        $is_test = env('APP_ENV', 'development') !== 'production' ? true : false;
        if ($is_test) {
            $receipts = FEGSystemHelper::getSystemEmailRecipients("send PO copy", null, true);
        } else {
            $receipts = FEGSystemHelper::getSystemEmailRecipients("send PO copy");
        }
        extract($receipts);
        $cc1 = "";
        // for Instant Win, Redemption Prize, Tickets, Uniforms and Office Supply categories send a copy of PO to
        // marissa sexton,mandee cook,lisa price
        $module = new OrderController();
        $pass = \FEGSPass::getMyPass($module->module_id, '', false, true);
        $order_types = "";
        if(!empty($pass['display email address in cc box for order types'])) {
            $order_types = $pass['display email address in cc box for order types']->data_options;
        }
        $order_types = explode(",",$order_types);
       if(in_array($order_type_id,$order_types)){
            $cc1 = $cc;

        } else {
            $cc1 = "";
        }
        $viewName = empty($isPop) ? 'order.saveorsendemail' : 'order.pop.saveorsendemail';
        return view($viewName, array('cc' => $cc1, "pageUrl" => $this->data['pageUrl']));
    }

    function postSaveorsendemail(Request $request)
    {
        $type = $request->get('type');
        $from = $request->get('from');
        $from = !empty($from) ? $from : env('MAIL_USERNAME');
        $order_id = $request->get('order_id');
        if ($type == "send") {
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
            $to[] = "marissa.sexton@fegllc.com";
            $to[] = "mandee.cook@fegllc.com";
            $to[] = "lisa.price@fegllc.com";
        }*/
        $opt = $request->get('opt');
        $redirect_module = \Session::get('redirect');
        \Session::put('filter_before_redirect', 'no');

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

            \Session::put('filter_before_redirect', 'redirect');
            $status = $this->getPo($order_id, true, $to, $from, $cc, $bcc, $message);

            if ($status == 1) {
                return response()->json(array(
                    'message' => \Lang::get('core.mail_sent_success'),
                    'status' => 'success',

                ));
            } elseif ($status == 2) {
                return response()->json(array(
                    'message' => \Lang::get('core.google_account_not_exist'),
                    'status' => 'error',

                ));
            } elseif ($status == 3) {
                return response()->json(array(
                    'message' => \Lang::get('core.gmail_smtp_connect_failed'),
                    'status' => 'error',

                ));
            } elseif ($status == 4) {
                return response()->json(array(
                    'message' => \Lang::get('core.error_sending_mail'),
                    'status' => 'error',

                ));
            }


        }
    }

    public function getRestoreorder($id = 0)
    {
        $this->data['ids'] = $id;
        return view("order.restorereasonexplain", $this->data);
    }

    public function postRestoreorder(Request $request)
    {
        // set order status as deleted for multipe rows
        $orderId = $request->input('ids');
        $explanations = trim(strip_tags($request->input('explaination')));
        $order = Order::withTrashed()->where('id', $orderId)->first();
        if (empty($order)) {
            return Redirect::to('order')->with('messagetext', "Invalid Order")->with('msgstatus', 'error');
        }

        if ($order->canRestoreAllReservedProducts() === false) {
            return Redirect::to('order')->with('messagetext', "Order has not been restored, Reason: Insufficient reserved quantity")->with('msgstatus', 'error');
        }

        $order->notes = $order->notes . '<br>' . $explanations;

        try {
            $result = $order->restore();
            $message = "Order ID : {$order->id} has been restored successfully!";
        } catch (\Exception $e) {
            $result = false;
            $message = "Order ID : {$order->id} has not been restored. Reason: " . $e->getMessage();
        }
        if ($result) {
            return Redirect::to('order')->with('messagetext', $message)->with('msgstatus', 'success');
        } else {
            return Redirect::to('order')->with('messagetext', $message)->with('msgstatus', 'error');
        }
    }

    public function postRemoveorderexplaination(Request $request)
    {
        $this->data['ids'] = implode(",", $request->input('ids'));
        $totalIdsCount = count($request->input('ids'));
        $ids = implode("','", $request->input('ids'));

        $sql = " select po_number from orders where po_number in ('$ids') and is_api_visible=0 and status_id<>10 and status_id<>2";

        $result = \DB::select($sql);
        $ids = [];
        foreach($result as $idsObject){
            $ids[]= $idsObject->po_number;
        }
        $remainIdsCount = count($ids);
        $excludedIdsCount = count($request->input('ids'))-$remainIdsCount;
        $postedtonetsuitePOIds =[];
        if(is_array($request->input('ids'))){
            foreach($request->input('ids') as $requestedIds){
                if(!in_array($requestedIds,$ids)){
                    $postedtonetsuitePOIds[] = $requestedIds;
                }
            }
        }

        if($remainIdsCount>0){
            $this->data['ids'] =implode(",",$ids);
            if($excludedIdsCount>0){
                $this->data['messagetext'] = "Following POs cannot be removed: <br>".implode("<br>",$postedtonetsuitePOIds);
                $this->data['msgstatus'] = "error";
            }
           return view("order.removalreasonexplain", $this->data);


        }else{
            return Redirect::to('order')->with('messagetext', "Closed/Removed orders may not be removed.")->with('msgstatus', 'error');
        }


    }

    public function postDelete(Request $request)
    {
        // set order status as deleted for multipe rows
        $poNumbers = $request->input('po_number');
        $explaination = $request->input('explaination');
        $uid = \Session::get('uid');
        $query = "";
        $result = false;
        $orders = Order::whereIn('po_number',$poNumbers)->get();

        $index = 0;
        foreach($orders as $order){
            $order->notes = $order->notes.'<br>'.trim(strip_tags($explaination[$index]));
            $result = $order->delete();
            $index++;
        }

        if ($result) {
            return Redirect::to('order')->with('messagetext', 'Order(s) has/have been removed successfully.')->with('msgstatus', 'success');
        } else {
            return Redirect::to('order')->with('messagetext', 'This order status has already been removed!')->with('msgstatus', 'error');
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
        $receipts = FEGSystemHelper::getSystemEmailRecipients($configName, null, $isTest);

        $messageData = [
            'userName' => $userName,
            'poNumber' => $po_number,
            'url' => url() . '/order/removeorder/' . $po_number,
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

        $this->data['ids'] = $poNumber;
        $totalIdsCount = 1;
        $ids = $poNumber;

        $sql = " select po_number from orders where po_number in ('$ids') and is_api_visible=0 and status_id<>10 and status_id<>2";

        $result = \DB::select($sql);
        $ids = [];
        foreach($result as $idsObject){
            $ids[]= $idsObject->po_number;
        }
        $remainIdsCount = count($ids);
        $excludedIdsCount = count(array($poNumber))-$remainIdsCount;
        $postedtonetsuitePOIds =[];
        if(is_array(array($poNumber))){
            foreach(array($poNumber) as $requestedIds){
                if(!in_array($requestedIds,$ids)){
                    $postedtonetsuitePOIds[] = $requestedIds;
                }
            }
        }

        if($remainIdsCount>0){
            $this->data['ids'] =implode(",",$ids);
            if($excludedIdsCount>0){
                $this->data['messagetext'] = "Following POs cannot be removed: <br>".implode("<br>",$postedtonetsuitePOIds);
                $this->data['msgstatus'] = "error";
            }

            return view("order.removalreasonexplain", $this->data);


        }else{
            return Redirect::to('order')->with('messagetext', "Closed/Removed orders may not be removed.")->with('msgstatus', 'error');
        }
//        $result = \DB::table('orders')->where('po_number', $poNumber)->delete();
//        if ($result) {
//            return Redirect::to('order')->with('messagetext', 'Po  removed successfully!')->with('msgstatus', 'success');
//        } else {
//            return Redirect::to('order')->with('messagetext', 'This PO has already been removed!')->with('msgstatus', 'error');
//        }
        //\Session::flash('success', 'Po  deleted successfully!');

    }

    public function getSearchFilterQuery($customQueryString = null)
    {
        // Filter Search for query
        // build sql query based on search filters


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '', 'status_id' => '']);
        $skipFilters = ['search_all_fields'];
        $statusIdFilter = $globalSearchFilter['status_id'];
        unset($globalSearchFilter['status_id']);
        $mergeFilters = [];
        extract($globalSearchFilter); //search_all_fields


        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        $orderStatusCondition = '';
        if (!empty($search_all_fields)) {
            $searchFields = [
                'orders.id',
                'OC.item_name',
                'OC.product_description',
                'U.username',
                'L.location_name',
                'V.vendor_name',
                'orders.order_total',
                'orders.order_description',
                'OT.order_type',
                'orders.po_number',
                'orders.po_notes',
                'orders.notes',
                'orders.is_partial',
                'orders.tracking_number',
                'YN.yesno',
                'OC.sku'
            ];
            $dateSearchFields = [
                'orders.date_ordered',
                'orders.created_at',
                'orders.updated_at',
            ];
            $dates = FEGSystemHelper::probeDatesInSearchQuery($search_all_fields);
            $searchInput = ['query' => $search_all_fields, 'dateQuery' => $dates,
                'fields' => $searchFields, 'dateFields' => $dateSearchFields];

            if (!empty($statusIdFilter)) {
                if ($statusIdFilter == Order::ORDER_INSTALLED_AND_RETURNED_STATUS) {
                    $orderStatusCondition = "AND orders.status_id = '" . $statusIdFilter . "' OR (orders.status_id = '2' AND orders.tracking_number!='') ";
                } else {
                    $orderStatusCondition = "AND orders.status_id = '" . $statusIdFilter . "'";
                }
            }

        } else {
            if (!empty($statusIdFilter)) {
                if ($statusIdFilter == Order::ORDER_INSTALLED_AND_RETURNED_STATUS) {
                    $orderStatusCondition = " OR (orders.status_id = '2' AND orders.tracking_number!='') AND orders.deleted_at is null ";
                } elseif ($statusIdFilter == 10) {
                    //@todo update order status after code merge
                    $orderStatusCondition = " AND orders.deleted_at is not null  ";
                } else {
                   // $orderStatusCondition = "AND (orders.status_id = '$statusIdFilter' AND  orders.tracking_number!='') AND orders.deleted_at is null ";
                    $orderStatusCondition = "AND (orders.status_id = '$statusIdFilter') AND orders.deleted_at is null ";

                }

            }
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);

        $filter .= $orderStatusCondition;
        return $filter;
    }

    function getPo($order_id = null, $sendemail = false, $to = null, $from = null, $cc = null, $bcc = null, $message = null)
    {
        $mode = "";
        if (isset($_GET['mode']) && !empty($_GET['mode'])) {
            $mode = $_GET['mode'];
        }
        $data = $this->model->getOrderData($order_id, $this->data['pass']);
        $row = $this->model->with([
            'location' => function($query){
                return $query->select('id', 'fedex_number');
            }
        ])->find($order_id);

        $data[0]['fedex_number'] = $row->location ? $row->location->fedex_number : null;
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
            $loadingInfo =[];
            if(!empty($data[0]['loading_info'])){
                $loadingInfo[] =" | Shipping Restriction: ".$data[0]['loading_info'];
            }
            if(isset($data[0]['liftgate']) && $data[0]['liftgate'] ==1 ){
                $loadingInfo[] = '| REQUIRES LIFTGATE';
            }
            $data[0]['loading_info'] = implode(" ",$loadingInfo);

            if (!empty($data[0]['loading_info']))
            {
                $data[0]['freight_type'] = $data[0]['freight_type'] . "\n" . ' ' . $data[0]['loading_info'] . '';
            }

            $data[0]['cc_email'] = '';

            if (!empty($data[0]['po_attn'])) {
                $data[0]['po_location'] = $data[0]['po_location'] . "\n" . $data[0]['po_attn'];
            }
            $PONote = "";

            $OrderSetting = OrdersettingContent::where(["ordertype_id" => $data[0]['order_type_id']])->get();
            $is_merchandiseorder = 0;
            if ($OrderSetting->count() > 0) {
                $PONoteSettings = $OrderSetting[0]->ordersetting()->get();
                $PONote = $PONoteSettings[0]->po_note;
                $is_merchandiseorder = $PONoteSettings[0]->is_merchandiseorder;
            }
            $addonPONote = !empty($data[0]['po_notes_additionaltext']) ? $data[0]['po_notes_additionaltext'] : $PONote;
            if (!empty($addonPONote)) {
                $addonPONote = str_replace("MERCHANDISE_CONTACT", (!empty($data[0]['loc_merch_contact_email']) ? $data[0]['loc_merch_contact_email'] : ""), $addonPONote);
                $addonPONote = str_replace("GENERAL_MANAGER", (!empty($data[0]['loc_general_manager_email']) ? $data[0]['loc_general_manager_email'] : ""), $addonPONote);
                $addonPONote = str_replace("REGIONAL_DIRECTOR", (!empty($data[0]['loc_regional_contact_email']) ? $data[0]['loc_regional_contact_email'] : ""), $addonPONote);
                $addonPONote = str_replace("SVP_CONTACT", (!empty($data[0]['loc_svp_contact_email']) ? $data[0]['loc_svp_contact_email'] : ""), $addonPONote);
                $addonPONote = str_replace("TECHNICAL_CONTACT", (!empty($data[0]['loc_technical_user_email']) ? $data[0]['loc_technical_user_email'] : ""), $addonPONote);
            }
            $data[0]['po_notes'] = " NOTE: " . $data[0]['po_notes'] . " " . $addonPONote;

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
                    $item_total = ($data[0]['brokenCaseArray'][$i]) ? $data[0]['OriginalUnitPriceArray'][$i]* $data[0]['orderQtyArray'][$i]: (!in_array($data[0]['order_type_id'],explode(",",$this->data['pass']['calculate price according to case price']->data_options)))?$data[0]['OriginalUnitPriceArray'][$i]* $data[0]['orderQtyArray'][$i]: $data[0]['OriginalCasePriceArray'][$i]* $data[0]['orderQtyArray'][$i];
                    //$item_total_string = "$ " . number_format($item_total, Order::ORDER_PERCISION);
                    $item_total_string = $item_total;
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
            $data['pass'] = $this->data['pass'];
            $pdf = \PDF::loadView('order.po', ['data' => $data, 'main_title' => "Purchase Order"]);
            if ($mode == "save") {
                $po_file_name = $data[0]['company_name_short'] . "_PO_" . $data[0]['po_number'] . '.pdf';
                $po_file_path = 'orders/' . $po_file_name;
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
                    if (is_array($cc)) {
                        $cc = implode(',', $cc);
                    }
                    if (is_array($bcc)) {
                        $bcc = implode(',', $bcc);
                    }


                    /* current user */
                    $google_acc = \DB::table('users')->where('id', \Session::get('uid'))->first();
                    $options = [
                        'cc' => $cc,
                        'bcc' => $bcc,
                        'attach' => $file_to_save,
                        'filename' => $filename,
                        'encoding' => 'base64',
                        'type' => 'application/pdf',
                        'preferGoogleOAuthMail' => false
                    ];
                    $configName = 'Send Email';
                    $sent = FEGSystemHelper::sendSystemEmail(array(
                        'to' => implode(',', $to),
                        'cc' => $cc,
                        'bcc' => $bcc,
                        'subject' => $subject,
                        'message' => $message,
                        'preferGoogleOAuthMail' => false,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'configName' => $configName,
                        'from' => (!empty($google_acc->oauth_token) && !empty($google_acc->refresh_token)) ? $google_acc->email : $from,
                        'replyTo' => $from,
                        'attach' => $file_to_save,
                        'filename' => $filename,
                        'encoding' => 'base64',
                        'type' => 'application/pdf',
                    ));
                    if (!$sent) {
                        return 3;
                    } else {
                        return 1;
                    }
                }
            } else {
                return $pdf->download($data[0]['company_name_short'] . "_PO_" . $data[0]['po_number'] . '.pdf');
            }
        }
    }

    function sendPhpEmail($message, $to, $from, $subject, $pdf, $filename, $cc, $bcc)
    {
        $result = \Mail::raw($message, function ($message) use ($to, $from, $subject, $pdf, $filename, $cc, $bcc) {
            $message->subject($subject);
            $message->from($from);
            $message->to($to);

            if (!empty($cc)) {
                $message->cc(explode(",", $cc));
            }
            if (!empty($bcc)) {
                $message->bcc(explode(",", $bcc));
            }
            $message->replyTo($from, $from);
            $message->attachData($pdf->output(), $filename);
        });
        if ($result) {
            return 1;
        } else {
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
        $this->data['data'] = $this->model->getOrderQuery($id, null, $this->data['pass']);
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
        $response['editUrl'] = url('/order/update/' . $newID);
        $response['viewUrl'] = url('/order/show/' . $newID);
        $response['poUrl'] = url('/order/po/' . $newID);
        $response['receiptUrl'] = url('/order/orderreceipt/' . $newID);

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
        $location =  location::find($location_id);
        return [
            'po_3'          =>  $this->validatePO($po, $po_full, $location_id),
            'fedex_number'  =>  $location ? $location->fedex_number ? $location->fedex_number : 'No Data' : 'No Data',
            'freight_id'    => $location ? $location->freight_id ? $location->freight_id : '' : '',
        ];
    }

    function validatePO($po, $po_full, $location_id)
    {
        if ($po != 0) {

            if ($this->model->isPOAvailable($po_full)) {
                $this->model->createPOTrack($po_full, $location_id);
                $po_3 = explode('-', $po_full);
                $msg = $po_3[2];
            } else {
                //die('po not available');
                $msg = $this->model->increamentPO($location_id);
            }
        } else {
            $msg = $this->model->increamentPo($location_id);
        }
        return $msg;
    }

    function getOrderreceipt($order_id = null)
    {
        $dpl = new DigitalPackingList();
        $this->data['data'] = $this->model->getOrderReceipt($order_id);
        $this->data['data']['order_items'] = \DB::select('SELECT * , g.game_name, O.id as id  FROM order_contents O LEFT JOIN game g ON g.id = O.game_id WHERE order_id = ' . $order_id);
        $showdblbutton = $dpl->isOrderReceived($order_id);
        $this->data['showdblbutton']=$showdblbutton;
        return view('order.order-receipt', $this->data);
    }

    function postReceiveorder(Request $request, $id = null)
    {
        \Input::merge(array_map(function ($value) {
            if (is_string($value)) {
                return trim($value);
            } else {
                return $value;
            }
        }, \Input::all()));

        $received_part_ids = array();
        $order_id = $request->get('order_id');
        $item_count = $request->get('item_count');
        $notes = addslashes($request->get('notes'));
        $order_status = $request->get('order_status');
        $added_to_inventory = $request->get('added_to_inventory');
        $user_id = $request->get('user_id');
        $order_type_id = $request->get('order_type_id');
        $added = 0;

        if (!empty($request->get('receivedInParts')) && $order_status == '2') {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.partial_close_restrict')
            ));
        }

        if (!empty($request->get('receivedInParts'))) {
            $received_part_ids = $request->get('receivedInParts');
        } else {
            // close order
            //$order_status = 2;
        }
        $received_qtys = $request->get('receivedQty');
        $item_ids = $request->get('itemsID');
        $received_item_qty = $request->get('receivedItemsQty');
        $date_received = date("Y-m-d", strtotime($request->get('date_received')));
        for ($i = 0; $i < count($item_ids); $i++) {
            $receivedQuantity = $received_qtys[$i];
            if (empty($receivedQuantity)) {
                continue;
            }
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
        if ($order_status == Order::CLOSEID1 && $order_type_id == Order::ORDER_TYPE_ADVANCED_REPLACEMENT) // Advanced Replacement Returned.. require tracking number
        {
            $rules['tracking_number'] = "required|min:3";
            $tracking_number = trim($request->get('tracking_number'));
        }
        $rules['tracking_number'] = "min:3";
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
            $record = \DB::select('SELECT  SUM(qty) as total_items,(SUM(qty)-SUM(item_received)) as remaining_items FROM order_contents WHERE order_id =' . $request->get('order_id'));

            if ($record[0]->remaining_items > 0 && $record[0]->remaining_items < $record[0]->total_items) {
                $partial = 1;
            }
            $orderNotes = \DB::table('orders')->where('id', $request->get('order_id'))->pluck('notes');
            if (!empty($orderNotes)) {
                $notes = $orderNotes . "<br>----------------------<br>" . $notes;
            }
            $data = array('date_received' => $date_received,
                'status_id' => $order_status,
                'notes' => $notes,
                'tracking_number' => trim($request->get('tracking_number')),
                'received_by' => $request->get('user_id'),
                'is_partial' => $partial,
                'added_to_inventory' => $added);
            \DB::table('orders')->where('id', $request->get('order_id'))->update($data);

            if ($request->get('mode') == 'update') {
                $this->updateOrderReceipt($request);
            }
            /**
             * Updating order status to open partial if received Items qty is less than ordered items qty
             */
            $order = Order::find($order_id);
            $order->setOrderStatus();
            $order->save();
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

    public function updateOrderReceipt($request)
    {
        //dd($request->all());
        $order_id = $request->get('order_id');
        $updateQty = $request->get('updateQty');
        //$updateProducts = $request->get('updateProducts');
        $item_ids = $request->get('orderLineItemId');
        $receivedQty = $request->get('updateAlreadyReceivedQty');
        $updateOrigQty = $request->get('updateOrigQty');
        $item_notes = $request->get('updateItemNotes');
        $date_received = date("Y-m-d", strtotime($request->get('date_received')));
        $user_id = $request->get('user_id');
        $updateProducts = [];
        $receiveHistory = [];
        if (!empty($item_ids) && $item_ids != 'NULL' && $item_ids != 'null' && $item_ids != 'undefined') {
            $receiveHistory = \DB::select("SELECT sum(quantity) AS total_qty, order_received.* FROM order_received WHERE order_id = $order_id AND order_line_item_id IN (" . implode(',', $item_ids) . ") GROUP BY order_line_item_id ORDER BY order_line_item_id");


            foreach ($receiveHistory as $key => $item) {
                if ($item->total_qty != $updateQty[$key]) {
                    array_push($updateProducts, $item->order_line_item_id);
                }
            }


            foreach ($item_ids as $i => $item_id) {
                if ($updateOrigQty[$i] == $updateQty[$i]) {
                    $status = 1;
                } else {
                    $status = 2;
                }

                if (in_array($item_id, $updateProducts) && $updateQty[$i] <= $updateOrigQty[$i]) {

                    \DB::table('order_received')->where('order_line_item_id', $item_id)->delete();

                    if ($updateQty[$i] != 0) {
                        \DB::insert('INSERT INTO order_received (`order_id`,`order_line_item_id`,`quantity`,`received_by`, `status`, `date_received`, `notes`)
							 	  		   VALUES (' . $order_id . ',' . $item_id . ',' . $updateQty[$i] . ',' . $user_id . ',' . $status . ', "' . $date_received . '" , "' . $item_notes[$i] . '" )');
                    }

                    \DB::update('UPDATE order_contents
								 	 	 SET item_received = ' . $updateQty[$i] . '
							   	   	   WHERE id = ' . $item_id);

                    if ($updateQty[$i] < $receivedQty[$i]) {
                        \DB::update('UPDATE orders
								 	 	 SET status_id =  1
							   	   	   WHERE id = ' . $order_id);
                    }
                }

            }

            /*return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));*/
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
        $excludeProducts = Input::get('exclude_products', null);
        $whereWithVendorCondition = $whereWithExcludeProductCondition = "";

        $orderTypeId = Input::get('order_type_id', 0);
        $whereOrderTypeCondition = $whereRestrictedTypeCondition = "";
        $restrictedOrderTypes = [Order::ORDER_TYPE_REDEMPTION,Order::ORDER_TYPE_INSTANT_WIN_PRIZE];
        // include order type match if type is any of - 6-Office Supplies, 7-Redemption Prizes, 8-Instant Win Prizes, 17-Party Supplies, 22-Tickets
        if (!empty($orderTypeId) && in_array($orderTypeId,$restrictedOrderTypes)) {
            $whereOrderTypeCondition = " AND products.prod_type_id in(".implode(",",$restrictedOrderTypes).")";
        }
        if($this->model->isTypeRestricted()){
            $whereRestrictedTypeCondition = " AND products.prod_type_id in(".$this->model->getAllowedTypes().")";
        }

        //get products related to selected vendor only
        if (!empty($vendorId)) {
            $whereWithVendorCondition = " AND products.vendor_id = $vendorId";
        }

        if ($excludeProducts) {
            $excludeProductsArray = explode(',', $excludeProducts);
            $excludeProductsIds = [];
            foreach ($excludeProductsArray as $item) {
                $product = product::find($item);
                //Hot fixing isuse on live environment https://www.screencast.com/t/DvCOTKqD8
                if($product){
                    $variations = $product->getProductVariations();
                    array_map(function ($row) use (&$excludeProductsIds) {
                        $excludeProductsIds[] = $row->id;
                    }, $variations->all());
                }
            }
            //Hot fixing isuse on live environment https://www.screencast.com/t/DvCOTKqD8
            if(!empty($excludeProductsIds)){
                $excludeProductsIds = implode(',', $excludeProductsIds);
                $whereWithExcludeProductCondition = " AND products.id NOT IN ($excludeProductsIds) ";
            }
        }

        $results = array();
        $term = addslashes($term);
        //fixing for https://www.screencast.com/t/vwFYE3AlF
        $sql = "SELECT *,LOCATE('$term',vendor_description) AS pos
                                FROM products
                                WHERE vendor_description LIKE '%$term%' 
                                AND products.inactive=0  $whereWithVendorCondition  $whereWithExcludeProductCondition  
                                  $whereOrderTypeCondition $whereRestrictedTypeCondition
                                GROUP BY vendor_description
                                ORDER BY pos, vendor_description
                                 Limit 0,10";
        $queries = \DB::select($sql);
        if (count($queries) != 0) {
            foreach ($queries as $query) {
                    $orderTypeId = (int) $orderTypeId;
                    $product = product::find($query->id);
                    $productVariations = $product->getProductVariations()->where("prod_type_id",$orderTypeId)->first();

                    if($productVariations){
                            $results[] = ['id' => $productVariations->id, 'value' => $productVariations->vendor_description];
                    }else{
                        $results[] = ['id' => $query->id, 'value' => $query->vendor_description];
                    }
            }
            echo json_encode($results);
        } else {
            echo json_encode(array('id' => 0, 'value' => "No Match"));
        }
    }

    public function getProductdata()
    {
        $product_id = Input::get('product_id');
        $row = \DB::select("select id,vendor_description,sku,item_description,unit_price,case_price,retail_price,num_items from products WHERE id='" . addslashes($product_id) . "'");
        $json = [];
        if (!empty($row)) {
            //$row = Order::hydrate($row);
            $json = array(
                'sku' => $row[0]->sku,
                'item_description' => !empty($row[0]->item_description) ? $row[0]->item_description: $row[0]->vendor_description." (SKU-".$row[0]->sku.")",
                'unit_price' => \CurrencyHelpers::formatPrice($row[0]->unit_price, Order::ORDER_PERCISION, false),
                'case_price' => \CurrencyHelpers::formatPrice($row[0]->case_price, Order::ORDER_PERCISION, false),
                'retail_price' => \CurrencyHelpers::formatPrice($row[0]->retail_price, Order::ORDER_PERCISION, false),
                'id' => $row[0]->id,
                'qty_per_case' => $row[0]->num_items,
            );
        }

        return json_encode($json);
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
        if (!empty($email)) {
            if (strpos($email, ',') != FALSE) {
                $email = explode(',', trim($email, ","));
            } else {
                $email = array($email);
            }
            foreach ($email as $index => $record) {
                $record = trim($record);
                if (!filter_var($record, FILTER_VALIDATE_EMAIL)) {
                    unset($email[$index]);
                } else {
                    $email[$index] = $record;
                }

            }
            return empty($email) ? false : $email;
        }
        return false;
    }

    function getExposeApi(Request $request, $eId)
    {
        $id = \SiteHelpers::encryptID($eId, true);
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $status = Order::apified($id);
            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? \Lang::get('core.order_api_not_exposable') : \Lang::get('core.order_api_exposed');
        }
        return response()->json($response);
    }

    function getVerifyInvoice(Request $request, $eId)
    {
        $id = \SiteHelpers::encryptID($eId, true);
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $status = \DB::table('orders')->where('id', $id)->update(['invoice_verified' => '1', 'invoice_verified_date' => date('Y-m-d')]);
            $response['status'] = $status == false ? 'error' : 'success';
            $response['message'] = $status == false ? \Lang::get('core.order_invoice_verify_error') : \Lang::get('core.order_invoice_verify_success');
        }
        return response()->json($response);
    }

    function getCheckEditable(Request $request, $id)
    {
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $orderData = Order::find($id) ? Order::find($id)->toArray() : null;
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
                //$message = \Lang::get('core.order_api_exposed_edit_alert');
                $message = \Lang::get('core.order_api_exposed_edit_restrict_alert');
                $status = false;
            }
            /*if ($apified && $partial) {
                $message = \Lang::get('core.order_api_edit_partial_alert');
                $status = false;
            }*/
            /*
            if ($closed) {
                $message = \Lang::get('core.order_closed_edit_alert');
                $status = false;
            }*/
            if ($voided) {
                $message = \Lang::get('core.order_voided_edit_alert');
                $status = false;
            }

            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? $message : 'Ready for edit';

            /*$isClone = $apified && (!$partial && !$voided && !$closed);

            if ($isClone) {
                $response['url'] = url('/order/insta-clone/'.\SiteHelpers::encryptID($id).'/voided');
                $response['action'] = 'clone';
            }*/
        }
        return response()->json($response);
    }

    function getCheckReceivable(Request $request, $eId)
    {
        $id = \SiteHelpers::encryptID($eId, true);
        $response = ['status' => 'error', 'message' => \Lang::get('core.order_missing_id')];
        if (!empty($id)) {
            $orderData = Order::find($id)->toArray();
            $freeHand = Order::isFreehand($id, $orderData);
            $apiable = Order::isApiable($id, $orderData);
            $apified = Order::isApified($id, $orderData);
            $voided = Order::isVoided($id, $orderData);
            $closed = Order::isClosed($id, $orderData);
            $partiallyReceived = Order::isPartiallyReceived($id, $orderData);
            //$status = !$voided && !$closed && ($freeHand || !$apiable || $apified);
            /**
             * All checks removed because
             * partial closed orders were not receiveable
             * logic changed. orders are receive able before posting to netsuite
             */
            $message = '';
            $status = !$voided;
            /*
            if (!$apified) {
                $message = \Lang::get('core.order_receive_error_api_not_exposed');
            }

            if ($closed && !$partiallyReceived) {
                $message = \Lang::get('core.order_closed_receipt_alert');
            }*/
            if ($voided) {
                $message = \Lang::get('core.order_voided_receipt_alert');
            }

            $response['status'] = $status === false ? 'error' : 'success';
            $response['message'] = $status === false ? $message : 'Ready to receive';

            if ($status) {
                $response['url'] = url('/order/orderreceipt/' . $id);
            }
        }
        return response()->json($response);

    }

    function getCheckClonable(Request $request, $eId)
    {

    }

    public function getEmailHistory(Request $request)
    {

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

        if ($returnSelf && !empty($searchFor)) {
            $dataList[] = $searchFor;
        }

        return response()->json($dataList);
    }

    public function getSidNotes(Request $request)
    {
        $notes = \DB::table('requests')->select('notes')->whereIn('id', $request->sids)->get();
        return $notes;
    }

   public static function array_splice_assoc(&$input, $offset, $length, $replacement) {
        $replacement = (array) $replacement;
        $key_indices = array_flip(array_keys($input));
        if (isset($input[$offset]) && is_string($offset)) {
            $offset = $key_indices[$offset];
        }
        if (isset($input[$length]) && is_string($length)) {
            $length = $key_indices[$length] - $offset;
        }

        $input = array_slice($input, 0, $offset, TRUE)
            + $replacement
            + array_slice($input, $offset + $length, NULL, TRUE);
    }
public static function array_move($which, $where, $array)
    {

        $tmpWhich = $which;
        $j=0;
        $keys = array_keys($array);

        for($i=0;$i<count($array);$i++)
        {
            if($keys[$i]==$tmpWhich)
                $tmpWhich = $j;
            else
                $j++;
        }
        $tmp  = array_splice($array, $tmpWhich, 1);
        self::array_splice_assoc($array, $where, 0, $tmp);
        return $array;
    }
    public static function changeProductReservedQtyOnRestoreOrder($order_id){
        if($order_id>0) {
            $sql = "SELECT DISTINCT product_id,sum(adjustment_amount) as reducedreservedqty FROM `reserved_qty_log` where order_id=$order_id";
            $result = \DB::select($sql);
            if(count($result)>0) {
                $product = \DB::table('products')->where(['id' => $result[0]->product_id,'is_reserved'=>1])->first();
                if(!empty($product)) {
                    $items = \DB::table('products')->where(['vendor_description' => $product->vendor_description, 'sku' => $product->sku])->get();
                    foreach($items as $itms){
                        $res = \DB::update("update products set  reserved_qty=(reserved_qty-".$result[0]->reducedreservedqty.") where id='".$itms->id."'");
                    }
                }
            }
        }
    }


    public function getUpdateProductVariantsWithDefaultExpenseCategoryHavingDifferentPrice(){

        $products = \Db::table('products')->select('id','sku','vendor_description','case_price','is_default_expense_category','vendor_id')
            ->groupBy('vendor_description','vendor_id','sku')
            ->havingRaw('COUNT(vendor_description) > 1 AND GROUP_CONCAT(is_default_expense_category) = "0,0"')
            ->get();

        $products = Product::hydrate($products);


        foreach($products as $product){

            if($product->hasDefaultExpenseCategory($product->id)){
                echo "Skipping For (ID: {$product->id} === Item Name:{$product->vendor_description} === SKU:{$product->sku} === Case Price: {$product->case_price} ) <br>";
                continue;
            }
            $variants = Product::where(['vendor_description' => $product->vendor_description, 'sku' => $product->sku, 'vendor_id' => $product->vendor_id])->get();
            foreach ($variants as $item){
                $item->is_default_expense_category = 1;
                $item->save();
                echo "Update default Expense Category For (ID: {$item->id} === Item Name:{$item->vendor_description} === SKU:{$item->sku} === Case Price: {$item->case_price} ) <br>";
            }

        }
    }

    public function getUpdateProductVariantsWithDefaultExpenseCategoryHavingDifferentSku(){

        $products = \Db::table('products')->select('id','sku','vendor_description','case_price','is_default_expense_category','vendor_id')
            ->groupBy('vendor_description','vendor_id','case_price')
            ->havingRaw('COUNT(vendor_description) > 1 AND GROUP_CONCAT(is_default_expense_category) = "0,0"')
            ->get();

        $products = Product::hydrate($products);


        foreach($products as $product){

            if($product->hasDefaultExpenseCategory($product->id)){
                echo "Skipping For (ID: {$product->id} === Item Name:{$product->vendor_description} === SKU:{$product->sku} === Case Price: {$product->case_price} ) <br>";
                continue;
            }
            $variants = Product::where(['vendor_description' => $product->vendor_description, 'case_price' => $product->case_price, 'vendor_id' => $product->vendor_id])->get();
            foreach ($variants as $item){
                $item->is_default_expense_category = 1;
                $item->save();
                echo "Update default Expense Category For (ID: {$item->id} === Item Name:{$item->vendor_description} === SKU:{$item->sku} === Case Price: {$item->case_price} ) <br>";
            }

        }
    }


    public function getUpdateProductVariantsWithDefaultExpenseCategory(){

        $products = \Db::table('products')->select('id','sku','vendor_description','case_price','is_default_expense_category')
            ->groupBy('vendor_description','vendor_id','sku','case_price')
            ->havingRaw('COUNT(vendor_description) > 1')
            ->get();

        $products = Product::hydrate($products);

        foreach($products as $product){

            if($product->hasDefaultExpenseCategory($product->id)){
                echo "Skipping For (ID: {$product->id} Item Name:{$product->vendor_description} SKU:{$product->sku} Case Price: {$product->case_price} ) <br>";
                continue;
            }
            $variants = $product->getProductVariations();
            $sorted = $variants->sortBy('inactive');

            $activeItemFound = false;
            foreach ($sorted as $item){
                if($item->inactive == 0){
                    $activeItemFound = true;
                    $item->is_default_expense_category = 1;
                    $item->save();
                    break;
                }
            }

            if(!$activeItemFound){
                $item = $variants->sortBy('id')->first();
                $item->is_default_expense_category = 1;
                $item->save();
            }
            echo "Update default Expense Category For (ID: {$product->id} Item Name:{$product->vendor_description} SKU:{$product->sku} Case Price: {$product->case_price} ) <br>";
        }


    }




    public function getCorrectOrdersBug242($step = '1'){
        die("Script blocked. To run this script please contact your development team. Thanks!");

        $records = \DB::select("SELECT
              orders.id AS aa_id,
              orders.po_number,
              orders.date_ordered,
              IF(orders.is_partial = 0,'No','Yes') AS is_partial,
              IF(orders.is_freehand = 0,'No','Yes') AS is_freehand,
              order_type.order_type,
              IF(orders.invoice_verified = 0,'No','Yes') AS `invoice verified`,
              
            (SELECT SUM(order_contents.qty)
            FROM orders
            LEFT JOIN order_contents ON orders.id = order_contents.order_id
            WHERE orders.id = aa_id
            GROUP BY order_contents.order_id) AS items_ordered,
            
            (SELECT SUM(order_received.quantity)
            FROM orders
            LEFT JOIN order_received ON orders.id = order_received.order_id
            WHERE orders.id = aa_id
            GROUP BY order_received.order_id) AS items_received
            
            FROM orders
            JOIN order_type ON order_type.id = orders.order_type_id
            WHERE     status_id = 2
                AND is_partial = 0    
                AND is_api_visible = 0
                AND is_freehand = 0
                AND order_type_id IN (8,17,4,6,7)
                
                AND YEAR(date_ordered) = 2017
                AND date_ordered < '2017-06-06'
            HAVING items_ordered < items_received
            ORDER BY aa_id");

        if($step == '1'){
            $ids = array_map(function($row){
                return $row->aa_id;
            }, $records);
            \DB::table('order_received')->whereIn('order_id', $ids)->update(['deleted_at' => Carbon::now()]);
            die("Step 1 completed!");
        }

        foreach ($records as $record){
            $order = Order::find($record->aa_id);

            $order_contents = \DB::table('order_contents')->where('order_id', $order->id)->get();

            $notes = '';

            foreach ($order_contents as $order_content){
                $order_received = \DB::table('order_received')
                    ->where('order_id', $order->id)
                    ->where('order_line_item_id', $order_content->id)
                    ->whereNull('deleted_at')
                    ->get();


                if(empty($order_received)){
                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $order_content->qty,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) All Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) All Items Received <br>----------------------<br>';

                }else{

                    $qty_received = collect($order_received)->sum('quantity');

                    if($qty_received < $order_content->qty){
                        $qty_left = $order_content->qty - $qty_received;
                    }else{
                        $qty_left = $order_content->qty;
                    }

                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $qty_left,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) Some Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) Some Items Received <br>----------------------<br>';
                }

                \DB::table('order_contents')->where('id', $order_content->id)->update(['item_received' => $order_content->qty]);
            }

            $order->status_id = 2;
            $order->invoice_verified = 1;
            $order->invoice_verified_date = Carbon::now();
            $order->is_api_visible = 1;
            $order->api_created_at = Carbon::now();
            $order->date_received = Carbon::now();
            $order->updated_at = Carbon::now();
            $order->received_by = '238';
            $order->notes = $notes;
            $order->save();
        }

        die("Script Completed!");
    }

    public function getCloseOrdersWithNoContent()
    {
        die("Script blocked. To run this script please contact your development team. Thanks!");
        $records = \DB::select("SELECT
                                  orders.id,
                                  orders.po_number,
                                  orders.date_ordered,
                                  orders.status_id,
                                  order_contents.id
                                FROM orders
                                  LEFT JOIN order_contents
                                    ON order_contents.order_id = orders.id
                                WHERE YEAR(date_ordered) <= 2016
                                    AND order_contents.id IS NULL
                                    AND orders.status_id <> 2
                                    AND orders.order_type_id IN(8,17,4,6,7)");

        if (!empty($records)) {
            foreach ($records as $order) {
                \DB::update("update orders set status_id = 2, notes='(System generated) Order has been closed.', updated_at='" . Carbon::now() . "' where po_number='" . $order->po_number . "'");
            }
        }
        die("Script Completed!");
    }

    function postAdditionalponote(Request $request)
    {

        $orderId = $request->input('order_id');
        $orderTypeId = !empty($request->input('ordertype_id')) ? $request->input('ordertype_id') : 0;
        $PONote = "";
        $producttypeid = 0;
        $is_merchandiseorder = 0;
        if ($orderId > 0) {
            $order = $this->model->find($orderId);
            if($order) {
                if ($order->order_type_id == $orderTypeId) {
                    $PONote = $order->po_notes_additionaltext;
                }
            }
        }
        if (empty($PONote)) {
            $OrderSetting = OrdersettingContent::where(["ordertype_id" => $orderTypeId])->get();

            if ($OrderSetting->count() > 0) {
                $PONoteSettings = $OrderSetting[0]->ordersetting()->get();
                $PONote = !empty($PONote) ? $PONote : $PONoteSettings[0]->po_note;
                $is_merchandiseorder = $PONoteSettings[0]->is_merchandiseorder;
            }
        }
        return response()->json([
            "PoNoteText" => $PONote,
            "is_merchandiseorder" => $is_merchandiseorder,
        ]);

    }

    public function getCorrectOrdersBug2016($step = '1')
    {
        die("Script blocked. To run this script please contact your development team. Thanks!");

        $records = \DB::select("SELECT
                              orders.id             AS aa_id,
                              orders.po_number,
                              orders.date_ordered,
                              IF(orders.is_partial = 0,'No','Yes') AS is_partial,
                              IF(orders.is_freehand = 0,'No','Yes') AS is_freehand,
                              order_type.order_type,
                              IF(orders.invoice_verified = 0,'No','Yes') AS `invoice verified`,
                              (SELECT
                                 SUM(order_contents.qty)
                               FROM orders
                                 LEFT JOIN order_contents
                                   ON orders.id = order_contents.order_id
                               WHERE orders.id = aa_id
                               GROUP BY order_contents.order_id) AS items_ordered,
                              (SELECT
                                 SUM(order_received.quantity)
                               FROM orders
                                 LEFT JOIN order_received
                                   ON orders.id = order_received.order_id
                               WHERE orders.id = aa_id
                                   AND order_received.deleted_at IS NULL
                               GROUP BY order_received.order_id) AS items_received
                            FROM orders
                              JOIN order_type
                                ON order_type.id = orders.order_type_id
                            WHERE is_api_visible = 0
                                AND is_freehand = 0
                                AND order_type_id IN(8,17,4,6,7)
                                AND YEAR(date_ordered) <= 2016
                            HAVING items_ordered < items_received
                            ORDER BY aa_id");

        if ($step == '1') {
            $ids = array_map(function ($row) {
                return $row->aa_id;
            }, $records);
            \DB::table('order_received')->whereIn('order_id', $ids)->update(['deleted_at' => Carbon::now()]);
            die("Step 1 completed!");
        }

        foreach ($records as $record) {
            $order = Order::find($record->aa_id);

            $order_contents = \DB::table('order_contents')->where('order_id', $order->id)->get();

            $notes = '';

            foreach ($order_contents as $order_content) {
                $order_received = \DB::table('order_received')
                    ->where('order_id', $order->id)
                    ->where('order_line_item_id', $order_content->id)
                    ->whereNull('deleted_at')
                    ->get();


                if (empty($order_received)) {
                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $order_content->qty,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) All Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) All Items Received <br>----------------------<br>';

                } else {

                    $qty_received = collect($order_received)->sum('quantity');

                    if ($qty_received < $order_content->qty) {
                        $qty_left = $order_content->qty - $qty_received;
                    } else {
                        $qty_left = $order_content->qty;
                    }

                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $qty_left,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) Some Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) Some Items Received <br>----------------------<br>';
                }

                \DB::table('order_contents')->where('id', $order_content->id)->update(['item_received' => $order_content->qty]);
            }

            $order->status_id = 2;
            $order->invoice_verified = 1;
            $order->invoice_verified_date = Carbon::now();
            $order->is_api_visible = 1;
            $order->api_created_at = Carbon::now();
            $order->date_received = Carbon::now();
            $order->updated_at = Carbon::now();
            $order->received_by = '238';
            $order->notes = $notes;
            $order->save();
        }

        die("Script Completed!");
    }
    public function getCorrectOrdersBugExtended242($step = '1'){
        //die("Script blocked. To run this script please contact your development team. Thanks!");

        $records = \DB::select("SELECT
  orders.id             AS aa_id,
  orders.po_number,
  orders.date_ordered,
  IF(orders.is_partial = 0,'No','Yes') AS is_partial,
  IF(orders.is_freehand = 0,'No','Yes') AS is_freehand,
  order_type.order_type,
  IF(orders.invoice_verified = 0,'No','Yes') AS `invoice verified`,
  IFNULL((SELECT SUM(order_contents.qty) FROM orders LEFT JOIN order_contents ON orders.id = order_contents.order_id WHERE orders.id = aa_id GROUP BY order_contents.order_id),0) AS items_ordered,
  IFNULL((SELECT SUM(order_received.quantity) FROM orders LEFT JOIN order_received ON orders.id = order_received.order_id WHERE orders.id = aa_id GROUP BY order_received.order_id),0) AS items_received
FROM orders
  JOIN order_type
    ON order_type.id = orders.order_type_id
WHERE status_id = 2
    AND is_partial = 0
    AND is_api_visible = 0
    AND is_freehand = 0
    AND order_type_id IN(8,17,4,6,7)
    AND YEAR(date_ordered) <= 2017
    AND date_ordered < '2017-06-06'
ORDER BY aa_id");

        /*
        if($step == '1'){
            $ids = array_map(function($row){
                return $row->aa_id;
            }, $records);
            \DB::table('order_received')->whereIn('order_id', $ids)->update(['deleted_at' => Carbon::now()]);
            die("Step 1 completed!");
        }*/

        foreach ($records as $record){
            $order = Order::find($record->aa_id);

            /*
            $order_contents = \DB::table('order_contents')->where('order_id', $order->id)->get();

            $notes = '';

            foreach ($order_contents as $order_content){
                $order_received = \DB::table('order_received')
                    ->where('order_id', $order->id)
                    ->where('order_line_item_id', $order_content->id)
                    ->whereNull('deleted_at')
                    ->get();


                if(empty($order_received)){
                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $order_content->qty,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) All Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) All Items Received <br>----------------------<br>';

                }else{

                    $qty_received = collect($order_received)->sum('quantity');

                    if($qty_received < $order_content->qty){
                        $qty_left = $order_content->qty - $qty_received;
                    }else{
                        $qty_left = $order_content->qty;
                    }

                    \DB::table('order_received')->insert([
                        'order_id' => $order->id,
                        'order_line_item_id' => $order_content->id,
                        'quantity' => $qty_left,
                        'received_by' => '238',
                        'date_received' => Carbon::now(),
                        'api_created_at' => Carbon::now(),
                        'notes' => '(System generated) Some Items Received',
                        'status' => 1
                    ]);

                    $notes .= '(System generated) Some Items Received <br>----------------------<br>';
                }

                \DB::table('order_contents')->where('id', $order_content->id)->update(['item_received' => $order_content->qty]);
            }*/

            $order->status_id = 2;
            $order->invoice_verified = 1;
            $order->invoice_verified_date = Carbon::now();
            $order->is_api_visible = 1;
            $order->api_created_at = '2017-06-06 00:00:00';
            $order->date_received = Carbon::now();
            $order->updated_at = Carbon::now();
            $order->received_by = '238';
            $order->notes = '(System generated) Some Items Received <br>----------------------<br>';
            $order->save();
        }

        die("Script Completed!");
    }
    public function getProductvariationid(){
        $products = \Db::table('products')->select('id','sku','vendor_description','case_price','is_default_expense_category','vendor_id')
            ->groupBy('vendor_description','vendor_id','sku','case_price')
            ->get();

        $products = product::hydrate($products); // converting product array to product object

        foreach($products as $product){
            $variationId = \SiteHelpers::encryptID($product->id);
            echo "Update default Expense Category For (ID: {$product->id} Variation ID:{$variationId} Item Name:{$product->vendor_description} SKU:{$product->sku} Case Price: {$product->case_price} ) <br>";
            \DB::update("update products set variation_id='".$variationId."' where vendor_description='".addcslashes($product->vendor_description,"'")."' and vendor_id='".$product->vendor_id."' and sku='".addcslashes($product->sku,"'")."' and case_price='".$product->case_price."' and variation_id is  null ");
        }
        die("Variation Id has been updated for all products.");
    }
    public function getUpdateRecordsIsApiVisible(){
        $records = DB::select('SELECT orders.id
        FROM orders
        LEFT OUTER JOIN location L ON orders.location_id=L.id
        LEFT OUTER JOIN vendor V ON orders.vendor_id=V.id
        LEFT OUTER JOIN users U ON orders.user_id=U.id
        LEFT OUTER JOIN order_type OT ON orders.order_type_id=OT.id
        LEFT OUTER JOIN order_contents OC ON orders.id=OC.order_id
        LEFT OUTER JOIN order_status OS ON orders.status_id=OS.id
        LEFT OUTER JOIN yes_no YN ON orders.is_partial=YN.id WHERE orders.id IS NOT NULL  AND orders.status_id IN ("2","6")  AND orders.order_type_id IN("8","7","6","17")  AND DATE(orders.created_at) <= "2017-06-06"  AND orders.is_freehand = "1"  AND orders.is_api_visible = "0"  AND orders.invoice_verified = "1" AND orders.deleted_at IS NULL  AND (orders.location_id IN ( SELECT id FROM location WHERE active = 1) )
        GROUP BY orders.id
        ORDER BY id DESC');
        foreach ($records as $record) {
            $order = Order::find($record->id);
            $order->is_api_visible = 1;
            $order->save();
        }
        dd('records saved');
    }
    public function getDplFile($orderId){

        //check if dpl file is already generated
        $downloadId = 0;
        $isFileNeedToBeRegenerated = true;
        $dpl = DigitalPackingList::where("order_id","=",$orderId)->first();

        $order = Order::where("id", '=', $orderId)->first();
        $location = $order->location;

        if(!is_null($dpl)){
            $downloadId = $dpl->id;
            $isFileNeedToBeRegenerated = $dpl->isFileNeedToBeRegenerated($order);
        }

        if($isFileNeedToBeRegenerated){
            Log::info("DPL FILE Order ID:".$orderId);
            if($order->isFullyReceived()){

                $dpl = new DigitalPackingList();
                $insertData = [
                    'order_id' => $order->id,
                    'name' => $order->po_number,
                    'location_id' => $order->location_id,
                    'type_id' => $location->debit_type_id
                ];
                $dpl = $dpl->saveOrUpdateDPL($insertData, $downloadId);

                $dpl->saveOrUpdateDPL(['name' => $dpl->name],$dpl->id);
                $dplFileContent = $dpl->getDPLFileData();
                $dpl->saveFile($dplFileContent);
            }
    }
        $headers = array(
            'Content-type: '.mime_content_type(public_path()."/uploads/dpl-files/".$dpl->name),
        );
        $updData = [
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'downloaded_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $dpl->saveOrUpdateDPL($updData,$dpl->id);
        Log::info("DPL File Downloaded:".public_path(DigitalPackingList::DPL_FILE_PATH).$dpl->name);
        return Response::download(public_path(DigitalPackingList::DPL_FILE_PATH).$dpl->name,$dpl->name,$headers);
    }
    public function getReceivefreehandandcloseorder($poNumbers){
        //Enter Comma separated po numbers in url request
        die("Script blocked. To run this script please contact your development team. Thanks!");
        if(!empty($poNumbers)){
            $records = \DB::table('orders')->whereIn("po_number",explode(",",$poNumbers))->get();
            foreach ($records as $record) {
                $order = Order::find($record->id);
                $order_contents = \DB::table('order_contents')->where('order_id', $order->id)->get();
                $notes = '';
                foreach ($order_contents as $order_content) {
                    $order_received = \DB::table('order_received')
                        ->where('order_id', $order->id)
                        ->where('order_line_item_id', $order_content->id)
                        ->whereNull('deleted_at')
                        ->get();

                    if (empty($order_received)) {
                        \DB::table('order_received')->insert([
                            'order_id' => $order->id,
                            'order_line_item_id' => $order_content->id,
                            'quantity' => $order_content->qty,
                            'received_by' => '238',
                            'date_received' => Carbon::now(),
                            'api_created_at' => Carbon::now(),
                            'notes' => '(System generated) All Items Received',
                            'status' => 1
                        ]);

                        $notes .= '(System generated) All Items Received <br>----------------------<br>';

                    } else {

                        $qty_received = collect($order_received)->sum('quantity');

                        if ($qty_received < $order_content->qty) {
                            $qty_left = $order_content->qty - $qty_received;
                        } else {
                            $qty_left = $order_content->qty;
                        }

                        \DB::table('order_received')->insert([
                            'order_id' => $order->id,
                            'order_line_item_id' => $order_content->id,
                            'quantity' => $qty_left,
                            'received_by' => '238',
                            'date_received' => Carbon::now(),
                            'api_created_at' => Carbon::now(),
                            'notes' => '(System generated) Some Items Received',
                            'status' => 1
                        ]);

                        $notes .= '(System generated) Some Items Received <br>----------------------<br>';
                    }

                    \DB::table('order_contents')->where('id', $order_content->id)->update(['item_received' => $order_content->qty]);
                }

                $order->status_id = 2;
                $order->invoice_verified = 1;
                $order->invoice_verified_date = Carbon::now();
                $order->is_api_visible = 1;
                $order->api_created_at = null;
                $order->date_received = Carbon::now();
                $order->updated_at = Carbon::now();
                $order->received_by = '238';
                $order->notes = $notes;
                $order->save();
            }
            die("Script Completed!");
        }else{
            die("Invalid Request");
        }
    }
    public function getTestGoogle(){
echo "<pre>";
      //  var_dump($_SERVER['HTTPS']);
        dd((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://').getenv('HTTP_HOST').env('G_REDIRECT'));
        exit;
    }
}
