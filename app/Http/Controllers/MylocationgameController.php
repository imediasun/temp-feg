<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Mylocationgame;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

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
        {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }            

        $this->data['access'] = $this->access;
        return view('mylocationgame.index', $this->data);
    }
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null($customQueryString) ? (is_null(Input::get('search')) ? '' : $this->buildSearch()) : 
            $this->buildSearch($customQueryString);

        // Get assigned locations list as sql query (part)
        $locationFilter = \SiteHelpers::getQueryStringForLocation('game');
        // if search filter does not have location_id filter
        // add default location filter
        $frontendSearchFilters = $this->model->getSearchFilters(array('location_id' => ''));
        if (empty($frontendSearchFilters['location_id'])) {
            $filter .= $locationFilter;
        } 
        
        return $filter;
    }
    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'mylocationgame')->pluck('module_id');
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
                $result->test_piece = "No";
            }
            if ($result->linked_to_game == 1) {
                $result->linked_to_game = "Yes";

            } else {
                $result->linked_to_game = "No";
            }
            if ($result->not_debit == 1) {
                $result->not_debit = "Yes";

            } else {
                $result->num_prize_meters = "No";
            }
            if ($result->num_prize_meters == 1) {
                $result->num_prize_meters = "Yes";

            } else {
                $result->not_debit = "No";
            }
            if ($result->num_prizes == 1) {
                $result->num_prizes = "Yes";

            } else {
                $result->num_prizes = "No";
            }
            if ($result->mfg_id == 0) {
                $result->mfg_id = "";

            }


        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }


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
            if (!empty($row->product_id)) {
                $row->product_id = json_decode($row->product_id);
                $row->product_id = implode(',', $row->product_id);
            }
            /*
            $products = \DB::table('game_product')
                ->where('game_id', '=', $row->id)
                ->select('product_id')
                ->get();
            if(count($products) > 0)
            {
                $products = json_decode(json_encode($products), true);
                $products = array_column($products, 'product_id');
                $row->product_id = implode(',', $products);
            }*/
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('game');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;
        $this->data['row']['game_title'] = empty($id) ? "" : $this->model->get_game_info_by_id($id, 'game_title');
        $this->data['row']['location_name'] = empty($id) ? "" : $this->model->get_location_info_by_id($row->location_id, 'location_name');

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
        $products = array();

        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
            
        if ($validator->passes()) {
            $data = $this->validatePost('game');
            //after validating data array become very small, so merge with post data
            $data = array_merge($data, $_POST);
            $gameID = $data['id'];
            $gameIDExists = \DB::table('game')->where('id', $gameID)->count() > 0;
            if ($id != $gameID && $gameIDExists) {
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'Asset ID already exists!'
                ));            
            }            
            if (!empty($request->input('product_id'))) {
                $products = $request->input('product_id');
                $products = json_encode($products);
                $data['product_id'] = $products;
            }
            
            /* NOTE: Game name will NOT ALWAYS be same as the Game Title */
            //$data['game_name'] = \DB::table('game_title')->where('id', '=', $data['game_title_id'])->pluck('game_title');
            /* Extracting game type id from game type */
            $data['game_type_id'] = \DB::table('game_title')->where('id', '=', $data['game_title_id'])->pluck('game_type_id');
            
            $sold = @$data['sold'];
            $oldSold = @$data['_oldSoldStatus'];
            if ($oldSold == 0 && $sold == 1) {
                $data['prev_location_id'] = @$data['old_location_id'];
                $data['location_id'] = 0;
                $data['status_id'] = 3;
            }
            elseif ($sold == 0) {
                $data['date_sold'] = NULL;
                $data['sold_to'] = '';
            }
            
            if (isset($data['_token'])) unset($data['_token']);
            if (isset($data['old_location_id'])) unset($data['old_location_id']);
            if (isset($data['_oldSoldStatus'])) unset($data['_oldSoldStatus']);
            if (isset($data['_test_piece'])) unset($data['_test_piece']);
            if (isset($data['_sale_pending'])) unset($data['_sale_pending']);
            if (isset($data['_for_sale'])) unset($data['_for_sale']);
            if (isset($data['_not_debit'])) unset($data['_not_debit']);
            if (isset($data['_sold'])) unset($data['_sold']);
            
            $id = $this->model->insertRow($data, $id);
            /*
            \DB::table('game_product')
                ->where('game_id', '=', $id)
                ->delete();
            if(count($products) > 0)
            {
                foreach($products as $product){
                    \DB::table('game_product')
                        ->insert(array('game_id' => $id, 'product_id' => $product));
                }
            }
            */

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
        
        $fromDetailedPage = array_pull($request, 'from_detailed_page');
        if ($fromDetailedPage == 1) {
            return $this->updateGameFromDetailsPage($request, $id);
        }
        
        // validate game id already exists or not
        $gameID = $request['id'];
        $gameIDExists = \DB::table('game')->where('id', $gameID)->count() > 0;        
        if ($gameID != $id && $gameIDExists) {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Asset ID already exists!'
            ));            
        }
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
    
    public function updateGameFromDetailsPage($data, $id = null) {
        
        $nowDate = date("Y-m-d");
        $nowDatetime = date("Y-m-d H:i:s");
        $userId = \Session::get('uid');
                
        $oldStatus = $data['old_status_id'];
        $status = isset($data['status_id']) || !empty($data['status_id']) ? 
                $data['status_id'] : $oldStatus;
        
        $sold = isset($data['sold']) ? $data['sold'] : 0;        
        $isSold = $sold == 1;
        $soldDate = isset($data['date_sold']) ? $data['date_sold'] : $nowDate;        
        $soldTo = @$data['sold_to'];
        
        $oldLocation = $data['old_location_id'];
        if (empty($oldLocation)) {
            $oldLocation = 0;
        }
        $prevLocation = $data['prev_location_id'];
        if (empty($prevLocation)) {
            $prevLocation = 0;
        }        
        $location = isset($data['location_id']) ? $data['location_id'] : $oldLocation;
        if (empty($location)) {
            $location = 0;
        }        
        $intendedLocation = $data['intended_first_location'];
        if (empty($intendedLocation)) {
            $intendedLocation = 0;
        }                          
       
//        $serial = @$data['serial'];
//        $version = @$data['version'];
//        $prevGameName = @$data['prev_game_name'];
        
        $newData = array();
//        $newData['serial'] = $serial;
//        $newData['version'] = $version;
//        $newData['prev_game_name'] = $prevGameName;
        $newData['last_edited_by'] = $userId;
        $newData['last_edited_on'] = $nowDatetime;
        $newData['status_id'] = $status;
        
        $inTransitToUp  = $oldStatus == 3 && $status == 1;
        $staysInTransit = $oldStatus == 3 && $status == 3;
        $upToInTransit  = $oldStatus == 1 && $status == 3;
        $staysUp        = $oldStatus == 1 && $status == 1;
        $upToRepair     = $oldStatus == 1 && $status == 2;
        $repairToUp     = $oldStatus == 2 && $status == 1;
        $staysRepair    = $oldStatus == 2 && $status == 2;
        
        if     ($inTransitToUp) {
            
            $move_id = $data['game_move_id'];
            
            $newData['status_id'] = $status;
            $newData['location_id'] = $location;
            $newData['intended_first_location'] = 0;
            $newData['date_last_move'] = $nowDate;
            
            if (empty($move_id) && empty($prevLocation)) {
                $newData['date_in_service'] = $nowDate;
                
                // Get game details for email etc.
                $gameDetails = $this->model->get_game_info_by_id($id, null, null);
                $gameDetails->status_id  = $status;
                $gameDetails->location_id  = $location;
                $gameDetails->location_name  = $this->model->get_location_info_by_id($location, 'location_name', '');
                $gameDetails->intended_first_location  = 0;
                $gameDetails->date_last_move  = $nowDate;
                $gameDetails->assetTag = $this->generate_asset_tag($id);

                \App\Library\FEG\System\Email\GenericReports::newGameReceived([
                        'game' => $gameDetails,
                    ]);
            }
            if (!empty($move_id)) {
                \DB::table('game_move_history')
                        ->where('id', '=', $move_id)
                        ->update([
                                'to_loc' => $location,
                                'to_by' => $userId,
                                'to_date' => $nowDate,
                            ]);
            }
            
        }
        elseif ($upToInTransit) {
            
            $newData['location_id'] = 0;
            $newData['prev_location_id'] = $oldLocation;
            $newData['intended_first_location'] = $location;
            $newData['date_last_move'] = $nowDate;
            
            $move_id = \DB::table('game_move_history')->insertGetId([
                    'game_id' => $id,
                    'from_loc' => $oldLocation,
                    'from_by' => $userId,
                    'from_date' => $nowDate,
                ]);
                    
            $newData['game_move_id'] = $move_id;
          
        }
        elseif ($staysInTransit) { 
            
            $newData['intended_first_location'] = $location;
        }
        elseif ($upToRepair) {  
            
            $dataDown = isset($data['date_down']) ? $data['date_down'] : $nowDate;
            $problem = @$data['problem'];
            $service_id = \DB::table('game_service_history')->insertGetId([
                    'game_id' => $id,
                    'location_id' => $oldLocation,
                    'problem' => $problem,
                    'down_user_id' => $userId,
                    'date_down' => $dataDown,
                ]); 
            $newData['game_service_id'] = $service_id;
        }
        elseif ($repairToUp) {         
            
            $service_id = $data['game_service_id'];
            if (empty($service_id)) {
                return response()->json(array(
                    'status' => 'error',
                    'message' => 'Error in moving game to Up & Running. Service history not found!'
                ));                 
            }
            $dateUp = isset($data['date_up']) ? $data['date_up'] : $nowDate;
            $solution = @$data['solution'];            
            \DB::table('game_service_history')
                    ->where('id', '=', $service_id)
                    ->update([
                        'solution' => $solution,
                        'location_id' => $oldLocation,                        
                        'date_up' => $dateUp,
                        'up_user_id' => $userId,
                    ]);
        }
        elseif ($staysUp) {            
            
        }
        elseif ($staysRepair) {            
            
        }        
        
        $newData['sold'] = $sold;
        $newData['sold_to'] = $soldTo;
        $newData['date_sold'] = $soldDate;         
        if ($isSold) {
            $newData['location_id'] = 0;
            $newData['prev_location_id'] = $oldLocation;
            $newData['status_id'] = 3;
            $newData['sale_pending'] = 0;            
        }

        $newID = $this->model->insertRow($newData, $id);
        
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));        
    }

    public function postGamelocation(Request $request)
    {
       // die('here....');
        $this->data['pageTitle'] = 'game in location';
        $request = $request->all();
        if(!empty($request['game_title_id']) && !empty($request['location_id']))
        {
            $results = \DB::table('game')->where('game_title_id', '=', $request['game_title_id'])->where('location_id', '=', $request['location_id'])->get();
        }
        elseif(!empty($request['game_title_id']))
        {
            $results = \DB::table('game')->where('game_title_id', '=', $request['game_title_id'])->get();
        }
        elseif(!empty($request['location_id'])){
            $results = \DB::table('game')->where('location_id', '=', $request['location_id'])->get();
        }
        else
        {
            $results = \DB::table('game')->get();
        }
            $info = $this->model->makeInfo($this->module);
        $rows = $results;
        if (!empty($request['validateDownload'])) {
            $status = [];
            if (empty($rows)) {
                $status['error'] = 'The selected Game Title is not present at the Location 
                    you have selected, so the export has been aborted. 
                    Please select a different Game Title and/or Location combination.';
            }
            else {
                $status['success'] = 1;
            }
            return response()->json($status);
        }
        foreach ($rows as &$row){
            $row->game_name=$row->game_title_id;
        }
        $fields = $info['config']['grid'];
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
        );
        return view('sximo.module.utility.csv', $content);
    }
    
    public function getAssetIdsFromFilter($request = null) {

        if (is_null($request)) {
            $request = array();
        }                
        $filterQuery = empty($request['filter']) ? '' : $request['filter'];
        parse_str(@$request['filter'], $querystring);
        $searchQuery = empty($querystring['search']) ? null : $querystring['search'];
        $filter = $this->getSearchFilterQuery($searchQuery);        
        $q = "SELECT id from game WHERE id IS NOT NULL $filter";
        $data = \DB::select($q);
        $assets = [];
        if (!empty($data)) {
            foreach($data as $item) {
                $assets[] = $item->id;
            }
        }
        $assetIds = implode(',', $assets);        
        return $assetIds;        
    }
    
    public function getHistory(Request $requestData = null)
    {
        $request = $requestData->all();
        $assetIds = $this->getAssetIdsFromFilter($request);
        if ($assetIds == '') {
            $assetIds = '0';
        }

        $rows = $this->model->getMoveHistory($assetIds);
        if (!empty($request['validateDownload'])) {
            $status = [];
            if (empty($assetIds) || empty($rows)) {
                $status['error'] = 'Game Move history is not found for the selected Games, 
                    so the download has been aborted. 
                    Please select a different Game Title and/or Location combination.';
            }
            else {
                $status['success'] = 1;
            }
            return response()->json($status);
        }

        $fields = array(
                'Asset ID' => 'game_id', 
                'Game Title' => 'game_title', 
                'From Location ID' => 'from_loc', 
                'From Location Name' => 'from_location', 
                'Sent by' => 'from_name', 
                'From Date' => 'from_date', 
                'To Location ID' => 'to_loc', 
                'To Location Name' => 'to_location', 
                'Accepted by' => 'to_name', 
                'To Date' => 'to_date'
            );
        $this->data['pageTitle'] = 'game move history';
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'move',
            'title' => $this->data['pageTitle'],
        );
        return view('mylocationgame.csvhistory', $content);
    }

    function getPending(Request $requestData = null)
    {
        $request = $requestData->all();
        $assetIds = $this->getAssetIdsFromFilter($request);
        if ($assetIds == '') {
            $assetIds = '0';
        }

        $rows = $this->model->getPendingList($assetIds);
        if (!empty($request['validateDownload'])) {
            $status = [];
            if (empty($assetIds) || empty($rows)) {
                $status['error'] = 'Pending sames information is not found for the selected Games, 
                    so the download has been aborted. 
                    Please select a different Game Title and/or Location combination.';
            }
            else {
                $status['success'] = 1;
            }
            return response()->json($status);
        }     
        
        $this->data['pageTitle'] = 'game pending list';
        $fields = array(
                "Manufacturer" => 'Manufacturer', 
                "Game Title" => 'Game_Title', 
                "Version" => 'version', 
                "Serial" => 'serial', 
                "Asset ID" => 'id', 
                "Location Id" => 'location_id', 
                "City" => 'city', 
                "State" => 'state', 
                "WholeSale" => 'Wholesale', 
                "Retail" => 'Retail', 
                "Notes" => 'notes'
            );
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'pending',
            'title' => $this->data['pageTitle'],
        );
        return view('mylocationgame.csvhistory', $content);
    }

    function getForsale(Request $requestData = null)
    {
        $request = $requestData->all();
        $assetIds = $this->getAssetIdsFromFilter($request);
        if ($assetIds == '') {
            $assetIds = '0';
        }
        
        $rows = $this->model->getForSaleList($assetIds);
        if (!empty($request['validateDownload'])) {
            $status = [];
            if (empty($assetIds) || empty($rows)) {
                $status['error'] = 'For Sale information is not found for the selected Games, 
                    so the download has been aborted. 
                    Please select a different Game Title and/or Location combination.';
            }
            else {
                $status['success'] = 1;
            }
            return response()->json($status);
        }         
        
        $this->data['pageTitle'] = 'game for-sale list';     
        $fields = array(
                "Manufacturer" => 'Manufacturer', 
                "Game Title" => 'Game_Title', 
                "Version" => 'version', 
                "Serial" => 'serial', 
                "Asset ID" => 'id',             
                "Date In Service" => 'date_service', 
                "Location Id" => 'location_id', 
                "City" => 'city', 
                "State" => 'state', 
                "WholeSale" => 'Wholesale', 
                "Retail" => 'Retail'
            );
        $content = array(
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'forsale',
            'title' => $this->data['pageTitle'],
        );
        return view('mylocationgame.csvhistory', $content);
    }

    function generate_asset_tag($id = null)
    {
        //// THE SCRIPT BELOW CIRCULATES THROUGH THE ASSET IDs IN THE COMMA SEPARATED STRING BELOW AND CREATES A QR TAG - ACTIVATE BY VISITING THIS PAGE - CURRENTLY COMMENTED //////////////
        //// START /////

        // $gameString = '20002114,20002146,20002147,20002149,20002150,20002151,20002152,20002153,20002154,20002155,20002157,20002159,20002160,20002161,20002162,20002164,20002165,20002166,20002167,20002168,20002169,20002170,20002171,20002172,20002173,20002174,20002175,20002176,20002177,20002178,20002179,20002180,20002181,20002182,20002183,20002184,20002185,20002186,20002187,20002188,20002189,20002190,20002191,20002192,20002193,20002194,20002195,20002196,20002197,20002198,20002199,20002200,20002201,20002202,20002203,20002204,20002205,20002206,20002207,20002208,20002209,20002210,20002211,20002212,20002214,20002215,20002216,20002217,20002218,20002219,20002220,20002221,20002222,20002223,20002224,20002225,20002226,20002227,20002228,20002229,20002230,20002231,20002232,20002233,20002234,20002235,20002236,20002237,20002240,20002241,20002242,20002243,20002244,20002247,30007187,30007188,30007253,30007263,30007264,30007265,30007266,30007267,30007268,30007269,30007270,30007271,30007272,30007274,30007275,30007277,30007278,30007279,30007280,3000728';

        // $item_count = substr_count($gameString, ',')+1;

        // for($i=1;$i <= $item_count;$i++)
        // {
        // 	$id = substr($gameString, 0, 8);

        ////// END ///// PLUS CLOSING TAG BELOW /////
        $filename = storage_path() . '/qr/' . $id . '.png';
        $data = url("/mylocationgame/?gamedetails=" . $id);
        $width = 147;
        $margin = 5;
        $xCenter = intval($width / 2);
        $idYTop = 122;
        $titleYTop = 145;
        $idFont = public_path() . "/sximo/fonts/EncodeSansWide-Regular.ttf";
        $titleFont = public_path() . "/sximo/fonts/pf_tempesta_seven_condensed.ttf";
        
        $qr = \QrCode::format('png')
                ->size($width)
                ->margin($margin)
                ->errorCorrection('M') //H Q M L
                ->generate($data, $filename);
        // $this->model->get_detail($id);

        //$row = \DB::select("SELECT G.id, T.game_title FROM game G LEFT JOIN game_title T ON T.id = G.game_title_id WHERE G.id=$id");
        $game_title = \DB::table('game')
                ->leftJoin('game_title', 'game_title.id', '=', 'game.game_title_id')
                ->where('game.id', '=', $id)->pluck('game_title');
        if (empty($game_title)) {
            $game_title = "";
        }

        \Image::make($filename)
            ->resizeCanvas(0, -6, 'bottom', true, 'ffffff')
            ->resizeCanvas(0, 18, 'top', true, 'ffffff')
            ->text($id, $xCenter, $idYTop, function($font) use ($idFont){
                $font->file($idFont);
                $font->size(19);
                $font->color('#000');
                $font->align('center');
                $font->valign('top');
                $font->angle(0);
            })               
            ->text($game_title, $xCenter, $titleYTop, function($font)  use ($titleFont){
                $font->file($titleFont);
                $font->size(8);
                $font->color('#000');
                $font->align('center');
                $font->valign('top');
                $font->angle(0);
            })               
            ->save($filename, 100);
        
        return $filename;
    }

    function postAssettag(Request $request, $asset_ids = null)
    {
        $asset_ids = $request->get('asset_ids');
        if (!empty($asset_ids)) {
            $zip = new \ZipArchive();
            $zip_name = "assettags.zip";
            $zip_file_path = storage_path() . "/qr"; // Zip name
            $zip_file = $zip_file_path . "/" . $zip_name;
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            //$zip->close();
            $item_count = substr_count($asset_ids, ',') + 1;
            if ($item_count > 1) {
                //die('here greater than one');
                for ($i = 1; $i <= $item_count; $i++) {
                    $id = substr($asset_ids, 0, 8);
                    $asset_ids = substr($asset_ids, 9);
                    $this->generate_asset_tag($id);
                    //$location = $this->get_game_info_by_id($id, 'location_id');
                    //   $location = $this->get_game_info_by_id($id, 'location_id');
                    $file = $zip_file_path . '/' . $id . '.png';
                    if (file_exists($file)) {
                        $zip->addFile($file, basename($file));
                    } else
                        die('file not exists');
                }
                $zip->close();
                if (file_exists($zip_file)) {
                    header('Content-type: application/zip');
                    header('Content-Description: File Transfer');
                    // header('Content-Disposition: attachment; filename="'.basename($zip_file).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($zip_file));
                    readfile($zip_file);
                    unlink($zip_file);
                    exit;
                } else {
                    echo "sorry";
                }
            } else {
                //  die('smaller than one');
                $this->generate_asset_tag($asset_ids);

                //$location = $this->get_game_info_by_id($id, 'location_id');

                //   $location = $this->get_game_info_by_id($id, 'location_id');
                $file = storage_path() . '/qr/' . $asset_ids . '.png';
                if (file_exists($file)) {
                    $zip->addFile($file, basename($file));
                    return response()->download($file, $asset_ids.'.png', array( 'Content-Type' => 'image/png' ))
                            ->setContentDisposition('attachment; filename='.$asset_ids.'.png');
                } else {
                    die('file does not exists');
                }
            }
        }
    }

    public function get_game_info_by_id($asset_id = null, $field = null)
    {
        $query = \DB::select('SELECT ' . $field . '
								 FROM game_title T
						 	LEFT JOIN game G ON G.game_title_id = T.id
							    WHERE G.id = ' . $asset_id);
        $game_info = $query[0]->location_id;
        if (empty($game_info)) {
            $game_info = 'NONE';
        }
        return $game_info;
    }

    function getDowload()
    {

    }
}