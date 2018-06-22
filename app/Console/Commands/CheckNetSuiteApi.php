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

    protected $urlString = 'http://demo.fegllc.com/fegapi';
    protected $tokenString = '&token=f1a9bE1f7M208M3eIb0b048L0d0O921Vd8bEbaa6ow35l23HaxcAn2Ddaf245I';
    protected $data;
    protected $products;
    protected $orderIds;
    protected $productData;

    public function handle()
    {

        $modules = ['order', 'product', 'itemreceipt'];
        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';
        $currentDate = date($dateFormat);
        $fromTime = date($timeFormat, time() - (3600 * 2));
        $toTime = date($timeFormat, time() - (3600 * 1));
        $timeString = '&created_from=' . $currentDate . ' ' . $fromTime;
        $timeString .= 'updated_from=' . $currentDate . ' ' . $fromTime;
        $timeString .= 'created_to=' . $currentDate . ' ' . $toTime;
        $timeString .= 'updated_to=' . $currentDate . ' ' . $toTime;
        Log::info("NetSuite Api:-*-*--*-*--*-*--*-*-Api process started-*-*--*-*--*-*--*-*-");
        foreach ($modules as $module) {
            Log::info("NetSuite Api: ".$module." api triggered checking record from ".date('Y-m-d')." ".$fromTime." to ".date('Y-m-d')." ".$toTime);
            if($module == 'product'){
                $this->data[$module] = $this->getProductResponse($module,'',2000);
            }else {
                $this->data[$module] = $this->getResponse($module, $timeString);
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
                $items = $row->items;
                foreach ($items as $item) {
                    $product_id = $item->product_id;
                    $this->checkProduct($product_id);
                }
                $this->checkReceipts($row->id);
            }
        }
    }

    public function checkReceipts($id)
    {
        if(in_array($id,$this->orderIds))
        {
            return true;
        }
        else
        {
            $this->sendErrorMail('OrdersReceipts', null, 404, 'Receipt For Order id '.$id.' not Found');
        }
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

        $url = $this->urlString . '?module=' . $module . $timeString. $this->tokenString;
        Log::info('NetSuite Api: Url: '. $url);
        $status = "";
        $response = "";
        try {
            $response = $this->client->request('GET', $url);
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
            $subject = 'Error';
            $message = 'Error Code = '.$errorCode . ' Error url = '. $url . "<br>Error Message = ". $errorMsg;
            Log::info("NetSuite Api:".$subject." : ".$message);
            FEGSystemHelper::sendEmail($to, $subject, $message);
            Log::info("NetSuite Api:".$subject." : Notification sent to ");
        }

    }

}
