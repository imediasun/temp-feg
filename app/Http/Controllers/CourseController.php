<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;
use Carbon\Carbon;

class CourseController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'course';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Course();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'course',
            'pageUrl' => url('course'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('course.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'course')->pluck('module_id');
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
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('course/data');
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
        return view('course.table', $this->data);


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
        if ($id != '') {
            $start_date = date_create($row->start_date);
            $row->start_date = date_format($start_date, "m/d/Y");
            $end_date = date_create($row->end_date);
            $row->end_date = date_format($end_date, "m/d/Y");
            $advertisement_start_date = date_create($row->advertisement_start_date);
            $row->advertisement_start_date = date_format($advertisement_start_date, "m/d/Y");
            $advertisement_end_date = date_create($row->advertisement_end_date);
            $row->advertisement_end_date = date_format($advertisement_end_date, "m/d/Y");

        }
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('courses');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('course.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('courses');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('course.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM courses ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO courses (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM courses WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $date = $request->get('start_date');
        $endDate = $request->get('end_date');
        $advStartDate = $request->get('advertisement_start_date');
        $advEndDate = $request->get('advertisement_end_date');
        $start_date = Carbon::createFromFormat('m/d/Y', $date);
        $end_date = Carbon::createFromFormat('m/d/Y', $endDate);
        $advertisement_start_date = Carbon::createFromFormat('m/d/Y', $advStartDate);
        $advertisement_end_date = Carbon::createFromFormat('d-m-Y', $advEndDate);

        $rules = $this->validateForm();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $data = $this->validatePost('courses');
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['advertisement_start_date'] = $advertisement_start_date;
            $data['advertisement_end_date'] = $advertisement_end_date;
            $id = $this->model->insertRow($data, $request->input('id'));

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