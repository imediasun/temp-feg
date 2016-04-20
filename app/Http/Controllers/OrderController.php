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
        $order_selected = isset($_GET['order_type']) ? $_GET['order_type'] : 'ALL';
        $results = $this->model->getRows($params, $order_selected);
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
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

            $vendor = \DB::table('vendor')->where('id', '=', $data->vendor_id)->get(array('vendor_name'));
            $rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');

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


    function getUpdate(Request $request, $id =0,$mode='')
    {
        if($id != 0 && $mode == '')
        {
            $mode = 'edit';
        }
        elseif($id == 0 && $mode == '')
        {
            $mode = 'create';
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
        $this->data['mode']=$mode;
        $this->data['id'] = $id;
        $this->data['data']=$this->model->getOrderQuery($id,$mode);
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
        $this->data['order_data']=$this->model->getOrderQuery($id,'edit');
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
       return \Redirect::to('/order');
       $rules=array('location_id'=>"required",'vendor_id'=>'required','order_type_id'=>"required",'freight_type_id'=>'required','date_ordered'=>'required','po_3'=>'required');
        $validator = Validator::make($request->all(), $rules);
        $order_contents=array();

        if ($validator->passes()) {

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
        $message = 'Link to Order: 192.232.207.127/fegsys/orders/removeorder' . $po_number . ' <br>Explanation: ' . $explanation . '';
        $from = \Session::get('email');
        $to = 'support@fegllc.com';
        $to = 'adnanali199@gmail.com';
        $subject = 'Order Removal Request';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $message = $message;
       if( mail($to, $subject, $message, $headers)) {
           return response()->json(array(
               'status' => 'success',
               'message' => \Lang::get('core.note_success')
           ));
       }
        else{
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
    function getPo($order_id=null)
    {
        $data=$this->model->getOrderData($order_id);


        if(empty($data))
        {

        }
        else
        {
            if (empty($data[0]['po_for_location']))
            {
                $data[0]['for_location'] = '';
            }
            else
            {
                $data[0]['for_location'] = '(for '.$data[0]['po_for_location'].')';
            }

            if($data[0]['freight_type'] == 'Employee Pickup')
            {
                $data[0]['po_location'] = '**WILL PICKUP FROM '.$data[0]['vendor_name'].'**'."\n".$data[0]['po_location'];
            }

            if(!empty($data[0]['loading_info']) && ($data[0]['order_type_id'] == 4 || $data[0]['order_type_id'] == 9)) //IF ORDER TYPE IS TICKTS/TOKENS OR FIXED ASSET -- AKA LARGE ITEMS
            {
                $data[0]['freight_type'] = $data[0]['freight_type']."\n".'DELIVERY NOTES: **'.$data[0]['loading_info'].'**';
            }

            if(!empty($data[0]['loc_merch_contact_email']) && ($data[0]['order_type_id'] == 7 || $data[0]['order_type_id'] == 8))
            {
                $data[0]['loc_contact_email'] = $data[0]['loc_merch_contact_email'];
            }

            if ($data[0]['email'] != $data[0]['loc_contact_email'])
            {
                $data[0]['loc_contact_email'] = ' AND '.$data[0]['loc_contact_email'];
            }
            else
            {
                $data[0]['loc_contact_email'] = '';
            }
            if ($data[0]['order_type_id'] == 3 || $data[0]['order_type_id'] == 4)
            {
                $data[0]['cc_email'] = ', lisa.price@fegllc.com';
            }
            else
            {
                $data[0]['cc_email'] = '';
            }
            if(!empty($data[0]['po_attn']))
            {
                $data[0]['po_location'] = $data[0]['po_location']."\n".$data[0]['po_attn'];
            }
            if(empty($data[0]['po_notes']))
            {
             $data[0]['po_notes']=" NOTE: **TO CONFIRM ORDER RECEIPT AND PRICING, SEND EMAILS TO ".$data[0]['email'].$data[0]['cc_email'].$data[0]['loc_contact_email']."**";
            }
            else
            {
                $data[0]['po_notes']= " NOTE: ".$data[0]['po_notes']." (Email Questions to ".$data[0]['email'].$data[0]['cc_email'].$data[0]['loc_contact_email'].")";
            }
            $order_description = $data[0]['order_description'];

            if(substr($order_description, 0, 3) === ' | ')
            {
                $order_description = substr($order_description, 3);
            }
            $order_description = str_replace(' | ',"\n",$order_description);

            if($data[0]['new_format'] == 1)
            {
                $item_description_string = '';
                $item_price_string = '';
                $item_qty_string = '';
                $item_total_string = '';
                $item_total = '';
                $order_total_cost = 0;
                $numLenghtyDescItems = 0;
                for($i=0;$i < $data[0]['requests_item_count']; $i++)
                {
                    $j = $i+1;
                    $item_total = $data[0]['orderPriceArray'][$i] * $data[0]['orderQtyArray'][$i];
                    $item_total_string = "$ ".number_format($item_total,2);
                    $item_description_string = "Item #".$j.": ".$data[0]['orderDescriptionArray'][$i];
                    $item_qty_string = $data[0]['orderQtyArray'][$i];
                    $item_price_string = $data[0]['orderPriceArray'][$i];
                    $descriptionLength = strlen($item_description_string);
                    $order_total_cost = $order_total_cost + $item_total;
                }
                $data[0]['item_description_string'][$i]=$item_description_string;
                $data[0]['item_price_string'][$i]= $item_price_string;
                $data[0]['item_qty_string'][$i]=$item_qty_string;
                $data[0]['item_total_string'][$i]=$item_total_string;
                $data[0]['order_total_cost']=$order_total_cost;
                // $item_total_string = $item_total_string."-----------------\n"."$ ".number_format($order_total_cost,2)."\n";
            }
            $pdf = \PDF::loadView('order.po',['data'=>$data,'main_title'=>"Purchase Order"]);
            return $pdf->download($data[0]['company_name_short']."_PO_".$data[0]['po_number'].'.pdf');
        }
    }
    function getClone($id)
    {
        if($id =='')
        {
            if($this->access['is_add'] ==0 )
                return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
        }

        if($id !='')
        {
            if($this->access['is_edit'] ==0 )
                return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
        }

        $row = $this->model->find($id);
        if($row)
        {
            $this->data['row'] 		=  $row;
        } else {
            $this->data['row'] 		= $this->model->getColumnTable('order');
        }
        $this->data['setting'] 		= $this->info['setting'];
      //  $this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
        $this->data['id'] = $id;
        $this->data['access']=$this->access;
        $this->data['data']=$this->model->getOrderQuery($id);
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
}