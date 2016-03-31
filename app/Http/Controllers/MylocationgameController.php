<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Mylocationgame;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Carbon;

class MylocationgameController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'mylocationgame';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Mylocationgame();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'mylocationgame',
            'pageUrl' => url('mylocationgame'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('mylocationgame.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'mylocationgame')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
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
        foreach ($results['rows'] as $result) {

            if ($result->dba == 1) {
                $result->dba = "Yes";

            } else {
                $result->dba = "No";
            }
            if ($result->sacoa == 1) {
                $result->sacoa = "Yes";

            } else {
                $result->sacoa = "No";
            }
            if ($result->embed == 1) {
                $result->embed = "Yes";

            } else {
                $result->embed = "No";
            }
            if ($result->for_sale == 1) {
                $result->for_sale = "Yes";

            } else {
                $result->for_sale = "No";
            }
            if ($result->sale_pending == 1) {
                $result->sale_pending = "Yes";

            } else {
                $result->sale_pending = "No";
            }
            if ($result->sold == 1) {
                $result->sold = "Yes";

            } else {
                $result->sold = "No";
            }
            if ($result->test_piece == 1) {
                $result->test_piece = "Yes";

            } else {
                $result->test_piece= "No";
            }
            if ($result->linked_to_game == 1) {
                $result->linked_to_game = "Yes";

            } else {
                $result->linked_to_game= "No";
            }
            if ($result->not_debit == 1) {
                $result->not_debit = "Yes";

            } else {
                $result->num_prize_meters= "No";
            }
            if ($result->num_prize_meters == 1) {
                $result->num_prize_meters = "Yes";

            } else {
                $result->not_debit= "No";
            }
            if ($result->num_prizes == 1) {
                $result->num_prizes = "Yes";

            } else {
                $result->num_prizes= "No";
            }


        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('mylocationgame/data');
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
        //get the image for game

// Render into template
        return view('mylocationgame.table', $this->data);

    }


    function getUpdate(Request $request, $id = null)
    {
        if ($id == null) {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != null) {
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

        return view('mylocationgame.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        $row['service_history'] = $this->model->getServiceHistory($id);
        $row['move_history'] = $this->model->getMoveHistory($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('game');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        if ($this->data['row'][0]->test_piece == 1) {
            $this->data['row'][0]->game_title = "**Test** " . $this->data['row'][0]->game_title;
        }
        return view('mylocationgame.view', $this->data);
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
        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('game');

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

    public function postUpdate(Request $request, $id = null)
    {
        $request = $request->all();
        $request = array_filter($request);
        array_shift($request);
        array_pull($request, 'submit');
        $service_data['id'] = array_pull($request, 'game_service_id');
        if ($request['status_id'] == 2) {
            $service_data['date_down'] = array_pull($request, 'date_down');
            $service_data['problem'] = array_pull($request, 'problem');
            \DB::table('game_service_history')->where('id', '=', $service_data['id'])->update($service_data);
        }
        $id = $this->model->insertRow($request, $id);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    public function postTest(Request $request)
    {
        $this->data['pageTitle'] = 'game in location';
        $request = $request->all();
        $results = \DB::table('game')->where('game_title_id', '=', $request['game_title_id'])->where('location_id', '=', $request['location_id'])->get();
        $info = $this->model->makeInfo($this->module);
            $rows = $results;
        $fields = $info['config']['grid'];
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
        );
        return view('sximo.module.utility.csv', $content);
    }
    public function getHistory()
    {
            $rows = $this->model->getMoveHistory();
            $fields = array('game', 'From Location', 'Sent by', 'From Date', 'To Location', 'Accepted by', 'To Date');
            $this->data['pageTitle'] = 'game move history';
            $content = array(
                'fields' => $fields,
                'rows' => $rows,
                'type' => 'move',
                'title' => $this->data['pageTitle'],
            );
        return view('mylocationgame.csvhistory', $content);
    }
    function getPending()
    {
            $this->data['pageTitle'] = 'game pending list';
            $rows= \DB::Select( "SELECT V.vendor_name AS Manufacturer,T.game_title AS Game_Title, G.version, G.serial, G.id, G.location_id, L.city, L.state, G.sale_price AS Wholesale,
									IF(G.sale_price >= 1000,
									ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
									(G.sale_price+100)
									) AS Retail, G.notes FROM game G  LEFT JOIN game_title T ON G.game_title_id = T.id LEFT JOIN vendor V ON V.id = T.mfg_id LEFT JOIN location L ON G.location_id = L.id WHERE G.sale_pending = 1 AND G.sold = 0 ORDER BY T.game_title ASC, G.location_id");
            $fields=array("Manufacturer","Game Title","Version","Serial","Id","Location Id","City","State","WholeSale","Retail","Notes");
            $content = array(
                'fields' => $fields,
                'rows' => $rows,
                'type' => 'pending',
                'title' => $this->data['pageTitle'],
            );
        return view('mylocationgame.csvhistory', $content);
    }
    function getForsale()
    {

        $this->data['pageTitle'] = 'game for-sale list';
        $rows= \DB::Select( "SELECT V.vendor_name AS Manufacturer,T.game_title AS Game_Title, G.version, G.serial, IF(G.date_in_service = '0000-00-00','', G.date_in_service) AS 'date_service', G.id, G.location_id, L.city, L.state, G.sale_price AS Wholesale,
										IF(G.sale_price >= 1000,
										ROUND(((G.sale_price*1.1)-1)/10+.5)*10+5,
										(G.sale_price+100)
										) AS Retail
									FROM game G
							   LEFT JOIN game_title T ON G.game_title_id = T.id
							   LEFT JOIN vendor V ON V.id = T.mfg_id
							   LEFT JOIN location L ON G.location_id = L.id
								   WHERE G.for_sale = 1
    AND G.sale_pending = 0 AND G.status_id!=3 AND G.sold = 0 ORDER BY T.game_title ASC, G.location_id");
        $fields=array("Manufacturer","Game Title","Version","Serial","Date In Service","Location Id","City","State","WholeSale","Retail");
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'forsale',
            'title' => $this->data['pageTitle'],
        );
        return view('mylocationgame.csvhistory', $content);



    }
}