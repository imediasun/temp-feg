<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\product;
use App\Models\Reviewvendorimportlist;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ReviewvendorimportlistController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'reviewvendorimportlist';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Reviewvendorimportlist();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'reviewvendorimportlist',
            'pageUrl' => url('reviewvendorimportlist'),
            'return' => self::returnUrl()
        );


    }
    function returnUrl()
    {
        $pages = (isset($_GET['page']) ? $_GET['page'] : '');
        $omit_vendor_list_id = (isset($_GET['omit_vendor_list_id']) ? $_GET['omit_vendor_list_id'] : '');
        $sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
        $order = (isset($_GET['order']) ? $_GET['order'] : '');
        $rows = (isset($_GET['rows']) ? $_GET['rows'] : '');
        $search = (isset($_GET['search']) ? $_GET['search'] : '');
        $v1 = (isset($_GET['v1']) ? $_GET['v1'] : '');
        $v2 = (isset($_GET['v2']) ? $_GET['v2'] : '');
        $v3 = (isset($_GET['v3']) ? $_GET['v3'] : '');

        $appends = array();
        if ($pages != '') $appends['page'] = $pages;
        if ($sort != '') $appends['sort'] = $sort;
        if ($order != '') $appends['order'] = $order;
        if ($rows != '') $appends['rows'] = $rows;
        if ($search != '') $appends['search'] = $search;
        if ($omit_vendor_list_id != '') $appends['omit_vendor_list_id'] = $omit_vendor_list_id;
        $url = "";
        foreach ($appends as $key => $val) {
            $url .= "&$key=$val";
        }
        return $url;

    }

    public function getIndex($vendorId = 0)
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        $this->data['vendor_id'] = $vendorId;
        return view('reviewvendorimportlist.index', $this->data);
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
        if (method_exists($this, 'getSearchFilterQuery')) {
            $filter = $this->getSearchFilterQuery();
        }
        else {
            $filter = (!is_null(Input::get('search')) ? $this->buildSearch() : '');
        }

        //$filter 	.=  $master['masterFilter'];
//    $params = array(
//        'params' => ''
//    );
        $sort = isset($_GET['sort']) ? $_GET['sort'] : $this->info['setting']['orderby'];
        $order = isset($_GET['order']) ? $_GET['order'] : $this->info['setting']['ordertype'];
        $params = array(
            'params' => '',
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
            'forExcel'=>1
        );


        $results = $this->model->getRows($params);

        $fields = $info['config']['grid'];
        $rows = $results['rows'];
        //print_r($fields[0]);die;
        $extra = array(
            'field' => '',
            'alias' => 'departments',
            'language' =>
                array('id' => ''),
            'label' => '',
            'view' => '1',
            'detail' => '1',
            'sortable' => '1',
            'search' => '1',

            'download' => '1',
            'frozen' => '1',
            'limited' => '',
            'width' => '100',
            'align' => 'left',
            'sortlist' => '0',
            'conn' =>
                array(
                    'valid' => '0',
                    'db' => '',
                    'key' => '',
                    'display' => ''),
            'attribute' =>
                array(
                    'hyperlink' => '',
                    array(
                        'active' => '0',
                        'link' => '',
                        'target' => 'modal',
                        'html' => ''),
                    'image' =>
                        array(

                            'active' => '0',
                            'path' => '',
                            'size_x' => '',
                            'size_y' => '',
                            'html' => ''),
                    'formater' =>
                        array(
                            'active' => '0',
                            'value' => '',
                        )));

        $rows = $this->updateDateInAllRows($rows);

        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
            'reviewVendorList' => 1,
            'excelExcludeFormatting' => isset($results['excelExcludeFormatting'])?$results['excelExcludeFormatting']:[]
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
    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'reviewvendorimportlist')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        \Session::put('config_id', $config_id);
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'hideUnchanged'=>$request->input('hideUnchanged',0),
            'hideOmittedItems' => $request->input('hideOmittedItems',0),
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );
        // Get Query
        $results = $this->model->getRows($params);
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('reviewvendorimportlist/data');
        $this->data['param'] = $params;
        $this->data['topMessage'] = @$results['topMessage'];
        $this->data['message'] = @$results['message'];
        $this->data['bottomMessage'] = @$results['bottomMessage'];
        $results['rows'] = $this->model->setRowStatus($results['rows']);
        $this->data['rowData'] = $this->model->addProductSubTypes($results['rows']);
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
        $this->data['product_import_vendor_id'] = $request->input('product_import_vendor_id');
        $this->data['importVendorListId'] = 0;
        $this->data['vendors_list'] = $this->model->getImportVendors($request->input('product_import_vendor_id'));
        if (!empty($this->data['rowData'])) {
            $this->data['importVendorListId'] = $this->data['rowData']['0']->import_vendor_id;
        }

        if ($request->has('omit_vendor_list_id') && !$request->has('hideUnchanged')){
            $this->data['importVendorListId'] = $request->input('omit_vendor_list_id');
            $this->data['resetOmit'] = [
                'selectedList'=> $request->input('omit_vendor_list_id'),
                'buttonText' => 'Remove from omitted filter'
            ];
        }
        if($request->has('hideUnchanged')){
            $this->data['importVendorListId'] = $request->input('omit_vendor_list_id');
        }

        $this->data['expense_categories'] = $this->model->getExpenseCategoryGroups();

        $this->data['productTypes'] = $this->model->getProductType();
        $this->data['hideUnchanged'] = $request->input('hideUnchanged',0);
        $this->data['hideOmittedItems'] = $request->input('hideOmittedItems',0);
        $this->data['showOnlyOmitted'] = $request->input('showOnlyOmitted',0);
