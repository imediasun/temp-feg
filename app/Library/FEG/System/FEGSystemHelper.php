<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;


class FEGSystemHelper
{    
    private static $L;
    
    public static function setLogger($_logger, $name = "elm5-general-log.log", $path = "FEGCronTasks/ELM5GeneralLog", $id = "LOG") {
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
    
    /**
     * 
     * @param type $obj
     * @param type $file
     * @param type $pathsuffix
     * @param type $skipDate
     */
    public static function logit($obj = "", $file = "elm5-system-log.log", $pathsuffix ="FEGCronTasks/ELM5SkimLog", $skipDate = false) {
        $path = self::set_log_path($file, $pathsuffix);
        $date = "[" . date("Y-m-d H:i:s"). "] ";
        if (is_array($obj) || is_object($obj)) {
            $log = json_encode($obj);
        }
        else {
            $log = $obj;
        }
        if (!$skipDate) {
            $log = $date.$log;
        }
        file_put_contents($path, $log."\r\n", FILE_APPEND);    
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
    
    
    public static function tableFromArray($array = array(), $options = array()) {
        
        extract(array_merge(array(
            'skipUnderscoreHeaders' => true,
            'cellArrayJoinDelimiter' => ',<br/>',

            'tableClass' => '',
            'tableStyles' => '',
            
            'TRStyles' => '',
            'headTRStyles' => '',
            'bodyTRStyles' => '',
            
            'cellWidths' => array(),
            
            'cellStyles' => '',
            
            'THStyles' => '',
            'TDStyles' => '',
            
            'TDClasses'=> array(),
            
        ), $options));
        
        $tablehtml = "";
        if (empty($array)) {
            return $tablehtml;
        }
        $htmlArr = array();
        $htmlArr[] = "<table class='$tableClass' style='$tableStyles'>";

        $count = 0;
        foreach($array as $id => $item){

            if ($count == 0) {
                $htmlArr[] = "<thead>";
                $htmlArr[] = "<tr class='' style='$TRStyles $headTRStyles'>";
                foreach ($item as $title => $col) {
                    if ($skipUnderscoreHeaders) {
                        if (strpos($title, '_') === 0) {
                            continue;
                        }
                    }
                    $th = "th class='' style='$cellStyles $THStyles' ";
                    if (!empty($cellWidths)) {
                        if (!empty($cellWidths[$title])) {
                            $th .= " width='".$cellWidths[$title]."' ";
                        }
                    }
                    
                    if ($title == 'checkbox') {
                        $htmlArr[] = "<{$th}><input type='checkbox' class='checkUncheckAll' /></th>";
                    }
                    elseif ($title == 'Count') {
                        $htmlArr[] = "<{$th}>#</th>";
                    }
                    else {
                        $htmlArr[] = "<{$th}>{$title}</th>";
                    }
                }
                $htmlArr[] = "</tr>";
                $htmlArr[] = "</thead>";
                $htmlArr[] = "<tbody>";
            }
            $count++;

            $classNamesArray = array();

            $rowHTML = array();
            foreach ($item as $title => $val) {
                if ($skipUnderscoreHeaders) {
                    if (strpos($title, '_') === 0) {
                        continue;
                    }
                }
                $sanitizedTitle = self::sanitizeTitleToId($title);
                $sanitizedId = self::sanitizeTitleToId($id);
                $cellClass = '';
                if (!empty($TDClasses)) {
                    if (!empty($TDClasses[$title])) {
                        $cellClass = $TDClasses[$title];
                    }
                }                
                $td = "td class='$sanitizedTitle $cellClass' style='$cellStyles $TDStyles' data-id='$sanitizedId' ";
                if ($title == 'checkbox') {
                    $rowHTML[] = "<{$td}>
                        <input type='checkbox' data-id='$sanitizedId' class='subselector' />
                        </td>";
                }
                else {
                    if (is_array($val)) {
                        $val = implode($cellArrayJoinDelimiter, $val);
                    }
                    $rowHTML[] = "<{$td} data-title='$title' data-value='$val'>$val</td>";
                }
            }
            $classNames = implode(' ', $classNamesArray);
            $htmlArr[] = "<tr class='$classNames' data-id='$sanitizedId' style='$bodyTRStyles'>";
            $htmlArr[] = implode("\r\n", $rowHTML);
            $htmlArr[] = "</tr>";

        }
        if ($count > 0) {
            $htmlArr[] = "</tbody>";    
        }
        $htmlArr[] = "</table>";    

        $tablehtml = implode("\r\n", $htmlArr);
        return $tablehtml;
    }
    
    public static function sanitizeTitleToId($title) {
        $sTitle = preg_replace('/\W/', '', strtolower($title));
        return $sTitle;
    }
    
    public static function joinArray($array, $groupOn = '', $concatOn = array(), $sumOn = array(), $ignore = array()) {
        $data = array();
        foreach ($array as $cell) {
            $groupValue = $cell[$groupOn];
            if (empty($data[$groupValue])) {
                $data[$groupValue] = array();
            }
            foreach($cell as $key => $value) {
                if (!in_array($key, $ignore)) {
                    if (!in_array($key, $sumOn)) {
                        if (empty($data[$groupValue][$key])) {
                            $data[$groupValue][$key] = array();
                        }
                        $data[$groupValue][$key][] = $value;
                    }
                    else {
                        if (empty($data[$groupValue][$key])) {
                            $data[$groupValue][$key] = 0;
                        }
                        $data[$groupValue][$key] += $value;
                    }
                }
            }
        }
        return $data;
    }

    
    public static function phpMail($to, $subject, $message, $from = "support@fegllc.com", $options = array()) {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";        
        if (!empty($options)) {
            if (!empty($options['cc'])) {
                $headers .= 'Cc: ' . $options['cc'] . "\r\n";  
            }
            if (!empty($options['bcc'])) {
                $headers .= 'Bcc: ' . $options['bcc'] . "\r\n";  
            }
        }
        
        mail($to, $subject, $message, $headers);
    }
    public static function sendEmail($to, $subject, $message, $from = "support@fegllc.com", $options = array()) { 
        //support@fegllc.com
        if (empty($from)) {
            //$from = "support@fegllc.com";
            //$from = "support@element5digital.com";
            $from = "support@fegllc.com";
        }        
        self::phpMail($to, $subject, $message, $from, $options);
    }
    
    public static function getHumanDate($date = "") {
        $hDate = "";
        if (!empty($date)) {
            $hDate = date("l, F d Y", strtotime($date));
        }
        return $hDate;
    }

    public static function split_trim($txt, $delim = ',', $trimChar = null) {
        $arr = array();
        if (empty($txt)) {
            $txt = "";
        }
        $data = explode($delim, $txt);
        foreach($data as $val) {
            $val = empty($trimChar) ? trim($val): trim($val, $trimChar);
            if (!empty($val)) {
                $arr[] = $val;
            }
        }
        return $arr;        
    }       
}
