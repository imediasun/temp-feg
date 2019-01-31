<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Sximo\Module;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Session;
use Validator, Input, Redirect;
use DB;
use App\Models\VendorImportSchedule;
use Illuminate\Support\Facades\Auth;
use App\Library\VendorProductsImportHelper;

class VendorController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    public $module = 'vendor';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Vendor();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);
        $this->module_id = Module::name2id($this->module);
        $this->pass = \FEGSPass::getMyPass($this->module_id);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'vendor',
            'pageUrl' => url('vendor'),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['country_id' => 'countries.country_name'];
        $this->sortUnMapping = ['countries.country_name' => 'country_id'];


    }

    public function getSearchFilterQuery($customQueryString = null) {


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '']);
        $skipFilters = ['search_all_fields'];
        $mergeFilters = [];
        extract($globalSearchFilter); //search_all_fields

        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        //Vendor name, phone, contact, billing account, email, email 2,
        //website, games contact name, games contact phone, status, created on and updated on.
        if (!empty($search_all_fields)) {
            $searchFields = [
                'vendor.vendor_name',
//                'vendor.street1',
//                'vendor.street2',
//                'vendor.city',
//                'vendor.state',
//                'vendor.zip',
                'vendor.phone',
                'vendor.contact',
                'vendor.email',
                'vendor.email_2',
                'vendor.website',
                'vendor.bill_account_num',
                'vendor.games_contact_name',
                'vendor.games_contact_email',
                'vendor.games_contact_phone',
            ];
            $dateSearchFields = [
                'vendor.created_at',
                'vendor.updated_at',
            ];
            $dates = \FEGHelp::probeDatesInSearchQuery($search_all_fields);
            $searchInput = ['query' => $search_all_fields, 'dateQuery' => $dates,
                'fields' => $searchFields, 'dateFields' => $dateSearchFields];

        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);
        $filter .= $this->buildSearch($trimmedSearchQuery);

