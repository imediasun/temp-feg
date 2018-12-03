<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Servicerequests;
use App\Models\Tablecols;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class TablecolsController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'tablecols';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Tablecols();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'tablecols',
            'pageUrl' => url('tablecols'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('tablecols.index', $this->data);
    }

    public function postData(Request $request)
    {
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
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('tablecols/data');

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
        // Render into template
        return view('tablecols.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('user_module_config');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('tablecols.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('user_module_config');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('tablecols.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM user_module_config ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO user_module_config (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM user_module_config WHERE id IN (" . $toCopy . ")";
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
            $data = $this->validatePost('user_module_config');
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

    public function postConfig()
    {
        $data = Input::all();
        $id = $this->model->checkModule($data['config_name'], $data['module_id']);
        $configstr="";
        $configstr = implode(',',$data['cols']);
        $configstr = \SiteHelpers::CF_encode_json($configstr);
        $columnData = [
            'user_id' => $data['user_id'],
            'module_id' => $data['module_id'],
            'config' => $configstr,
            'config_name' => $data['config_name'],
            'is_private' => $data['user_mode'],
            'group_id' => $data['group_id']
        ];
        if(!empty($data['tab_type'])){
            $columnData['tab_type'] = $data['tab_type'];
        }
        $id = $this->model->insertRow($columnData, $data['config_id']);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success'),
            'id' => $id
        ));

    }

    public function getArrangeCols($pageModule, $mode = null)
    {       

        $info = $this->model->makeInfo($pageModule);
        $module_id = \DB::table('tb_module')->where('module_name', '=', $pageModule)->pluck('module_id');
        $user_id = \Session::get('uid');
        $configs="";
        $group_id="";
        $is_private="";
        $config_name="";
        if($mode != null)
        {
            $configIDName = "config_id";
            $configIDWithModuleName = "{$pageModule}_config_id";
            $module_id = \DB::table('tb_module')->where('module_name', '=',$pageModule)->pluck('module_id');
            if (\Session::has($configIDWithModuleName)) {
                $config_id = \Session::get($configIDWithModuleName);
                $configIDName = $configIDWithModuleName;
            }
            elseif (\Session::has($configIDName)) {
                $config_id = \Session::get($configIDName);
            }
            else {
                $config_id = 0;
            }
            $config = $this->model->getModuleConfig($module_id, $config_id);
            if (!empty($config)) {
                $configs = \SiteHelpers::CF_decode_json($config[0]->config);
                $group_id=$config[0]->group_id;
                $is_private=$config[0]->is_private;
                $config_name=$config[0]->config_name;
                \Session::put($configIDName, $config_id);
            }
        }
        else{
            $config_id = null;
        }
        //add code here to get all columns for a module
        $groups = \SiteHelpers::getAllGroups();
       // $groups = \DB::table('tb_groups')->where('level', '>=', \Session::get('level'))->get();
        $tabType = '';
        if(!empty($_GET['tab_type'])){
            $tabType=$_GET['tab_type'];
            if ($tabType == 'game-related'){
                $serviceRequest = new Servicerequests();
                $info['config']['grid'] = $serviceRequest->displayFieldsByType(@$info['config']['grid'],$tabType);
            }
        }
        return view('tablecols.arrange_cols', [
            'allColumns' => $info['config']['grid'],
            'user_id' => $user_id, 
            'module_id' => $module_id, 
            'pageModule' => $pageModule, 
            'groups' => $groups, 
            'cols'=>$configs,
            'group_id'=>$group_id,
            'config_name'=>$config_name,
            'is_private'=>$is_private,
            'config_id'=>$config_id,
            'tabType' => $tabType
        ]);
    }
    function getDeleteConfig(Request $request)
    {
        $module=$request->get('module');
        $config_id=$request->get('config_id');
        $user_id=\Session::get('uid');
        $is_deleted=\DB::table('user_module_config')->where('id','=',$config_id)->delete();
        if($is_deleted)
        {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.delete_success')
            ));
        }
        else
        {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.delete_error')
            ));
        }
    }

}