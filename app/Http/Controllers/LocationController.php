<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEGDBRelationHelpers;
use App\Models\Core\Users;
use App\Models\Location;
use App\Models\Ordertyperestrictions;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator, Input, Redirect;

class LocationController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'location';
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Location();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'location',
            'pageUrl' => url('location'),
            'return' => self::returnUrl()
        );
        $this->sortMapping = [
            'debit_type_id' => 'debittype_c.company',
            'general_manager_id' => 'u1.first_name',
            'merch_contact_id' => 'u6.first_name',
            'regional_manager_id' => 'u5.first_name',
            'technical_user_id' => 'u2.first_name',
            'loc_group_id' => 'loc_group.loc_group_name',
            'company_id' => 'cmp.company_name_long',
            'vp_id' => 'u3.first_name'
        ];
        $this->sortUnMapping = [
            'debittype_c.company' => 'debit_type_id',
            'u1.first_name' => 'general_manager_id',
            'u6.first_name' => 'merch_contact_id',
            'u5.first_name' => 'regional_manager_id',
            'u2.first_name' => 'technical_user_id',
            'loc_group.loc_group_name' => 'loc_group_id',
            'cmp.company_name_long' => 'company_id',
            'u3.first_name' => 'vp_id'
        ];;


    }
    
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null($customQueryString) ? (is_null(Input::get('search')) ? '' : $this->buildSearch()) :
            $this->buildSearch($customQueryString);

        
        // Special filter for default active location
        if (stripos($filter, "location.active") === false ) {
            $filter .= " AND location.active = '1'";
        }
        // and showing both active and inactive location
        if (stripos($filter, "AND location.active = '-1'") >= 0 ) {
            $filter = str_replace("AND location.active = '-1'", "", $filter);
        }

        /*$assignmentFields = \SiteHelpers::getUniqueLocationUserAssignmentMeta('-field');
        foreach($assignmentFields as $field) {
            $filter = str_replace("location.$field", "$field.user_id", $filter);
        } */
        
        return $filter;
    }
    
    public function getIndex(Request $request, $id = 0)
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        $this->data['id'] = $id;
        return view('location.index', $this->data);
    }

    public function postData(Request $request, $id = null)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'location')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
          //  \Session::put('config_id',$config_id);
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
        } else {
            \Session::put('config_id', '0');
        }

        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();
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
        if ($id == null) {
            $results = $this->model->getRows($params);

        } else {
            $results['rows'] = $this->model->getRow($id);
            $results['total'] = 1;
        }
        $results['rows'] = $this->model->setExcludedData($results['rows']);

        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }


        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('location/data');
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
        return view('location.table', $this->data);

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
                
        $rows = $this->model->getRow($id);
        $row = json_decode(json_encode($rows), true);

        if ($row) {
            $row = $row[0];
            $this->data['alreadyExcludedProductTypes']  =   FEGDBRelationHelpers::getCustomRelationRecords($id, location::class, Ordertyperestrictions::class, 1, true)->lists('ordertyperestrictions_id')->toArray();
            $this->data['alreadyExcludedProducts']      =   FEGDBRelationHelpers::getCustomRelationRecords($id, location::class, product::class, 1, true)->lists('product_id')->toArray();
        } else {
            $row = $this->model->getColumnTable('location');
        }

        $this->data['productTypes'] = Ordertyperestrictions::where('can_request', 1)->orderBy('order_type', 'asc')->lists('order_type', 'id');
        $this->data['products']     = product::orderBy('vendor_description', 'asc')->lists('vendor_description', 'id');
        $this->data['row'] = $row;
        
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['id'] = $id;
        \Session::put('location_updated',$id);
        return view('location.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        if ($row) {
            $row = $this->data['row'] = $row[0];

            $location                           =   $this->model->find($id);
            $this->data['excludedProductTypes'] =   $location->excludedProductTypes()->get();
            $this->data['excludedProducts']     =   $location->excludedProducts()->get();
        } else {
            $row = $this->data['row'] = $this->model->getColumnTable('location');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $gridSettings = $this->info['config']['grid'];

        $row->contact_name = '';
        if (!empty($row->contact_id)) {
            $contactDetails = \SiteHelpers::getUserDetails($row->contact_id);
            $row->contact_name = $contactDetails['first_name'] . ' ' . $contactDetails['last_name'];
        }
        
        foreach($gridSettings as $field) {
            $fieldName = $field['field'];
            if($field['view'] == '1' && isset($row->$fieldName)) {
                $conn = (isset($field['conn']) ? $field['conn'] : array());
                $value = \AjaxHelpers::gridFormater($row->$fieldName, $row, $field['attribute'], $conn,isset($field['nodata'])?$field['nodata']:0);
                $this->data['row']->$fieldName = $value;
            }
            $gridSettings[$field['field']] = $field;
        }
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('location.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM location ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO location (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM location WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $rules = $this->validateForm();
        $input_id=$request->get('id');
        //  $locationAssignmentFields = \SiteHelpers::getUniqueLocationUserAssignmentMeta('field-id');
        
        if(\Session::get('location_updated') != $input_id) {
            if (!empty($input_id)) {
                $rules['id'] = 'required|unique:location,id,'.$input_id;
            }
        }
        else{
            if(is_null($id)) {
               $rules['id'] = 'required';
            }
        }
        $rules['location_name'] = 'required|regex:/^[-a-zA-Z0-9\s]+$/';
        $rules['location_name_short'] = 'required|regex:/^[-a-zA-Z0-9\s]+$/';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('location');
            // old id in case the existing location's id has been modified
            $oldId = $id;
            $newId = isset($data['id']) ? $data['id'] : $id;
            if (empty($newId) || empty($oldId) || $oldId == $newId) {
                $oldId = null;
            }
            unset($data['product_type_ids']);
            unset($data['product_ids']);
            $id = $this->model->insertRow($data, $id);

            if($id){

                $product_type_ids   = is_array($request->get('product_type_ids')) ? $request->get('product_type_ids'):[];
                $product_ids        = is_array($request->get('product_ids')) ? $request->get('product_ids'):[];

                FEGDBRelationHelpers::destroyCustomRelation(Ordertyperestrictions::class, Locationgroups::class, 1, 0, $id);
                FEGDBRelationHelpers::destroyCustomRelation(product::class, Locationgroups::class, 1, 0, $id);

                foreach ($product_type_ids as $product_type_id){
                    FEGDBRelationHelpers::insertCustomRelation($product_type_id, $id, Ordertyperestrictions::class, location::class, 1);
                }

                foreach ($product_ids as $product_id){
                    FEGDBRelationHelpers::insertCustomRelation($product_id, $id, product::class, location::class, 1);
                }
            }

            // clean orphan user location assignmens
            \SiteHelpers::addLocationToAllLocationUsers();
            \SiteHelpers::refreshUserLocations(\Session::get('uid'));


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

//            $locationIds = $request->input('ids');
//
//            foreach ($locationIds as $locationId){
//                FEGDBRelationHelpers::destroyCustomRelation(location::class, product::class, 1, 0, $locationId);
//                FEGDBRelationHelpers::destroyCustomRelation(location::class, Ordertyperestrictions::class, 1, 0, $locationId);
//            }

            $this->model->destroy($request->input('ids'));
            
            // clean orphan user location assignmens
            \SiteHelpers::cleanUpUserLocations();
            \SiteHelpers::refreshUserLocations(\Session::get('uid'));
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

    function getDetails(Request $request, $id = 0)
    {
        if ($id > 0) {
            $this->data['location_id'] = $id;
            $this->data['row'] = $this->model->getLocation($id);
            return view('location.details', $this->data);
        } else {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

    }
    function postUpdatelocation(Request $request, $id)
    {
        $data = $request->all();
        array_pop($data);
        array_shift($data);
        $update = \DB::table('location')->where('id', '=', $id)->update($data);
        if ($update) {
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

    function getLocation($id = null)
    {
        $this->data['access'] = $this->access;
        $this->data['id'] = $id;
        return view('location.index', $this->data);
    }
    function getIsLocationAvailable($id)
{
    $isAvailable=\DB::select("select count('id') as count from location where id=$id");
    if($isAvailable[0]->count > 0)
    {
        return response()->json(array(
            'status' => 'error',
            'message' => \Lang::get('*Location Id Exists Already')
        ));
    }
    else {
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('*Location Available')
        ));
    }
}
    function postTrigger(Request $request)
    {
        $isActive = $request->get('isActive');
        $locationId = $request->get('locationId');
        /*$user_locations=\Session::get('user_locations');
        foreach ($user_locations as $key=>$value) {
            if ($value->id == $locationId) {
                unset($user_locations[$key]);
            }
        }*/
        //\Session::put('user_locations',$user_locations);
        $message="";
        if ($isActive == "true") {
            $update = \DB::update('update location set active=1 where id=' . $locationId);
        $message="active";
        } else {
            $update = \DB::update('update location set active=0 where id=' . $locationId);
        $message="inactive";
        }
        \SiteHelpers::addLocationToAllLocationUsers();
        \SiteHelpers::refreshUserLocations(\Session::get('uid'));
        if ($update) {
            return response()->json(array(
                'status' => 'success',
                'message' => $message
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in Activation'
            ));
        }

    }
    public function getUserActiveLocation(){
        $locationId = \Session::get('selected_location');
        $allLocations = Location::all();
        return response()->json(['location_id'=>$locationId,'all_location'=>$allLocations]);
    }

    public function getExcludedProductsAndProductTypes($locationId = null){
       return FEGDBRelationHelpers::getExcludedProductTypeAndExcludedProductIds($locationId, true,  true);
    }
    public function getExcludedProductsAndTypesInline($locationId = 0){

        $products = product::select('id','vendor_description')->orderBy('vendor_description','asc')->get()->toArray();
        $productTypes = Ordertyperestrictions::select('id','order_type as product_type')->where('can_request', 1)->orderBy('order_type','asc')->get()->toArray();
        $excludedProductIds = FEGDBRelationHelpers::getCustomRelationRecords($locationId,location::class,product::class,1)->pluck('product_id')->toArray();
        $excludedProductTypeIds = FEGDBRelationHelpers::getCustomRelationRecords($locationId,location::class,Ordertyperestrictions::class,1)->pluck('ordertyperestrictions_id')->toArray();
        $excludedData = [
            'excluded_product_ids' =>$excludedProductIds,
            'excluded_product_type_ids' => $excludedProductTypeIds
];
        $products = view('location.dropdown',['products' => $products,'optionType'=>'products'])->render();
        $productTypes = view('location.dropdown',['productTypes' => $productTypes,'optionType'=>'productTypes'])->render();
        $data = [
            'products' => $products,
            'productTypes' => $productTypes,
            'ExcludedData' => $excludedData
        ];
        return response()->json($data);

    }
}
