<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\NetSuiteApiLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Test extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->data = "";
        $this->products = array();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    protected $urlString = 'http://admin1.fegllc.com/fegapi';
    protected $tokenString = '&token=f1a9bE1f7M208M3eIb0b048L0d0O921Vd8bEbaa6ow35l23HaxcAn2Ddaf245I';
    protected $data;
    protected $products;

    public function handle()
    {
        //
        $modules = ['order', 'product', 'itemreceipt'];
        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';
        $currentDate = date($dateFormat);
        $fromTime = date($timeFormat, time() - (3600 * 2));
        $toTime = date($timeFormat, time() - (3600 * 1));

        foreach ($modules as $module) {
            $this->data[$module] = $this->getResponse($module, $currentDate, $currentDate, $fromTime, $toTime);
        }
        $this->getProductIds();
        $this->validateOrders();
//        if($orders['code'] == 200)
//        {
//            $data = json_decode($orders['data']);
//            $total = $data->total;
//            if($total>0)
//            {
//
//            }
//        }

//        echo $data;
    }

    public function validateOrders()
    {
        $orders = json_decode($this->data['order']['data']);
        $orderTotal = $orders->total;
        if ($orderTotal > 0) {
            $rows = $orders->rows;
            foreach ($rows as $row) {
                $items = $row->items;
                foreach ($items as $item) {
                    $product_id = $item->product_id;
                    $this->checkProduct($product_id);
                }
            }
        }
//        var_dump($orderTotal);
    }

    public function checkProduct($id)
    {
        if(in_array($id,$this->products))
        {
            return true;
        }
        else
        {
            $this->sendErrorMail('Product', null, 404, 'Product id '.$id.' not Found');
        }
    }

    public function getProductIds()
    {
        $products = json_decode($this->data['product']['data']);
        $rows = $products->rows;
        foreach ($rows as $row) {
            array_push($this->products,$row->id);
        }
    }

    private function getResponse($module, $from_date = null, $to_date = null, $from_time = null, $to_time = null, $limit = 50)
    {
        $fromString = "";
        $toString = "";
        if ($from_date && $from_time) {
            $fromString = '&created_from=' . $from_date . ' ' . $from_time;
        }
        if ($to_date && $from_time) {
            $toString = '&created_to=' . $to_date . ' ' . $to_time;
        }
        $url = $this->urlString . '?module=' . $module . $fromString;
        $url .= $toString . $this->tokenString;
        $url = 'http://demo.fegllc.com/fegapi?module=' . $module . '&limit=50&page=1&token=f1a9bE1f7M208M3eIb0b048L0d0O921Vd8bEbaa6ow35l23HaxcAn2Ddaf245I';
        $status = "";
        $response = "";
        try {
            $response = $this->client->request('GET', $url);
        } catch (BadResponseException $e) {
            //this Will Catch All error response code and body
            $errorCode = $e->getResponse()->getStatusCode();
            $errorMsg = $e->getResponse()->getBody();
            $this->sendErrorMail($module, $url, $errorCode, $errorMsg);
            $this->addDataLog($module, $url, $errorCode, $errorMsg);
        }
        if ($response) {
            $status = $response->getStatusCode();
            if ($status == 200) {
                $this->addDataLog($module, $url, $status, 'Record Found.');
                return [
                    'code' => 200,
                    'data' => $response->getBody()
                ];
            }
        }
        return $status;
    }

    private function sendErrorMail($module, $url, $errorCode, $errorMsg)
    {
        $to = 'dev1@shayansolutions.com';
        $subject = 'Error';
        $message = $errorCode . $url . $errorMsg;
        FEGSystemHelper::sendEmail($to, $subject, $message);
    }

    private function addDataLog($module, $url, $code, $message)
    {
        $nsa = new NetSuiteApiLog();
        $nsa->module = $module;
        $nsa->url = $url;
        $nsa->response_code = $code;
        $nsa->response_message = $message;
        $nsa->save();
        return "success";
    }
}
