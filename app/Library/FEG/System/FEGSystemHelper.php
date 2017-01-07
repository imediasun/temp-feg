<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;


class FEGSystemHelper
{    
    private static $L;
    
    public static function setLogger($_logger, $name = "elm5-general-log.log", $path = "FEG-General", $id = "LOG") {
        global $__logger;
        if (empty($_logger)) {
            $_logger = new MyLog($name, $path, $id);                
        }
        return $_logger;
    }
    public static function secondsToHumanTime($seconds) {
        $time = array();
        if (!empty($seconds)) {
            //$seconds = intval($seconds);
            $dtF = new \DateTime('@0');
            $dtT = new \DateTime("@$seconds");
            $diff = $dtF->diff($dtT);
            $days = $diff->format('%a');
            $hours = $diff->format('%h');
            $mins = $diff->format('%i');
            $snds = $diff->format('%s');
            
            if (!empty($days)) {
                $time[] = "$days days";
            }
            if (!empty($hours)) {
                $time[] = "$hours hours";
            }
            if (!empty($mins)) {
                $time[] = "$mins minutes";
            }
            if (!empty($snds)) {
                $time[] = "$snds seconds";
            }            
        }
        
        $timeString = implode(" ", $time);
        return $timeString;
    }
        
    public static function logit($obj = "", $file = "elm5-system-log.log", $pathsuffix ="") {
        $path = self::set_log_path($file, $pathsuffix);
        $date = "[" . date("Y-m-d H:i:s"). "] ";
        if (is_array($obj) || is_object($obj)) {
            $log = json_encode($obj);
        }
        else {
            $log = $obj;
        }
        file_put_contents($path, $date.$log."\r\n", FILE_APPEND);    
    }	
    public static function set_log_path($file = "elm5-system-log.log", $pathsuffix = "") {
        $fileprefix = "log-" . date("Ymd") . "-";
        $path = realpath(storage_path() . '/logs/').(empty($pathsuffix) ? "" : '/'.$pathsuffix);        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filepath = $path . '/'. $fileprefix . $file;        
        return $filepath;
    }
    public static function set_session_log_path($file = "session.log" , $isReadonly = false) {
        $path = realpath(storage_path())."/logs/ELM5_Sessions";        
        if (!$isReadonly && !file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filepath = $path.'/'.$file;        
        return $filepath;
    }
    
    public static function session_get($var, $default = '', $deleteFile = false) {
        $path = self::set_session_log_path($var.'.log', true);
        $value = $default;
        if (file_exists($path)) {
            $value = file_get_contents($path);
            if ($deleteFile) {
                unlink($path);
            }
        }
        return $value;
    }
    public static function session_put($var, $value = '') {
        $path = self::set_session_log_path($var.'.log');
        if (is_array($value) || is_object($value)) {
            $log = json_encode($value);
        }
        else {
            $log = $value;
        }
        file_put_contents($path, $log);    
        return $log;
    }
    public static function session_pull($var, $default = '') {
        return self::session_get($var, $default, true);        
    }
    public static function session_forget($var) {
        $path = self::set_session_log_path($var, true);
        if (file_exists($path)) {
            return unlink($path);
        }    
    }
}
