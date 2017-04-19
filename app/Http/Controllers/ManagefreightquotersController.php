<?php

namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Managefreightquoters;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator,
    Input,
    Redirect;

class ManagefreightquotersController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'managefreightquoters';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Managefreightquoters();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);


        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'managefreightquoters',
            'pageUrl' => url('managefreightquoters'),
            'return' => self::returnUrl()
        );
    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['access'] = $this->access;
        return view('managefreightquoters.index', $this->data);
    }

    public function postData(Request $request)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'managefreightquoters')->pluck('module_id');
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
            \Session::put('config_id', 0);
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
        $freight_status = $request->get('status');
        if (!empty($freight_status)) {
            $request->session()->put('freight_status', $freight_status);
        } else {
            \Session::has('freight_status') ?: \Session::put('freight_status', 'requested');
        }
        $this->data['freight_status'] = \Session::get('freight_status');
// Get Query
        $results = $this->model->getRows($params, $freight_status);
        $description = array();
        foreach ($results['rows'] as $row) {
            $description[$row->id] = $this->model->getDescription($row->id);
        }
        $this->data['description'] = $description;
// Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));
        $pagination->setPath('managefreightquoters/data');
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
        return view('managefreightquoters.table', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('freight_orders');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('managefreightquoters.form', $this->data);
    }

    public function getShow($id = null)
    {
        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('freight_orders');
        }
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('managefreightquoters.view', $this->data);
    }

    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM freight_orders ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO freight_orders (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM freight_orders WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {
        $from_form_type = $request->get('from_type');
        $to_form_type = $request->get('to_type');
        $form_data = array();
        $form_data['loc_to_1'] = 0;
        $form_data['date_submitted'] = date('Y-m-d');
        $form_data['date_paid'] = "";
        $form_data['date_booked'] = "";

        $rules['from_type'] = $rules['to_type'] = $rules['games_per_location'] = $rules['description'] = $rules['dimensions'] = 'required';

        if ($to_form_type == 'blank') {
            $rules['to_add_name'] = $rules['to_add_city'] = $rules['to_add_state'] = $rules['to_add_zip'] = $rules['to_add_street'] = $rules['to_contact_name'] = $rules['to_contact_phone'] = $rules['to_contact_email'] = 'required';
            $rules['to_contact_email'] = 'required|email';
            $form_data['to_add_name'] = $request->get('to_add_name');
            $form_data['to_add_city'] = $request->get('to_add_city');
            $form_data['to_add_state'] = $request->get('to_add_state');
            $form_data['to_add_zip'] = $request->get('to_add_zip');
            $form_data['to_add_street'] = $request->get('to_add_street');
            $form_data['to_contact_name'] = $request->get('to_contact_name');
            $form_data['to_contact_email'] = $request->get('to_contact_email');
            $form_data['to_contact_phone'] = $request->get('to_contact_phone');
            $form_data['to_loading_info'] = $request->get('to_loading_info');
        } elseif ($to_form_type == 'location') {
            $location_to = $request->get('location_to');
            $form_data['loc_to_1'] = isset($location_to[0]) ? $location_to[0] : 0;
            $rules['location_to'] = 'required';
        } else {
            $form_data['vend_to'] = $request->get('vend_to');
            $rules['vend_to'] = 'required';
        }
        if ($from_form_type == 'blank') {
            $rules['from_add_name'] = $rules['from_add_city'] = $rules['from_add_state'] = $rules['from_add_zip'] = $rules['from_add_street'] = $rules['from_contact_name'] = $rules['from_contact_phone'] = $rules['from_contact_email'] = 'required';
            $rules['from_contact_email'] = "required|email";
            $form_data['from_add_name'] = $request->get('from_add_name');
            $form_data['from_add_city'] = $request->get('from_add_city');
            $form_data['from_add_state'] = $request->get('from_add_state');
            $form_data['from_add_zip'] = $request->get('from_add_zip');
            $form_data['from_add_street'] = $request->get('from_add_street');
            $form_data['from_contact_name'] = $request->get('from_contact_name');
            $form_data['from_contact_email'] = $request->get('from_contact_email');
            $form_data['from_contact_phone'] = $request->get('from_contact_phone');
            $form_data['from_loading_info'] = $request->get('from_loading_info');
        } elseif ($from_form_type == 'location') {
            $form_data['loc_from'] = $request->get('location_from');
            $rules['location_from'] = 'required';
        } else {
            $form_data['vend_from'] = $request->get('vend_from');
            $rules['vend_from'] = 'required';
        }
        $pallet_data['dimensions'] = $request->get('dimensions');
        $pallet_data['description'] = $request->get('description');
        $form_data['notes'] = $request->get('ship_notes');
        $form_data['num_games_per_destination'] = $request->get('games_per_location');
        $add_from_vendor_to_list = $request->get('add_from_vendor_to_list'); //NO CHECK
        if ($add_from_vendor_to_list == 1) {
            $rules['from_add_name'] = "unique:vendor,vendor_name";
        }
        $add_to_vendor_to_list = $request->get('add_to_vendor_to_list'); //NO CHECK
        if ($add_to_vendor_to_list == 1) {
            $rules['from_add_name'] = "unique:vendor,vendor_name";
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data['freight_orders'] = $form_data;
            if (isset($location_to)) {
                $data['freight_location_to'] = $location_to;
            }
            $data['pallte_details'] = $pallet_data;
            $id = $this->model->sendFreightQuotes($data);
            if ($add_from_vendor_to_list == 1) {

                $from_vendor_type = $request->get('from_vendor_type');
                if ($from_vendor_type == 'games') {
                    $is_game_vendor = 1;
                    $is_merch_vendor = 0;
                } else {
                    $is_game_vendor = 0;
                    $is_merch_vendor = 1;
                }
                $data = array(
                    'vendor_name' => $request->get('from_add_name'),
                    'street1' => $request->get('from_add_street'),
                    'city' => $request->get('from_add_city'),
                    'state' => $request->get('from_add_state'),
                    'zip' => $request->get('from_add_zip'),
                    'games_contact_name' => $request->get('from_contact_name'),
                    'games_contact_email' => $request->get('from_contact_email'),
                    'games_contact_phone' => $request->get('from_contact_phone'),
                    'partner_hide' => 1,
                    'isgame' => $is_game_vendor,
                    'ismerch' => $is_merch_vendor
                );
                \DB::table('vendor')->insert($data);
            }
// ADD TO-VENDOR LOGIC
            if ($add_to_vendor_to_list == 1) {
                $to_vendor_type = $request->get('to_vendor_type'); //NO CHECK
                if ($to_vendor_type == 'games') {
                    $is_game_vendor = 1;
                    $is_merch_vendor = 0;
                } else {
                    $is_game_vendor = 0;
                    $is_merch_vendor = 1;
                }

                $data = array(
                    'vendor_name' => $request->get('to_add_name'),
                    'street1' => $request->get('to_add_street'),
                    'city' => $request->get('to_add_city'),
                    'state' => $request->get('to_add_state'),
                    'zip' => $request->get('to_add_zip'),
                    'games_contact_name' => $request->get('to_contact_name'),
                    'games_contact_email' => $request->get('to_contact_email'),
                    'games_contact_phone' => $request->get('to_contact_phone'),
                    'partner_hide' => 1,
                    'isgame' => $is_game_vendor,
                    'ismerch' => $is_merch_vendor
                );
                \DB::table('vendor')->insert($data);
            }
            if ($from_form_type == 'location') {
                $query = \DB::select('SELECT L.location_name,L.street1,L.city AS loc_city,L.state AS loc_state,L.zip AS loc_zip,L.loading_info,
                 U.first_name AS user_first_name,U.last_name AS user_last_name,U.email AS user_email,U.primary_phone AS user_phone
										 FROM location L                                          
                                         LEFT JOIN user_locations UL ON UL.location_id = L.id AND UL.group_id=101
                                         LEFT JOIN users U ON U.id = UL.user_id
										WHERE L.id = ' . $form_data['loc_from'] . '');
                if (count($query)) {
                    $from_name = $query[0]->location_name;
                    $from_street = $query[0]->street1;
                    $from_city = $query[0]->loc_city;
                    $from_state = $query[0]->loc_state;
                    $from_zip = $query[0]->loc_zip;
                    $from_loading_info = $query[0]->loading_info;
                    $from_contact_full_name = $query[0]->user_first_name . ' ' . $query[0]->user_last_name;
                    $from_contact_email = $query[0]->user_email;
                    $from_contact_phone = $query[0]->user_phone;
                }
            } else if ($from_form_type == 'vendor') {
                $query = \DB::select('SELECT vendor_name,street1,city,state, zip, games_contact_name,games_contact_email,games_contact_phone
										 FROM vendor WHERE id = ' . $form_data['vend_from'] . '');
                if (count($query) == 1) {
                    $from_name = $query[0]->vendor_name;
                    $from_street = $query[0]->street1;
                    $from_city = $query[0]->city;
                    $from_state = $query[0]->state;
                    $from_zip = $query[0]->zip;
                    $from_loading_info = '';
                    $from_contact_full_name = $query[0]->games_contact_name;
                    $from_contact_email = $query[0]->games_contact_email;
                    $from_contact_phone = $query[0]->games_contact_phone;
                }
            } else {
                $from_name = $request->get('from_add_name');
                $from_street = $request->get('from_add_street');
                $from_city = $request->get('from_add_city');
                $from_state = $request->get('from_add_state');
                $from_zip = $request->get('from_add_zip');
                $from_contact_full_name = $request->get('from_contact_name');
                $from_contact_email = $request->get('from_contact_email');
                $from_contact_phone = $request->get('from_contact_phone');
                $from_loading_info = $request->get('from_loading_info');

///ADD VENDOR LOGIC COMING SOON
            }
            if ($to_form_type == 'location') {
                foreach ($location_to as $location) {
                    $query = \DB::select('SELECT L.location_name, L.street1,  L.city AS loc_city, L.state AS loc_state, L.zip AS loc_zip, L.loading_info,
											  U.first_name AS user_first_name, U.last_name AS user_last_name, U.email AS user_email,  U.primary_phone AS user_phone
										 FROM location L 
                                         LEFT JOIN user_locations UL ON UL.location_id = L.id AND UL.group_id=101
                                         LEFT JOIN users U ON U.id = UL.user_id
                                         WHERE L.id = ' . $location . '');
                    if (count($query) == 1) {
                        $to_name[] = $query[0]->location_name;
                        $to_street[] = $query[0]->street1;
                        $to_city[] = $query[0]->loc_city;
                        $to_state[] = $query[0]->loc_state;
                        $to_zip[] = $query[0]->loc_zip;
                        $to_loading_info[] = $query[0]->loading_info;
                        $to_contact_full_name[] = $query[0]->user_first_name . ' ' . $query[0]->user_last_name;
                        $to_contact_email[] = $query[0]->user_email;
                        $to_contact_phone[] = $query[0]->user_phone;
                    }
                }
            } else if ($to_form_type == 'vendor') {
                $query = \DB::select('SELECT vendor_name, street1,city,state,zip,games_contact_name,games_contact_email,games_contact_phone
										 FROM vendor WHERE id = ' . $form_data['vend_to'] . '');
                if (count($query) == 1) {
                    $to_name = $query[0]->vendor_name;
                    $to_street = $query[0]->street1;
                    $to_city = $query[0]->city;
                    $to_state = $query[0]->state;
                    $to_zip = $query[0]->zip;
                    $to_loading_info = '';
                    $to_contact_full_name = $query[0]->games_contact_name;
                    $to_contact_email = $query[0]->games_contact_email;
                    $to_contact_phone = $query[0]->games_contact_phone;
                }
            } else {
                $to_name = $request->get('to_add_name');
                $to_street = $request->get('to_add_street');
                $to_city = $request->get('to_add_city');
                $to_state = $request->get('to_add_state');
                $to_zip = $request->get('to_add_zip');
                $to_contact_full_name = $request->get('to_contact_name');
                $to_contact_email = $request->get('to_contact_email');
                $to_contact_phone = $request->get('to_contact_phone');
                $to_loading_info = $request->get('to_loading_info');
            }
            $from_loading_info = $request->get('from_loading_info');
            if (!empty($from_loading_info)) {
                $from_loading_info = '<b>**' . $from_loading_info . '</b>** <br>';
            }
            $subject = 'FREIGHT QUOTE for Family Entertainment Group - ' . $request->get('from_add_city') . ' to ' . $request->get('to_add_city') . '';
            if (!empty($from_contact_full_name) || !empty($from_contact_phone) || !empty($from_contact_email)) {
                $from_contact_summary = $from_contact_full_name . ' | ' . $from_contact_phone . ' | ' . $from_contact_email . '<br>';
            } else {
                $from_contact_summary = '';
            }
            $fromMessage = '<b>FROM:</b><br>' .
                $from_name . '<br>' .
                $from_street . '<br>' .
                $from_city . ', ' . $from_state . ' ' . $from_zip . '<br>' .
                $from_contact_summary .
                '<b style="color:red">' . $from_loading_info . '</b>';
            if (!empty($to_contact_full_name) || !empty($to_contact_phone) || !empty($to_contact_email)) {
                $to_contact_summary = "";
                for ($i = 1;
                     $i < count($to_contact_full_name);
                     $i++) {
                    $to_contact_summary .= $to_contact_full_name[$i] . ' | ' . $to_contact_phone[$i] . ' | ' . $to_contact_email[$i] . '<br>';
                }
            } else {
                $to_contact_summary = '';
            }
            $toMessage = "";
            if (is_array($to_name)) {
                for ($i = 0; $i < count($to_name); $i++) {
                    if ($i == 0) {
                        $toMessage = $toMessage . '<b> TO:</b><br>';
                    } else {
                        $toMessage = $toMessage . '<b>AND TO:</b><br>';
                    }

                    $toMessage = $toMessage . " " . $to_name[$i] . '<br>' .
                        $to_street[$i] . '<br>' .
                        $to_city[$i] . ', ' . $to_state[$i] . ' ' . $to_zip[$i] . '<br>' .
                        $to_contact_full_name[$i] . ' | ' . $to_contact_phone[$i] . ' | ' . $to_contact_email[$i] . '<br>' .
                        '<b style="color:red">' . $to_loading_info[$i] . '</b><br><br>';
                    $subject = $subject . ', ' . $to_city[$i];
                }
            } else {
                $toMessage = $toMessage . '<b> TO:</b><br>';
                $toMessage = $toMessage . " " . $to_name . '<br>' .
                    $to_street . '<br>' .
                    $to_city . ', ' . $to_state . ' ' . $to_zip . '<br>' .
                    $to_contact_full_name . ' | ' . $to_contact_phone . ' | ' . $to_contact_email . '<br>' .
                    '<b style="color:red">' . $to_loading_info . '</b><br><br>';
                $subject = $subject . ', ' . $to_city;
            }
            $forMessage = "";
            for ($i = 1;
                 $i <= count($pallet_data['description']);
                 $i++) {
                $forMessage .= '<br><b>FOR:</b><br>
						Pallet #' . $i . ': ' .
                    $pallet_data['description'][$i - 1] . '<br>
						Pallet Dimms.: ' .
                    $pallet_data['dimensions'][$i - 1] . '<br>';
            }
            $notes = $request->get('ship_notes');
            if (!empty($notes)) {
                $forMessage = $forMessage . '<br>
							<b style="color:red">NOTES</b><br>
							<b>' . $notes . '</b>';
            }
            $message = $fromMessage . $toMessage . $forMessage;
            $bcc = 'freight-notifications@fegllc.com';
            $from = \Session::get('eid');
            $sender_name = \Session::get('fname');
            $sender_name .= \Session::get('lname');
            $freightCompanyQuery = \DB::select('SELECT rep_email FROM freight_companies WHERE active = 1  AND rep_email != ""');
            foreach ($freightCompanyQuery as $rowFreight) {
                $to = $rowFreight->rep_email;
                $headers = "Bcc: " . $bcc . "\r\n";
                $headers .= "From: " . $from . "\r\n" . "X-Mailer: php";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                //mail($to, $subject, $message, $headers);
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

    public function postUpdate(Request $request, $id = null)
    {
        $data['request'] = $request->all();
        $data['freight_order_id'] = $id;
        $this->model->updateFreightOrder($data);
        return Redirect::to('managefreightquoters')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

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

    public function getPaid($freight_order_id)
    {
        $update = array('status' => 2, 'date_paid' => date('Y-m-d'));
        \DB::table('freight_orders')->where('id', $freight_order_id)->update($update);
        return Redirect::to('managefreightquoters')->with('messagetext', \Lang::get('core.note_freight_paid'))->with('msgstatus', 'success');
    }

    public function getGamedetails($SID)
    {
        return Redirect::to('mylocationgame')->with('game_id', $SID);
    }
    public function messages()
    {
        return [
            'email.required' => 'Er, you forgot your email address!',
        ];
    }
}