//
//        // Special filter for default active status
//        if (stripos($filter, "vendor.status") === false ) {
//            $filter .= " AND vendor.status = '1'";
//        }
//        // special filter for default no-hidden status
//        if (stripos($filter, "vendor.hide") === false ) {
//            $filter .= " AND vendor.hide = '0'";
//        }
        // and showing both active and inactive vendors
        if (stripos($filter, "AND updated_by_user") >= 0 ) {
            $filter = str_replace("AND updated_by_user", "AND users.id", $filter);
        }
        if (stripos($filter, "AND vendor.status = '-1'") >= 0 ) {
            $filter = str_replace("AND vendor.status = '-1'", "", $filter);
        }
        // showing both hidden and not hidden vendors
        if (stripos($filter, "AND vendor.hide = '-1'") >= 0 ) {
            $filter = str_replace("AND vendor.hide = '-1'", "", $filter);
        }

        return $filter;

    }

    public function getIndex()
    {

        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['access'] = $this->access;
        return view('vendor.index', $this->data);
    }


    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'vendor')->pluck('module_id');
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
        $filter = $this->getSearchFilterQuery();
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

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
        // Get Query
        $results = $this->model->getRows($params);
        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

        foreach ($results['rows'] as $result) {

            if ($result->partner_hide == 1) {
                $result->partner_hide = "Yes";

            } else {
                $result->partner_hide = "No";
            }
            if ($result->isgame == 1) {
                $result->isgame = "Yes";

            } else {
                $result->isgame = "No";
            }
            if ($result->ismerch == 1) {
                $result->ismerch = "Yes";

            } else {
                $result->ismerch = "No";
            }
        }

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }


        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('vendor/data');

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
        //  return response()->json($this->data);

        $this->data['viewProductListExportOption'] = false;
        if (!empty($this->pass['display options to export vendor products'])) {
            $userGroups = !empty($this->pass['display options to export vendor products']->group_ids) ? explode(",", $this->pass['display options to export vendor products']->group_ids) : [];
            $users = !empty($this->pass['display options to export vendor products']->user_ids) ? explode(",", $this->pass['display options to export vendor products']->user_ids) : [];

            $this->data['viewProductListExportOption'] = (in_array(Session::get('uid'), $users)
                || in_array(Session::get('gid'), $userGroups));
        }
        return view('vendor.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('vendor');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['countries'] = \DB::select('select * from countries where active=1 order by id asc ');

        $this->data['id'] = $id;

        return view('vendor.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        //Due to vendor status and hide issue we don't using sximo getRow method. Vendor model where clauses have these conditions
        $row = $this->getVendor($id);
        if ($row) {
            if ($row->partner_hide == 1) {
                $row->partner_hide = "Yes";

            } else {
                $row->partner_hide = "No";
            }
            if ($row->isgame == 1) {
                $row->isgame = "Yes";

            } else {
                $row->isgame = "No";
            }
            if ($row->ismerch == 1) {
                $row->ismerch = "Yes";

            } else {
                $row->ismerch = "No";
            }

            $this->data['row'] = $row;
        } else {
            $this->data['row'] = (object)$this->model->getColumnTable('vendor');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('vendor.view', $this->data);
    }

    private function getVendor($id) {

        $result = \DB::select('
        SELECT vendor.*,countries.country_name FROM vendor left join countries on countries.id = vendor.country_id
        WHERE vendor.id IS NOT NULL
        AND vendor.id = '.$id.' 
        ');
        if (count($result) <= 0) {
            $result = array();
        } else {
            $result = $result[0];
        }
        return $result;
    }

    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM vendor ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO vendor (" . implode(",", $columns) . ") ";

        $columns[0] = "CONCAT('copy ',vendor_name)";
        $sql .= " SELECT " . implode(",", $columns) . " FROM vendor WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {

        $rules = $this->validateForm();
//        $v = Validator::make($request->all(), [
//            'Vendor' => 'required|unique:vendor|max:100|min:5'
//        ]);

//        $vendor = \App\Models\Vendor
//            ::where("vendor_name", "=", $request->input('vendor_name'))->first();

//        if($vendor != null) {


//            $v->errors()->add('Duplicate', 'Duplicate Vendor found!');

//            die("Duplicate vendor found ");
//            return redirect('Create-Category')
//                ->withErrors($v)
//                ->withInput();

//        }

        $rules["vendor_name"] = "required|unique:vendor,vendor_name," . $id;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            //No one tested for 1 year that edit was not saving missing data
            $data = $this->validatePost('vendor');
            $data['updated_by'] = \Session::get('uid');
            $data['hide'] = $request->get('hide') == "1" ?1:0;
            if (!empty($request->get('status'))) {
                $data['status'] = $request->get('status') == "1" ? 1 : 0;
            }
            if (!empty($data['website'])) {
                if (preg_match('/^https?\:\/\//', trim($data['website'])) !== 1) {
                    $data['website'] = 'http://' . trim($data['website']);
                }
            }
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
    public function getItemcheck(Request $request)
    {
        if($request->id == 'NONE' || empty($request->id))
        {
            return 0;
        }
        $module = str_replace(' ', '', "\App\Models\ ".$request->module);
        $columns = explode('|', $request->column);
        $result = '';
        $count = count($columns);
        if($request->module == 'Vendor')
        {
            $item = DB::select("SELECT ".implode(',' , $columns)."  FROM vendor WHERE id=$request->id AND ($request->check = 0 OR hide=1)");
            if(!empty($item))
            {
                $item = $item[0];
            }

        }
        else
        {
            $item = $module::where('id',$request->id)->where($request->check,$request->inverse)->first()?$module::where('id',$request->id)->where($request->check,$request->inverse)->first():0;
        }
        if(!empty($item))
        {
            $i = 1;
            foreach ($columns as $column)
            {
                $result .= $item->$column;
                if($i < $count)
                {
                    $result .= ' | ';
                }
                $i++;
            }
        }
        else
        {
            $result = 0;
        }
        return $result;
    }
    function postTrigger(Request $request)
    {
        $isActive = $request->get('isActive');
        $field = $request->get('field');
        $vendorId = $request->get('vendorId');
        if ($isActive == "true") {
            $update = \DB::update('update vendor set '.$field.' = 1 where id=' . $vendorId);
        }
        else
        {
            $update = \DB::update('update vendor set '.$field.' = 0 where id=' . $vendorId);
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

    /**
     * @param $id
     * @return mixed
     */
    function postSendList($id)
    {
        $row = $this->getVendor($id);
        
        if($row->email == '' && $row->email_2 == ''){
            return response()->json(array(
                'status' => 'error',
                'message' => 'Vendor Email does not exist.'
            ));
        }
        $vendorEmail = '';
        if(!empty($row->email) && $row->email !='') {
            $vendorEmail[] = $row->email; //get vendor mail address one
        }
        if(!empty($row->email_2) && $row->email_2 !='') {
            $vendorEmail[] = $row->email_2; //get vendor mail address one
        }
        if(!empty($vendorEmail)) {
            if (count($vendorEmail) > 1) {
                $vendorEmail = array_unique($vendorEmail);
            }
        }
        $vendorEmail = !empty($vendorEmail) ? implode(',',$vendorEmail):$vendorEmail;
        
        $response = VendorProductsImportHelper::exportExcel($id, $vendorEmail);
        if($response){
//            dd($response);
            return response()->json(array(
                'status' => 'success',
                'message' => 'Mail sent successfully.'
            ));
        }else{
            return response()->json(array(
                'status' => 'error',
                'message' => 'Mail sending failed.'
            ));
        }
    }

    /**
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    function getScheduleList(Request $request, $id = null)
    {

        $this->data['row'] = $this->model->getColumnTable('vendor_import_schedules');

        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['vendorId'] = $id;

        $schedule = VendorImportSchedule::where('vendor_id', $id)->first();
        $this->data['schedule'] = $schedule;
        return view('vendor.import_list_schedule', $this->data);
    }


    /**
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    function postVendorImportSchedule(Request $request, $id = null)
    {
//        dd($request->all());
        $schedule = new VendorImportSchedule();
        $response = $schedule->createOrUpdateSchedule($id, Auth::user()->id, $request->all());
        if ($response) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Record updated successfully.'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in updating record.'
            ));
        }

    }

    /**
     * @return mixed
     */
    function postClearAllSchedulesList(){
        $schedule = new VendorImportSchedule();
        $clear = $schedule->truncate();
        if ($clear) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'All vendors schedule cleared successfully.'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in updating record.'
            ));
        }
    }


}
