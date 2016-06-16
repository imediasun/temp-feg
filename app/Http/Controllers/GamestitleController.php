<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Gamestitle;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class GamestitleController extends Controller
{
    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'gamestitle';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Gamestitle();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'gamestitle',
            'pageUrl' => url('gamestitle'),
            'return' => self::returnUrl()
        );
    }

    public function getIndex()
    {

        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('gamestitle.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'gamestitle')->pluck('module_id');
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

            if ($result->has_manual == 1) {
                $result->has_manual = "Yes";

            } else {
                $result->has_manual = "No";
            }
                if ($result->has_servicebulletin == 1) {
                    $result->has_servicebulletin = "Yes";

            } else {
                    $result->has_servicebulletin = "No";
            }
            if ($result->num_prize_meters == 1) {
                $result->num_prize_meters = "Yes";

            } else {
                $result->num_prize_meters = "No";
            }
        }

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('gamestitle/data');

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
        }// Render into template
        return view('gamestitle.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('game_title');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('gamestitle.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);

        if ($row) {


                if ($row[0]->has_manual == 1) {
                    $row[0]->has_manual = "Yes";

                } else {
                    $row[0]->has_manual = "No";
                }
                if ($row[0]->has_servicebulletin == 1) {
                    $row[0]->has_servicebulletin = "Yes";

                } else {
                    $row[0]->has_servicebulletin = "No";
                }
                if ($row[0]->num_prize_meters == 1) {
                    $row[0]->num_prize_meters = "Yes";

                } else {
                    $row[0]->num_prize_meters = "No";

            }
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('game_title');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('gamestitle.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM game_title ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO game_title (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM game_title WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $files = array('manual' => Input::file('manual'),'bulletin'=> Input::file('service_bulletin'));
        $rules = $this->validateForm();
     //   $rules['manual']='Required|mimes:pdf';
        $rules['service_bulletin']='Required|mimes:pdf';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if($id==0) {
                $data = $this->validatePost('game_title');
                $id = $this->model->insertRow($data, $request->input('id'));
            }
            else
            {
                $data = $this->validatePost('game_title');
                $id = $this->model->insertRow($data,$id);
            }
                $updates = array();
                if ($request->hasFile('manual')) {
                    $file = $request->file('manual');
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                    $newfilename = $id . '.' . $extension;
                    $destinationPath = './uploads/games/manuals';
                    $uploadSuccess = $request->file('manual')->move($destinationPath, $newfilename);
                    if ($uploadSuccess) {
                        $updates['manual'] = $newfilename;
                        $updates['has_manual'] = '1';
                    }
                }
                if ($request->hasFile('service_bulletin')) {
                    $file1 = $request->file('service_bulletin');
                    $filename1 = $file1->getClientOriginalName();
                    $extension1 = $file1->getClientOriginalExtension();
                    $newfilename1 = $id . '.' . $extension1;
                    $destinationPath1 = './uploads/games/bulletins';
                    $uploadSuccess1 = $request->file('service_bulletin')->move($destinationPath1, $newfilename1);
                    if ($uploadSuccess1) {
                        $updates['bulletin'] = $newfilename1;
                        $updates['has_servicebulletin'] = '1';
                    }
                }
                $this->model->insertRow($updates, $id);
                return response()->json(array(
                    'status' => 'success',
                    'message' => \Lang::get('core.note_success')
                ));

        }

         else {
            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'status' => 'error',
                'message' => $message

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
    function getUpload(Request $request,$id=0)
    {
        $uploadType=$request->get('type');
        $this->data['game_id']=$id;
        switch($uploadType)
        {
            case 1:
                $this->data['pageTitle']="Game Image";
                $this->data['pageNote']="Game Image";
                $this->data['upload_inst']='**MUST BE jpg, jpeg, gif, png**';
                break;
            case 2:
                $this->data['pageTitle']="Game Manual";
                $this->data['pageNote']="Game Manual";
                $this->data['upload_inst']='**MUST BE PDF**';
                break;
            case 3:
                $this->data['pageTitle']="Game Bulletin";
                $this->data['pageNote']="Game Bulletin";
                $this->data['upload_inst']='**MUST BE PDF**';
                break;
                defaule:
                $this->data['pageTitle']="Games Title";
                $this->data['pageNote']="";

        }
        $this->data['upload_type']=$uploadType;
        $res=\DB::table('game_title')->select('img','game_title')->where('id', $id)->get();
        $this->data['game_image']=$res[0]->img;
        $this->data['game_title']=$res[0]->game_title;
         return view('gamestitle.upload',$this->data);

    }
    function postUpload(Request $request)
    {

        $files = array('file' => Input::file('avatar'));
        $type=Input::get('upload_type'); $id = Input::get('id');
        $destinationPath="./uploads/games";
        $id=Input::get('id');
        $upload_type=Input::get('upload_type');
      //  $rules=array();
        switch($type) {
            case 1:
                $rules = array('file' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png
                $destinationPath .= '/images';
                break;
            case 2:
                $rules = array('file' => 'required|mimes:pdf'); //mimepdf
                $destinationPath .= '/manuals';
                break;
            case 3:
                $rules = array('file' => 'required|mimes:pdf'); //mimpdf
                $destinationPath .= '/bulletins';
                break;
            default:
                $rules = array('file' => 'required|mimes:pdf'); //mimpdf
                $destinationPath .= '/images';

        }
                // doing the validation, passing post data, rules and the messages
                $validator = Validator::make($files,$rules);

            if ($validator->fails()) {

                return Redirect::to('gamestitle/upload/' . $id ."?type=".$upload_type)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);

        } else {
        $updates = array();
        $file = $request->file('avatar');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //if you need extension of the file
        $newfilename = $id . '.' . $extension;
        $uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);
        if ($uploadSuccess) {
            if($type==1)
            {
                $updates['img'] = $newfilename;
            }
            elseif($type==2)
            {
                $updates['manual'] = $newfilename;
                $updates['has_manual']='1';
            }
            elseif($type==3)
            {
                $updates['bulletin'] = $newfilename;
                $updates['has_servicebulletin']='1';
            }

        }
        $this->model->insertRow($updates, $id);
        $return = 'gamestitle/upload/' . $id;
        return Redirect::to('gamestitle/upload/' . $id."?type=".$upload_type)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

    }



    }
    function getImagesupdate()
    {
        $rows=\DB::table('game_title')->select('id')->get();
        foreach($rows as $row)
        {
            $img=$row->id.".jpg";
            \DB::table('game_title')->where('id','=',$row->id)->update(array('img'=>$img));
        }
    }
    function getManualupdate()
{
    $rows=\DB::table('game_title')->select('id')->get();
    foreach($rows as $row)
    {
        $manual=$row->id.".pdf";
        \DB::table('game_title')->where('id','=',$row->id)->update(array('manual'=>$manual));
    }
}
    function getBulletinupdate()
    {
        $rows=\DB::table('game_title')->select('id')->get();
        foreach($rows as $row)
        {
            $bulletin=$row->id.".pdf";
            \DB::table('game_title')->where('id','=',$row->id)->update(array('bulletin'=>$bulletin));
        }
    }
    function getImageremove(Request $request,$id)
    {
        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
            die;

        }
        // delete multipe rows
        if (true) {
            $filename = public_path()."\\uploads\\games\\images\\".$id.".jpg";
            if (\File::exists($filename)) {
               \ File::delete($filename);
            }
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