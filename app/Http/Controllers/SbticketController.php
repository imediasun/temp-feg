<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Sbticket;
use App\Models\SbticketSetting;
use App\Models\Ticketcomment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Library\FEG\System\FEGSystemHelper;
use Validator, Input, Redirect;
use App\Models\Core\Groups;

class SbticketController extends Controller
{

    static $per_page = '10';
    public $module = 'sbticket';
    protected $layout = "layouts.main";
    protected $data = array();

    public function __construct()
    {
        //die('====THIS CODE IS DEPRECATED, ITS MARKED FOR REMOVE, CONTACT DEVELOPER======');
        parent::__construct();
        $this->model = new Sbticket();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'sbticket',
            'pageUrl' => url('sbticket'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('sbticket.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'sbticket')->pluck('module_id');
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
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : "AND sb_tickets.Status != 'close'");

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
        if (count($results['rows']) == 0 and $page != 1) {
            $params['limit'] = $this->info['setting']['perpage'];
            $results = $this->model->getRows($params);

        }
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;

        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }


        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('sbticket/data');
        $rows = $results['rows'];
        $comments = new Ticketcomment();

        $user_id = \Session::get('uid');
        $group_id = \Session::get('gid');
        foreach ($rows as $index => $row) {
            $flag = 1;
            if (isset($row->department_id) && !empty($row->department_id)&& false)
            {
                //$row->comments = $comments->where('TicketID', '=', $row->TicketID)->orderBy('TicketID', 'desc')->take(1)->get();
                $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $row->department_id . "");
            $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);

            $assign_employee_ids = explode(',', $row->assign_to);

            $members_access = array_unique(array_merge($assign_employee_ids, $department_memebers));
            foreach ($members_access as $i => $id) {
                $get_user_id_from_employess = \DB::select("Select user_id FROM employees WHERE id = " . $id . "");
                //print"<pre>";
                //print_r($get_user_id_from_employess);
                if (isset($get_user_id_from_employess[0]->user_id)) {
                    $members_access[$i] = $get_user_id_from_employess[0]->user_id;
                    //echo $members_access[$i]."<br>";
                }

            }

            if ($group_id != Groups::SUPPER_ADMIN) {
                if (!in_array($user_id, array_unique($members_access))) {
                    $flag = 0;
                }
            }

            if ($flag == 1) {
                $assign_employee_names = array();
                foreach ($assign_employee_ids as $key => $value) {
                    $assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM employees WHERE id = " . $value . "");
                }
                $row->assign_employee_names = $assign_employee_names;
            } else {
                unset($rows[$index]);
            }
        }
        }

        $this->data['param'] = $params;
        $this->data['rowData'] = $rows;
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
        return view('sbticket.table', $this->data);

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
        if ($id != null) {
            $this->data['in_edit_mode'] = true;
        } else {
            $this->data['in_edit_mode'] = false;
        }
        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('sb_tickets');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;


        return view('sbticket.form', $this->data);
    }

    public function getShow($id = null)
    {
        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->find($id);
        $assign_employee_ids = explode(',', $row->assign_to);
        $assign_employee_names = array();
        foreach ($assign_employee_ids as $key => $value) {
            $assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM employees WHERE id = " . $value . "");
        }
        $row->assign_employee_names = $assign_employee_names;
        if ($row) {
            $comments = new Ticketcomment();
            $this->data['comments'] = $comments->where('TicketID', '=', $id)->get();
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('sb_tickets');
        }

        $this->data['id'] = $id;
        $this->data['uid'] = \Session::get('uid');
        $this->data['fid'] = \Session::get('fid');
        $this->data['access'] = $this->access;
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('sbticket.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM sb_tickets ") as $column) {
            if ($column->Field != 'TicketID')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO sb_tickets (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM sb_tickets WHERE TicketID IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $data['need_by_date'] = date('Y-m-d');
        //$rules = $this->validateForm();
        $rules = array('Subject' => 'required', 'Description' => 'required', 'Priority' => 'required', 'issue_type' => 'required', 'location_id' => 'required');
        unset($rules['debit_card']);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('sb_tickets');
            //$data['need_by_date']= date("Y-m-d", strtotime($request->get('need_by_date')));
            if ($id == 0) {

                $data['Created'] = date('Y-m-d');

            }
            if ($id != 0) {
                die('in edit mode...');
            }
            $id = $this->model->insertRow($data, $request->input('TicketID'));

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

    public function postComment(Request $request)
    {
        $rules = $this->validateTicketCommentsForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            //validate post for sb_tickets module
            $ticketsData = $this->validatePost('sb_tickets');
            if ($ticketsData['Status'] == 'close') {
                $ticketsData['closed'] = date('Y-m-d');
            }
            $ticketsData['updated'] = date('Y-m-d');
            $commentsData['USERNAME'] = \Session::get('fid');
            $comment_model = new Ticketcomment();
            $TicketID = $request->input('TicketID');
            $total_comments = \DB::select("Select * FROM sb_ticketcomments WHERE TicketID = " . $TicketID . "");
            if (count($total_comments) == 0) {
                $ticketsData['Status'] = 'inqueue';
            }

            //re-populate info array to ticket comments module
            $this->info = $comment_model->makeInfo('ticketcomment');
            $commentsData = $this->validatePost('sb_ticketcomments');

            //@todo need separate table for comment attachments
            unset($ticketsData['file_path']);
            $comment_model->insertRow($commentsData, NULL);
            $ticketId = $request->input('TicketID');
            $this->model->insertRow($ticketsData, $ticketId);
            $message = $commentsData['Comments'];
            //send email
            $this->departmentSendMail($ticketsData['department_id'], $ticketId, $message);
            $this->assignToSendMail($ticketsData['assign_to'], $ticketId, $message);
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {

            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }
    }

    function validateTicketCommentsForm()
    {
        $rules = array();
        $rules['Comments'] = 'required';
        $rules['department_id'] = 'required|numeric';
        $rules['Priority'] = 'required';
        $rules['Status'] = 'required';
        return $rules;
    }

    public function departmentSendMail($departmentId, $ticketId, $message)
    {
        $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $departmentId . "");
        $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);
        $reply_to='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        $subject = 'FEG Ticket #' . $ticketId;
        //$headers = 'MIME-Version: 1.0' . "\r\n";
        //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'From: ' . $reply_to . ' <' . $reply_to . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM employees JOIN users ON users.id=employees.user_id WHERE employees.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
                //mail($to, $subject, $message, $headers);
                if(!empty($to)){
                    FEGSystemHelper::sendSystemEmail(array(
                        'to' => $to,
                        'subject' => $subject,
                        'message' => $message,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'from' => $reply_to,
                        //'bcc' => $bcc,
                        'configName' => 'SUBMIT TICKET EMAIL'
                    ));
                }
            }
        }
    }

    public function assignToSendMail($assignTo, $ticketId, $message)
    {
        $assigneesTo = $assigneesTo = \DB::select("select users.email FROM employees JOIN users ON users.id=employees.user_id WHERE employees.id IN (" . $assignTo . ")");
        foreach ($assigneesTo as $assignee) {
            if (isset($assignee->email)) {
                $to = $assignee->email;
                $reply_to='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
                $subject = 'FEG Ticket #' . $ticketId;
                //$headers = 'MIME-Version: 1.0' . "\r\n";
                //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                //$headers .= 'From: ' . $reply_to . ' <' . $reply_to . '>' . "\r\n";
                //mail($to, $subject, $message, $headers);
                if(!empty($to)){
                    FEGSystemHelper::sendSystemEmail(array(
                        'to' => $to,
                        'subject' => $subject,
                        'message' => $message,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'from' => $reply_to,
                        //'bcc' => $bcc,
                        'configName' => 'SUBMIT TICKET EMAIL'
                    ));
                }
            }
        }
    }

    public function postSavepermission(Request $request,$id = 1)
    {
        $data = $request->all();
        unset($data['_token']);
        foreach ($data as $index => $value) {
            $data[$index] = implode(',', $data[$index]);
        }
        $data = $this->filterPermissions($data);

        $sbticketsetting = new SbticketSetting();
        unset($data['setting_type']);

        $id = $sbticketsetting->insertRow($data, $id);

        if ($id >= 1) {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        } else {
            return response()->json(array(
                'message' => 'Error',
                'status' => 'error'
            ));
        }
    }

    public function getSetting()
    {
        $ticket_setting = \DB::select("Select * FROM sbticket_setting");

        $individuals = \DB::select("Select id,first_name,last_name FROM users");
        $roles = \DB::select("Select group_id,name FROM tb_groups");

        $this->data['ticket_setting'] = $ticket_setting[0];
        $this->data['roles'] = $roles;
        $this->data['individuals'] = $individuals;
        $this->data['access'] = $this->access;
        return view('sbticket.setting', $this->data);
    }
    protected function filterPermissions($data){
        $cols = \App\Models\Sximo::getColumnTable('sbticket_setting');
        unset($cols["id"]);unset($cols["updated_at"]);unset($cols["setting_type"]);
        foreach ($cols as $col => $value){
            if(!array_key_exists($col,$data))
                    $data[$col]="";
        }
        return $data;
    }

}
