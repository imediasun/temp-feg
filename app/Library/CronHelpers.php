<?php
namespace App\Library;

use Cron;

class CronHelpers
{
    public static function getNextRunDate ($crontab, $format = "Y-m-d H:i:s", $count = 1) {
        $crontab = self::validateCrontab(trim($crontab));
        $nextDate = "";
        if (!empty($crontab)) {
            $cron = Cron\CronExpression::factory($crontab);
            $nextDate =  $cron->getNextRunDate()->format($format);
        }        
        return $nextDate;
    }
    
    public static function validateCrontab($crontab) {
        if (!empty($crontab)) {
            $splitView = preg_split('/\s+?/', $crontab);
            $count = count($splitView);
            if ($count < 6) {
                $crontab .= str_repeat(" *", 6 - $count);
            }
        }        
        return $crontab;
    }

}


