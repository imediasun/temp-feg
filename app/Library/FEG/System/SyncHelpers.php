<?php

namespace App\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;

class SyncHelpers
{    
    public static function transferEarnings($params = array()) {      
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => new MyLog('daily-earnings.log', 'daily-transfer', 'TransferEarnings'),
        ), $params));
        $L = $_logger;
        $L->log("Start Daily Earnings Sync $date");
        
        $L->log("End Daily Earnings Sync $date");

    }        
    
}
