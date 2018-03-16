<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
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
        $this->data['MerchandisePO'] = $MerchandiseSetting;
        $this->data['NonMerchandisePO'] = $NonMerchandiseSetting;
        $this->data['MerchandiseOrder'] = implode(",", $MerchandiseOrderTypes);
        $this->data['NonMerchandiseOrder'] = implode(",", $NonMerchandiseOrderTypes);

        $this->data['access'] = $this->access;
        return view('ordersetting.setting', $this->data);
    }

    public function postSave(Request $request)
    {

        $merchandisePONote = $request->input('merchandisePONote');
        $NonmerchandisePONote = $request->input('NonmerchandisePONote');
        $merchandiseOrderTypes = $request->input('merchandiseordertypes');
        $NonMerchandiseOrderTypes = $request->input('Nonmerchandiseordertypes');
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
        foreach ($merchandiseOrderTypes as $MerchandiseOrderType) {
            $Ordersettingcontent = new OrdersettingContent();
            $Ordersettingcontent->ordersetting_id = $merchandiseOrderSetting->id;
            $Ordersettingcontent->ordertype_id = $MerchandiseOrderType;
            $Ordersettingcontent->save();
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
        foreach ($NonMerchandiseOrderTypes as $NonMerchandiseOrderType) {
            $Ordersettingcontent = new OrdersettingContent();
            $Ordersettingcontent->ordersetting_id = $NonMerchandiseOrderSetting->id;
            $Ordersettingcontent->ordertype_id = $NonMerchandiseOrderType;
            $Ordersettingcontent->save();
        }


        return response()->json(array(
            'message' => 'Order Setting has been saved successfully.',
            'status' => 'success',

        ));


    }

}