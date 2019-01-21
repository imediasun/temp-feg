<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Addtocart;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Mockery\CountValidator\Exception;
use Validator, Input, Redirect;
use App\Models\Core\Groups;
use App\Http\Controllers\OrderController;
use \App\Models\Sximo\Module;
use App\Models\product;

class AddtocartController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    public $module_id;
    public $pass = [];
    public $module = 'addtocart';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Addtocart();
        $this->info = $this->model->makeInfo($this->module);
        $this->module_id = Module::name2id($this->module);
        $this->access = $this->model->validAccess($this->info['id']);
        $this->pass = \FEGSPass::getPasses($this->module_id, 'module.addtocart.special.allowusers/usergroupstosubmitthepurchaserequestinspiteoftheerrormessage', false);;

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'addtocart',
            'pageUrl' => url('addtocart'),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['prod_sub_type_id' => 'product_type.type_description'];
        $this->sortUnMapping = ['product_type.type_description' => 'prod_sub_type_id'];

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
        $sort = !empty($this->sortMapping) && isset($this->sortMapping[$sort]) ? $this->sortMapping[$sort] : $sort;

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
        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

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
        $this->data['specialPermissions'] = $this->pass;

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
// Render into template
        $addToCart = new Addtocart();
        $this->data['rowData'] = $addToCart->calculateProductTotalAccordingToProductType($this->data['rowData']);
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
            $data = $this->validatePost('requests',true);

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
        $ids = $request->input('ids');
        if(!is_array($request->input('ids'))){
            $ids = [$request->input('ids')];
        }
        if (count($ids) >= 1) {
            $this->model->destroy($ids);

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
        $inputs = \Input::all();
        $products = $inputs['products'];
        $location_id = \Session::get('selected_location');
        $data['user_level'] = \Session::get('gid');

        $statusId = 4;

        $userId = \Session::get('uid');
        $groupId = \Session::get('gid');

        $addToCart = new addtocart();
        if(!$addToCart->hasPermission()) {
            $check = \DB::select("SELECT * FROM requests INNER JOIN products ON (requests.product_id = products.id) WHERE location_id = $location_id AND status_id = 1 AND product_id IN (" . implode(',', $products) . ") group by requests.product_id");
            if (!empty($check)) {
                $productsNames = "<ul style='padding-left: 17px;margin-bottom: 0px;'>";
                $count = count($check);
                foreach ($check as $key => $request) {
                    $productsNames .= "<li>" . addslashes($request->vendor_description) . "</li>";
                }
                $productsNames .= "</ul>";
                return redirect('/addtocart')->with('messagetext', "Another employee at your location has already ordered the following product(s): $productsNames Please remove the duplicate product(s) from your cart to submit your order and contact the head of the department relevant to Order Type to make any further quantity adjustments for this product.")->with('msgstatus', 'error');
            }
        }


        $newRequests = $this->model->getNewRequests($products)->get();
        if($newRequests->count()>0){
            foreach($newRequests as $newRequest){
                $this->model->mergeRequests($newRequest);
            }
        }

        $update = array('status_id' => 1,
            'request_user_id' => \Session::get('uid'),
            'request_date' => $now);
        \DB::table('requests')->where('location_id', $location_id)->where('request_user_id', \Session::get('uid'))->where('status_id', $statusId)->update($update);

        if (empty($new_location)) {
            return Redirect::to('/shopfegrequeststore')->with('messagetext', 'Submitted successfully')->with('msgstatus', 'success');
            \Session::put('total_cart', 0);
        } else {
            /* @todo refactor code
             * comment line because $new_location value always come null from addtocart/table.blade
            $this->getChangelocation($new_location);
             */
            return redirect('/shopfegrequeststore/popup-cart/');
        }
    }

    public function getSave($id = null, $qty = null, $vendor_name = null, $notes = null)
    {
        if(is_null($vendor_name))
            $vendor_name = \request()->get('vendor');

        if(is_null($notes))
            $notes = \request()->get('notes');

        try {
            $data = array('qty' => $qty, 'notes' => $notes);
            \DB::table('requests')->where('id', $id)->update($data);
           // $vendor_name = str_replace('_', ' ', $vendor_name);
            // Bug found while regression testing.If products have vendor name e5_Test_Vendor on updating qty of product in cart it shows error popup

            $updated = $this->model->popupCartData(null, $vendor_name);
            return json_encode(array('vendor_name' => $updated['subtotals'][0]['vendor_name'], 'subtotal' => $updated['subtotals'][0]['vendor_total']));
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }
    }

    public function getCartdata($ajax = true)
    {
        $productId = \Session::get('productId');
        $cart_data = $this->model->popupCartData($productId);

        if($ajax){
            return response()->json($cart_data);
        }else{
            return $cart_data;
        }
    }
    public function postCheckUserPermissions(Request $request){
        $addToCart = new Addtocart();
        $inputs = $request->all();
        // if user/user group has ability to request a product that already been requested by another user from same location

        $qtyCheckMessage = [
            'messagetext' => "",
            'showError' => false,
        ];

        $requestQtyCheck = $this->model->requestQtyFilterCheck($inputs['products']);
        if ($requestQtyCheck->count() > 0) {
            $productsNames = "<ul style='padding-left: 17px;margin-bottom: 0px; text-align:left !important;'>";
            foreach ($requestQtyCheck as $request) {
                $productsNames .= "<li>" . addslashes($request->vendor_description) . " | Reserve Qty = ".$request->productQty." | Already Requested Qty = ". ($request->alreadyRequestedQTY) ." | Remaining Qty = ".$request->remainingQTY."</li>";
            }
            $productsNames .= "</ul>";
            $qtyCheckMessage = [
                'messagetext' => "Your request cannot be submitted because there is not enough reserve qty to allow the purchase.<br /><br /> $productsNames <br />Please reduce the amount requested for purchase below or contact the Merchandise Team.",
                'showError' => $requestQtyCheck->count() > 0,
            ];
        }

        return response()->json([
            'hasPermission'=>$addToCart->hasPermission(),
            'exceptionMessage' =>$addToCart->getsubmittedRequests($inputs['products']),
            'qtyErrorMessage' =>$qtyCheckMessage
        ]);
    }

}
