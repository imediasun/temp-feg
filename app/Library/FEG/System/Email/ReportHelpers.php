<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;

class ReportHelpers
{  
    public static function daily($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => new MyLog('daily-earnings-report.log', 'system-earnings-email-report', 'GenerateDailyEmailReports'),
        ), $params));
        $L = $_logger;
        $L->log("Start Generate Daily Email Reports $date");
        
        $L->log("End Generate Daily Email Reports $date");

    }
    
    
    public static function weekly($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'date_end' => date('Y-m-d', strtotime('-1 day')),
            'date_start' => date('Y-m-d', strtotime('-7 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => new MyLog('weekly-earnings-report.log', 'system-earnings-email-report', 'GenerateWeeklyEmailReport'),
        ), $params));
        $L = $_logger;
        $L->log("Start Weekly Email Report $date_start - $date_end");
        
        $L->log("End Weekly Email Report $date_start - $date_end");

    }
        
}
