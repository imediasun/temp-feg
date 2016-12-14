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
            new MyLog('daily-report.log', 'daily-transfer-reports', 'Reports');
        $params['_logger'] = $L;        
        ReportGenerator::daily($params);
    }
    public static function weekly($params = array()) {
        $L = isset($params['_logger']) ? $params['_logger'] : 
            new MyLog('weekly-report.log', 'weekly-transfer-reports', 'Reports');
        $params['_logger'] = $L;
        ReportGenerator::weekly($params);
    }
       
}
