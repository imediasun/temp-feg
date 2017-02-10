<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Gamesintransit;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class GamesintransitController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'gamesintransit';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Gamesintransit();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'gamesintransit',
            'pageUrl' => url('gamesintransit'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('gamesintransit.index', $this->data);
    }
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null($customQueryString) ? (is_null(Input::get('search')) ? '' : $this->buildSearch()) : 
            $this->buildSearch($customQueryString);

        // Get assigned locations list as sql query (part)
        $locationFilter = \SiteHelpers::getQueryStringForLocation('game', 'intended_first_location', [], " OR game.location_id=0 ") ;
        // if search filter does not have location_id filter
        // add default location filter
        $frontendSearchFilters = $this->model->getSearchFilters(array('intended_first_location' => ''));
        if (empty($frontendSearchFilters['intended_first_location'])) {
            $filter .= $locationFilter;
        } 
        
        return $filter;
    }
    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'gamesintransit')->pluck('module_id');
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
        } else {
            \Session::put('config_id', 0);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();

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


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }

        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('gamesintransit/data');
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
        return view('gamesintransit.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('game');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('gamesintransit.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('game');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('gamesintransit.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM game ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO game (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM game WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $rules = array('game_title_id' => 'required');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data['game_title_id'] = $request->get('game_title_id');
            $data['for_sale'] = $request->get('for_sale');
            $data['sale_price'] = $request->get('sale_price');
            $data['notes'] = $request->get('notes');
            $data['game_name'] = $request->get('game_name');
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

    function postAddNewGame(Request $request)
    {
        $rules = array('asset_number' => 'required|min:8|max:8|unique:game,id');
        $validator = Validator::make(array_map('trim',$request->all()), $rules);
        if ($validator->passes()) {
            $serial = $request->get('serial');
            $game_title_id = $request->get('game_title');
            $asset_number = $request->get('asset_number');
            $notes = $request->get('notes');
            $test_piece = $request->get('test_piece');
            $insert = array(
                'id' => $asset_number,
                'game_title_id' => $game_title_id,
                'serial' => $serial,
                'status_id' => 3,
                'test_piece' => $test_piece,
                'notes' => $notes
            );
            \DB::table('game')->insert($insert);

            return response()->json(array(
                'status' => 'success',
                'message' => 'New Game Added Successfully'
            ));
        }
        else
        {
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }


    }
    function getAssetNumberAvailability($asset_num)
    {
        if(strlen(trim($asset_num)) < 8 || strlen(trim($asset_num)) > 8)
        {
            return json_encode(array('status'=>'error','message'=>'Asset Number must have 8 characters'));

        }
        $row=\DB::select('select id from game where id ='.trim($asset_num));
        if(count($row) > 0)
        {
            echo json_encode(array('status'=>'error','message'=>'This Asset Number not available'));
        }
        else
        {
            echo json_encode(array('status'=>'success','message'=>'This Asset Number is available'));
        }
    }
}