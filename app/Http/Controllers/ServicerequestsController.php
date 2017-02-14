<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Servicerequests;
use App\Models\servicerequestsSetting;
use App\Models\Ticketcomment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;
use App\Models\Core\TicketMailer;

class servicerequestsController extends Controller
{

    static $per_page = '10';
    public $module = 'Servicerequests';
    protected $layout = "layouts.main";
    protected $data = array();

    public function __construct()
    {
        parent::__construct();
        $this->model = new Servicerequests();

        $this->model->attachObserver('FirstEmail',new TicketMailer);
        $this->model->attachObserver('AddComment',new TicketMailer);

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'servicerequests',
            'pageUrl' => url('servicerequests'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('servicerequests.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'servicerequests')->pluck('module_id');
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
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : "AND sb_tickets.Status != 'closed'");

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


        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('servicerequests/data');
        $rows = $results['rows'];
        $comments = new Ticketcomment();

        $user_id = \Session::get('uid');
        $group_id = \Session::get('gid');
        foreach ($rows as $index => $row) {

            $flag = 1;
            $settings = (array)\DB::table('sbticket_setting')->first();
            $groupIds =explode(",", $settings['role1']);
            $users = (array) \DB::table('users')->select("id")->whereIn('group_id', $groupIds)->get();
            $AllTickets = array();
            foreach($users as $user){
                $AllTickets[] = $user->id;
            }
            //Add indivisuals to our array
            $AllTickets = array_merge($AllTickets,explode(",", $settings['individual1']));

            //get only assignee
            $groupIds=array();
            $groupIds =explode(",", $settings['role3']);
            $users=null;
            $users = (array) \DB::table('users')->select("id")->whereIn('group_id', $groupIds)->get();
            $OnlyAssigneees = array();
            foreach($users as $user){
                $OnlyAssigneees[] = $user->id;
            }
            //Add indivisuals to our array
            $OnlyAssigneees = array_merge($OnlyAssigneees,explode(",", $settings['individual3']));

            foreach ($rows as $index => $row) {
                $flag = 0;
                $status=0;
                if (isset($user_id))
                {
                    if ($group_id != 10) {

                        if(in_array($user_id,$OnlyAssigneees) && in_array($user_id,$AllTickets)){
                                    $status = 2; //if user group exists in both we keep track of it 
                         }
                        
                         if(($flag == 0 ||$status == 2) && in_array($user_id,explode(",", $row->assign_to)))
                            { 
                                $flag = 1; 
                             } 
                         if(($status != 2 && $flag != 1) && in_array($user_id,$AllTickets)) 
                          {
                                $flag = 1; 
                          }
                    if($flag == 0)
                      unset($rows[$index]);
                    }
                }
            }



            //this code is not woring for some reason
            /*if (isset($row->department_id) && !empty($row->department_id)&& false)
            {
                //$row->comments = $comments->where('TicketID', '=', $row->TicketID)->orderBy('TicketID', 'desc')->take(1)->get();
                $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $row->department_id . "");
                $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);

                $assign_employee_ids = explode(',', $row->assign_to);

                $members_access = array_unique(array_merge($assign_employee_ids, $department_memebers));
                foreach ($members_access as $i => $id) {
                    $get_user_id_from_employess = \DB::select("Select user_id FROM users WHERE id = " . $id . "");
                    //print"<pre>";
                    //print_r($get_user_id_from_employess);
                    if (isset($get_user_id_from_employess[0]->user_id)) {
                        $members_access[$i] = $get_user_id_from_employess[0]->user_id;
                        //echo $members_access[$i]."<br>";
                    }

                }

                if ($group_id != 10) {
                    if (!in_array($user_id, array_unique($members_access))) {
                        $flag = 0;
                    }
                }

                if ($flag == 1 && count($assign_employee_ids) > 0) {
                    echo count($assign_employee_ids);
                    $assign_employee_names = array();
                    foreach ($assign_employee_ids as $key => $value) {
                        $assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM users WHERE id = " . $value . "");
                    }
                    $row->assign_employee_names = $assign_employee_names;
                } else {
                    unset($rows[$index]);
                }
            }*/
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
        return view('servicerequests.table', $this->data);

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
        $this->data['uid'] = \Session::get('uid');
        $this->data['fid'] = \Session::get('fid');
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;


        return view('servicerequests.form', $this->data);
    }

    public function getShow($id = null)
    {
        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->find($id);
        $assign_employee_ids = explode(',', $row->assign_to);
        $assign_employee_names = array();

        if(!empty($assign_employee_ids[0]) ) {
            foreach ($assign_employee_ids as $key => $value) {
                $assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM users WHERE id = " . $value . "");
            }
        }
        $row->assign_employee_names = $assign_employee_names;
        if ($row) {
            $comments = new Ticketcomment();
            $this->data['comments'] = $comments->select(
                    'sb_ticketcomments.*', 
                    'users.username',  
                    'users.first_name',  
                    'users.last_name',  
                    'users.email',  
                    'users.avatar',  
                    'users.active',  
                    'users.group_id'
                )
                ->join('users', 'users.id', '=', 'sb_ticketcomments.UserID')
                ->where('TicketID', '=', $id)
                ->orderBy('Posted', 'asc')
                ->get();
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('sb_tickets');
        }

        
        $this->data['creator_details'] = !empty($row->entry_by) ? \SiteHelpers::getUserDetails($row->entry_by) : [];
        $this->data['id'] = $id;
        $this->data['uid'] = \Session::get('uid');
        $this->data['fid'] = \Session::get('fid');
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('servicerequests.view', $this->data);
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

    function postSave(Request $request, $id = NULL)
    {
        //$data['need_by_date'] = date('Y-m-d');
        //$rules = $this->validateForm();
        $sendMail=false;
        if(empty($id)) {
            $sendMail = true;
        }
        $rules = $this->validateForm();
        unset($rules['department_id']);
       //$rules = array('Subject' => 'required', 'Description' => 'required', 'Priority' => 'required', 'issue_type' => 'required', 'location_id' => 'required');
        //unset($rules['debit_card']);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('sb_tickets');
            $data['need_by_date']= date("Y-m-d", strtotime($request->get('need_by_date')));
            $data['Status']=$request->get('Status');
            
            if (empty($id)) {
                $data['Created'] = date('Y-m-d H:i:s');
            }
            $id = $this->model->insertRow($data, $id);
            if($sendMail){
                $message = $data['Description'];
                $this->model->notifyObserver('FirstEmail',[
                    "message"       =>$message,
                    "ticketId"      => $id,
                    'ticket'        => $data
                ]);

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

    public function postComment(Request $request)
    {
            $TicketID = $request->input('TicketID');

            //validate post for sb_tickets module
            $ticketsData = $this->validatePost('sb_tickets');
            $ticketsData['updated'] = date('Y-m-d H:i:s');
            
            $comment_model = new Ticketcomment();
            $total_comments = $comment_model->where('TicketID', '=', $TicketID)->count();

            $status = $ticketsData['Status'];
            $isStatusClosed = $status == 'closed';
            if (!$isStatusClosed && $total_comments == 0) {
                $ticketsData['Status'] = 'inqueue';
            }
            $ticketsData['closed']="";   
            if ($isStatusClosed) {
                $ticketsData['closed'] = date('Y-m-d H:i:s');
            }

            //re-populate info array to ticket comments module
            $this->info = $comment_model->makeInfo('ticketcomment');
            $commentsData = $this->validatePost('sb_ticketcomments');
            $commentsData['USERNAME'] = \Session::get('fid');
            $commentsData['Posted'] = date('Y-m-d H:i:s');;

            //@todo need separate table for comment attachments
            unset($ticketsData['file_path']);
            $comment_model->insertRow($commentsData, NULL);
            $ticketId = $request->input('TicketID');
            $this->model->insertRow($ticketsData, $ticketId);
            $message = $commentsData['Comments'];
            //send email
            $this->model->notifyObserver('AddComment',[
                    'message'       =>$message,
                    'ticketId'      => $ticketId,
                    'ticket'        => $ticketsData,
                    "department_id" =>"",                
                ]);

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));


    }

    function validateTicketCommentsForm()
    {
       // $rules = array();
       // $rules['Comments'] = 'required';
        //$rules['department_id'] = 'required|numeric';
      //  $rules['Priority'] = 'required';
      //  $rules['Status'] = 'required';
      //  return $rules;
    }

    public function departmentSendMail($departmentId, $ticketId, $message)
    {
        $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $departmentId . "");
        $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);

        $subject = 'FEG Ticket #' . $ticketId;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . CNF_REPLY_TO . ' <' . CNF_REPLY_TO . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM users  WHERE users.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
                mail($to, $subject, $message, $headers);
            }
        }
    }

    public function assignToSendMail($assignTo, $ticketId, $message)
    {
        $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
       if(count($assigneesTo) > 0) {
           foreach ($assigneesTo as $assignee) {
               if (isset($assignee->email)) {
                   $to = $assignee->email;
                   $subject = 'FEG Ticket #' . $ticketId;
                   $headers = 'MIME-Version: 1.0' . "\r\n";
                   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                   $headers .= 'From: ' . CNF_REPLY_TO . ' <' . CNF_REPLY_TO . '>' . "\r\n";
                   mail($to, $subject, $message, $headers);
               }
           }
       }
    }

    public function postSavepermission(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        foreach ($data as $index => $value) {
            $data[$index] = implode(',', $data[$index]);
        }
        $servicerequestssetting = new servicerequestsSetting();
        $id = $servicerequestssetting->insertRow($data, 1);

        if ($id == 1) {
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
        $ticket_setting = \DB::select("Select * FROM servicerequests_setting");

        $individuals = \DB::select("Select id,first_name,last_name FROM users");
        $roles = \DB::select("Select group_id,name FROM tb_groups");

        $this->data['ticket_setting'] = $ticket_setting[0];
        $this->data['roles'] = $roles;
        $this->data['individuals'] = $individuals;
        $this->data['access'] = $this->access;
        return view('servicerequests.setting', $this->data);
    }
}