// Render into template
        return view('reviewvendorimportlist.table', $this->data);

    }


    function getUpdate(Request $request, $id = null)
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
            $this->data['row'] = $this->model->getColumnTable('vendor_import_products');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('reviewvendorimportlist.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('vendor_import_products');
        }

        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata'] = \SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('reviewvendorimportlist.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM vendor_import_products ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO vendor_import_products (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM vendor_import_products WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {

        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('vendor_import_products');

            $id = $this->model->insertRow($data, $request->input('id'));

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

    function postSaveData(Request $request, $id = 0)
    {
        //check if type of all product is selected
//        if(count($request->input('parent_id')) != count(array_filter($request->input('prod_type_id')))){
//            return response()->json(array(
//                'status' => 'error',
//                'message' => 'Please set type of all products.'
//            ));
//        }
//
//        //check if expense categories of all product is selected
//        if(count($request->input('parent_id')) != count(array_filter($request->input('expense_category')))){
//            return response()->json(array(
//                'status' => 'error',
//                'message' => 'Please set expense categories of all products.'
//            ));
//        }

        $parentIds = $request->input('parent_id');
        $prodTypeId = $request->input('prod_type_id');
        $expenseCategory = $request->input('expense_category');

        $itemIds = $request->input('item_id');


        $retailPrice = $request->input('retail_price');
        $ticketValue = $request->input('ticket_value');
        $prodSubTypeId = $request->input('prod_sub_type_id');

        $isReserved = $request->input('is_reserved');
        $allowNegativeReserveQty = $request->input('allow_negative_reserve_qty');
        $inactive = $request->input('inactive');
        $inDevelopment = $request->input('in_development');
        $hotItem = $request->input('hot_item');
        $excludeExport = $request->input('exclude_export');

            if (count($itemIds) > 0) {

                for ($i = 0; $i < count($itemIds); $i++) {

                    $data = [
                        'prod_type_id' => $prodTypeId[$i],
                        'prod_sub_type_id' => $prodSubTypeId[$i],
                        'retail_price' => $retailPrice[$i],
                        'expense_category' => $expenseCategory[$i],
                        'is_reserved' => $isReserved[$i],
                        'allow_negative_reserve_qty' => $allowNegativeReserveQty[$i],
                        'inactive' => $inactive[$i],
                        'in_development' => $inDevelopment[$i],
                        'hot_item' => $hotItem[$i],
                        'exclude_export' => $excludeExport[$i],
                        'ticket_value' =>$ticketValue[$i],
                    ];

                    if ($itemIds[$i] == 0) {
                        $product = Reviewvendorimportlist::find($parentIds[$i])->toArray();

                        $data['sku'] = $product['sku'];
                        $data['upc_barcode'] = $product['upc_barcode'];
                        $data['vendor_description'] = $product['vendor_description'];
                        $data['item_description'] = $product['item_description'];
                        $data['netsuite_description'] = $product['netsuite_description'];
                        $data['details'] = $product['details'];
                        $data['num_items'] = $product['num_items'];
                        $data['vendor_id'] = $product['vendor_id'];
                        $data['unit_price'] = $product['unit_price'];
                        $data['case_price'] = $product['case_price'];
                        $data['reserved_qty'] = $product['reserved_qty'];
                        $data['reserved_qty_reason'] = $product['reserved_qty_reason'];
                        $data['variation_id'] = $product['variation_id'];
                        $data['min_order_amt'] = $product['min_order_amt'];
                        $data['img'] = $product['img'];
                        $data['limit_to_loc_group_id'] = $product['limit_to_loc_group_id'];
                        $data['date_added'] = date('Y-m-d H:s:i');
                        $data['created_at'] = date('Y-m-d H:s:i');
                        $data['import_vendor_id'] = $product['import_vendor_id'];
                        $data['product_id'] = 0;
                        $data['is_imported'] = 0;
                        $data['imported_by'] = 0;
                        $data['is_omitted'] = $product['is_omitted'];
                        $data['is_updated'] = $product['is_updated'];
                        $data['is_new'] = $product['is_new'];
                    }

                    $itemIds[$i] = $itemIds[$i] == 0 ? null : $itemIds[$i];
                    $id = $this->model->insertRow($data, $itemIds[$i]);

                }

                return response()->json(array(
                    'status' => 'success',
                    'message' => \Lang::get('core.note_success')
                ));
            } else {
                return response()->json(array(
                    'status' => 'error',
                    'message' => \Lang::get('core.note_error')
                ));
            }

    }

    public function postDelete(Request $request)
    {
        $id = $request->input('id');
        // delete multipe rows
        if ($id > 0) {
            $this->model->where('import_vendor_id',$id)->where('is_omitted','0')->delete();
            \DB::table('import_vendors')->where('id',$id)->delete();


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

    public function getAllProductSubTypes(Request $request)
    {
        $productTypeId = (int)$request->input('id');
        $productSubTypes = $this->model->getProductAllSubTypes();

        $filteredTypes = $productSubTypes->where('request_type_id', $productTypeId)->toArray();

        $types = [];
        foreach ($filteredTypes as $filteredType){
            $types[] = [
                'id'=>$filteredType['id'],
                'type_description' => $filteredType['type_description'],
            ];
        }

        return response()->json($types);
    }

    public function postOmit(Request $request){
        $importItemIds = $request->input('ids');
       $this->model->whereIn('id',$importItemIds)->update(['is_omitted'=>1]);

        return response()->json(array(
            'status' => 'success',
            'message' => "Items has been Omitted Successfully."
        ));

    }

    public function postUnomit(Request $request){
        $importItemIds = $request->input('ids');
        $vendorListId = $request->input('selectedList');
        $this->model->whereIn('id',$importItemIds)->update(['is_omitted'=>0]);

        return response()->json(array(
            'status' => 'success',
            'message' => "Items has been Omitted Successfully."
        ));

    }

    public function postUpdateProductListModule(Request $request){

        $isUpdated = $this->model->updateProductModule($request->input('id'));

        return $isUpdated;
    }
}