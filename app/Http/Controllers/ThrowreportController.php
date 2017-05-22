<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Throwreport;
use App\Models\Throwdata;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ThrowreportController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'throwreport';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Throwreport();


        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'throwreport',
            'pageUrl' => url('throwreport'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('throwreport.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'throwreport')->pluck('module_id');
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
        $filters = $this->model->getSearchFilters();
        $dateStart = @$filters['date_start'];
        $dateEnd = @$filters['date_end'];
        if (empty($dateStart) && empty($dateEnd)) {
            $dateStart = $this->model->getStartDayOfWeek();
            $dateEnd = $this->model->getEndDayOfWeek();
        }

        if (count($results['rows']) == 0 && !empty($dateStart) && !empty($dateEnd)) {
            $dateStart_expression = date("Y-m-d", strtotime($dateStart));
            $dateEnd_expression = date("Y-m-d", strtotime($dateEnd));
            $location = \Session::get('selected_location');
            $rows = \DB::select("SELECT game_earnings.date_start, game_earnings.date_end, SUM(std_actual_cash) AS revenue_total,game.*
FROM game
JOIN game_earnings ON game_earnings.game_id = game.id WHERE game_earnings.loc_id =$location and
game_earnings.date_start >= '$dateStart_expression' and game_earnings.date_end <= '$dateEnd_expression'
and game.game_type_id = 3
GROUP BY game.id");
            if (count($rows) > 0) {
                $this->importDateIntoMerchThrow($rows);
                $results = $this->model->getRows($params);
            }
        }

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('throwreport/data');
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
        $this->data['setDate'] = isset($dateStart) ? $dateStart . '-' . $dateEnd : '';
        $this->data['setWeek'] = date("W", strtotime($dateEnd));;
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        // Render into template
        return view('throwreport.table', $this->data);

    }

    private function importDateIntoMerchThrow($rows)
    {
        foreach ($rows as $row) {
            $data = array(
                'date_start' => date("Y-m-d", strtotime($row->date_start)),
                'date_end' => date("Y-m-d", strtotime($row->date_end)),
                'game_id' => $row->id,
                'location_id' => $row->location_id,
                'product_id' => $row->product_id,
                'product_qty_1' => $row->product_qty_1,
                'flag' => 0,
                'retail_price' => '',
                'game_earnings' => $row->revenue_total,
            );
            $id = $this->model->insertRow($data, '');
        }
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

        return view('throwreport.form', $this->data);
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
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('throwreport.view', $this->data);
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

    function postTemp(Request $request)
    {
        if ($request->input('id') != '') {
            $data = $request->all();
            $meter = array();
            for ($index = 0; $index < count($data['meter_start']); $index++) {
                $meter[$index][] = $data['meter_start'][$index];
                $meter[$index][] = $data['meter_end'][$index];
            }
            unset($data['meter_start']);
            unset($data['meter_end']);
            $meter = json_encode($meter);
            $data['meter'] = $meter;
            $this->model->insertRow($data, $request->input('id'));
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {
            return response()->json(array(
                'message' => 'not exist',
                'status' => 'error'
            ));
        }
    }

    function getUpdateStatus(Request $request)
    {
        $date = $request->input('weekdate');
        $date = explode('-', $date);
        $dateStart_expression = date("Y-m-d", strtotime($date[0]));
        $dateEnd_expression = date("Y-m-d", strtotime($date[1]));
        $rows = \DB::table('merch_throws')->where('date_start', '>=', $dateStart_expression)->where('date_end', '<=', $dateEnd_expression)->select('id')->get();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                \DB::table('merch_throws')->where('id', $row->id)->update(array('flag' => 1));
            }
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {
            return response()->json(array(
                'message' => 'not exist',
                'status' => 'error'
            ));
        }
    }


    //step1 get complete record of row
    //	print_r($request->all());
    //step2 save data in db
    //	$throwModel = new \Location();

    //	$throwModel->insertData();


    function postSave(Request $request, $rid = 0)
    {

        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('game');

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

}