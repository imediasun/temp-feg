<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\FEG\System\Email\ReportGenerator;

class Report
{  
    
    public static function daily($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('daily-report.log', 'FEGCronTasks/daily-transfer-reports', 'Reports');
        $params['_logger'] = $L;        
        ReportGenerator::daily($params);
    }
    public static function weekly($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('weekly-report.log', 'FEGCronTasks/weekly-transfer-reports', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::weekly($params);
    }
    
    public static function missingDataReport($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('missing-data-report.log', 'FEGCronTasks/missing-data-reports', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::missingDataReport($params);
    }

    public static function pendingOrdersToReceive($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] :
            new MyLog('pending-orders-to-receive-report.log', 'FEGCronTasks/daily-reports/orders-to-receive', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::sendLocationWiseDailyPendingOrdersToReceiveEmail($params);
    }
    public static function pendingOrdersToReceiveWeekly($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] :
            new MyLog('pending-orders-to-receive-report.log', 'FEGCronTasks/daily-reports/orders-to-receive', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::sendLocationWiseDailyPendingOrdersToReceiveEmailWeekly($params);
    }

    /**
     * Send daily report with duplicate assets IDs handler
     * @param $params
     * @return boolean
     */
    public static function duplicateAssetIDs($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] :
            new MyLog('duplicate-assets-report.log', 'FEGCronTasks/duplicate-assets-reports', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::sendDuplicateAssetIDReport($params);
        return true;
    }

}
