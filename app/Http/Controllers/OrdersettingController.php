<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Ordersetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect;

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
        $ordersetting = new $this->model();
        echo "<pre>";
        dd($ordersetting->where("id", 1));
        die;
        $this->data['access'] = $this->access;
        return view('ordersetting.setting', $this->data);
    }

    public function postSave(Request $request)
    {

    }

}