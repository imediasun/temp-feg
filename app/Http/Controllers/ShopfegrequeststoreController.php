<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Shopfegrequeststore;
use App\Models\Addtocart;
use \App\Models\Sximo\Module;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, URL;

class ShopfegrequeststoreController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'shopfegrequeststore';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Shopfegrequeststore();
        $this->addToCartModel = new Addtocart();
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

    public function getIndex()
    {dd(Shopfegrequeststore::get_location_group_ids(\SiteHelpers::getLocationDetails(\Session::get('uid'))));
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('shopfegrequeststore.index', $this->data);
    }

    public function postData(Request $request)
    {


        $module_id = \DB::table('tb_module')->where('module_name', '=', 'shopfegrequeststore')->pluck('module_id');
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

        // Get order_type search filter value
        $priceRangeFilter = $this->model->getSearchFiltersAsArray('', array('price_range' => ''));
        extract($priceRangeFilter);
        
        // rebuild search query skipping 'price_range' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery(null, array('price_range'));

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($trimmedSearchQuery);
        // add special price range query
        if (!empty($price_range)) {
            $pr1 = '';
            if (isset($price_range['value'])) {
                $pr1 = preg_replace('/[^\d\.\-]|\s/', '', ''.$price_range['value']);
            }            
            $pr2 = '';
            if (isset($price_range['value2'])) {
                $pr2 = preg_replace('/[^\d\.\-]|\s/', '', ''.$price_range['value2']);
            }
            if (is_numeric($pr1) || is_numeric($pr2)) {
                if ($pr2 === '') {
                    $pr2 = $pr1;
                }
                if ($pr1 === '') {
                    $pr1 = $pr2;
                }
                $pr1 = (float)$pr1;
                $pr2 = (float)$pr2;

                $filter .= " AND (
                                (products.unit_price   BETWEEN $pr1 AND $pr2) 
                             OR (products.case_price   BETWEEN $pr1 AND $pr2) 
                             OR (products.retail_price BETWEEN $pr1 AND $pr2) 
                            )";
            }
        }
        
        // Filter Search for query
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


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
        $type = $request->get('type');
        $this->data['type'] = $type;
        $active_inactive = $request->get('active_inactive');
        $this->data['active_inactive'] = $active_inactive;
        \Session::put('active_inactive', $active_inactive);
        $order_type = $request->get('order_type');
        $this->data['order_type'] = $order_type;
        $product_type = $request->get('product_type');
        $this->data['product_type'] = $product_type;
        $cond = array('type' => $type, 'active_inactive' => $active_inactive, 'order_type' => $order_type, 'product_type' => $product_type,);
        $results = $this->model->getRows($params, $cond);

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total']) {
            $params['limit'] = $results['total'];
        }
        if ($results['total'] === 0) {
            $params['limit'] = 1;
        }

        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('shopfegrequeststore/data');
        $this->data['param'] = $params;
        $this->data['rowData'] = $results['rows'];
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
        $this->data['cart'] = $this->model->shoppingCart();
// Render into template
        return view('shopfegrequeststore.table', $this->data);

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

        return view('shopfegrequeststore.form', $this->data);
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
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('shopfegrequeststore.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM products ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO products (" . implode(",", $columns) . ") ";
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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('products',true);
            $id = $this->model->insertRow($data, $id);

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

    function getRecentlyadded()
    {
        if (false) {
            redirect('fegsys/home', 'refresh');
        } else {
            $this->data['recent_products'] = $this->model->getRecentlyAddedProduct();
            return view('shopfegrequeststore.recentlyAddedProducts', $this->data);
        }
    }

    function getNewGraphicRequest()
    {
        return view('shopfegrequeststore.newgraphicrequest', $this->data);
    }

    function postNewgraphic(Request $request)
    {
        $rules['img'] = 'mimes:jpeg,gif,png';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $item_id = $request->get('item_id');
            $graphics_description = $request->get('graphics_description');
            $graphics_description = str_replace('"', '', $graphics_description);
            $qty = $request->get('qty');
            $date_needed = date("Y-m-d", strtotime($request->get('date_needed')));
            $game_info = $request->get('game_info');
            $locationId = $request->get('location_name');
            $statusId = 1;
            $now = date('Y-m-d');
            $filesnames = $request->get('myInputs');
            if(!empty($filesnames)) {
                $filesnames = implode(',', $filesnames);
              }
            $data = array('location_id' => $locationId, 'request_user_id' => \Session::get('uid'), 'request_date' => $now, 'need_by_date' => $date_needed, 'description' => $game_info . ' - ' . $graphics_description, 'qty' => $qty, 'status_id' => $statusId, 'img' => $filesnames);
            $last_insert_id = $this->model->newGraphicRequest($data);
            

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.request_sent_success')
            ));

        } else {

        }

    }

    function getPopupCart($productId = null, $qty = 0)
    {
        $current_total_cart = \Session::get('total_cart');
        \Session::put('productId', $productId);
        $cartData = $this->addToCartModel->popupCartData($productId, null, $qty);
        $total_cart = $this->addToCartModel->totallyRecordInCart();
        if ($current_total_cart == $total_cart[0]->total) {

            $message = \Lang::get('core.already_add_to_cart');
        } else {
            $message = \Lang::get('core.add_to_cart');
        }
        \Session::put('total_cart', $total_cart[0]->total);
        return response()->json(array(
            'status' => 'success',
            'message' => $message,
            'total_cart' => $total_cart[0]->total
        ));
        //return redirect('addtocart')->with(array('productId'=>$productId));

    }

    public function postUploadfiles()
    {
        $last_id = \DB::select("select max(id) as id from new_graphics_request");

        $last_id = $last_id[0]->id + 1;
        $input = \Input::all();
        $rules['file'] = 'mimes:jpeg,gif,png';

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return \Response::json(array('error'));
        }

        $destinationPath = public_path() . '/uploads/newGraphic'; // upload path

        $extension = \Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = $last_id . "_" . rand(111, 999) . '.' . $extension; // renameing image
        $upload_success = \Input::file('file')->move($destinationPath, $fileName); // uploading file to given path

        if ($upload_success) {
            return $fileName;
        }
    }
}
