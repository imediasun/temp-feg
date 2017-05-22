<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ProductController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'product';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Product();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'product',
            'pageUrl' => url('product'),
            'return' => self::returnUrl()
        );


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
                    'O.order_type',
                    'T.type_description',
                    'vendor.vendor_name',
                    'products.vendor_description',
                    'products.case_price',
                    'products.retail_price',
                    'products.unit_price',
                    'T.type_description',
                    'products.expense_category',
                    'products.sku',
                    'products.size',
                    'products.item_description',
                    'products.ticket_value'
                ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);


        return $filter;
    }
    
    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('product.index', $this->data);
    }

    public function postData(Request $request)
    {

        $prod_list_type = isset($_GET['prod_list_type']) ? $_GET['prod_list_type'] : '';
        $active = isset($_GET['active']) ? $_GET['active'] : '';
        $sub_type = isset($_GET['sub_type']) ? $_GET['sub_type'] : '';


        $module_id = \DB::table('tb_module')->where('module_name', '=', 'product')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
            \Session::put('config_id',$config_id);
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
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();//(!is_null($request->input('search')) ? $this->buildSearch() : '');


        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),

        );
        // Get Query
        if ($prod_list_type) {
            $this->data['product_list_type'] = $prod_list_type;
            $this->data['active_prod'] = $active;
        } else {
            $this->data['product_list_type'] = 'select';
            $this->data['active_prod'] = 0;
        }
        $this->data['sub_type']=$sub_type;
        $results = $this->model->getRows($params, $prod_list_type, $active,$sub_type);

        $rows = $results['rows'];

        foreach ($rows as $index => $data) {
            if ($data->is_reserved == 1) {
                $data->is_reserved = "Yes";

            } else {
                $data->is_reserved = "No";
            }
            if ($data->inactive == 1) {
                $data->inactive = "Yes";

            } else {
                $data->inactive = "No";
            }
            if ($data->hot_item == 1) {
                $data->hot_item = "Yes";

            } else {
                $data->hot_item = "No";
            }
            /*
			$product_type = \DB::select("Select product_type FROM product_type WHERE id = '".$data->prod_type_id ."'");
			$rows[$index]->prod_type_id = (isset($product_type[0]->product_type) ? $product_type[0]->product_type : '');
			$product_sub_type = \DB::select("Select product_type FROM product_type WHERE id = ".$data->prod_sub_type_id ."");
			$rows[$index]->prod_sub_type_id = (isset($product_sub_type[0]->product_type) ? $product_sub_type[0]->product_type : '');

           $vendor = \DB::select("Select vendor_name FROM vendor WHERE id = ".htmlentities($data->vendor_id) ."");
			$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');
            */
        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));

        $pagination->setPath('product/data');


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
        // Render into template
        return view('product.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('products');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('product.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('products');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('product.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM products ") as $column) {

            if ($column->Field != 'id')
                $columns[] = $column->Field;

        }
        $toCopy = implode(",", $request->input('ids'));

        $sql = "INSERT INTO products (" . implode(",", $columns) . ") ";
        $columns[1] = "CONCAT('copy ',vendor_description)";
        $sql .= " SELECT " . implode(",", $columns) . " FROM products WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);

        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $rules = $this->validateForm();
        $rules['img'] = 'mimes:jpeg,gif,png';
        $rules['sku'] = 'required|unique:products,sku,'.$id;
        $rules['expense_category'] = 'required|numeric|min:0';
        $validator = Validator::make($request->all(), $rules);
        $retail_price = $request->get('retail_price');
        if($request->get('prod_type_id') != 8)
        {
            $retail_price=0.000;
        }
        if ($validator->passes()) {
            if ($id == 0) {
                $data = $this->validatePost('products');
                $data['retail_price']=$retail_price;
                $id = $this->model->insertRow($data, $request->input('id'));
            } else {
                //for inline editing all fields do not get saved
                $data = $this->validatePost('products',true);
                $data['retail_price']=$retail_price;
                $id = $this->model->insertRow($data, $id);
            }
            /*
			$updates = array();
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $destinationPath = './uploads/products/';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename = $id . '.' . $extension;
                $uploadSuccess = $file->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $updates['img'] = $newfilename;
                }
                $this->model->insertRow($updates, $id);
            }
			*/
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

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
            die;

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

    function getUpload($id = NULL)
    {
        $data['img'] = \DB::table('products')->where('id', $id)->pluck('img');
        $data['return'] = "";
        return view('product.upload', $data);
    }

    function postListcsv(Request $request)
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-'.$exportId;
            \Session::put($exportSessionID, microtime(true));
        }
        
        $vendor_id = $request->vendor_id;
        $rows = $this->model->getVendorPorductlist($vendor_id);
        $fields = array('Vendor', 'Description', 'Sku', 'Unit Price', 'Item Per Case', 'Case Price', 'Ticket Value', 'Order Type', 'Product Type', 'INACTIVE');
        $this->data['pageTitle'] = 'ProductList_';
        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'move',
            'title' => $this->data['pageTitle'],
        );
        return view('product.csvhistory', $content);
    }


    function postUpload(Request $request)
    {

        $files = array('img' => Input::file('img'));
        // setting up rules
        $rules = array('img' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);;

        } else {
            $updates = array();
            $file = $request->file('img');
            $destinationPath = './uploads/products/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . '.' . $extension;
            $uploadSuccess = $request->file('img')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $updates['img'] = $newfilename;
            }
            $this->model->insertRow($updates, $id);
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        }


    }

    function getTest()
    {
        $row = \DB::select("select id,img from products where id > 2820");
        $img = "";
        foreach ($row as $r) {
            if (!empty($r->img)) {
                $img = $r->id . ".jpg";
                \DB::update("update products set img='" . $img . "'where id=" . $r->id);
            }
        }
    }

    function postTrigger(Request $request)
    {
        $isActive = $request->get('isActive');
        $productId = $request->get('productId');
        if ($isActive == "true") {
            $update = \DB::update('update products set inactive = 1 where id=' . $productId);
        }
        else
         {
            $update = \DB::update('update products set inactive = 0,in_development = 0 where id=' . $productId);
             /*if($update &&  \Session::get('product_type') == "productsindevelopment")
             {
                 \DB::update('update products set in_development = 0 where id=' . $productId);
             }*/
        }
        if ($update) {
            return response()->json(array(
                'status' => 'success'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in Activation'
            ));
        }
    }


}
