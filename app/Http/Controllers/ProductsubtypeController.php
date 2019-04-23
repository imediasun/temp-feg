<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Ordertyperestrictions;
use App\Models\product;
use App\Models\Productsubtype;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect;

class ProductsubtypeController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    public $module = 'productsubtype';
    static $per_page = '10';
    protected $L = null;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Productsubtype();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'productsubtype',
            'pageUrl' => url('productsubtype'),
            'return' => self::returnUrl()
        );

        $this->sortMapping = ['order_type' => 'order_type.order_type', 'product_type' => 'product_type.type_description'];
    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('productsubtype.index', $this->data);
    }


    function getComboselect(Request $request)
    {

        if ($request->ajax() == true && \Auth::check() == true) {
            $param = explode(':', $request->input('filter'));
            $parent = (!is_null($request->input('parent')) ? $request->input('parent') : null);

            $limit = (!is_null($request->input('limit')) ? $request->input('limit') : null);
            $delimiter = empty($request->input('delimiter')) ? ' ' : $request->input('delimiter');
            $assignedLocation = $param[0] == 'location' && strtolower(''. @$request->input('assigned')) == 'me';

            if ($assignedLocation) {
                $rows = $this->model->getUserAssignedLocation();
            }
            else {
                $rows = $this->model->getComboselect($param, $limit, $parent);
            }

            $items = array();

            $fields = explode("|", $param[2]);
            foreach ($rows as $row) {
                $value = "";
                $values = array();
                foreach ($fields as $item => $val) {
                    if ($val != "") {
                        $values[] = $row->$val;
                    }
                    $value = implode($delimiter, $values);
                }
                $items[] = array($row->$param['1'], $value);

            }
            return json_encode($items);
        } else {
            return json_encode(array('OMG' => " Ops .. Cant access the page !"));
        }
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'productsubtype')->pluck('module_id');
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
        $filter = $this->getSearchFilterQuery();//(!is_null($request->input('search')) ? $this->buildSearch() : '');

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
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('productsubtype/data');
        $this->data['param'] = $params;
        $this->data['topMessage'] = @$results['topMessage'];
        $this->data['message'] = @$results['message'];
        $this->data['bottomMessage'] = @$results['bottomMessage'];

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

        /**
         *  Exclude order types which having can_request as 0 and r
         */
        $this->data['product_type_ids_to_be_excluded']      = Ordertyperestrictions::where('can_request', 0)->lists('id')->toArray();
        $this->data['product_sub_type_ids_to_be_excluded'] = [];
        if($request->has('search')){
            $searchQuery = $request->get('search');
            if(str_contains($searchQuery, 'request_type_id:equal:')){
                $searchQueryOrderTypeIdWithPipeSign = explode('request_type_id:equal:', $searchQuery)[1];
                $orderTypeId = explode('|', $searchQueryOrderTypeIdWithPipeSign)[0];
                $this->data['product_sub_type_ids_to_be_excluded']  = ProductType::where('request_type_id', '!=' ,$orderTypeId)
                    ->orWhere(function($q){
                        $q->whereNull('request_type_id')->orWhereNotNull('deleted_at');
                    })->orderBy('product_type', 'asc')->lists('product_type')->toArray();
            }
        }

        // Render into template
        return view('productsubtype.table', $this->data);

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
                'order_type.order_type',
                'product_type.product_type',
            ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);


        return $filter;
    }

    function postRemoval(Request $request, $productSubtypeId)
    {
        /**
         *  All the three commented lines in this method should be un-commented if client says
         *  to show all the sub-types in the sub-type removal modal and update
         *  both the sub-type and product type for the products.
         */
        $newProductSubtype = $request->get('newProductSubtype');
        //$productSubtype = Productsubtype::find($newProductSubtype);
        $deletingProductSubTypeObj = $this->model->find($productSubtypeId);
        $replacingProductSubTypeObj = $this->model->find($newProductSubtype);
        DB::transaction(function() use (
            $productSubtypeId,
            $newProductSubtype,
            $deletingProductSubTypeObj,
            $replacingProductSubTypeObj
        ){
            product::where('prod_sub_type_id', $productSubtypeId)
                ->update([
                    'prod_sub_type_id'=>$newProductSubtype
                ]);
            DB::table('order_contents')
                ->where('prod_sub_type_id', $productSubtypeId)
                ->orWhere('updated_prod_sub_type_id', $productSubtypeId)
                ->update([
                    'updated_prod_sub_type_id'=>$newProductSubtype
                ]);
            Productsubtype::where('id', $productSubtypeId)->delete();

            $L = FEGSystemHelper::setLogger($this->L, "product-subtype-remove.log", "FEGProductSubTypeChange/RemoveLog", "ProductSubTypeChange");
            $L->log('---------------- Start Product Subtype Remove Log ----------------');
            $L->log('User ID: '.auth()->user()->id);
            $L->log('Removed Product SubType: '.$productSubtypeId."  SubType Name: (".$deletingProductSubTypeObj->product_type.")");
            $L->log(
                ($replacingProductSubTypeObj)
                    ?
                'Replacing Product SubType: '.$newProductSubtype."  SubType Name: (".$replacingProductSubTypeObj->product_type.")"
                    :
                'No Product Subtype Replaced the deleting one, as no products were assigned to it.'
            );
            $L->log('================ End Product Subtype Remove Log ==================');
            $L->log('                                                                  ');
        });


        return response()->json([
            'message'   =>  \Lang::get('core.note_success_delete'),
            'status'    =>  'success'
        ]);
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
            $this->data['row'] = $this->model->getColumnTable('product_type');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('productsubtype.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('product_type');
        }

        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata'] = \SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('productsubtype.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM product_type ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO product_type (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM product_type WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $messages = [
            'product_type.required'    => 'Product Sub Type is required!',
        ];
        $request->merge(['type_description' => $request->input('product_type')]);
        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->passes()) {
            $data = $this->validatePost('product_type');
            $exceptionCustomMessages = [
                'SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry'=>'Product Sub Type already exists!'
            ];
            if($id != 0){
                $request->merge(['id'=>$id]);
            }
            try{
                $id = $this->model->insertRow($data, $request->input('id'));
                if($id){
                    if(!$request->input('id') && array_key_exists('request_type_id', $data)){
                        $expenseCategoryMappingObject = DB::table('expense_category_mapping')->where('order_type', $data['request_type_id'])->first();
                        if($expenseCategoryMappingObject)
                        {
                            DB::table('expense_category_mapping')->insert([
                                'order_type'                =>  $data['request_type_id'],
                                'mapped_expense_category'   =>  $expenseCategoryMappingObject->mapped_expense_category,
                                'product_type'              =>  $id,
                            ]);
                        }

                    }
                }

                return response()->json(array(
                    'status' => 'success',
                    'message' => \Lang::get('core.note_success')
                ));

            } catch (\Exception $exception) {

                $errorMessage = '';

                foreach ($exceptionCustomMessages as $key=>$message){
                   if (str_contains($exception->getMessage(), $key)) {
                       $errorMessage = $exceptionCustomMessages[$key];
                   }
                }

                return response()->json(array(
                    'status'    => 'error',
                    'message'   => $errorMessage
                ));
            }


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

    public function postProductsubtypesAlreadyDeleted(Request $request){
        $productsubtype = $request->input('productsubtype');
        $ordertype = $request->input('ordertype');
        $id = $request->input('id');

        $productsubtypeNotDeleted = $this->model->where('id', '!=', $id)
            ->where('product_type', $productsubtype)
            ->where('request_type_id', $ordertype)
            ->first();

        if($productsubtypeNotDeleted){
            return [
                'status'=>'error',
                'message'=>'Product SubType Already exists'
            ];
        }

        $query = $this->model->onlyTrashed()
            ->where('product_type', $productsubtype)
            ->where('request_type_id', $ordertype);

        $alreadyDeletedRecord = $query->first();

        return [
            'status'=>'success',
            'count'=>$query->count(),
            'alreadyDeletedRecord'=>$alreadyDeletedRecord
        ];
    }

    public function postReactivateProductSubtype(Request $request, $id){
        $productSubType = Productsubtype::onlyTrashed()->where('id', $id)->first();

        $isRestored = 'error';
        $message = \Lang::get('core.reactivation_failure');
        if($productSubType){
            $productSubType->restore();

            $isRestored = 'success';
            $message = \Lang::get('core.reactivation_success');
        }

        return response()->json(array(
            'status' => $isRestored,
            'message' => $message
        ));
    }

}