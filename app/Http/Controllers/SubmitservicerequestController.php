<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Submitservicerequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Mail;

class SubmitservicerequestController extends Controller
{
    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'submitservicerequest';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Submitservicerequest();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'submitservicerequest',
            'pageUrl' => url('submitservicerequest'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex($GID = null, $LID = null)
    {

        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['access'] = $this->access;
        $this->data['data'] = $this->model->getSubmitServiceRequestInfo($GID, $LID);
        return view('submitservicerequest.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'submitservicerequest')->pluck('module_id');
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
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('submitservicerequests/data');
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
        return view('submitservicerequest.table', $this->data);

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

        return view('submitservicerequest.form', $this->data);
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
        return view('submitservicerequest.view', $this->data);
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
        $now = date('m/d/Y');
        $rules = array('description' => 'required|min:10', 'location_id' => 'required', 'need_by_date' => 'required|min:10|max:10', 'userfile' => 'mimes:jpeg,gif,png,tif,bmp,jpg,doc,docx,pdf,xlx,xlsx,txt,rtf,log,zip,rar,7z,tar,bz2,bz,gz');
        $tech_type = $request->get('tech_type');
        if ($tech_type == 'part') {
            $rules['qty'] = 'required|integer';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $data = array_filter($data);
            $data['status_id'] = 1;
            $data['request_user_id'] = \Session::get('uid');
            $data['request_date'] = $now;
            $data['need_by_date'] = date_format(date_create_from_format('d-m-Y', $data['need_by_date']), 'm/d/Y');
            if ($tech_type == 'part') {
                $insert = array(
                    'location_id' => $data['location_id'],
                    'request_user_id' => $data['request_user_id'],
                    'request_date' => $data['request_date'],
                    'need_by_date' => $data['need_by_date'],
                    'description' => $data['description'],
                    'qty' => $data['qty'],
                    'part_cost' => $data['part_cost'],
                    'status_id' => $data['status_id']
                );
                $id = \DB::table('part_request')->insertGetId($insert);
            } elseif ($tech_type == 'service') {
                $insert = array(
                    'requestor_id' => $data['request_user_id'],
                    'location_id' => $data['location_id'],
                    'request_date' => $data['request_date'],
                    'request_title' => $data['requestTitle'],
                    'attachment_path' => "",
                    'problem' => $data['description'],
                    'need_by_date' => $data['need_by_date'],
                    'status_id' => $data['status_id']
                );
                $id = \DB::table('service_requests')->insertGetId($insert);
            }

            $updates = array();
            $file = $request->file('userfile');
            if (is_file($file)) {
                $destinationPath = './uploads/serviceRequest/';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename = $id . '.' . $extension;
                $uploadSuccess = $request->file('userfile')->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $updates['attachment_path'] = $newfilename;
                }
                $data['full_upload_path'] = url() . '/uploads/serviceRequest/' . $newfilename;
                \DB::table('service_requests')->where('id', '=', $id)->update($updates);
            }
//            $user_data = $this->model->get_user_data();
//            $loc_and_field_mgr = $this->model->get_user_emails('location_manager_and_field_manager', $data['location_id']);
//            $tech_contact = $this->model->get_user_emails('technical_contact', $data['location_id']);
//            $location_name = $this->model->get_location_info_by_id($data['location_id'], 'location_name_short');
//            $message['date_required'] =  $now ;
//			$message['requestor'] =	 $user_data['user_name'];
//            $message['location'] = $data['location_id'] . ' | ' . $location_name;
//            $message['description']=$data['description'] ;
//            $message['need_by_date']=$data['need_by_date'];
//			$message['click_path']=	 url() . '/manageservicerequests';
//            $to_group = 'silvia.lintner@fegllc.com' .
//                (!empty($data['email']) ? (', ' . $data['email']) : '') .
//                (!empty($loc_and_field_mgr) ? (', ' . $loc_and_field_mgr) : '') .
//                (!empty($tech_contact) ? (', ' . $tech_contact . '') : '');
//            $full_upload_path = isset($data['full_upload_path']) ? $data['full_upload_path'] : "";
//            $cc_group = 'rich.pankey@fegllc.com,
//					     jim.paolucci@fegllc.com,
//					     john.vaughn@fegllc.com,
//                         support@fegllc.com';
//
//           
//            $from = $user_data['email'];
//            $to = 'dev5@shayansolutions.com';
//            $cc = $cc_group;
//            $bcc = '';
//            //$subject = 'New Service Request for '.$location_name . ' on ' . $now;
//            $subject = 'Service Request ' . $location_name . ' ' . $now . ' ' . $data['requestTitle'];
//            $message = $message;

//    //$result = Mail::send('submitservicerequest.test', $message, function ($message) use ($to, $from, $full_upload_path, $subject) {
//
//        if (isset($full_upload_path) && !empty($full_upload_path)) {
//            $message->attach($full_upload_path);
//        }
//        $message->subject($subject);
//        $message->to($to);
//        $message->from($from);
//        
//    });

           

           if(empty($result)) {
               return response()->json(array(
                   'status' => 'success',
                   'message' => \Lang::get('core.note_success')
               ));
           }
            else{
                  return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
            }

        } else {
            $game_id = $request->get('game_id');
            $location_id = $request->get('location_name');
            $this->getIndex('LID' . $location_id, 'GID' . $game_id);
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

}