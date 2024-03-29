<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEGDBRelationHelpers;
use App\Models\Productusagereport;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ProductusagereportController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'productusagereport';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Productusagereport();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'productusagereport',
            'pageUrl' => url('productusagereport'),
            'return' => self::returnUrl()
        );


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
        $rows = \SiteHelpers::addPostPreFixToField($rows,'Cases_Ordered','*','is_broken_case',['yes','YES','Yes',1],true,false,true);

        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
            'excelExcludeFormatting' => isset($results['excelExcludeFormatting'])?$results['excelExcludeFormatting']:[],
            'AddNote'=>'* in Quantity Ordered denotes the # of units receive, not cases.'
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

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('productusagereport.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'productusagereport')->pluck('module_id');
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


        if($this->model->isTypeRestricted()){
            $filter .= " AND P.prod_type_id IN('".$this->model->getAllowedTypes()."') ";
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
        $results = $this->model->getRows($params);
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('productusagereport/data');
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
        $this->data['typeRestricted'] = [];
        if($this->model->isTypeRestrictedModule($this->module)){
            if($this->model->isTypeRestricted()){
                $this->data['typeRestricted'] = [
                    'isTypeRestricted' => $this->model->isTypeRestricted(),
                    'displayTypeOnly' => $this->model->getAllowedTypes(),
                ];
            }
        }
        $this->data['isTypeRestricted'] = $this->model->isTypeRestricted();
        $this->data['displayTypesOnly'] = $this->model->getAllowedTypes();

        $productTypeExcludedbyLocation = FEGDBRelationHelpers::getExcludedProductTypesOnly();

        if(count($productTypeExcludedbyLocation) > 0){
            $this->data['typeRestricted']['isTypeRestrictedExclude'] =true;
            $this->data['typeRestricted']['excluded'] = $productTypeExcludedbyLocation;
        }


// Render into template
        return view('productusagereport.table', $this->data);

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

        return view('productusagereport.form', $this->data);
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
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('productusagereport.view', $this->data);
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

    function postSave(Request $request, $id = 0)
    {

        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('requests');

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
    public
    function getSearch($mode = 'ajax')
    {

        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['searchMode'] = $mode;
        $this->data['typeRestricted'] = ['isTypeRestricted' => false ,'displayTypeOnly' => ''];
        $this->data['excluded_locations'] = $this->getUsersExcludedLocations();

        if($this->model->isTypeRestrictedModule($this->module)){
            if($this->model->isTypeRestricted()){
                $this->data['typeRestricted'] = [
                    'isTypeRestricted' => $this->model->isTypeRestricted(),
                    'displayTypeOnly' => $this->model->getAllowedTypes(),
                ];
            }
        }
        $productTypeExcludedbyLocation = FEGDBRelationHelpers::getExcludedProductTypesOnly();

        if(count($productTypeExcludedbyLocation) > 0){
            $this->data['typeRestricted']['isTypeRestrictedExclude'] =true;
            $this->data['typeRestricted']['excluded'] = $productTypeExcludedbyLocation;
        }

        if ($this->info['setting']['hideadvancedsearchoperators'] == 'true') {
            return view('feg_common.search', $this->data);
        } else {
            return view('sximo.module.utility.search', $this->data);
        }

    }

}