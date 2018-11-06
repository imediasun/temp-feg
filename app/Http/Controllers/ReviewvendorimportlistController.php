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

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('reviewvendorimportlist.index', $this->data);
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
        $this->data['importVendorListId'] = 0;
        $this->data['vendors_list'] = $this->model->getImportVendors();
        if (!empty($this->data['rowData'])) {
            $this->data['importVendorListId'] = $this->data['rowData']['0']->import_vendor_id;
        }
        $this->data['expense_categories'] = $this->model->getExpenseCategoryGroups();

        $this->data['productTypes'] = $this->model->getProductType();
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
        $itemIds = $request->input('item_id');
        $parentIds = $request->input('parent_id');
        $prodTypeId = $request->input('prod_type_id');
        $retailPrice = $request->input('retail_price');
        $prodSubTypeId = $request->input('prod_sub_type_id');
        $expenseCategory = $request->input('expense_category');
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
                        $data['ticket_value'] = $product['ticket_value'];
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
                        $data['is_omitted'] = 0;

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

    public function getAllProductSubTypes($productTypeId = 0)
    {
        $productTypeId = (int)$productTypeId;
        $productSubTypes = $this->model->getProductAllSubTypes();

        $filteredTypes = $productSubTypes->where('request_type_id', $productTypeId)->toArray();
        return response()->json($filteredTypes);
    }
}