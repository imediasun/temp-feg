<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Servicerequests;
use App\Models\servicerequestsSetting;
use App\Models\Ticketcomment;
use App\Models\Ticketfollowers;
use App\Models\ticketsetting;
use App\Models\Core\TicketMailer;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;
use App\Library\FEG\System\FEGSystemHelper;
use App\Library\FEG\System\Formatter;  

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
            'return' => self::returnUrl(),
            
            'priorityOptions' => $this->model->getPriorities(),
            'statusOptions' => $this->model->getStatuses(),
            'issueTypeOptions' => $this->model->getIssueTypes(),
            'canChangeStatus' => ticketsetting::canUserChangeStatus(),
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('servicerequests.index', $this->data);
    }
    
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters
        
        
        // Get custom Ticket Type filter value 
        $customTicketTypeFilter = $this->model->getSearchFilters(['ticket_custom_type' => '', 'Status' => 'status']);
        $skipFilters = ['ticket_custom_type'];
        $mergeFilters = [];
        extract($customTicketTypeFilter); //$ticket_custom_type, $status
        
        // add custom ticket type filters
        if (!empty($ticket_custom_type)) {
            list($debitType, $issue_type) = explode('-', $ticket_custom_type);             
            if (empty($issue_type)) {
                $skipFilters[] = 'issue_type';
            }
            else {
                $mergeFilters['issue_type'] = [
                        'fieldName' => 'issue_type',
                        'operator' => 'equal',
                        'value' => $issue_type,
                    ];
            }
        }
        
        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($trimmedSearchQuery);
        
        
        if (!empty($debitType)) {
            $filter .= " AND sb_tickets.location_id IN (SELECT id from location where debit_type_id='$debitType') ";
        } 
        if (empty($status)) {
            $filter .= " AND sb_tickets.Status != 'closed' ";
        } 
        
        return $filter;
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
        \Session::put('config_id', $config_id);
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : "AND sb_tickets.Status != 'closed'");
        $filter = $this->getSearchFilterQuery();

        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'extraSorts' => [
                ['updated', 'desc']
            ],
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
        
        // This part of the code seems to be filtering tickets based on assignment
        // which is not required now hence commenting out
        /*
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



            //this code is not woring for some reason [removed on 15 Feb 2017]
        }
         
         *
        */

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
        //return view('servicerequests.table', $this->data);
        var_dump($this->data);
        die();

    }


    function getUpdate(Request $request, $id = null)
    {

        $isAdd = $this->data['isAdd'] = is_null($id);
        
        if ($isAdd) {
            if ($this->access['is_add'] == 0) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        else {
            if ($this->access['is_edit'] == 0) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('sb_tickets');
        }
        $this->data['uid'] = $userId = \Session::get('uid');
        $this->data['fid'] = \Session::get('fid');
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        $this->data['issueType'] = $row['issue_type'];
        $this->data['priority']  =  $row['Priority'];
        $this->data['status'] = $row['Status'];
        $this->data['ticketStatusLabel'] = Formatter::getTicketStatus($row['Status'], 'Open');
        $this->data['needByDate'] = \DateHelpers::formatDate($row['need_by_date']);
        $this->data['filePaths'] = explode(",", $row['file_path']);        
        $this->data['entryBy'] = $isAdd ? $userId : $row['entry_by'];
        $this->data['locationId'] = $isAdd ? \Session::get('selected_location') : $row['location_id'];

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
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('sb_tickets');
        }
        
        $comments = Ticketcomment::getCommentsWithUserData($id);
        $this->data['comments'] = $comments;

        $userId = \Session::get('uid');
        $this->data['access'] = $this->access;
        $this->data['id'] = $id;
        $this->data['uid'] = $userId;
        $this->data['fid'] = \Session::get('fid');
        $this->data['creator'] = $creator = !empty($row->entry_by) ? \SiteHelpers::getUserDetails($row->entry_by) : [];
        $this->data['following'] = Ticketfollowers::isFollowing($id, $userId);
        $this->data['followers'] = Ticketfollowers::getAllFollowers($id);
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        
        
        $this->data['commentsCount'] =  $commentsCount = $comments->count();
        $this->data['conversationCount'] = $commentsCount + 1;

        $this->data['ticketID'] = $row->TicketID;
        $this->data['ticketStatus'] = $row->Status;
        $this->data['ticketStatusLabel'] = Formatter::getTicketStatus($row->Status, 'Open');
        $this->data['dateNeeded'] = \DateHelpers::formatDate($row->need_by_date);
        $this->data['createdOn'] = \DateHelpers::formatDate($row->Created);
        $this->data['createdOnWithTime'] = \DateHelpers::formatDateCustom($row->Created);
        $this->data['updatedOn'] = \DateHelpers::formatDate($row->updated);
        $this->data['updatedOnWithTime'] = \DateHelpers::formatDateCustom($row->updated);
        $this->data['locationName'] = \SiteHelpers::gridDisplayView($row->location_id,'location_id','1:location:id:id|location_name');

        $this->data['creatorID'] = $row->entry_by;
        $this->data['creatorProfile'] = $creatorProfile = FEGSystemHelper::getUserProfileDetails($creator);

        $this->data['creatorName'] = $creatorProfile['fullName'];
        $this->data['creatorAvatar'] =  $creatorProfile['avatar'];
        $this->data['creatorTooltip'] = $creatorProfile['tooltip'];

        $this->data['myUserAvatar'] = FEGSystemHelper::getUserAvatarUrl($userId);
        $this->data['myUserTooltip'] = "You";        
        
        
        return view('servicerequests.view', $this->data);
    }

    function getSubscribe(Request $request, $id = NULL, $userID = NULL, $unfollow = NULL) {
        
        $unfollowDictionary = ["unfollow", "false", "unsubscribe"];
        $unsubscribe = !is_null($unfollow) && in_array(strtolower(''.$unfollow), $unfollowDictionary);
        
        if (!empty($id) && !empty($userID))  {
            if ($unsubscribe) {
                Ticketfollowers::unfollow($id, $userID, '', true);
            }
            else {
                Ticketfollowers::follow($id, $userID, '', true);
            }
        }        
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
    
    function validateDates ($data, $request = null) {
        $dateFields = ['need_by_date', 'Created', 'closed'];
        foreach($dateFields as $field) {
            if (isset($data[$field])) {
                if (!is_null($request)) {
                    if (empty($request->get($field))) {
                        $data[$field] = null;
                    }
                }                
            }            
        }
        return $data;
    }
    function postSaveInline(Request $request, $id)
    {
        $data = $this->validatePost('sb_tickets', true);
        $data = $this->validateDates($data, $request);
        unset($data['file_path']);
        if (!ticketsetting::canUserChangeStatus()) {
            unset($data['Status']);
            unset($data['closed']);
        }
        $rules = [];
        foreach($data as $field => $value) {
            $rules[$field] = 'required';
        }
        
        if(isset($data['Status'])) { 
            if ($data['Status'] != "closed") {
                $data['closed'] = null;
                unset($rules['closed']);
            }
            else {
                $rules['closed'] = 'required';
            }
        }
        if(isset($data['closed']) && !isset($data['Status'])) { 
            unset($data['closed']);
            unset($rules['closed']);
        }
        
 
        $validator = Validator::make($data, $rules);
        if ($validator->passes()) {
            $data['updated'] = date("Y-m-d H:i:s");
            $this->model->insertRow($data, $id);
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));            
        }
        else {
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));            
        }
    }
    
    
    function postSave(Request $request, $id = NULL)
    {
        $date = date("Y-m-d");
        //$data['need_by_date'] = date('Y-m-d');
        //$rules = $this->validateForm();
        $isAdd = empty($id);
        $rules = $this->validateForm();
        unset($rules['department_id']);
       //$rules = array('Subject' => 'required', 'Description' => 'required', 'Priority' => 'required', 'issue_type' => 'required', 'location_id' => 'required');
        //unset($rules['debit_card']);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('sb_tickets', !empty($id));
            $data = $this->validateDates($data);
            //$data['need_by_date']= date("Y-m-d", strtotime($request->get('need_by_date')));
            $data['Status'] = $request->get('Status');
            $oldStatus = $request->get('oldStatus');
            if (!$isAdd) {
                if (!ticketsetting::canUserChangeStatus()) {
                    unset($data['Status']);
                    unset($data['closed']);
                }
                if(isset($data['Status'])) { 
                    if ($data['Status'] == "closed") {
                        if ($oldStatus != 'closed') {
                            $data['closed'] = date('Y-m-d H:i:s');
                        }   
                    }
                    else {
                        $data['closed'] = null;
                    }
                }
            }            
            else {
                $data['Created'] = date('Y-m-d H:i:s');                
            }
            
            unset($data['oldStatus']);            
            $id = $this->model->insertRow($data, $id);
                        
            $files = $this->uploadTicketAttachments("/ticket-$id/$date/", "--$id");
            if (!empty($files['file_path'])) {                
                if ($isAdd) {
                    $data['file_path'] = $files['file_path'];                 
                }
                else {
                    $oldFiles = $data['file_path'];
                    if (empty($oldFiles)) {
                        $data['file_path'] = $files['file_path'];
                    }
                    else {
                        $data['file_path'] .= ','.$files['file_path'];
                    }
                }
                
                $data['_base_file_path'] = $files['_base_file_path'];
                
                $this->model->where('TicketID', $id)
                    ->update(['file_path' => $data['file_path']]);
            }
            
            if($isAdd){
                Ticketfollowers::follow($id, $data['entry_by'], '', true, 'requester');
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
    
    public function uploadTicketAttachments ($suffixPath = '', $suffixFileName = '') {
        return self::uploadFilesFromInputToPublicFolder($suffixPath, $suffixFileName);
    }
    public function uploadFilesFromInputToPublicFolder($suffixPath = '', $suffixFileName = '') {
        $request = new Request;
        $date = date('Y-m-d');
        $formConfig = $this->info['config']['forms'];
        $dataForTable = $this->info['config']['table_db'];
        $data = [];
        
        foreach ($formConfig as $config) {                    
            
            $field = $config['field'];            
            $isFileInput = $config['type'] == 'file' && !is_null(Input::file($field));
            
            if ($isFileInput) {
                
                $option = $config['option'];
                $isMultiple = !empty($option['image_multiple']);
                $baseUploadPath = $option['path_to_upload'];
                $uploadImage = $option['upload_type'] == 'image';
                
                $inputFiles = Input::file($field);
                if (!is_array($inputFiles)) {
                    $inputFiles = [$inputFiles];
                }
                
                $urls = [];
                $filePaths = [];
                $baseTargetPath = $baseUploadPath.$suffixPath;
                $paths = FEGSystemHelper::getSanitisedPublicUploadPath($baseTargetPath);
                $targetPath = $paths['target'];
                $realPath = $paths['real'];
                $urlPath = $paths['url'];
                
                foreach($inputFiles as $file) {
                    
                    $oringalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalExtension = $file->getClientOriginalExtension(); 
                    $targetFile = $oringalFilename. $suffixFileName .                            
                            (empty($originalExtension) ? '': (".$originalExtension"));
                    $targetFile = FEGSystemHelper::possiblyRenameFileToResolveDuplicate($targetFile, $targetPath);
                    try {
                        $success = $file->move($targetPath, $targetFile);
                    } catch (Exception $ex) {
                        $success = false;
                    }
                    if($success) {
                        if ($uploadImage && !empty($option['resize_width'])) {
                            $resizeWidth = $option['resize_width'];
                            $resizeHeight = $option['resize_height'];

                            if (empty($resizeHeight)) {
                                $resizeHeight = $resizeWidth;
                            }
                            $fileWithPath = $realPath.$targetFile;
                            \SiteHelpers::cropImage($resizeWidth, $resizeHeight, $fileWithPath, $originalExtension, $fileWithPath);
                        }                        
                        
                        $urls[] = $urlPath.$targetFile;
                        $filePaths[] = $realPath.$targetFile;
                    }                    
                }
                
                $data['_base_'.$field] = implode(',', $filePaths);
                $data[$field] = implode(',', $urls);

            }            
        }
        return $data;    
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
        $date = date("Y-m-d");
        $ticketId = $request->input('TicketID');

        //validate post for sb_tickets module
        $ticketsData = $this->validatePost('sb_tickets', true);

        $ticketsData['updated'] = date('Y-m-d H:i:s');

        $comment_model = new Ticketcomment();
        $total_comments = $comment_model->where('TicketID', '=', $ticketId)->count();
        
        if (!ticketsetting::canUserChangeStatus()) {
            unset($ticketsData['Status']);
            unset($ticketsData['closed']);
        }
        elseif (isset($ticketsData['Status'])) {
            $status = $ticketsData['Status'];
            $isStatusClosed = $status == 'closed';
            if ($isStatusClosed) {                
                $oldStatus = $request->get('oldStatus');
                if ($oldStatus != 'closed') {
                    $ticketsData['closed'] = date('Y-m-d H:i:s');
                }
            }
            else {
                $ticketsData['closed']= null;   
            }
        }

        //re-populate info array to ticket comments module
        $this->info = $comment_model->makeInfo('ticketcomment');
        $commentsData = $this->validatePost('sb_ticketcomments', true);

        $commentsData['USERNAME'] = \Session::get('fid');
        $commentsData['Posted'] = date('Y-m-d H:i:s');;

        //@todo need separate table for comment attachments
        unset($ticketsData['oldStatus']);
        unset($ticketsData['file_path']);
        $requestedOn = $ticketsData['Created'];
        unset($ticketsData['Created']);
        $commentId = $comment_model->insertRow($commentsData, NULL);

        $files = $this->uploadTicketAttachments("/ticket-$ticketId/$date/", "--$ticketId");
        if (!empty($files['Attachments'])) {
            $comment_model->where('CommentID', $commentId)
                ->update(['Attachments' => $files['Attachments']]);                
        }

        $this->model->insertRow($ticketsData, $ticketId);
        $message = $commentsData['Comments'];
        if (!empty($files['Attachments'])) {
            $ticketsData['_base_file_path'] = $files['_base_Attachments'];
        }
        /*
            $isFollowing = $request->input('isFollowingTicket');
            $allFollowers = $request->input('allFollowers');
                       
            if (!empty($ticketsData['assign_to'])) {
                //Ticketfollowers::follow($ticketId, $ticketsData['assign_to']);
            }
            if (!in_array($ticketsData['entry_by'], $allFollowers)) {
               // Ticketfollowers::follow($ticketId, $ticketsData['entry_by']);
            }
            
            $recordedFollowers = Ticketfollowers::getRecordedFollowers($ticketId);
            $unFollowers = [];
            foreach($recordedFollowers as $follower) {
                if (!in_array($follower, $allFollowers)) {
                    $unFollowers[] = $follower;
                }
            }
            Ticketfollowers::unfollow($ticketId, $unFollowers, '', true);
            
            $location = $request->input('location_id');
            $defaultFollowers = Ticketfollowers::getDefaultFollowers($location);
            
            $customFollowers = [];
            foreach($allFollowers as $follower) {
                if (!in_array($follower, $defaultFollowers)) {
                    $customFollowers[] = $follower;
                }
            }            
            Ticketfollowers::follow($ticketId, $customFollowers, '', true);
        */
            
        //send email
        $ticketsData['Created'] = $requestedOn;
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