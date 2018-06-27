<?php

namespace App\Console\Commands;

use App\Library\FEG\System\FEGSystemHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;
use App\Http\Requests;
use Log;

class CheckNetSuiteApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * CheckNetSuiteApi constructor.
     * @param Client $client
     */

    protected $client;

    private $to;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->data = "";
        $this->products = array();
        $this->orderIds = array();
        $this->productData = array();
        $this->to = array([
            'shayansolutions@gmail.com',
            //'gabes@inmedianetworks.com',
            'stanlymarian@gmail.com',
            'dev3@shayansolutions.com',
            'qa@shayansolutions.com'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    protected $urlString = 'http://feg-laravel/fegapi';
    protected $tokenString = '&token=f1a9bE1f7M208M3eIb0b048L0d0O921Vd8bEbaa6ow35l23HaxcAn2Ddaf245I';
    protected $data;
    protected $products;
    protected $orderIds;
    protected $productData;
    protected $parems =[];
    protected $orderApiUrl;
    protected $apiResponse;
    protected $errorMessage;
    protected $orderId;
    protected $errorMessageText;
    protected $orderContents = [];
    protected $orderReceipts = [];


    public function handle()
    {

        $modules = ['order', 'product', 'itemreceipt'];
        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';
        $currentDate = date($dateFormat);
        $fromTime = date($timeFormat, time() - (3600 * 2));
        $toTime = date($timeFormat, time() - (3600 * 1));
        $timeString = '&created_from=' . $currentDate . ' ' . $fromTime;
        $timeString .= '&updated_from=' . $currentDate . ' ' . $fromTime;
        $timeString .= '&created_to=' . $currentDate . ' ' . $toTime;
        $timeString .= '&updated_to=' . $currentDate . ' ' . $toTime;
        $this->parems = [
            'created_from=' . $currentDate . ' ' . $fromTime,
            'updated_from=' . $currentDate . ' ' . $fromTime,
            'created_to=' . $currentDate . ' ' . $toTime,
            'updated_to=' . $currentDate . ' ' . $toTime
        ];
        Log::info("NetSuite Api:-*-*--*-*--*-*--*-*-Api process started-*-*--*-*--*-*--*-*-");
        $this->orderApiUrl = $this->urlString . '?module=order'  . $timeString. $this->tokenString."&page=1&limit=5000";
        Log::info("NetSuite Api: Order Api Url: ".$this->orderApiUrl);
        foreach ($modules as $module) {
            Log::info("NetSuite Api: ".$module." api triggered checking record");
            if($module == 'product'){
                $this->data[$module] = $this->getProductResponse($module,'',5000);
            }else {
                $this->data[$module] = $this->getResponse($module, $timeString,5000);
            }
        }
        $this->getProductIds();
        $this->getorderIdsFromReceipts();
        $this->validateOrders();
    }

    public function validateOrders()
    {
        $orders = json_decode($this->data['order']['data']);
        $orderTotal = $orders->total;
        if ($orderTotal > 0) {
            $rows = $orders->rows;
            foreach ($rows as $row) {
                $this->orderId = $row->id;
                $items = $row->items;
                foreach ($items as $item) {
                    $this->checkProduct($item);
                }
                $this->checkReceipts($item,$row);
            }
        }
    }

    public function checkReceipts($item,$row)
    {
        try {
            $response = $this->client->request('GET', $this->urlString."/".$row->id."?module=itemreciept&token=".$this->tokenString);
        } catch (BadResponseException $e) {

                $errorMessage = [
                    'Error code 404 : Order receipt not found.',
                    'Error URL : '.$this->orderApiUrl,
                    'Order Id : '.$row->id,
                    'PO Number:'.$row->po_number,
                    'Module Effected : itemreciept',
                    'Error occurred Date time : '.date("Y-m-d H:i:s"),
                ];
                $this->sendErrorMail('OrdersReceipts', null, 404, implode('<br>',$errorMessage));
        }
    }

    public function checkProduct($item)
    {
        if(in_array($item->product_id,$this->products))
        {
            return true;
        }
        else
        {
            $this->errorMessageText = 'Product not found.';
            $errorMessage = [
                'Error code 404 : Product not found.',
                'Error URL : '.$this->urlString."/".$item->product_id."?module=product&token=".$this->tokenString,
                'Product Id : '.$item->product_id,
                'Item Name : '.$item->item_name,
                'Item Description : '.$item->product_description,
                'Module Effected : product',
                'Error occurred Date time : '.date("Y-m-d H:i:s"),
            ];
            $this->sendErrorMail('Product', null, 404, implode("<br>",$errorMessage));
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

    public function getorderIdsFromReceipts()
    {
        $products = json_decode($this->data['itemreceipt']['data']);
        $rows = $products->rows;
        foreach ($rows as $row) {
            array_push($this->orderIds,$row->order_id);
        }
    }

    private function getResponse($module, $timeString = '', $limit = 50)
    {

        $url = $this->urlString . '?module=' . $module . $timeString. $this->tokenString."&page=1&limit=".$limit;
        Log::info('NetSuite Api: Triggered Url: '. $url);
        $status = "";
        $response = "";
        try {
            $response = $this->client->request('GET', $url);
        } catch (BadResponseException $e) {
            //this Will Catch All error response code and body
            $errorCode = $e->getResponse()->getStatusCode();
            $errorMsg = [];
            $this->errorMessageText = 'Internal Server Error.';

            Log::info("NetSuite Api: Status Code: ".$errorCode." [".$this->prepareErrorMessage()." ]");
            $this->sendErrorMail($module, $url, $errorCode, $this->prepareErrorMessage());
        }
        if ($response) {
            $status = $response->getStatusCode();
            if ($status == 200) {
                Log::info("NetSuite Api: Status Code: ".$status." [".$module." api record found ]"." Data: ".$response->getBody());
                return [
                    'code' => 200,
                    'data' => $response->getBody()
                ];
            }
        }
        return $status;
    }
    private function getProductResponse($module, $timeString = '', $limit = 1){
        $url = $this->urlString . '?module=' . $module . $timeString. $this->tokenString."&limit=1&page=1";
        Log::info('NetSuite Api: Url: '. $url);
        $status = "";
        $response = "";
        $totalResponseData = "";
        try {
            $response = $this->client->request('GET', $url);
            if($response){
                $data = json_decode($response->getBody());
                for($i=1; $i<=ceil($data->total/$limit); $i++) {
                    $url = $this->urlString . '?module=' . $module . $timeString . $this->tokenString . "&limit=".$limit."&page=".$i;
                    $httpResponse = $this->client->request('GET', $url);
                    $responseData = json_decode($httpResponse->getBody());
                    if(isset($responseData->rows)){
                        foreach ($responseData->rows as $row):
                        array_push($this->productData,$row);
                            endforeach;
                    }
                }
                $totalResponseData = json_encode(["total"=>count($this->productData),"records"=>count($this->productData),'rows'=>$this->productData]);
            }
        } catch (BadResponseException $e) {
            //this Will Catch All error response code and body
            $errorCode = $e->getResponse()->getStatusCode();
            $errorMsg = $e->getResponse()->getBody();
            Log::info("NetSuite Api: Status Code: ".$errorCode." [".$errorMsg." ]");
            $this->sendErrorMail($module, $url, $errorCode, $errorMsg);
        }
        if ($response) {
            $status = $response->getStatusCode();
            if ($status == 200) {
                Log::info("NetSuite Api: Status Code: ".$status." [".$module." api record found ]"." Data: ".$totalResponseData);
                return [
                    'code' => 200,
                    'data' => $totalResponseData
                ];
            }
        }
        return $status;
    }

    private function sendErrorMail($module, $url, $errorCode, $errorMsg)
    {
        foreach ($this->to[0] as $to)
        {
            $subject = 'Netsuite API Error';
            Log::info("NetSuite Api:".$subject." : ".$errorMsg);
            FEGSystemHelper::sendEmail($to, $subject, $errorMsg);
            Log::info("NetSuite Api:".$subject." : Notification sent to :".$to);
        }

    }
    private function getApiDateTimeParems(){
        return implode(' ',$this->parems);
    }
    private function getResonseCode(){
        return $this->apiResponse->getResponse()->getStatusCode();
    }
    private function getResonseBody(){
        return $this->apiResponse->getResponse()->getStatusCode();
    }
    private function getErrorMessageText(){
        return $this->errorMessageText;
    }
    private function prepareErrorMessage(){
        $errorCode = $this->getResonseCode();
        $orderId = $this->orderId;

        if($errorCode == 401){
            $this->errorMessage = implode('<br>',[
                'Order Id: '.$orderId,
                'Error code  401 : '.$this->getErrorMessageText(),
                'Error URL : '.$this->orderApiUrl,
                'Module Effected : Order',
                'Error occurred Date time : '.$this->getApiDateTimeParems(),
            ]);
        }elseif ($errorCode == 500){
            $this->errorMessage = implode('<br>',[
                'Order Id: '.$orderId,
                'Error code  500 : format issue in code',
                'Error URL : '.$this->orderApiUrl,
                'Module Effected : Order',
                'Error occurred Date time : '.$this->getApiDateTimeParems(),
            ]);
        }else{
            $this->errorMessage = implode('<br>',[
                'Order Id: '.$orderId,
                'Error code  500 : format issue in code',
                'Error URL : '.$this->orderApiUrl,
                'Module Effected : Order',
                'Error occurred Date time : '.$this->getApiDateTimeParems(),
            ]);
        }

       return $this->errorMessage;
    }
    private function getOrderedContents(){
        $orderContents = json_decode($this->data['order']['data']);
        if ($orderContents->total > 0) {
            $rows = $orderContents->rows;
            foreach ($rows as $row) {
                $items = $row->items;
                foreach ($items as $item) {
                   $this->orderContents[] = ['order_id'=>$row->id,'item'=>$item];
                }
            }
        }
    }

}
