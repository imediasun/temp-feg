<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Addtocart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Mockery\CountValidator\Exception;
use Validator, Input, Redirect;
use App\Models\Core\Groups;

class AddtocartController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'addtocart';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Addtocart();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'addtocart',
            'pageUrl' => url('addtocart'),
            'return' => self::returnUrl()
        );

    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $productId = \Session::get('productId');
        \Session::put('productId', $productId);
        // $cartData = $this->model->popupCartData(null);
        //$this->data['cartData'] = $cartData;
        // \Session::put('cartData', $cartData);
        $this->data['access'] = $this->access;
        return view('addtocart.index', $this->data);
    }

    public function postData(Request $request)
    {
        if(count(\Session::get('user_locations'))<=0){
            return response()->json(array(
                'status' => 'error',
                'message' => "No location assigned!"
            ));
        }
        
        $productId = \Session::get('productId');
        $cartData = $this->model->popupCartData(null);
        $this->data['cartData'] = $cartData;
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'addtocart')->pluck('module_id');
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
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

        $filter .= ' AND requests.request_user_id='.\Session::get('uid');

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
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('addtocart/data');
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
// Render into template
        return view('addtocart.table', $this->data);

    }


    function getUpdate(Request $request, $id = null, $v = null)
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
            $this->data['row'] = $this->model->getColumnTable('requests');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('addtocart.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('requests');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        return view('addtocart.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM requests ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO requests (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM requests WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {

        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('requests');

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

    public function getCart()
    {
        $this->data['access'] = $this->access;
        $this->data['data'] = \Session::get('data');
        $this->data['id'] = 1;
        return view('addtocart.index', $this->data);
    }

    function getSubmitRequests($new_location = null)
    {

        $now = date('Y-m-d');

        $location_id = \Session::get('selected_location');
        $data['user_level'] = \Session::get('gid');
        /*if ($data['user_level'] == Groups::MERCHANDISE_MANAGER || $data['user_level'] == Groups::FIELD_MANAGER || $data['user_level'] == Groups::OFFICE_MANAGER || $data['user_level'] == Groups::FINANCE_MANAGER || $data['user_level'] == Groups::GUEST || $data['user_level'] == Groups::SUPPER_ADMIN) {
            $statusId = 9; /// 9 IS USED AS AN ARBITRARY DELIMETER TO KEEP CART SEPERATE FROM LOCATIONS' OWN
        } else {
            $statusId = 4;
        }*/
        $statusId = 4;
        if (!empty($new_location)) {
            $query = \DB::select('SELECT product_id,description,qty,status_id,request_type_id FROM requests
                                  WHERE location_id = ' . $location_id . ' AND status_id = 9 AND request_user_id = '.\Session::get('uid'));

            foreach ($query as $row) {
                $insert = array(
                    'product_id' => $row->product_id,
                    'description' => $row->description,
                    'qty' => $row->qty,
                    'request_user_id' => \Session::get('uid'),
                    'request_date' => $now,
                    'location_id' => $new_location,
                    'status_id' => $row->status_id,
                    'request_type_id' => $row->request_type_id
                );
                \DB::table('requests')->insert($insert);
            }
        }
        $update = array('status_id' => 1,
            'request_user_id' => \Session::get('uid'),
            'request_date' => $now);
        \DB::table('requests')->where('location_id', $location_id)->where('status_id', $statusId)->update($update);

        if (empty($new_location)) {
            return Redirect::to('/shopfegrequeststore')->with('messagetext', 'Submitted successfully')->with('msgstatus', 'success');
            \Session::put('total_cart', 0);
            //redirect('fegllc/popupCart', 'refresh');
        } else {
            /* @todo refactor code
             * comment line because $new_location value always come null from addtocart/table.blade
            $this->getChangelocation($new_location);
             */
            return redirect('/shopfegrequeststore/popup-cart/');
        }
    }

    public function getSave($id = null, $qty = null, $vendor_name = null)
    {

        try {
            $data = array('qty' => $qty);
            \DB::table('requests')->where('id', $id)->update($data);
            $vendor_name = str_replace('_', ' ', $vendor_name);

            $updated = $this->model->popupCartData(null, $vendor_name);
            return json_encode(array('vendor_name' => $updated['subtotals'][0]['vendor_name'], 'subtotal' => $updated['subtotals'][0]['vendor_total']));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }
    }

    public function getCartdata()
    {
        $productId = \Session::get('productId');
        $cart_data = $this->model->popupCartData($productId);
        return response()->json($cart_data);
    }

}
