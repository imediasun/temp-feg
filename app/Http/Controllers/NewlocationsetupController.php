<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEG\System\SyncHelpers;
use App\Models\location;
use App\Models\Newlocationsetup;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class NewlocationsetupController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'new-location-setup';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Newlocationsetup();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'new-location-setup',
            'pageUrl' => url('new-location-setup'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('new-location-setup.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'new-location-setup')->pluck('module_id');
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
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('new-location-setup/data');
        $this->data['param'] = $params;
        $this->data['topMessage'] = @$results['topMessage'];
        $this->data['message'] = @$results['message'];
        $this->data['bottomMessage'] = @$results['bottomMessage'];
        $this->data['excludedUserLocations']		= $this->getUsersExcludedLocations();
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
        return view('new-location-setup.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('new_location_setups');
        }



        if(!empty($this->data['row']['teamviewer_passowrd']) && $this->data['row']['use_tv']==1){
            $this->data['row']['teamviewer_passowrd'] = \SiteHelpers::decryptStringOPENSSL($this->data['row']['teamviewer_passowrd']);
        }
        if(!empty($this->data['row']['windows_user_password'])&& $this->data['row']['is_server_locked']){
            $this->data['row']['windows_user_password'] = \SiteHelpers::decryptStringOPENSSL($this->data['row']['windows_user_password']);
        }
        if(!empty($this->data['row']['rdp_computer_password'])&& $this->data['row']['is_remote_desktop']==1){
            $this->data['row']['rdp_computer_password'] = \SiteHelpers::decryptStringOPENSSL($this->data['row']['rdp_computer_password']);
        }

        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;
        $this->data['excludedUserLocations']		= $this->getUsersExcludedLocations();
        return view('new-location-setup.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('new_location_setups');
        }

        $passwords = [
            'rdp' => [
                'encrypted' => $row->rdp_computer_password,
                'decrypted' => \SiteHelpers::decryptStringOPENSSL($row->rdp_computer_password),
            ],
            'tmv' => [
                'encrypted' => $row->teamviewer_passowrd,
                'decrypted' => \SiteHelpers::decryptStringOPENSSL($row->teamviewer_passowrd),
            ],
            'wndows' => [
                'encrypted' => $row->windows_user_password,
                'decrypted' => \SiteHelpers::decryptStringOPENSSL($row->windows_user_password),
            ]
        ];
        $this->data['passwords'] = json_encode($passwords);

        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata'] = \SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('new-location-setup.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM new_location_setups ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO new_location_setups (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM new_location_setups WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $sendEmail = false;
        if ($id==0){
            $sendEmail = true;
        }
        $rules = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('new_location_setups');

            if (isset($data["is_server_locked"]) && $data["is_server_locked"] == "on"){
                $data["is_server_locked"] = 1;
            }
            else{
                $data["is_server_locked"] = 0;
                $data["windows_user"] = '';
                $data["windows_user_password"] = '';
            }
            if (isset($data["is_remote_desktop"])&& $data["is_remote_desktop"] == "on" ) {
                $data["is_remote_desktop"] = 1;
            } else {
                $data["is_remote_desktop"] = 0;
                $data["rdp_computer_name"] = '';
                $data["rdp_computer_password"] ='';
                $data["rdp_computer_user"] = '';
            }
            if (isset($data["use_tv"]) && $data["use_tv"] == "on") {
                $data["use_tv"] = 1;
            } else {
                $data["use_tv"] = 0;
                $data["teamviewer_id"] = '';
                $data["teamviewer_passowrd"] = '';
            }
            if(!empty($data['teamviewer_passowrd']) && $data['use_tv']==1){
                $data['teamviewer_passowrd'] = \SiteHelpers::encryptStringOPENSSL($data['teamviewer_passowrd']);
            }
            if(!empty($data['windows_user_password'])&& $data['is_server_locked']==1){
                $data['windows_user_password'] = \SiteHelpers::encryptStringOPENSSL($data['windows_user_password']);
            }
            if(!empty(['rdp_computer_password'])&& $data['is_remote_desktop']==1){
                $data['rdp_computer_password'] = \SiteHelpers::encryptStringOPENSSL($data['rdp_computer_password']);
            }

            $id = $this->model->insertRow($data, $id);
            /**
             * sending Notification on Create a new Location Setup
             */

            $locationSetup = $this->model->find($id)->toArray();
            $location = location::find($locationSetup['location_id']);
            $locationSetup['location_name'] = $location->location_name;
            if($sendEmail==true){
                $url = url() . "/" . $this->data['pageModule'] . "/?view=" . \SiteHelpers::encryptID($id);
                $notificationContent['element5Digital'] = view('new-location-setup.email.newlocationsetupemail', ['row' => $locationSetup, 'type' => 'element5Digital', 'url' => $url])->render();
                $notificationContent['embed'] = view('new-location-setup.email.newlocationsetupemail', ['row' => $locationSetup, 'type' => 'embed', 'url' => $url])->render();
                $notificationContent['sacoa'] = view('new-location-setup.email.newlocationsetupemail', ['row' => $locationSetup, 'type' => 'sacoa', 'url' => $url])->render();
                $notificationContent['internal_team'] = view('new-location-setup.email.newlocationsetupemail', ['row' => $locationSetup, 'type' => 'internal_team', 'url' => $url])->render();
                $this->model->sendNotificationByEmail($id, $notificationContent, $location->location_name);
            }
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

    public function getLocationInfo(Request $request)
    {
        $locationId = $request->input('location_id', 0);
        $location = location::where('id', $locationId)->first();
        $locationType['debit_type'] = null;
        if ($location) {
            $locationType['debit_type'] = SyncHelpers::getDebitTypeName($location->debit_type_id);
        }
        if ($locationType['debit_type'] != null) {
            return response()->json($locationType);
        } else {
            $locationType['debit_type'] = '-';
            return response()->json($locationType);
        }
    }

}