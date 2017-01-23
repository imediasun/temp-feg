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
       
    
}
