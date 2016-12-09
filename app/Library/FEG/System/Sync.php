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
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-earnings.log', 'daily-transfer', 'TransferEarnings');
        $params['_logger'] = $L;
        SyncHelpers::transferEarnings($params);
    }
    
    public static function generateDailySummary($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-earnings-summary-database.log', 'daily-summary', 'GenerateDailySummary');
        $params['_logger'] = $L;
        $L->log("Start Generate Daily Summary");
        SyncHelpers::generateDailySummary($params);
        $L->log("End Generate Daily Summary");
    }
 
    public static function retryTransferMissingEarnings($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-earnings-retry-missing.log', 'daily-transfer-retry', 'RetryMissingTransferEarnings');
        $params['_logger'] = $L;
        
        $L->log("Start Retry Missing Transfer Earnings Sync");
        SyncHelpers::retryTransferEarnings($params);
        $L->log("End Retry Missing Transfer Earnings Sync");
    }

    public static function cleanupInactiveDailySummary($params = array()) {

       $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-earnings-summary-database-cleanup.log', 'daily-summary', 'CleanupInactiveDailySummary');
        $params['_logger'] = $L;
        
        $L->log("Start cleanup Inactive Daily Summary");
        
        $L->log("End cleanup Inactive Daily Summary");

    }
    public static function deleteDailySummary($params = array()) {
        global $__logger;       
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('now -1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params)); 
       $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-earnings-summary-database-cleanup.log', 'daily-summary', 'CleanupInactiveDailySummary');
        $params['_logger'] = $L;
        
        $L->log("Start cleanup Inactive Daily Summary");
        self::clean_daily_report($date, $location);
        $L->log("End cleanup Inactive Daily Summary");

    }
    
    public static function clean_daily_report($date = null, $location = null) {
          
        $sql = "DELETE FROM report_locations WHERE record_status = 0";
        if (!empty($date)) {
            $sql .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $sql .= " AND location_id in ($location)";
        }
        $affected_rows = DB::delete($sql);
                
        $sql = "DELETE FROM report_game_plays WHERE record_status = 0";
        if (!empty($date)) {
            $sql .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $sql .= " AND location_id in ($location)";
        }
        $affected_rows = DB::delete($sql);      
    }
    
    public static function delete_daily_report($date = null, $location = null) {
        if (empty($date) && empy($location)) {
            return;
        }
        $sql = "DELETE FROM report_locations WHERE id IS NOT NULL ";
        if (!empty($date)) {
            $sql .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $sql .= " AND location_id in ($location)";
        }
        $affected_rows = DB::delete($sql); 
        
        $sql = "DELETE FROM report_game_plays WHERE id IS NOT NULL ";
        if (!empty($date)) {
            $sql .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $sql .= " AND location_id in ($location)";
        }
        $affected_rows = DB::delete($sql);        
    }     
}
