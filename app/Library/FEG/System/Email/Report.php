<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\FEG\System\Email\ReportHelpers;

class Report
{  
    
    public static function daily($params = array()) {
        ReportHelpers::daily($params);
    }
    public static function weekly($params = array()) {
        ReportHelpers::weekly($params);
    }
       
}
