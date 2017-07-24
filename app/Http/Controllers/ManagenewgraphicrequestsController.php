<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Managenewgraphicrequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class ManagenewgraphicrequestsController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'managenewgraphicrequests';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Managenewgraphicrequests();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'managenewgraphicrequests',
            'pageUrl' => url('managenewgraphicrequests'),
            'return' => self::returnUrl()
        );
    }

    public function getApprove($id)
    {


        $request = Managenewgraphicrequests::find($id);
        $data = array(
            'status_id' => 2,
            'aprrove_user_id' => \Session::get('uid'),
            'approve_date' => date('Y-m-d')
        );

        if ($request->insertRow($data, $id)) {
            return Redirect::to('managenewgraphicrequests')->with('messagetext', 'Graphic request approved')->with('msgstatus', 'success');
        } else {
            return Redirect::to('managenewgraphicrequests')->with('messagetext', 'Error on approving graphic request')->with('msgstatus', 'error');
        }
    }

    public function getDeny($id)
    {
        $request = Managenewgraphicrequests::find($id);
        $data = array(
            'status_id' => 0,
        );

        if ($request->insertRow($data, $id)) {
            return Redirect::to('managenewgraphicrequests')->with('messagetext', 'Graphic request denied')->with('msgstatus', 'success');
        } else {
            return Redirect::to('managenewgraphicrequests')->with('messagetext', 'Error on declining graphic request')->with('msgstatus', 'error');
        }

    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('managenewgraphicrequests.index', $this->data);
    }
    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null($customQueryString) ? (is_null(Input::get('search')) ? '' : $this->buildSearch()) :
            $this->buildSearch($customQueryString);

        // Get assigned locations list as sql query (part)
        //$locationFilter = \SiteHelpers::getQueryStringForLocation('new_graphics_request', 'location_id', [], ' OR new_graphics_request.location_id=0 ');
        $locationFilter = \SiteHelpers::getQueryStringForLocation('new_graphics_request');
        // if search filter does not have location_id filter
        // add default location filter
        $frontendSearchFilters = $this->model->getSearchFilters(array('location_id' => ''));
        if (empty($frontendSearchFilters['location_id'])) {
            $filter .= $locationFilter;
        }

        return $filter;
    }
    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'managenewgraphicrequests')->pluck('module_id');
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
        } else {
            \Session::put('config_id', '0');
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0)
        );
        $view = $request->get('view');
        $cond = array('view' => $view);
        $this->data['view'] = $view;
        $this->data['manageNewGraphicsInfo'] = $this->model->getManageGraphicsRequestsInfo();
        // Get Query
        $results = $this->model->getRows($params, $cond);

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;


        if (count($results['rows']) == $results['total'] && $results['total'] != 0) {
            $params['limit'] = $results['total'];
        }
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('managenewgraphicrequests/data');
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
        return view('managenewgraphicrequests.table', $this->data);

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
            $this->data['row'] = $this->model->getColumnTable('new_graphics_request');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('managenewgraphicrequests.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('new_graphics_request');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('managenewgraphicrequests.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM new_graphics_request ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO new_graphics_request (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM new_graphics_request WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = null)
    {
        $rules = array('priority_id' => 'required', 'status_id' => 'required', 'description' => 'required|min:5');
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('requests',true);
            if (\Session::has('uid') && $data['status_id']) {
                $data['aprrove_user_id'] = \Session::get('uid');
                $data['approve_date'] = date('Y-m-d');
            } else {
                $data['aprrove_user_id'] = '';
                $data['approve_date'] = '';
            }
            $id = $this->model->insertRow($data, $id);

            //////File uploads////////////////////////////
            $new_graphics_request = \DB::table('new_graphics_request')->select('img')->where('id',$id)->get();
            $images = explode(',', $new_graphics_request[0]->img);

            $input = \Input::all();
            foreach ($images as $index => $image) {
                if (Input::hasFile('img_'.($index+1).'')) {

                   /* $rules['img_'.($index+1).''] = 'mimes:jpeg,gif,png';
                    $validation = Validator::make($input, $rules);

                    if ($validation->fails()) {
                        return response()->json(array(
                            'status' => 'error',
                            'message' => implode(' ', $validation->errors()->all())
                        ));
                    }*/

                    $destinationPath = public_path() . '/uploads/newGraphic'; // upload path

                    $extension = \Input::file('img_'.($index+1).'')->getClientOriginalExtension(); // getting file extension
                    $fileName = $id . "_" . rand(111, 999) . '.' . $extension;
                    $upload_success = \Input::file('img_'.($index+1).'')->move($destinationPath, $fileName); // uploading file to given path

                    $images[$index] = $fileName;
                }
            }

            \DB::table('new_graphics_request')->where('id',$id) ->update(['img' => implode(',', $images)]);
            //////End File uploads////////////////////////////

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

    public function postDeletegraphic(Request $request){

        $images = \DB::table('new_graphics_request')->select('img')->where('id',$request->id)->get();
        $images = explode(',', $images[0]->img);
        unset($images[array_search($request->img, $images)]);
        \DB::table('new_graphics_request')->where('id',$request->id) ->update(['img' => implode(',', $images)]);

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

    function getTest()
    {
        $rows = \DB::select('select id,img from new_graphics_request');
        $img = "";
        foreach ($rows as $row) {
            if (!empty($row->img)) {
                $img = $row->id . ".jpg";
                \DB::update('update new_graphics_request set img="' . $img . '" where id=' . $row->id);
            }
        }
    }

    function getComboselect(Request $request)
    {
        if ($request->ajax() == true && \Auth::check() == true) {
            $param = explode(':', $request->input('filter'));
            if($param[0] != 'new_graphics_request_status'){
                return parent::getComboselect($request);
            }
            $parent = (!is_null($request->input('parent')) ? $request->input('parent') : null);

            $limit = (!is_null($request->input('limit')) ? $request->input('limit') : null);
            $delimiter = empty($request->input('delimiter')) ? ' ' : $request->input('delimiter');

            $rows = \DB::table('new_graphics_request_status')->orderBy('sort','asc')->get();

            $items = array();

            $fields = explode("|", $param[2]);

            foreach ($rows as $row) {
                $value = "";
                $values = array();
                foreach ($fields as $item => $val) {
                    if ($val != "") {
                        $values[] = $row->$val;
                    }
                    $value = implode($delimiter, $values);
                }
                $items[] = array($row->$param['1'], $value);

            }

            return json_encode($items);
        } else {
            return json_encode(array('OMG' => " Ops .. Cant access the page !"));
        }
    }


}