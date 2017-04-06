<?php
namespace App\Http\Controllers\core;

use App\Http\Controllers\controller;
use App\Models\Core\Users;
use App\Models\Core\Groups;
use App\Models\Sximo\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Session, Auth, DB;


class UsersController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'users';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->model = new Users();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'users',
            'pageUrl' => url('core/users'),
            'pageDetails'       => array('url' => 'core/users', 'module' => 'users'),    
            'siteUrl'           => url(),
            'return' => self::returnUrl()

        );
    }

    public function getCheckAccess()
    {
        $moduleName = Input::get('module');
        $moduleId = Module::where('module_name', $moduleName)->pluck('module_id');
        if (!empty($moduleId)) {
            $access = $this->model->validAccess($moduleId);
        }
        else {
            $access = $this->model->validPageAccess($moduleName);
        }
        
        return response()->json($access);
    }

    public function getIndex(Request $request, $id=null)
    {
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		$this->data['access']		= $this->access;
        $this->pageData($request, $id);
        return view('core.users.index', $this->data);
    }
    
    public function pageData(Request $request = null, $id=null) {
       $moduleName = $this->data['pageModule'];
       if ($this->access['is_view'] == 0) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
       }
 
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'users')->pluck('module_id');

        $this->data['modules'] = \DB::table('tb_module')->where('module_type', '!=', 'core')->orderBy('module_title', 'asc')->get();
        $this->data['pages'] = \DB::table("tb_pages")->orderBy('title', 'asc')->get();

        $this->data['module_id'] = $module_id;

        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
            \Session::put("{$moduleName}_config_id", $config_id);
        } 
        elseif (\Session::has("{$moduleName}_config_id")) {
            $config_id = \Session::get("{$moduleName}_config_id");
        }
        else {
            $config_id = 0;
        }
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
        }
        else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        \Session::put("{$moduleName}_config_id", $config_id);

        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
        //@todo check if that condition is needed in future
        //$filter .= " AND tb_users.group_id >= '".\Session::get('gid')."'" ;


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
        $results = $this->model->getRows($params, $id);

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('users');
        $this->data['param'] = $params;

        $this->data['rowData'] = $results['rows'];

        $this->data['modules'] = \DB::table('tb_module')->where('module_type', '!=', 'core')->orderBy('module_title', 'asc')->get();
        $this->data['pages'] = \DB::table("tb_pages")->orderBy('title', 'asc')->get();


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
        $this->data['setting'] = $this->info['setting'];
        // Detail from master if any

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
    }
            
	public function postData(Request $request, $id=null)
	{
        $this->pageData($request, $id);
        // Render into template
        return view('core.users.table', $this->data);
	}
        
    public function getPlay($id = null)
    {
        $return_id = \Session::get('uid');
        $row = Users::find($id);
        Auth::loginUsingId($row->id);

        DB::table('users')->where('id', '=', $row->id)->update(array('last_login' => date("Y-m-d H:i:s")));
        //Session::regenerate();

        Session::put('uid', $row->id);
        Session::put('gid', $row->group_id);
        Session::put('eid', $row->email);
        Session::put('flgStatus', 1);
        Session::put('ll', $row->last_login);
        Session::put('fid', $row->first_name . ' ' . $row->last_name);
        Session::put('user_name', $row->username);
        Session::put('ufname', $row->first_name);
        Session::put('ulname', $row->last_name);
        Session::put('company_id', $row->company_id);
        $user_locations = \SiteHelpers::getLocationDetails($row->id);
        if (!empty($user_locations)) {
            Session::put('user_locations', $user_locations);
            Session::put('selected_location', $user_locations[0]->id);
            Session::put('selected_location_name', $user_locations[0]->location_name_short);
        }
        Session::put('get_locations_by_region', $row->get_locations_by_region);
        Session::put('email_2', $row->email_2);
        Session::put('primary_phone', $row->primary_phone);
        Session::put('secondary_phone', $row->secondary_phone);
        Session::put('street', $row->street);
        Session::put('city', $row->city);
        Session::put('state', $row->state);
        Session::put('zip', $row->zip);
        Session::put('reg_id', $row->reg_id);
        Session::put('restricted_mgr_email', $row->restricted_mgr_email);
        Session::put('restricted_user_email', $row->restricted_user_email);
        Session::save();

        if (Session::get('return_id') == $id) {

            Session::put('return_id', '');
        } else {

            Session::put('return_id', $return_id);
        }
        return Redirect::to('dashboard');
    }

    function get($id = NULL)
    {
        $data['profile_img'] = \DB::table('users')->where('id', $id)->pluck('avatar');
        $data['return'] = "";
        return view('core.users.upload', $data);
    }

    function postUpload(Request $request)
    {
        $files = array('image' => Input::file('avatar'));
        // setting up rules
        $rules = array('image' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('core/users/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);;

        } else {
            $updates = array();
            $file = $request->file('avatar');
            $destinationPath = './uploads/users/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . '.' . $extension;
            $uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $updates['avatar'] = $newfilename;
            }
            $this->model->insertRow($updates, $id);
            $return = 'core/users/upload/' . $id;
            return Redirect::to('core/users/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        }


    }

    function getBlock($id = null)
    {
        DB::table('users')->where('id', '=', $id)->update(array('banned' => 1));
        //return Redirect::to('dashboard')->with("messagetext",\Lang::get('core.note.success'))-with('msgstatus','success');
        return Redirect::to('core/users')->with('messagetext', \Lang::get('core.note_block'))->with('msgstatus', 'success');
    }

    function getUnblock($id = null)
    {

        DB::table('users')->where('id', '=', $id)->update(array('banned' => 0));
        //return Redirect::to('dashboard')->with("messagetext",\Lang::get('core.note.success'))-with('msgstatus','success');
        return Redirect::to('core/users')->with('messagetext', \Lang::get('core.note_unblock'))->with('msgstatus', 'success');
    }

    function getUpdate(Request $request, $id = null)
    {


        if ($id == '') {

            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            $this->data['user_locations'] = 0;
        }

        if ($id != '') {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            $this->data['user_locations'] = $this->model->getLocations($id);
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('users');
        }

        $this->data['id'] = $id;

        $this->data['modules'] = \DB::table('tb_module')->where('module_type', '!=', 'core')->orderBy('module_title', 'asc')->get();
        $this->data['pages'] = \DB::table("tb_pages")->orderBy('title', 'asc')->get();
        return view('core.users.form', $this->data);
    }

    function getUpload($id = NULL)
    {
        $data['profile_img'] = \DB::table('users')->where('id', $id)->pluck('avatar');
        $data['return'] = "";
        return view('core.users.upload', $data);
    }

    function postUpload1(Request $request)
    {

        $files = array('image' => Input::file('avatar'));
        // setting up rules
        $rules = array('image' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');
        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('core/users/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);;

        } else {
            $updates = array();
            $file = $request->file('avatar');
            $destinationPath = './uploads/users/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . '.' . $extension;
            $uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $updates['avatar'] = $newfilename;
            }
            $this->model->insertRow($updates, $id);
            $return = 'core/users/upload/' . $id;
            return Redirect::to('core/users/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        }


    }

    public function getShow($id = null, $mode = null)
    {
        if ($this->access['is_detail'] == 0) {
            if ($mode == 'popup') {
                return \Lang::get('core.note_restric');
            }
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        
        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('users');
        }
        $this->data['mode'] = $mode;
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $location_details = \SiteHelpers::getLocationDetails($id);
        $this->data['user_locations'] = $location_details;
        if (empty($mode)) {
            return view('core.users.view', $this->data);
        }
        else {
            return view("core.users.view.$mode", $this->data);
        }
        
    }

    function postSave(Request $request, $id = 0)
    {
        $form_data['date'] = date('Y-m-d');
        $form_data['last_login'] = date('Y-m-d');
        $form_data['created_at'] = date('Y-m-d');
        $form_data['updated_at'] = date('Y-m-d');
        $rules = $this->validateForm();
        $rules['g_mail'] = 'email';
        $rules['g_password'] = 'min:8';
       

        $rules['email'] = 'required|email|unique:users,email';
        if ($request->input('id') == '') {
            $rules['password'] = 'required|between:6,12';
            $rules['password_confirmation'] = 'required|between:6,12';
            $rules['username'] = 'required|min:2|unique:users';


        } else {
            $rules['email'] = 'required|email|unique:users,email,'.$request->input('id');
            if ($request->input('password') != '') {
                $rules['password'] = 'required|between:6,12';
                $rules['password_confirmation'] = 'required|between:6,12';
            }


        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('users');




            if ($request->input('id') == '') {
                $logId = Users::insertLog($this->module, 'insert');
                $data['password'] = \Hash::make(Input::get('password'));
            } else {
                $logId = Users::insertLog($this->module, 'update');
                if (Input::get('password') != '') {
                    $data['password'] = \Hash::make(Input::get('password'));
                } else {
                    unset($data['password']);
                }

                $file = $request->file('avatar');
                //in case of editing removes empty values from array and does not allow to
                //set empty redirect value
                $data = array_filter($data);
            }
            //moved redirect_link in bottom because if user want to reset value
            //then above array_filter does not remove it
            $data['redirect_link'] = $request->get('redirect_link');

            $data['active']=$request->get('active');
            /* add google account password and email*/
            $data['g_mail'] = $request->input('g_mail');
            if(!is_null($request->input('g_password')))
            {
                $password = base64_encode(env('SALT_KEY').$request->input('g_password').env('SALT_KEY'));
                $data['g_password'] = $password;
            }
            $id = $this->model->insertRow($data, $request->input('id'));
            $all_locations = Input::get('all_locations');
            if (empty($all_locations)) {
                $this->model->inserLocations($request->input('multiple_locations'), $id, $request->input('id'));
                \DB::update("update users set has_all_locations=0 where id=$id");
            } else {
                $all_locations = \DB::select('select id from location');
                $locations = array();
                $i = 0;
                foreach ($all_locations as $l) {
                    $locations[$i] = $l->id;
                    $i++;
                }
                $this->model->inserLocations($locations, $id, $request->input('id'));
                \DB::update("update users set has_all_locations=1 where id=$id");
            }
            if (!is_null(Input::file('avatar'))) {
                $updates = array();
                $file = $request->file('avatar');
                $destinationPath = './uploads/users/';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename = $id . '.' . $extension;
                $uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $updates['avatar'] = $newfilename;
                }

                $this->model->insertRow($updates, $id);
            } else {

            }
            $user_locations = \SiteHelpers::getLocationDetails(\Session::get('uid'));
            $user_location_ids = \SiteHelpers::getIdsFromLocationDetails($user_locations);
            $has_all_locations = empty($request->input('has_all_locations')) ? 0 : 1;
            \Session::put('user_has_all_locations', $has_all_locations);              
            if (!empty($user_locations)) {
                \Session::put('user_locations', $user_locations);
                \Session::put('selected_location', $user_locations[0]->id);
                \Session::put('selected_location_name', $user_locations[0]->location_name_short);
                \Session::put('user_location_ids', $user_location_ids);
            }
            if (!is_null($request->input('apply'))) {
                $return = 'core/users/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'core/users?return=' . self::returnUrl();
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        } else {

            return Redirect::to('core/users/update/' . $request->input('id'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }

    }

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $logId = Users::insertLog($this->module, 'delete');
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));
            
            // clean orphan user location assignmens
            \SiteHelpers::cleanUpUserLocations();

            // redirect
            return Redirect::to('core/users')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');

        } else {
            return Redirect::to('core/users')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }

    }

    public function getSearch($mode = 'native')
    {

        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['searchMode'] = 'native';
        $this->data['pageUrl'] = url('core/users');
        return view('sximo.module.utility.search', $this->data);

    }

    function getBlast()
    {
        $this->data = array(
            'groups' => Groups::all(),
            'pageTitle' => 'Blast Email',
            'pageNote' => 'Send email to users'
        );
        return view('core.users.blast', $this->data);
    }

    function postDoblast(Request $request)
    {

        $rules = array(
            'subject' => 'required',
            'message' => 'required|min:10',
            'groups' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            //gabe requested to set from email address as
            $replyEmailAddress = "donotreply@fegllc.com";
            if (!is_null($request->input('groups'))) {
                $groups = $request->input('groups');
                for ($i = 0; $i < count($groups); $i++) {
                    if ($request->input('uStatus') == 'all') {
                        $users = \DB::table('users')->where('group_id', '=', $groups[$i])->get();
                    } else {
                        $users = \DB::table('users')->where('active', '=', $request->input('uStatus'))->where('group_id', '=', $groups[$i])->get();
                    }
                    $count = 0;
                    foreach ($users as $row) {

                        $to = $row->email;
                        $subject = $request->input('subject');
                        $message = $request->input('message');
                        $message = $this->replaceVariables($message, $row);
                        $headers = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                        $headers .= 'From: ' . CNF_APPNAME . ' <' . $replyEmailAddress . '>' . "\r\n";
                        mail($to, $subject, $message, $headers);

                        $count = ++$count;
                    }

                }
                //Total 8 Messages has been sent
                //Total 1 Message has been sent
                return Redirect::to('core/users/blast')->with('messagetext', 'Total '.$count<=1?"$count Message has been sent":"$count Messages has been sent")->with('msgstatus', 'success');

            }
            return Redirect::to('core/users/blast')->with('messagetext', 'No Message has been sent')->with('msgstatus', 'info');


        } else {

            return Redirect::to('core/users/blast')->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();

        }

    }

    protected function replaceVariables($content,$object){
        $content = str_replace("[fullname]",$object->first_name." ".$object->last_name,$content);
        $content = str_replace("[first_name]",$object->first_name,$content);
        $content = str_replace("[last_name]",$object->last_name,$content);
        $content = str_replace("[email]",$object->email,$content);
        return $content;
    }
    public function getSendPasswordResetEmails()
    {
        $this->model->passwordForgetEmails();
    }
public function getUserDetails($id)
{
    $request=new Request();
return $this->getIndex($request,$id);
//    return view('core.users.index', $data);
}
}
