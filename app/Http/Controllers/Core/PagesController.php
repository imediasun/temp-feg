<?php namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Pages;
use App\Models\Core\Groups;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Session, Auth, DB;


class PagesController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'pages';
    static $per_page = '10';

    public function __construct()
    {

        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->model = new Pages();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'core/pages',
            'pageUrl' => url('core/pages'),
            'return' => self::returnUrl()

        );
    }

    public function getIndex(Request $request, $id=null)
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

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
        //$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('pages');

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
        // Render into template
        return view('core.pages.index', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('tb_pages');
        }

        if ($this->data['row']['access'] != '') {
            $access = json_decode($this->data['row']['access'], true);
        } else {
            $access = array();
        }

        if ($id == '') {
            $this->data['content'] = '';
        } else {

            if ($row->pageID == 1) {
                $filename = base_path() . "/resources/views/pages/home.blade.php";
                $this->data['content'] = file_get_contents($filename);

            } else {

                $filename = base_path() . "/resources/views/pages/" . $row->filename . ".blade.php";
                if (file_exists($filename)) {
                    $this->data['content'] = file_get_contents($filename);
                } else {
                    $this->data['content'] = '';
                }
            }
        }

        $groups = Groups::all();
        $group = array();
        foreach ($groups as $g) {
            $group_id = $g['group_id'];
            $a = (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0);
            $group[] = array('id' => $g->group_id, 'name' => $g->name, 'access' => $a);
        }

        $this->data['groups'] = $group;
        $this->data['id'] = $id;

        $redirect = $request->has('return') ? $request->input('return') : '';
        $this->data['return'] = $redirect;

        return view('core.pages.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('tb_pages');
        }
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        return view('core.pages.view', $this->data);
    }

    function postSave(Request $request, $id = 0)
    {

        $rules = array(
            'title' => 'required',
            'alias' => 'required',
            'filename' => 'required|alpha',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $content = $request->input('content');
            $content = $this->addEditLinkTemplate($content);
            
            $data = $this->validatePost('tb_pages');

            if ($request->input('pageID') == 1) {
                $filename = base_path() . "/resources/views/pages/home.blade.php";
            } else {
                $filename = base_path() . "/resources/views/pages/" . $request->input('filename') . ".blade.php";
            }
            $fp = fopen($filename, "w+");
            fwrite($fp, $content);
            fclose($fp);

            $groups = Groups::all();
            $access = array();
            foreach ($groups as $group) {
                $access[$group->group_id] = (isset($_POST['group_id'][$group->group_id]) ? '1' : '0');
            }

            $data['access'] = json_encode($access);

            $data['allow_guest'] = $request->input('allow_guest');
            $data['template'] = $request->input('template');
            
            $data['direct_edit_groups'] = $request->input('direct_edit_groups');
            $data['direct_edit_users'] = $request->input('direct_edit_users');
            $data['direct_edit_users_exclude'] = $request->input('direct_edit_users_exclude');

            if(is_array($data['direct_edit_groups'])) {
                $data['direct_edit_groups'] = implode(',', $data['direct_edit_groups']);
            }
            if(is_array($data['direct_edit_users'])) {
                $data['direct_edit_users'] = implode(',', $data['direct_edit_users']);
            }
            if(is_array($data['direct_edit_users_exclude'])) {
                $data['direct_edit_users_exclude'] = implode(',', $data['direct_edit_users_exclude']);
            }

            $data['alias']=str_slug($request->input('alias'), '-');

            $this->model->insertRow($data, $request->input('pageID'));
            self::createRouters();

            $redirect = $request->has('return') ? $request->input('return') : '';
            if (empty($redirect)) {
                $redirect = 'core/pages?return=' . self::returnUrl();
            }
            
            return Redirect::to($redirect)
                    ->with('messagetext', \Lang::get('core.note_success'))
                    ->with('msgstatus', 'success');

        } else {
            //return $request->all();
            return Redirect::to('core/pages/update/' . $id)
                ->with('messagetext', \Lang::get('core.note_error'))
                ->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }

    }

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            self::createRouters();
            return Redirect::to('core/pages')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');

        } else {
            return Redirect::to('core/pages')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }

    }

    function createRouters()
    {
        $rows = \DB::table('tb_pages')->where('pageID', '!=', '1')->get();
        $val = "<?php \n";
        foreach ($rows as $row) {

            $slug = $row->alias;
            $val .= "Route::get('{$slug}', 'HomeController@index');\n";
        }
        $val .= "?>";
        $filename = app_path() . '/Http/pageroutes.php';
        $fp = fopen($filename, "w+");
        fwrite($fp, $val);
        fclose($fp);
        return true;

    }

    public function addEditLinkTemplate($content = '') {
        $hasLink = stripos($content, '$editLink') !== FALSE;
        if ($hasLink) {
            return $content;
        }
        $titlePosition = stripos($content, '$pageTitle');
        if ($titlePosition === false) {
            return $content;
        }
        $templateEndBraceCount = 2;
        $endOfTitleTemplatePosition = stripos($content, '}}', $titlePosition + 1);
        if ($endOfTitleTemplatePosition === false) {
            $templateEndBraceCount = 1;
            $endOfTitleTemplatePosition = stripos($content, '}', $titlePosition + 1);
        }
        if ($endOfTitleTemplatePosition !== false) {            
            $content = substr_replace($content, '{!! $editLink !!}',
                    $endOfTitleTemplatePosition + $templateEndBraceCount, 0);
        }
        return $content;        
    }
}