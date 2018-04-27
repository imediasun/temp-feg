<?php

namespace App\Console\Commands;

use App\Library\FEG\System\FEGSystemHelper;
use App\Models\NetSuiteApiLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Console\Command;

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

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    protected $urlString = 'http://demo.fegllc.com/fegapi';
    protected $tokenString = '&token=f1a9bE1f7M208M3eIb0b048L0d0O921Vd8bEbaa6ow35l23HaxcAn2Ddaf245I';

    public function handle()
    {
        //
        $dateFormat = 'Y-m-d';
        $timeFormat = 'H:i:s';
        $currentDate = date($dateFormat);
        $fromTime = date($timeFormat , time() - (3600*2));
        $toTime = date($timeFormat , time() - (3600*1));
        $orders = $this->getResponse('order',$currentDate , $currentDate , $fromTime, $toTime);
        if($orders['status'] == 200)
        {
            $data = $orders['data'];
        }
        $this->info($orders);
    }
    private function getResponse($module , $from_date ,$to_date , $from_time , $to_time ,$limit = 50)
    {
        $url = $this->urlString.'?module='.$module.'&limit='.$limit.'&created_from='.$from_date.' '.$from_time;
        $url .= '&updated_from='.$to_date.' '.$to_time.$this->tokenString;
        $response = false;
        $status = "";
        try {
            $response = $this->client->get($url);
        } catch (BadResponseException $e) {
            //this Will Catch All error response code and body
            $errorCode = $e->getResponse()->getStatusCode();
            $errorMsg = $e->getResponse()->getBody();
            $this->sendErrorMail($module , $errorCode , $errorMsg);
            return [
                'code' => $errorCode,
                'data' => $errorMsg
            ];
        }
        if($response)
        {
            $status = $response->getStatusCode();
            if($status == 200)
            {
                $this->addDataFoundLog($module);
                return [
                    'code' => 200,
                    'data' => $response
                ];
            }
        }
        return $status;
    }
    private function sendErrorMail($module , $errorCode , $errorMsg)
    {
        $to = 'dev1@shayansolutions.com';
        $subject = 'Error';
        $message = $errorCode.$errorMsg;
        FEGSystemHelper::sendEmail($to,$subject,$message);
    }
    private function addDataFoundLog($module)
    {
        $nsa = new NetSuiteApiLog();
        $nsa->module = $module;
        $nsa->response_code = 200;
        $nsa->response_message = 'OK';
        $nsa->save();
        return "success";
    }

}
