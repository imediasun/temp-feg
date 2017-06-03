<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Managefegrequeststore;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ManagefegrequeststoreController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'managefegrequeststore';
    static $per_page = '10';
    protected $_request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->model = new Managefegrequeststore();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'managefegrequeststore',
            'pageUrl' => url('managefegrequeststore'),
            'return' => self::returnUrl()
        );

        $this->_request = $request;


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['access'] = $this->access;
        return view('managefegrequeststore.index', $this->data);
    }

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

        // End Filter sort and order for query
        // Filter Search for query
        // if (is_null($this->_request->input('search'))) {
        //   $filter = \SiteHelpers::getQueryStringForLocation('requests');
        //} else {
        //   $filter = $this->buildSearch();
        //}
        $filter = $this->getSearchFilterQuery();

        //$filter 	.=  $master['masterFilter'];
        $params = array(
            'params' => $filter,
            'sort' => 'id',
            'order' => 'asc',
        );

        $v1 = $this->_request->get('v1');
        $v2 = $this->_request->get('v2');
        $v3 = $this->_request->get('v3');

        $manageRequestInfo = $this->model->getManageRequestsInfo($v1, $v2, $v3, $filter);

        $this->data['TID'] = $manageRequestInfo['TID'];
        $this->data['LID'] = $manageRequestInfo['LID'];
        $this->data['VID'] = $manageRequestInfo['VID'];

        $view = $this->_request->get('view');
        $cond = array('view' => $view, 'order_type_id' => $manageRequestInfo['TID'], 'location_id' => $manageRequestInfo['LID'], 'vendor_id' => $manageRequestInfo['VID']);
        $this->data['view'] = $view;
        /*
        echo '<pre>';
        print_r($params);
        print_r($cond);
        echo '</pre>';
        exit;
        */
        $results = $this->model->getRows($params, $cond);


        $fields = $info['config']['grid'];
        $rows = $results['rows'];
        $rows = $this->updateDateInAllRows($rows);
        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
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


    public function postData(Request $request)
    {
        $this->getSearchParamsForRedirect();
        $user_level = \Session::get('gid');
        if ($user_level == 2) {
            return redirect('dashboard');
        } else {
            $v1 = $request->get('v1');
            $v2 = $request->get('v2');
            $v3 = $request->get('v3');

            $module_id = \DB::table('tb_module')->where('module_name', '=', 'managefegrequeststore')->pluck('module_id');
            $this->data['module_id'] = $module_id;
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
            // Filter Search for query
            //  if (is_null($request->input('search'))) {
            //     $filter = \SiteHelpers::getQueryStringForLocation('requests');
            //   } else {
            //        $filter = $this->buildSearch();
            //    }
            $filter = $this->getSearchFilterQuery();
            $manageRequestInfo = $this->model->getManageRequestsInfo($v1, $v2, $v3, $filter);
            $this->data['manageRequestInfo'] = $manageRequestInfo;
            $this->data['TID'] = $manageRequestInfo['TID'];
            $this->data['LID'] = $manageRequestInfo['LID'];
            $this->data['VID'] = $manageRequestInfo['VID'];
            $this->data['view'] = $request->get('view');
            $data_options_array = array_flatten($manageRequestInfo['order_dropdown-data']);
             if (!empty($data_options_array) && !in_array($this->data['TID'], $data_options_array)) {
                $this->data['TID'] = "";
                $this->data['LID'] = "";
                $this->data['VID'] = "";
            } if (!empty($manageRequestInfo['loc_options']) && !array_key_exists(!empty($this->data['LID'])?$this->data['LID']:0, $manageRequestInfo['loc_options'])) {
                $this->data['LID'] = "";
                $this->data['VID'] = "";
            } if (!empty($manageRequestInfo['vendor_options']) && !array_key_exists(isset($this->data['VID'])?$this->data['VID']:0,$manageRequestInfo['vendor_options'])) {
                $this->data['VID'] = "";
            }
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
            $view = $request->get('view');
            \Session::put('manage-request-view', $view);
            $isRedirected = session('filter_before_redirect');
            $cond = array('view' => $view, 'order_type_id' => $this->data['TID'], 'location_id' => $this->data['LID'], 'vendor_id' => $this->data['VID']);
            $this->data['view'] = $view;
            $results = $this->model->getRows($params, $cond);
            // Build pagination setting
            $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
            $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
            $pagination->setPath('managefegrequeststore/data');
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
            return view('managefegrequeststore.table', $this->data);
        }
    }

    public function getSearchFilterQuery($customQueryString = null)
    {
        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null($customQueryString) ? (is_null(Input::get('search')) ? '' : $this->buildSearch()) :
            $this->buildSearch($customQueryString);

        // Get assigned locations list as sql query (part)
        //$locationFilter = \SiteHelpers::getQueryStringForLocation('new_graphics_request', 'location_id', [], ' OR new_graphics_request.location_id=0 ');
        $locationFilter = \SiteHelpers::getQueryStringForLocation('requests');
        // if search filter does not have location_id filter
        // add default location filter
        $frontendSearchFilters = $this->model->getSearchFilters(array('location_id' => ''));
        if (empty($frontendSearchFilters['location_id'])) {
            $filter .= $locationFilter;
        }

        return $filter;
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
            $this->data['row'] = $this->model->getColumnTable('requests');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('managefegrequeststore.form', $this->data);
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
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['nodata'] = \SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('managefegrequeststore.view', $this->data);
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

        $rules = array('qty' => 'required', 'status_id' => 'required');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('requests', true);
            $id = $this->model->insertRow($data, $id);
            return response()->json(array(
                'status' => 'success',
                'view' => \Session::get('manage-request-view'),
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

    function postMultirequestorderfill(Request $request)
    {
        $rules = array('location_id' => 'required', 'vendor_id' => 'required');
        $location = $request->get('location_id');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $vendor = $request->get('vendor_id');
            $query = \DB::select('SELECT R.* FROM requests R LEFT JOIN products P ON P.id = R.product_id WHERE R.location_id = "' . $location . '"  AND P.vendor_id = "' . $vendor . '" AND R.status_id = 1 AND R.blocked_at IS NULL');

            //$query = \DB::select('select id from requests where location_id = "' . $location . '" AND status_id = 1 AND product_id IN (Select id from products where id IN (select product_id from requests where location_id = "' . $location . '" AND status_id = 1) And vendor_id = "' . $vendor . '")');
            if (count($query) > 0) {
                $SID = 'SID';
                foreach ($query as $row) {
                    $SID = $SID . '-' . $row->id;
                }
                return Redirect::to('order/submitorder/' . $SID . '-');
            } else {
                $this->data['access'] = $this->access;
                \Session::put('filter_before_redirect','redirect');
                return view('managefegrequeststore.index',$this->data)->with('error' ,'These items are blocked');
                $manageRequestInfo = $this->model->getManageRequestsInfo();
                $this->data['manageRequestInfo'] = $manageRequestInfo;
                return view('managefegrequests.table', $this->data);
            }
        }
    }

    function postDeny(Request $request)
    {
        $request_id = $request->get('request_id');
        $query = \DB::update("UPDATE requests set status_id=2 WHERE id = $request_id");
        if ($query) {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_denied')
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error_denied')
            ));
        }
    }

    public function removeBlockedCheck(Request $request)
    {
        $requestIds = $request->requestIds;
        $query = \DB::update("UPDATE requests set blocked_at = null WHERE id IN ($requestIds)");
        if ($query) {
            return response()->json(array(
                'status' => 'success',
                'message' => 'Removed Blocked'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Unable to remove'
            ));
        }
    }

    public function AddBlockedCheck(Request $request)
    {
        $requestIds = $request->requestIds;
        if(!empty($requestIds))
        {
            $query = \DB::update("UPDATE requests set blocked_at = NOW() WHERE id IN ($requestIds)");
            if ($query) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Added Blocked Time'
                ));
            } else {
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'Unable to add time'
                ));
            }
        }
        return 'requestIds are empty!';
    }
}
