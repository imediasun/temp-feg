<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Merchindisetheminggallary;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Image;

;

class MerchindisetheminggallaryController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'merchindisetheminggallary';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Merchindisetheminggallary();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'merchindisetheminggallary',
            'pageUrl' => url('merchindisetheminggallary'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('merchindisetheminggallary.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'merchindisetheminggallary')->pluck('module_id');
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
        $filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => "",
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
        $pagination->setPath('merchindisetheminggallary/data');
        $this->data['param'] = $params;
        $this->data['topMessage'] = @$results['topMessage'];
        $this->data['message'] = @$results['message'];
        $this->data['bottomMessage'] = @$results['bottomMessage'];

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
        return view('merchindisetheminggallary.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('img_uploads');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('merchindisetheminggallary.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('img_uploads');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('merchindisetheminggallary.view', $this->data);
    }

    function postCopy(Request $request)
    {
        foreach (\DB::select("SHOW COLUMNS FROM img_uploads ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO img_uploads (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM img_uploads WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $rules = array('merch_image' => 'required', 'location' => 'required');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data['loc_id'] = $request->get('location');
            $data['theme_name'] = $request->get('theme_name');
            $data['users'] = $request->get('employees_involved');
            $data['image_category'] = "mer";
            $data['batch'] = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $files = $request->file('merch_image');$i=0;
            foreach($files as $file) {
                $img = Image::make($file->getRealPath());
                $mime = $img->mime();
                if ($mime == 'image/jpeg') {
                    $extension = '.jpg';
                } elseif ($mime == 'image/png') {
                    $extension = '.png';
                } elseif ($mime == 'image/gif') {
                    $extension = '.gif';
                } else {
                    $extension = '';
                }
                $id = $this->model->insertRow($data, NULL);
                $img->save('./uploads/gallary/' . $id . $extension);
                $img->resize(101, 150);
                $img->save('./uploads/gallary/' . $id . '_thumb' . $extension);
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

    public function getDelete(Request $request, $id = null)
    {
        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
            die;
        }

        if ($id) {
            $this->model->destroy($id);
           $image_path = array(public_path() . '/uploads/gallary/' . $id . ".jpg", public_path() . '/uploads/gallary' . $id . "_thumb.jpg");
             foreach ($image_path as $img) {
                if (file_exists($img)) {
                    unlink($img);
                }
            }
            return Redirect::to('merchindisetheminggallary')->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')));
        }
    }
    function postRotate(Request $request)
    {
        $id=$request->get('id');
        $angle=$request->get('angle');
        $img = Image::make('./uploads/gallary/' . $id .'jpg');
        $img->rotate($angle);
    }
}