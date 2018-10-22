<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Feg\System\Options;
use App\Models\order;
use App\Models\Ordersetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;
use App\Models\OrdersettingContent;

class OrdersettingController extends Controller
{
    protected $layout = "layouts.main";
    protected $data = array();
    public $module = 'ordersetting';
    static $per_page = '10';

    /**
     * Constant representing FEG Settings for Orders
     *
     * @var array
     */
    const FEG_SETTINGS = ['order_receipt_reminder_days_threshold'];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Ordersetting();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'ordersetting',
            'pageUrl' => url('ordersetting'),
            'return' => self::returnUrl()
        );


    }

    public function getSetting()
    {
        $MerchandiseSetting = "";
        $NonMerchandiseSetting = "";
        $MerchandiseOrderTypes = [];
        $NonMerchandiseOrderTypes = [];
        $excludedOrders = [];

        $MerchandiseOrderSetting = $this->model->where("is_merchandiseorder", 1)->first();
        if ($MerchandiseOrderSetting) {
            $MerchandiseSetting = $MerchandiseOrderSetting->po_note;
            foreach ($MerchandiseOrderSetting->ordersettingcontent as $SettingContent) {
                $MerchandiseOrderTypes[] = $SettingContent->ordertype_id;
            }
        }
        $NonMerchandiseOrderSetting = $this->model->where("is_merchandiseorder", 0)->first();
        if ($NonMerchandiseOrderSetting) {
            $NonMerchandiseSetting = $NonMerchandiseOrderSetting->po_note;
            foreach ($NonMerchandiseOrderSetting->ordersettingcontent as $SettingContent) {
                $NonMerchandiseOrderTypes[] = $SettingContent->ordertype_id;
            }
        }
        $GraphicsSender = "";
        $GraphicsReceiver = "";

        $GraphicsRequestSetting = $this->model->where("is_graphics_setting", 1)->first();
        if ($GraphicsRequestSetting) {
            $GraphicsSender = $GraphicsRequestSetting->graphics_sender_content;
            $GraphicsReceiver = $GraphicsRequestSetting->graphics_recever_content;
        }


        $orders = order::take(10)->distinct()->lists('po_number', 'po_number')->toArray();
        $option = Options::where('option_name', 'excluded_orders')->first();
        if($option){
            $excludedOrders = explode(',', $option->option_value);
            $orders = array_merge($orders, $excludedOrders);
        }
        $option = Options::where('option_name', 'excluded_orders_from_groups')->first();
        $excludedOrdersPos = [];
        if($option){
            $excludedOrdersPos = explode(',', $option->option_value);
            $orders = array_merge($orders, $excludedOrdersPos);
        }
        $option = Options::where('option_name', 'excluded_orders_groups')->first();
        $excludedGroups = "";
        if($option){
            $excludedGroups = $option->option_value; // explode(',', $option->option_value);
        }

        $newOrdersArray = [];
        foreach ($orders as $order){
            $newOrdersArray[$order] = $order;
        }
        $orders = $newOrdersArray;

        $this->data['MerchandisePO']        = $MerchandiseSetting;
        $this->data['NonMerchandisePO']     = $NonMerchandiseSetting;
        $this->data['MerchandiseOrder']     = implode(",", $MerchandiseOrderTypes);
        $this->data['NonMerchandiseOrder']  = implode(",", $NonMerchandiseOrderTypes);
        $this->data['GraphicsSender']       = $GraphicsSender;
        $this->data['GraphicsReceiver']     = $GraphicsReceiver;
        $this->data['ExcludedOrders']       = $excludedOrders;
        $this->data['Orders']               = $orders;
        $this->data['ExcludedOrdersPos']    = $excludedOrdersPos;
        $this->data['excludedGroups']       = $excludedGroups;
        $productOptions =   Options::whereIn('option_name', ['product_label_new','product_label_backinstock'])->get();

        foreach ($productOptions as $productOption){
            $this->data[$productOption->option_name] = $productOption->option_value;
        }
        $this->data['access'] = $this->access;

        $settingsData = [];
        foreach(self::FEG_SETTINGS as $fegSetting) {
            $settingsData[camel_case($fegSetting)] = \FEGHelp::getOption($fegSetting, '', false, true, true);
        }
        $this->data['fegSettings'] = $settingsData;

        return view('ordersetting.setting', $this->data);
    }

    public function postSave(Request $request)
    {
        $merchandisePONote = $request->input('merchandisePONote');
        $NonmerchandisePONote = $request->input('NonmerchandisePONote');
        $merchandiseOrderTypes = $request->input('merchandiseordertypes');
        $NonMerchandiseOrderTypes = $request->input('Nonmerchandiseordertypes');
        $GraphicsRequestSenderContent = $request->input('newgraphicsrequestsendercontent');
        $GraphicsRequestReceiverContent = $request->input('newgraphicsrequestreceivercontent');
        $excludedOrders = !empty($request->input('excluded_orders')) ? implode(',', $request->input('excluded_orders')) : '';
        $excludedOrdersFromSpecifiedGroup = !empty($request->input('excluded_orders_from_groups')) ? implode(',', $request->input('excluded_orders_from_groups')) : '';
        $excludedUserGroups = !empty($request->input('userGroups')) ? implode(',', $request->input('userGroups')) : '';;
        $productLabelNew = !empty($request->product_label_new) ? $request->product_label_new : 0;
        $productLabelBackinstock = !empty($request->product_label_backinstock) ? $request->product_label_backinstock:14;
        if (is_array($merchandiseOrderTypes) && is_array($NonMerchandiseOrderTypes)) {
            if (count(array_intersect($merchandiseOrderTypes, $NonMerchandiseOrderTypes)) > 0) {
                return response()->json(array(
                    'message' => 'You can not select the same order type for both Merchandise and Non Merchandise.',
                    'status' => 'error',

                ));
            }
        }

        $merchandiseOrderSetting = $this->model->firstOrNew(["is_merchandiseorder" => 1]);
        $merchandiseOrderSetting->po_note = $merchandisePONote;
        $merchandiseOrderSetting->is_merchandiseorder = 1;
        $merchandiseOrderSetting->save();
        $settingContent = $merchandiseOrderSetting->ordersettingcontent();

        if ($settingContent->getResults()->count() > 0) {
            foreach ($settingContent->getResults() as $orderType) {
                $orderType->delete();
            }
        }
        if (!empty($merchandiseOrderTypes)) {
            foreach ($merchandiseOrderTypes as $MerchandiseOrderType) {
                $Ordersettingcontent = new OrdersettingContent();
                $Ordersettingcontent->ordersetting_id = $merchandiseOrderSetting->id;
                $Ordersettingcontent->ordertype_id = $MerchandiseOrderType;
                $Ordersettingcontent->save();
            }
        }

        $NonMerchandiseOrderSetting = $this->model->firstOrNew(["is_merchandiseorder" => 0]);
        $NonMerchandiseOrderSetting->po_note = $NonmerchandisePONote;
        $NonMerchandiseOrderSetting->is_merchandiseorder = 0;
        $NonMerchandiseOrderSetting->save();
        $settingContent = $NonMerchandiseOrderSetting->ordersettingcontent();

        if ($settingContent->getResults()->count() > 0) {
            foreach ($settingContent->getResults() as $orderType) {
                $orderType->delete();
            }
        }
        if (!empty($NonMerchandiseOrderTypes)) {
            foreach ($NonMerchandiseOrderTypes as $NonMerchandiseOrderType) {
                $Ordersettingcontent = new OrdersettingContent();
                $Ordersettingcontent->ordersetting_id = $NonMerchandiseOrderSetting->id;
                $Ordersettingcontent->ordertype_id = $NonMerchandiseOrderType;
                $Ordersettingcontent->save();
            }
        }

        $GraphicsRequestSetting = $this->model->firstOrNew(["is_graphics_setting" => 1]);
        $GraphicsRequestSetting->graphics_sender_content = $GraphicsRequestSenderContent;
        $GraphicsRequestSetting->graphics_recever_content = $GraphicsRequestReceiverContent;
        $GraphicsRequestSetting->is_graphics_setting = 1;
        $GraphicsRequestSetting->save();

        $option = Options::where('option_name', 'product_label_new')->first();
        if(!$option){
            Options::addOption('product_label_new', $productLabelNew, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Tag New will be removed after days(x)',
                'option_description' => 'Tag New will be added automatically to any product which is newly added to the Product List module. The label/banner will exist for a # of days determined by the relevant entry in the Settings module, after which it will automatically remove itself.',
                'option_form_element_details' => null
            ]);
        }else{
            Options::updateOption('product_label_new', $productLabelNew, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Tag New will be removed after days(x)',
                'option_description' => 'Tag New will be added automatically to any product which is newly added to the Product List module. The label/banner will exist for a # of days determined by the relevant entry in the Settings module, after which it will automatically remove itself.',
                'option_form_element_details' => null
            ]);
        }

        $option = Options::where('option_name', 'product_label_new')->first();
        if(!$option){
            Options::addOption('product_label_backinstock', $productLabelBackinstock, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'The tag Back in Stock will be removed after days(x)',
                'option_description' => 'The tag will be present for a # of days configured in the Orders/Requests > Settings module. The default configuration will be 14 days from the date found in the product\'s Updated_On field in the Product List module.',
                'option_form_element_details' => null
            ]);
        }else{
            Options::updateOption('product_label_backinstock', $productLabelBackinstock, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'The tag Back in Stock will be removed after days(x)',
                'option_description' => 'The tag will be present for a # of days configured in the Orders/Requests > Settings module. The default configuration will be 14 days from the date found in the product\'s Updated_On field in the Product List module.',
                'option_form_element_details' => null
            ]);
        }

        $option = Options::where('option_name', 'excluded_orders')->first();
        if(!$option){
            Options::addOption('excluded_orders', $excludedOrders, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Exclude Orders',
                'option_description' => 'Exclude Orders from the Product Usage, Merchandise Expense and Inventory Report',
                'option_form_element_details' => null
            ]);
        }else{
                Options::updateOption('excluded_orders', $excludedOrders, [
                    'is_active' => 1,
                    'notes' => null,
                    'option_title' => 'Exclude Orders',
                    'option_description' => 'Exclude Orders from the Product Usage, Merchandise Expense and Inventory Report',
                    'option_form_element_details' => null
                ]);
        }
        $option = Options::where('option_name', 'excluded_orders_from_groups')->first();
        if(!$option){
            Options::addOption('excluded_orders_from_groups', $excludedOrdersFromSpecifiedGroup, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Exclude Order(s) From Specified User Groups',
                'option_description' => 'The Orders which are related to these PO Numbers will be excluded from specified user groups.',
                'option_form_element_details' => null
            ]);
            Options::addOption('excluded_orders_groups', $excludedUserGroups, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Exclude Order(s) From Specified User Groups',
                'option_description' => 'The Orders which are related to these PO Numbers will be excluded from specified user groups.',
                'option_form_element_details' => null
            ]);
        }else{
            if(!empty($option->option_value)) {
                Options::updateOption('excluded_orders_from_groups', $excludedOrdersFromSpecifiedGroup, [
                    'is_active' => 1,
                    'notes' => null,
                    'option_title' => 'Exclude Order(s) From Specified User Groups',
                    'option_description' => 'The Orders which are related to these PO Numbers will be excluded from specified user groups.',
                    'option_form_element_details' => null
                ]);
            }else{
                Options::destroy($option->id);
            }
            Options::updateOption('excluded_orders_groups', $excludedUserGroups, [
                'is_active' => 1,
                'notes' => null,
                'option_title' => 'Exclude Order(s) From Specified User Groups',
                'option_description' => 'The Orders which are related to these PO Numbers will be excluded from specified user groups.',
                'option_form_element_details' => null
            ]);
        }





        $requestData = $request->all();
        foreach(self::FEG_SETTINGS as $fegSetting) {
            if (isset($requestData[$fegSetting])) {
                \FEGHelp::updateOption($fegSetting, $requestData[$fegSetting]);
            }
        }

        return response()->json(array(
            'message' => 'Order Setting has been saved successfully.',
            'status' => 'success',
        ));

    }

    public function searchTheOrderByPONumber(Request $request){

        $selected_po_numbers = $request->get('selected_po_numbers');

        if(!$selected_po_numbers)
            $selected_po_numbers = [];

        $poNumber = $request->get('po_number');

        $searchedPONumbers = order::select('po_number as id','po_number as text');

        if($poNumber)
            $searchedPONumbers = $searchedPONumbers->where('po_number', 'LIKE', '%'.$poNumber.'%');

        $searchedPONumbers = $searchedPONumbers
            ->whereNotIn('po_number', $selected_po_numbers)
            ->distinct()
            ->take(10)
            ->get();

        return $searchedPONumbers;
    }

}