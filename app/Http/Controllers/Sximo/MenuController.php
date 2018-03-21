<?php namespace App\Http\Controllers\sximo;

use App\Models\Sximo\Menu;
use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use Validator, Input, Redirect;
use App\Models\Core\Groups;
use App\Models\Sximo;


class MenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Menu();
        $this->info = $this->model->makeInfo('menu');
        $this->access = $this->model->validAccess($this->info['id']);
        $this->data['pageTitle'] = "Menu Management";
        $this->data['groups'] = \DB::select(" SELECT * FROM tb_groups ");
    }


    public function getIndex(Request $request, $id = null)
    {


        $pos = (!is_null($request->input('pos')) ? $request->input('pos') : 'top');
        $row = \DB::table('tb_menu')->where('menu_id', $id)->get();
        if (count($row) >= 1) {

            $rows = $row[0];
            $this->data['row'] = (array)$rows;


            $this->data['menu_lang'] = json_decode($rows->menu_lang, true);
        } else {
            $this->data['row'] = array(
                'menu_id' => '',
                'parent_id' => '',
                'menu_name' => '',
                'menu_type' => '',
                'url' => '',
                'module' => '',
                'position' => '',
                'menu_icons' => '',
                'active' => '',
                'allow_guest' => '',
                'access_data' => '',

            );
            $this->data['menu_lang'] = array();
        }
        //echo '<pre>';print_r($this->data);echo '</pre>';  exit;
        $this->data['menus'] = \SiteHelpers::menus($pos, 'all', in_array(\Session::get('gid'), [Groups::SUPPER_ADMIN]));
        $this->data['modules'] = \DB::table('tb_module')->where('module_type', '!=', 'core')->orderBy('module_title', 'asc')->get();
        $this->data['groups'] = \DB::select(" SELECT * FROM tb_groups ");
        $this->data['pages'] = \DB::table("tb_pages")->orderBy('title', 'asc')->get();
        $this->data['active'] = $pos;
        $this->data['usergroupAccess'] = [];
        $sximo = new Sximo();
        $info = Sximo::makeInfo($this->data['row']['module']);
        if (isset($info['id'])) {

            $this->data['usergroupAccess'] = $sximo->moduleViewAccess($info['id']);
        }

        return view('sximo.menu.index', $this->data);
    }

    function postSaveorder(Request $request, $id = 0)
    {

        $rules = array(
            'reorder' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $menus = json_decode($request->input('reorder'), true);
            $child = array();
            $a = 0;
            foreach ($menus as $m) {
                if (isset($m['children'])) {
                    $b = 0;
                    foreach ($m['children'] as $l) {
                        if (isset($l['children'])) {
                            $c = 0;
                            foreach ($l['children'] as $l2) {
                                $level3[] = $l2['id'];
                                \DB::table('tb_menu')->where('menu_id', '=', $l2['id'])
                                    ->update(array('parent_id' => $l['id'], 'ordering' => $c));
                                $c++;
                            }
                        }
                        \DB::table('tb_menu')->where('menu_id', '=', $l['id'])
                            ->update(array('parent_id' => $m['id'], 'ordering' => $b));
                        $b++;
                    }
                }
                \DB::table('tb_menu')->where('menu_id', '=', $m['id'])
                    ->update(array('parent_id' => '0', 'ordering' => $a));
                $a++;
            }
            return Redirect::to('feg/menu')
                ->with('messagetext', 'Data have been saved successfully')->with('msgstatus', 'success');
        } else {
            return Redirect::to('feg/menu')
                ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error');

        }


    }

    function postSave(Request $request, $id = 0)
    {

        $rules = array(
            'menu_name' => 'required',
            'active' => 'required',
            'menu_type' => 'required',
            'position' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        $pos = '';
        if ($validator->passes()) {
            $pos = $request->input('position');
            $data = $this->validatePost('tb_menu');
            if (CNF_MULTILANG == 1) {
                $lang = \SiteHelpers::langOption();
                $language = array();
                foreach ($lang as $l) {
                    if ($l['folder'] != 'en') {
                        $menu_lang = (isset($_POST['language_title'][$l['folder']]) ? $_POST['language_title'][$l['folder']] : '');
                        $language['title'][$l['folder']] = $menu_lang;
                    }
                }

                $data['menu_lang'] = json_encode($language);
            }

            $arr = array();
            $groups = \DB::table('tb_groups')->get();
            foreach ($groups as $g) {
                $arr[$g->group_id] = (isset($_POST['groups'][$g->group_id]) ? "1" : "0");
            }
            $data['access_data'] = json_encode($arr);
            $data['allow_guest'] = $request->input('allow_guest');
            $this->model->insertRow($data, $request->input('menu_id'));

            return Redirect::to('feg/menu?pos=' . $pos)
                ->with('messagetext', 'Data have been saved successfully')->with('msgstatus', 'success');

        } else {
            return Redirect::to('feg/menu?pos=' . $pos)
                ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')->withErrors($validator)->withInput();


        }

    }

    public function getDestroy($id)
    {
        // delete multipe rows

        $menus = \DB::table('tb_menu')->where('parent_id', '=', $id)->get();
        foreach ($menus as $row) {
            $this->model->destroy($row->menu_id);
        }

        $this->model->destroy($id);
        return Redirect::to('feg/menu')
            ->with('messagetext', 'Successfully deleted row!')->with('msgstatus', 'success');

    }

    public function postViewPermission(Request $request)
    {

        $module_name = $request->input('module_name');
        $sximo = new Sximo();
        $info = Sximo::makeInfo($module_name);
        $html = '';
        if (isset($info['id'])) {
            $html = '<div id="permission-overlay" style=" z-index:1000; cursor:not-allowed; background:rgba(128, 128, 128, 0);; width: 100%; height: 100%; position: absolute;"></div>';

            $usergroupAccess = $sximo->moduleViewAccess($info['id']);

            foreach ($this->data['groups'] as $group) {
                $checked = '';

                if (in_array($group->group_id, $usergroupAccess)) {
                    $checked = ' checked="checked"';
                    $html .= '<label class="checkbox">
                           <input  type="checkbox"  name="groups[' . $group->group_id . ']" value="' . $group->group_id . '" ' . $checked . ' >
                            ' . $group->name . '
                        </label>';

                } else {
                    $html .= '<label class="checkbox">
                           <input  type="checkbox"  name="groups[' . $group->group_id . ']" value="' . $group->group_id . '" ' . $checked . ' >
                            ' . $group->name . '
                        </label>';

                }

            }
        } else {
            $html = '<div id="permission-overlay" style=" z-index:1000; cursor:not-allowed; background:rgba(128, 128, 128, 0);; width: 100%; height: 100%; position: absolute;"></div>';

            $PagePermision = \DB::table("tb_pages")->where("alias", '=', $module_name)->first();
            $permission = [];
            if ($PagePermision) {
                $PagePermision = (array)json_decode($PagePermision->access);
                $keys = array_keys($PagePermision);

                foreach ($PagePermision as $key => $value) {
                    if ($value == 1) {
                        $permission[] = $key;
                    }
                }
                }

            foreach ($this->data['groups'] as $group) {
                $checked = '';
                $onclick = "onclick='$(this).children(\":first\").toggleClass(";;
                $onclick .= "\"checked\");'";
                if (in_array($group->group_id, $permission)) {
                    $checked = ' checked="checked"';
                    $html .= '<label class="checkbox">
                           <input  type="checkbox"  name="groups[' . $group->group_id . ']" value="' . $group->group_id . '" ' . $checked . ' >
                            ' . $group->name . '
                        </label>';
                } else {
                    $html .= '<label class="checkbox">
                           <input  type="checkbox"  name="groups[' . $group->group_id . ']" value="' . $group->group_id . '" ' . $checked . ' >
                            ' . $group->name . '
                        </label>';
                }
            }

        }
        return $html;
    }


}
