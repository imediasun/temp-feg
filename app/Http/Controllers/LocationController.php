<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class LocationController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'location';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Location();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'location',
            'pageUrl' => url('location'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex(Request $request, $id = 0)
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        $this->data['id'] = $id;
        return view('location.index', $this->data);
    }

    public function postData(Request $request, $id = null)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'location')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
          //  \Session::put('config_id',$config_id);
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
            \Session::put('config_id', '0');
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
        if ($id == null) {
            $results = $this->model->getRows($params);

        } else {
            $results['rows'] = $this->model->getRow($id);
            $results['total'] = 1;
        }
        foreach ($results['rows'] as $result) {

            if ($result->contact_id == 0) {
                $result->contact_id="";

            }
            if ($result->merch_contact_id == 0) {
                $result->merch_contact_id="";

            }
            if ($result->field_manager_id == 0) {
                $result->field_manager_id="";
            }
            if ($result->tech_manager_id == 0) {
                $result->tech_manager_id="";
            }

            if ($result->general_contact_id == 0) {
                $result->general_contact_id="";
            }
            if ($result->technical_contact_id == 0) {
                $result->technical_contact_id="";
            }
            if ($result->regional_contact_id == 0) {
                $result->regional_contact_id="";
            }
            if ($result->senior_vp_id == 0) {
                $result->senior_vp_id="";
            }



        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }


        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('location/data');
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
        return view('location.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('location');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['id'] = $id;
        \Session::put('location_updated',$id);
        return view('location.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('location');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('location.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM location ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO location (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM location WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $form_data['date_opened'] = date('Y-m-d');
        $form_data['date_closed'] = date('Y-m-d');
        $rules = $this->validateForm();
        $input_id=$request->get('id');
        if(\Session::get('location_updated') != $input_id) {
            $rules['id'] = 'required|unique:location';
        }
        else{
            $rules['id'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('location');
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

    function getDetails(Request $request, $id = 0)
    {
        if ($id > 0) {
            $this->data['location_id'] = $id;
            $this->data['row'] = $this->model->getLocation($id);
            return view('location.details', $this->data);
        } else {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

    }
    function postUpdatelocation(Request $request, $id)
    {
        $data = $request->all();
        array_pop($data);
        array_shift($data);
        $update = \DB::table('location')->where('id', '=', $id)->update($data);
        if ($update) {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));
        }
    }

    function getLocation($id = null)
    {
        $this->data['access'] = $this->access;
        $this->data['id'] = $id;
        return view('location.index', $this->data);
    }
function getIsLocationAvailable($id)
{
    $isAvailable=\DB::select("select count('id') as count from location where id=$id");
    if($isAvailable[0]->count > 0)
    {
        return response()->json(array(
            'status' => 'error',
            'message' => \Lang::get('*Location Id Exists Already')
        ));
    }
    else {
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('*Location Available')
        ));
    }
}
    function getGmailData()
    {
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "tickets@tickets.fegllc.com";
        $password = "8d<Sy%68";

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
        echo "connection established";
        /* grab emails */
        $emails = imap_search($inbox,'TEXT "ticket-reply-"');
        /* if emails are returned, cycle through each... */
        if($emails) {
            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                //var_dump($overview[0]);
                $from = $overview[0]->from;
                $from = substr($from, strpos($from, "<") + 1,-1);
                $to = $overview[0]->to;
                $to=substr($to, strpos($to, "<") + 1,-1);
                // date format according to sql
                $date = str_replace('at','',$overview[0]->date);
                $posted =date_create($date);

                //Parse subject to find comment id
                $subject = $overview[0]->subject;
                $ticketId = explode('-', $to);
                echo "T0:".$to;
                echo "<pre>";
                print_r($ticketId);
                $ticketId = substr($ticketId[2], strpos($ticketId[2], "@") + 1);
                echo $ticketId;
                //insert comment
                $postUser = \DB::select("Select * FROM users WHERE email = '". $from ."'");
                $userId = $postUser[0]->id;

                $message = imap_fetchbody($inbox,$email_number,1);

                //Insert In sb_comment table
               // $comment_model = new Ticketcomment();
               // $commentsData = array(
               //    'TicketID' => $ticketId,
                //    'Comments' => $message,
                //    'Posted'   => $posted,
                //    'UserID'   => $userId
               // );
               // $comment_model->insertRow($commentsData, NULL);
            }

            imap_delete($inbox,$email_number);
        }
        /* close the connection */
        imap_close($inbox);
    }
}