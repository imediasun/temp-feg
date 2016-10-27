<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\FEG\System\SyncHelpers;

class Sync
{    
    public static function transferEarnings($params = array()) {
        SyncHelpers::transferEarnings($params);
    }
    
    public static function generateDailySummary($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => new MyLog('daily-earnings-summary-database.log', 'daily-summary', 'GenerateDailySummary'),
        ), $params));
        $L = $_logger;
        $L->log("Start Generate Daily Summary $date");
        
        $L->log("End Generate DailySummary $date");

    }
 
    public static function retryTransferMissingEarnings($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => new MyLog('daily-earnings-retry-missing.log', 'daily-transfer-retry', 'RetryMissingTransferEarnings'),
        ), $params));
        $L = $_logger;
        $L->log("Start Retry Missing Transfer Earnings Sync $date");
        
        $L->log("End Retry Missing Transfer Earnings Sync $date");

    }

    public static function cleanupInactiveDailySummary($params = array()) {
         extract(array_merge(array(
            '_task' => array(),
            '_logger' => new MyLog('daily-earnings-summary-database-cleanup.log', 'daily-summary', 'CleanupInactiveDailySummary'),
        ), $params));
        $L = $_logger;
        $L->log("Start cleanup Inactive Daily Summary");
        
        $L->log("End cleanup Inactive Daily Summary");

    }
}
