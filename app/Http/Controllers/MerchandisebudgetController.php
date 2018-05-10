<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Merchandisebudget;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class MerchandisebudgetController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'merchandisebudget';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Merchandisebudget();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'merchandisebudget',
            'pageUrl' => url('merchandisebudget'),
            'return' => self::returnUrl()
        );
    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('merchandisebudget.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'merchandisebudget')->pluck('module_id');
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
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'location_id');
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
        $budget_year = \Session::get('budget_year');
        $budget_year = isset($budget_year) ? $budget_year : date('Y');
        $budget_year = isset($_GET['budget_year']) ? Input::get('budget_year') : $budget_year;
        \Session::put('budget_year', $budget_year);
        $simpleSearch = isset($_GET['simplesearch']) ? Input::get('simplesearch') : 0;
        $advanceSearch = false;
        if($simpleSearch == 0 && !isset($_GET['budget_year']) && isset($_GET['search']))
        {
            $budget_year = null;
            $advanceSearch = true;
        }
        $results = $this->model->getRows($params, $budget_year ,$advanceSearch);


        $results["rows"] = array_map(function($row){
            $row->Jan = \CurrencyHelpers::formatPrice($row->Jan);
            $row->Feb = \CurrencyHelpers::formatPrice($row->Feb);
            $row->March = \CurrencyHelpers::formatPrice($row->March);
            $row->April = \CurrencyHelpers::formatPrice($row->April);
            $row->May = \CurrencyHelpers::formatPrice($row->May);
            $row->June = \CurrencyHelpers::formatPrice($row->June);
            $row->July = \CurrencyHelpers::formatPrice($row->July);
            $row->August = \CurrencyHelpers::formatPrice($row->August);
            $row->September = \CurrencyHelpers::formatPrice($row->September);
            $row->October = \CurrencyHelpers::formatPrice($row->October);
            $row->November = \CurrencyHelpers::formatPrice($row->November);
            $row->December = \CurrencyHelpers::formatPrice($row->December);
            return $row;
        },$results["rows"]);

        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'],
            (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
                ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('merchandisebudget/data');
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
        $this->data['budget_year'] = $budget_year;

// Render into template
        return view('merchandisebudget.table', $this->data);

    }


    function getUpdate(Request $request, $id = 0)
    {

        if ($id == '0') {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != '0') {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }


        $row = $this->model->getRow($id, false);
        $row = json_decode(json_encode($row), true);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = array("id" => null, "location_id" => "", 'budget_year' => "", 'Jan' => '0', 'Feb' => '0', 'March' => '0', 'April' => '0', 'May' => '0', 'June' => '0', 'July' => '0', 'August' => '0', 'September' => '0', 'October' => '0','November' => '0', 'December' => '0');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('merchandisebudget.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('location_budget');
        }
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('merchandisebudget.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM location_budget ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO location_budget (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM location_budget WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {

        $budget_vals = array();
        $location_id = $request->get('location_id');
        if(empty($location_id))
        {
            $location = $request->get('location');
            $id = explode('|',$location);
            $location_id = rtrim($id[0]);
        }
        $budget_year = $request->get('budget_year');
        $budget_vals['jan'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-01-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('jan')));
        $budget_vals['feb'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-02-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('feb')));
        $budget_vals['march'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-03-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('march')));
        $budget_vals['april'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-04-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('april')));
        $budget_vals['may'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-05-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('may')));
        $budget_vals['jun'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-06-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('june')));
        $budget_vals['jul'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-07-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('july')));
        $budget_vals['aug'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-08-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('august')));
        $budget_vals['sep'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-09-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('september')));
        $budget_vals['oct'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-10-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('october')));
        $budget_vals['nov'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-11-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('november')));
        $budget_vals['dec'] = array('location_id' => $location_id, 'budget_date' => $budget_year . '-12-01', 'budget_value' => \CurrencyHelpers::truncateDecimalValue($request->get('december')));
        if ($id == 0) {
            $id = $this->model->insertRow($budget_vals, $request->input('id'), $location_id, $budget_year);
        } else {
            $id = $this->model->insertRow($budget_vals, $id, $location_id, $budget_year);
        }
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));


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

    function buildSearch($customSearchString = null)
    {
        $months = [
            'Jan'=>'Jan',
            'Feb'=>'Feb',
            'March'=>'Mar',
            'April'=>'Apr',
            'May'=>'May',
            'June'=>'Jun',
            'July'=>'Jul',
            'August'=>'Aug',
            'September'=>'Sep',
            'October'=>'Oct',
            'November'=>'Nov',
            'December'=>'Dec',
        ];
        $keywords = '';
        $fields = '';
        $param = '';
        $allowsearch = $this->info['config']['forms'];
        $searchQuerystring = !is_null($customSearchString) ? $customSearchString :
                (isset($_GET['search']) ? $_GET['search'] : '');

        foreach ($allowsearch as $as) $arr[$as['field']] = $as;
        if ($searchQuerystring != '') {
            $type = explode("|", $searchQuerystring);
            if (count($type) >= 1) {
                foreach ($type as $t) {
                    $keys = explode(":", $t);

                    if (in_array($keys[0], array_keys($arr))):
                        if ($arr[$keys[0]]['type'] == 'select' || $arr[$keys[0]]['type'] == 'radio') {
                            if ($keys[0] == "budget_date") {
                                \Session::put('budget_year', $keys[2]);
                                $param .= " AND " . "YEAR(" . $arr[$keys[0]]['alias'] . "." . $keys[0] . ") " . self::searchOperation($keys[1]) . " '" . addslashes($keys[2]) . "' ";
                            } else if($arr[$keys[0]]['alias'] . "." . $keys[0] == 'location_budget.location_id') {
                                $values = explode(',',addslashes($keys[2]));
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " IN ('" . implode("','",$values) . "') ";
                            }  else {
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " " . self::searchOperation($keys[1]) . " '" . addslashes($keys[2]) . "' ";
                            }
                        } else {
                            $operate = self::searchOperation($keys[1]);
                            if ($operate == 'like') {
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " LIKE '%" . addslashes($keys[2]) . "%%' ";
                            } else if ($operate == 'is_null') {
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " IS NULL ";

                            } else if ($operate == 'not_null') {
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " IS NOT NULL ";

                            } else if ($operate == 'between') {
                                $param .= " AND (" . $arr[$keys[0]]['alias'] . "." . $keys[0] . " BETWEEN '" . addslashes($keys[2]) . "' AND '" . addslashes($keys[3]) . "' ) ";
                            } else {
                                if(!in_array($keys[0],$months,true))
                                $param .= " AND " . $arr[$keys[0]]['alias'] . "." . $keys[0] . " " . self::searchOperation($keys[1]) . " '" . addslashes($keys[2]) . "' ";
                            }
                        }
                    endif;
                }
//                $hasMonths = false;
//                $count = 0;
//                foreach ($type as $t) {
//                    $keys = explode(":", $t);
//                    if(in_array($keys[0],$months,true)) {
//                        $count++;
//                        if($count==1)
//                        {
//                            $param .= "AND (";
//                        }
//                        else
//                        {
//                            $param .= 'OR ';
//                        }
//                        $hasMonths = true;
//                        $this->removeCommas($keys[2]);
//                        $param .=" (DATE_FORMAT(location_budget.budget_date,'%b')='".$months[$keys[0]]."'  AND location_budget.budget_value=".$this->removeCommas($keys[2]).") ";
//                    }
//                }
//                if($hasMonths)
//                {
//                    $param .= ")";
//                }
            }
        }
        return $param;

    }

    public function removeCommas($value)
    {
        $value = htmlspecialchars($value);
        $value = str_replace("%2C","",$value);
        $value = str_replace("%24","",$value);
        $value = str_replace("%20","",$value);
        return $value;
    }
//script for getting data from location_old table and inserting into location_budget
    /*public function getTest()
    {
        $row = \DB::select('select id,Jan_2012 as "2012-01-01",Feb_2012 as "2012-02-01",Mar_2012 as "2012-03-01",Apr_2012 as "2012-04-01",May_2012 as "2012-05-01",Jun_2012 as "2012-06-01",Jul_2012 as "2012-07-01",Aug_2012 as "2012-08-01",Sep_2012 as "2012-09-01",Oct_2012 as "2012-10-01",Nov_2012 as "2012-11-01",Dec_2012 as "2012-12-01" FROM location_old');
        $res = array();
        foreach ($row as $k => $v) {
            $r = json_decode(json_encode($v), true);
            $keys=array_keys($r);
            foreach($keys as $key)
            {

                $vvv[]=array('location_id'=>$r['id'],'budget_value'=>$r[$key],'budget_date'=>$key);
            }
        }
        \DB::table('location_budget')->insert($vvv);

    }*/

}
