<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

class OrderController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'order';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
        $this->modelview = new  \App\Models\Sbinvoiceitem();
        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'order',
            'pageUrl' => url('order'),
            'return' => self::returnUrl()
        );


    }

    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        $this->data['sid'] = "";
        $this->data['access'] = $this->access;
        return view('order.index', $this->data);
    }

    public function postData(Request $request)
    {
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'order')->pluck('module_id');
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
        //$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
        if(is_null($request->input('search')))
        {
            $filter = \SiteHelpers::getQueryStringForLocation('orders');
        }
        else
        {
            $filter = $this->buildSearch();
        }


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
        // passing All gives error in query, $cond
        //$order_selected = isset($_GET['order_type']) ? $_GET['order_type'] : 'ALL';
        $order_selected = isset($_GET['order_type']) ? $_GET['order_type'] : '';


        $results = $this->model->getRows($params, $order_selected);
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;

        

        if(count($results['rows']) == $results['total']){
            $params['limit'] = $results['total'];
        }

        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('order/data');
        $rows = $results['rows'];
        foreach ($rows as $index => $data) {
            $rows[$index]->date_ordered = date("m/d/Y", strtotime($data->date_ordered));
            $location = \DB::select("Select location_name FROM location WHERE id = " . $data->location_id . "");
            $rows[$index]->location_id = (isset($location[0]->location_name) ? $location[0]->location_name : '');

            $user = \DB::select("Select username FROM users WHERE id = " . $data->user_id . "");
            $rows[$index]->user_id = (isset($user[0]->username) ? $user[0]->username : '');

            $order_type = \DB::select("Select order_type FROM order_type WHERE id = " . $data->order_type_id . "");
            $rows[$index]->order_type_id = (isset($order_type[0]->order_type) ? $order_type[0]->order_type : '');

            //  $vendor = \DB::table('vendor')->where('id', '=', $data->vendor_id)->get(array('vendor_name'));
            //$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');

            $order_status = \DB::select("Select status FROM order_status WHERE id = " . $data->status_id . "");
            $rows[$index]->status_id = (isset($order_status[0]->status) ? $order_status[0]->status : '');
        }
        $this->data['param'] = $params;
        $this->data['rowData'] = $rows;
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
        $this->data['order_selected'] = $order_selected;
        // Render into template
        return view('order.table', $this->data);

    }


    function getUpdate(Request $request, $id = 0, $mode = '')
    {
        $editmode = $prefill_type = 'edit';
        $where_in_expression = '';
        $this->data['setting'] = $this->info['setting'];
        if ($id != 0 && $mode == '') {
            $mode = 'edit';
        } elseif ($id == 0 && $mode == '') {
            $mode = 'create';
        } elseif (substr($mode, 0, 3) == 'SID') {
            $mode = $mode;
        }
        if ($id == 0) {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        if ($id != 0) {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('orders');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        $this->data['mode'] = $mode;
        $this->data['id'] = $id;
        $this->data['data'] = $this->model->getOrderQuery($id, $mode);
        return view('order.form', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('orders');
        }
        $this->data['order_data'] = $this->model->getOrderQuery($id, 'edit');
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('order.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM orders ") as $column) {
            if ($column->Field != 'id')
                $columns[] = $column->Field;
        }
        $toCopy = implode(",", $request->input('ids'));


        $sql = "INSERT INTO orders (" . implode(",", $columns) . ") ";
        $sql .= " SELECT " . implode(",", $columns) . " FROM orders WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);
        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {

       //return \Redirect::to('/order');
        $rules = array('location_id' => "required", 'vendor_id' => 'required', 'order_type_id' => "required", 'freight_type_id' => 'required', 'date_ordered' => 'required', 'po_3' => 'required');
        $validator = Validator::make($request->all(), $rules);
        $order_data = array();
        $order_contents = array();
        $data = array_filter($request->all());
        if ($validator->passes()) {

            $order_id = $request->get('order_id');
            $editmode = $request->get('editmode');
            $where_in = $request->get('where_in_expression');
            $SID_string = $request->get('SID_string');
            $company_id = $request->get('company_id');
            $location_id = $request->get('location_id');
            $order_type = $request->get('order_type_id');
            $vendor_id = $request->get('vendor_id');
            $vendor_email = $this->model->getVendorEmail($vendor_id);
            $freight_type_id = $request->get('freight_type_id');

            $date_ordered = date("Y-m-d", strtotime($request->get('date_ordered')));

            $total_cost = $request->get('order_total');
            $notes = $request->get('po_notes');
            $po_1 = $request->get('po_1');
            $po_2 = $request->get('po_2');
            $po_3 = $request->get('po_3');
            $po = $po_1 . '-' . $po_2 . '-' . $po_3;
            $altShipTo = $request->get('alt_ship_to');
            $alt_address = '';
            $order_description = '';
            if (!empty($altShipTo)) {
                $rules = array('to_add_name' => 'required|max:60', 'to_add_street' => 'required|min:5', 'to_add_city' => 'required|min:5', 'to_add_state' => 'required|max:2', 'to_add_zip' => 'required|max:10');
                $validator = Validator::make($request->all(), $rules);
                $to_add_name = $request->get('to_add_name');
                $to_add_street = $request->get('to_add_street');
                $to_add_city = $request->get('to_add_city');
                $to_add_state = $request->get('to_add_state');
                $to_add_zip = $request->get('to_add_zip');
                $to_add_notes = $request->get('to_add_notes');
                $alt_address = $to_add_name . '|' . $to_add_street . '|' . $to_add_city . '| ' . $to_add_state . '| ' . $to_add_zip . '|' . $to_add_notes;
            }
            $itemsArray = $request->get('item');
            $itemNamesArray=$request->get('item_name');
            $casePriceArray=$request->get('case_price');
            $priceArray = $request->get('price');
            $qtyArray = $request->get('qty');
            $productIdArray = $request->get('product_id');
            $requestIdArray = $request->get('request_id');
            $games = $request->get('game');
            $num_items_in_array = count($itemsArray);
            for ($i = 0; $i < $num_items_in_array; $i++) {
                $j = $i + 1;
                $order_description .= ' | item' . $j . ' - (' . $qtyArray[$i] . ') ' . $itemsArray[$i] . ' @ $' . $priceArray[$i] . ' ea.';
            }
            if ($editmode) {
                $orderData = array(
                    'company_id' => $company_id,
                    'location_id' => $location_id,
                    'order_type_id' => $order_type,
                    'date_ordered' => $date_ordered,
                    'vendor_id' => $vendor_id,
                    'order_description' => $order_description,
                    'order_total' => $total_cost,
                    'freight_id' => $freight_type_id,
                    'po_number' => $po,
                    'alt_address' => $alt_address,
                    'request_ids' => $where_in,
                    'po_notes' => $notes
                );
                $this->model->insertRow($orderData, $order_id);
                $last_insert_id = $order_id;
                \DB::table('order_contents')->where('order_id', $last_insert_id)->delete();
                //$this->db->delete('order_contents', array('order_id' => $last_insert_id));
            } else {

                $orderData = array(
                    'user_id' => \Session::get('uid'),
                    'company_id' => $company_id,
                    'location_id' => $location_id,
                    'order_type_id' => $order_type,
                    'date_ordered' => $date_ordered,
                    'vendor_id' => $vendor_id,
                    'order_description' => $order_description,
                    'status_id' => 1,
                    'order_total' => $total_cost,
                    'freight_id' => $freight_type_id,
                    'po_number' => $po,
                    'alt_address' => $alt_address,
                    'request_ids' => $where_in,
                    'new_format' => 1,
                    'po_notes' => $notes
                );
                $this->model->insert($orderData, $id);
                $order_id = \DB::getPdo()->lastInsertId();
                //$this->db->insert('orders', $orderData);
                //$last_insert_id = $this->db->insert_id();
            }
            for ($i = 0; $i < $num_items_in_array; $i++) {
                if (empty($productIdArray[$i])) {
                    $product_id = '0';
                } else {
                    $product_id = $productIdArray[$i];
                }

                if (empty($requestIdArray[$i])) {
                    $request_id = '0';
                } else {
                    $request_id = $requestIdArray[$i];
                }

                if ($order_type == 1) {
                    $game_id = $games[$i];
                } else {
                    $game_id = '0';
                }
                $contentsData = array(
                    'order_id' => $order_id,
                    'request_id' => $request_id,
                    'product_id' => $product_id,
                    'product_description' => $itemsArray[$i],
                    'price' => $priceArray[$i],
                    'qty' => $qtyArray[$i],
                    'game_id' => $game_id,
                    'item_name'=> $itemNamesArray[$i],
                    'case_price' => $casePriceArray[$i]
                );
                \DB::table('order_contents')->insert($contentsData);
                if ($order_type == 18) //IF ORDER TYPE IS PRODUCT IN-DEVELOPMENT, ADD TO PRODUCTS LIST WITH STATUS IN-DEVELOPMENT
                {
                    $productData = array(
                        'vendor_id' => $vendor_id,
                        'vendor_description' => $itemsArray[$i],
                        'case_price' => $priceArray[$i],
                        'num_items' => $qtyArray[$i],
                        'in_development' => 1,
                    );
                    \DB::table('products')->insert($productData);
                }
            }
           // $mailto = $vendor_email;
           // $from = \Session::get('eid');
            //send product order as email to vendor only if sendor and reciever email is available
           // if(!empty($mailto) && !empty($from))
           // {
           // $this->getPo($order_id, true,$mailto,$from);
            //}
            //$result = Mail::send('submitservicerequest.test', $message, function ($message) use ($to, $from, $full_upload_path, $subject) {
//
//        if (isset($full_upload_path) && !empty($full_upload_path)) {
//            $message->attach($full_upload_path);
//        }
//        $message->subject($subject);
//        $message->to($to);
//        $message->from($from);
//
//    });
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

    function getRemovalrequest($po_number = null)
    {
        $this->data['po_number'] = $po_number;
        return view('order.removalexplain', $this->data);
    }

    function postRemovalrequest(Request $request)
    {
        $po_number = $request->get('po_number');
        $explanation = $request->get('explaination');
        $message = 'Link to Order: http://'.$_SERVER['HTTP_HOST'].'/fegsys/orders/removeorder' . $po_number . ' <br>Explanation: ' . $explanation . '';
        $from = \Session::get('email');
        $to = 'support@fegllc.com';
        $to = 'greg@fegllc.com';
        $subject = 'Order Removal Request';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $message = $message;
        if (mail($to, $subject, $message, $headers)) {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        } else {
            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));
        }
    }

    function getRemoveorder($po)
    {
        echo $po;
    }

    function getPo($order_id = null, $sendemail = false, $to = null, $from = null)
    {
        $data = $this->model->getOrderData($order_id);


        if (empty($data)) {

        } else {
            if (empty($data[0]['po_for_location'])) {
                $data[0]['for_location'] = '';
            } else {
                $data[0]['for_location'] = '(for ' . $data[0]['po_for_location'] . ')';
            }

            if ($data[0]['freight_type'] == 'Employee Pickup') {
                $data[0]['po_location'] = '**WILL PICKUP FROM ' . $data[0]['vendor_name'] . '**' . "\n" . $data[0]['po_location'];
            }

            if (!empty($data[0]['loading_info']) && ($data[0]['order_type_id'] == 4 || $data[0]['order_type_id'] == 9)) //IF ORDER TYPE IS TICKTS/TOKENS OR FIXED ASSET -- AKA LARGE ITEMS
            {
                $data[0]['freight_type'] = $data[0]['freight_type'] . "\n" . 'DELIVERY NOTES: **' . $data[0]['loading_info'] . '**';
            }

            if (!empty($data[0]['loc_merch_contact_email']) && ($data[0]['order_type_id'] == 7 || $data[0]['order_type_id'] == 8)) {
                $data[0]['loc_contact_email'] = $data[0]['loc_merch_contact_email'];
            }

            if ($data[0]['email'] != $data[0]['loc_contact_email']) {
                $data[0]['loc_contact_email'] = ' AND ' . $data[0]['loc_contact_email'];
            } else {
                $data[0]['loc_contact_email'] = '';
            }
            if ($data[0]['order_type_id'] == 3 || $data[0]['order_type_id'] == 4) {
                $data[0]['cc_email'] = ', lisa.price@fegllc.com';
            } else {
                $data[0]['cc_email'] = '';
            }
            if (!empty($data[0]['po_attn'])) {
                $data[0]['po_location'] = $data[0]['po_location'] . "\n" . $data[0]['po_attn'];
            }
            if (empty($data[0]['po_notes'])) {
                $data[0]['po_notes'] = " NOTE: **TO CONFIRM ORDER RECEIPT AND PRICING, SEND EMAILS TO " . $data[0]['email'] . $data[0]['cc_email'] . $data[0]['loc_contact_email'] . "**";
            } else {
                $data[0]['po_notes'] = " NOTE: " . $data[0]['po_notes'] . " (Email Questions to " . $data[0]['email'] . $data[0]['cc_email'] . $data[0]['loc_contact_email'] . ")";
            }
            $order_description = $data[0]['order_description'];

            if (substr($order_description, 0, 3) === ' | ') {
                $order_description = substr($order_description, 3);
            }
            $order_description = str_replace(' | ', "\n", $order_description);

            if ($data[0]['new_format'] == 1) {
                $item_description_string = '';
                $item_price_string = '';
                $item_qty_string = '';
                $item_total_string = '';
                $item_total = '';
                $order_total_cost = 0;
                $numLenghtyDescItems = 0;
                for ($i = 0; $i < $data[0]['requests_item_count']; $i++) {
                    $j = $i + 1;
                    $item_total = $data[0]['orderPriceArray'][$i] * $data[0]['orderQtyArray'][$i];
                    $item_total_string = "$ " . number_format($item_total, 2);
                    $item_description_string = "Item #" . $j . ": " . $data[0]['orderDescriptionArray'][$i];
                    $item_qty_string = $data[0]['orderQtyArray'][$i];
                    $item_price_string = $data[0]['orderPriceArray'][$i];
                    $descriptionLength = strlen($item_description_string);
                    $order_total_cost = $order_total_cost + $item_total;
                }
                $data[0]['item_description_string'][$i] = $item_description_string;
                $data[0]['item_price_string'][$i] = $item_price_string;
                $data[0]['item_qty_string'][$i] = $item_qty_string;
                $data[0]['item_total_string'][$i] = $item_total_string;
                $data[0]['order_total_cost'] = $order_total_cost;
                // $item_total_string = $item_total_string."-----------------\n"."$ ".number_format($order_total_cost,2)."\n";
            }
            $pdf = \PDF::loadView('order.po', ['data' => $data, 'main_title' => "Purchase Order"]);
            if ($sendemail) {
                if (isset($to)) {
                    $filename='PO_'.$order_id.'.pdf';
                    $subject = "Purchase Order";
                    $message = "Purchase Order";
                    $result = \Mail::raw($message, function ($message) use ($to, $from, $subject, $pdf,$filename) {
                        $message->subject($subject);
                        $message->from($from);
                        $message->to($to);
                        $message->attachData($pdf->output(),$filename);
                    });
                }
            } else {
                return $pdf->download($data[0]['company_name_short'] . "_PO_" . $data[0]['po_number'] . '.pdf');
            }
        }
    }

    function getClone($id)
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
            $this->data['row'] = $this->model->getColumnTable('order');
        }
        $this->data['setting'] = $this->info['setting'];
        //  $this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['data'] = $this->model->getOrderQuery($id);
        return view('order.clonenew', $this->data);
    }

    function postValidateponumber(Request $request)
    {
        $po_1 = $request->get('po_1');
        $po_2 = $request->get('po_2');
        $po_3 = $request->get('po_3');
        $po_full = $po_1 . '-' . $po_2 . '-' . $po_3;
        $msg = $this->model->getPoNumber($po_full);
        echo $msg;
    }

    function getOrderreceipt($order_id = null)
    {
        $this->data['data'] = $this->model->getOrderReceipt($order_id);
        return view('order.order-receipt', $this->data);
    }

    function postReceiveorder(Request $request, $id = null)
    {

        $order_id = $request->get('order_id');
        $item_count = $request->get('item_count');
        $notes = $request->get('notes');
        $order_status = $request->get('order_status');
        $added_to_inventory = $request->get('added_to_inventory');
        $user_id = $request->get('user_id');
        $added = 0;
        $rules = array();
        if (empty($notes)) {
            $rules['order_status'] = "required:min:2";
        }
        if ($order_status == 5) // Advanced Replacement Returned.. require tracking number
        {
            $rules['tracking_number'] = "required|min:3";
            $tracking_number = $request->get('tracking_number');
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if (!empty($item_count) && $added_to_inventory == 0) {

                ///////APPLY PRIZES TO THE PROPER GAMES / LOCATIONS
                for ($i = 1; $i <= $item_count; $i++) {
                    $product_id = $request->get('product_id_' . $i);
                    $order_qty = $request->get('order_qty_' . $i);
                    $game = $request->get('game_' . $i);

                    // IF NO GAME SELECTED, INSERT INTO INVENTORY FOR USER'S LOCATION
                    if (!empty($game)) {
                        // IF ALL AVAILABLE QUANTITIES ARE ALLOCATED TO THE GAME
                        $allGame = array('product_id' => $product_id);
                        \DB::table('game')->where('id', $game)->update($allGame);
                    }

                    $location_id = $request->get('location_id');

                    $query = \DB::select('SELECT id
											 FROM merch_inventory
											WHERE product_id = ' . $product_id . '
								   	 		  AND location_id = ' . $location_id . '');

                    if (count($query) == 1) {
                        \DB::update('UPDATE merch_inventory
								 	 	 SET product_qty = product_qty + ' . $order_qty . '
							   	   	   WHERE product_id = ' . $product_id . '
								   	     AND location_id = ' . $location_id);
                    } else {
                        \DB::insert('INSERT INTO merch_inventory (`location_id`,`product_id`,`product_qty`,`user_id`)
							 	  		   VALUES (' . $location_id . ',' . $product_id . ',' . $order_qty . ',' . $user_id . ')');
                    }
                }
                $added = 1;
            }
            $data = array('date_received' => $request->get('date_received'),
                'status_id' => $order_status,
                'notes' => $notes,
                'tracking_number' => $request->get('tracking_number'),
                'received_by' => $request->get('user_id'),
                'added_to_inventory' => $added);
            \DB::table('orders')->where('id', $request->get('order_id'))->update($data);
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

    public function getSubmitorder($SID)
    {
        $this->data['sid'] = $SID;
        $this->data['access'] = $this->access;
        return view('order.index', $this->data);
    }
    public function getProduct()
    {
        $rows=\DB::select('select vendor_description,sku from products where id is not null');
        $json=array();
        foreach($rows as $row)
        {
            $json[]=array('label'=>$row->vendor_description,'sku'=>$row->sku);
        }
        return json_encode($json);
    }
    public function getAutocomplete()
    {
        $term = Input::get('term');
        $results = array();
        $queries = \DB::table('products')
            ->where('vendor_description', 'LIKE', '%' . $term . '%')
            ->take(10)->get();
        if (count($queries) != 0) {
            foreach ($queries as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->vendor_description];
            }
            echo json_encode($results);
        }
        else{
            echo json_encode(array('id' => 0 ,'value' => "No Match"));
        }
    }

    public function getProductdata()
    {
        $vendor_description=Input::get('product_id');
        $row=\DB::select('select id,item_description,unit_price,case_price from products WHERE vendor_description="'.$vendor_description.'"');
        $json=array('item_description'=>$row[0]->item_description,'unit_price'=>$row[0]->unit_price,'case_price'=>$row[0]->case_price,'id'=>$row[0]->id);
        echo json_encode($json);
    }

}