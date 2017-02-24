<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use File;
use Carbon\Carbon;
use App\Library\MyLog;
use PHPMailer;
use Mail;
use App\Models\Feg\System\Options;


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
    
    public static function joinArray($array, $groupOn = '', $concatOn = array(), $sumOn = array(), $ignore = array(), $options = array()) {
        $options = array_merge(array(
        ), $options);
        extract($options);
        
        $data = array();
        foreach ($array as $cell) {
            
            if (!is_array($groupOn)) {
                $groupOn = array($groupOn);
            }
            $groupValues = array();
            foreach($groupOn as $groupOnItem) {
                $groupValues[] = $cell[$groupOnItem];
            }
            $groupValue = implode('-', $groupValues);
            
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
        if (isset($options['fromName'])) {
            $headers .= 'From: ' . $options['fromName'] . ' <'. $from . '>'. "\r\n";
        }
        else {
            $headers .= 'From: ' . $from . "\r\n";        
        }
        
        if (!empty($options)) {
            if (!empty($options['cc'])) {
                if (isset($options['ccName'])) {
                    $headers .= 'Cc: ' . $options['ccName'] . ' <'. $options['cc'] . '>'. "\r\n";
                }
                else {
                    $headers .= 'Cc: ' . $options['cc'] . "\r\n";  
                }                
            }
            if (!empty($options['bcc'])) {
                if (isset($options['bccName'])) {
                    $headers .= 'Bcc: ' . $options['bccName'] . ' <'. $options['bcc'] . '>'. "\r\n";
                }
                else {
                    $headers .= 'Bcc: ' . $options['bcc'] . "\r\n";  
                }                 
            }
            if (!empty($options['replyTo'])) {
                if (isset($options['replyToName'])) {
                    $headers .= 'Reply-To: ' . $options['replyToName'] . ' <'. $options['replyTo'] . '>'. "\r\n";
                }
                else {
                    $headers .= 'Reply-To: ' . $options['replyTo'] . "\r\n";  
                }                 
            }
            if (!empty($options['sender'])) {
                if (isset($options['senderName'])) {
                    $headers .= 'Sender: ' . $options['senderName'] . ' <'. $options['sender'] . '>'. "\r\n";
                }
                else {
                    $headers .= 'Sender: ' . $options['sender'] . "\r\n";  
                }                 
            }
        }
        
        mail($to, $subject, $message, $headers);
    }
    
    public static function phpMailer($to, $subject, $message, $from = "support@fegllc.com", $options = array()) {
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        //$mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        //$mail->Username = '';                 // SMTP username
        //$mail->Password = '';                           // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 587;                                    // TCP port to connect to

        $mail->From = 'from@example.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('name@domain.com', 'User');     // Add a recipient
        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');

        $mail->addAttachment('');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
    
    public static function configLaravelMail($mail, $options) {
        extract($options);

        $mail->subject($subject);
        $mail->setBody($message, 'text/html');
        
        $toArray = explode(',', $to);            
        if (count($toArray)== 1 && isset($toName)) {
            $mail->to($toArray[0], $toName);
        }
        else {
            $mail->to($toArray);
        }

        if (isset($cc) && !empty(trim($cc))) {
            $ccArray = explode(',', $cc);            
            if (count($ccArray)== 1 && isset($ccName)) {
                $mail->cc($ccArray[0], $ccName);
            }
            else {
                $mail->cc($ccArray);
            }                            
        }
        if (isset($bcc) && !empty(trim($bcc))) {
            $bccArray = explode(',', $bcc);            
            if (count($bccArray)== 1 && isset($bccName)) {
                $mail->bcc($bccArray[0], $bccName);
            }
            else {
                $mail->bcc($bccArray);
            }                            
        }
        if (isset($sender) && !empty(trim($sender))) {
            $senderArray = explode(',', $sender);            
            if (count($senderArray)== 1 && isset($senderName)) {
                $mail->sender($senderArray[0], $senderName);
            }
            else {
                $mail->sender($senderArray);
            }                            
        }            
        if (isset($from) && !empty(trim($from))) {
            $fromArray = explode(',', $from);            
            if (count($fromArray)== 1 && isset($fromName)) {
                $mail->from($fromArray[0], $fromName);
            }
            else {
                $mail->from($fromArray);
            }                            
        }            
        if (isset($replyTo) && !empty(trim($replyTo))) {
            $replyToArray = explode(',', $replyTo);            
            if (count($replyToArray)== 1 && isset($replyToName)) {
                $mail->replyTo($replyToArray[0], $replyToName);
            }
            else {
                $mail->replyTo($replyTo);
            }                            
        }            

        if (isset($attach)) {
            if (is_array($attach)) {
                foreach($attach as $attachment) {
                    $mail->attach($attachment);
                }
            }
            else {
                $mail->attach($attach);
            }
        }        
    }
    public static function laravelMail($to, $subject, $message, $from = "support@fegllc.com", $options = array()) {
        $view = empty($options['view']) ? '': $options['view'];
//        $options['to'] = $to;
//        $options['subject'] = $subject;
//        $options['message'] = $message;
//        $options['from'] = $from;
        
        if (!empty($view)) {
            Mail::send($view, $options, function ($mail) use ($options) {
                self::configLaravelMail($mail, $options);
            });            
        }
        else {
            Mail::send([], [], function ($mail) use ($options) {
                self::configLaravelMail($mail, $options);
            });             
//            Mail::raw($message, function ($mail) use ($options) {
//                self::configLaravelMail($mail, $options);
//            });            
        }
    }
    
    /**
     * Function which sends email using php mail (mail function) or laravel Mail class when using attachments
     * [Under Development]
     * @param type $to
     * @param type $subject
     * @param type $message
     * @param string $from
     * @param type $options
     */
    public static function sendEmail($to, $subject, $message, $from = "support@fegllc.com", $options = array()) { 
        //support@fegllc.com
        if (empty($from)) {
            //$from = "support@fegllc.com";
            //$from = "support@element5digital.com";
            $from = "support@fegllc.com";
        }
        
        $preventEmailSendingSetting = env('PREVENT_FEG_SYSTEM_EMAIL', false);
        if (!$preventEmailSendingSetting)  {
            if (!isset($options['attach']) || !empty($options['usePHPMail'])) {
                self::phpMail($to, $subject, $message, $from, $options);                
            }
            else {
                self::laravelMail($to, $subject, $message, $from, $options);
            }            
        }
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
    public static function split_trim_join($txt, $delim = ',', $trimChar = null) {
        $arr = self::split_trim($txt, $delim, $trimChar);
        $joined = implode($delim, $arr);
        return $joined;        
    }   

    public static function syncTable($params = array()) {
        extract(array_merge(array(
            'sourceDB' => '',  //mysql, livemysql, livemysql_sacoa, livemysql_embed
            'targetDB' => '', //embed_sync, sacoa_sync
            'table' => '', 
            'targetTable' => '', 
            'chunk' => 1000, 
            'cleanFirst' => 0,
        ), $params)); // $sourceDB, $targetDB, $table, $chunk, $cleanFirst,
        self::$L = $_logger;
        
        if (empty($targetTable)) {
            $targetTable = $table;
            $params['targetTable'] = $targetTable;
        }
        
        $syncLogTemplate = "$sourceDB.$table => $targetDB.$targetTable";
        self::$L->log("Start DATABASE SYNC: $syncLogTemplate");
        
        if (empty($table)) {
            $log = "No table to sync. Ending...";
            self::$L->log($log);
            self::$L->log("End DATABASE SYNC: $syncLogTemplate ");
            return $log;
        }
        if ($targetTable == $table) {
            if (empty($sourceDB) && empty($targetDB) || ($sourceDB == $targetDB)) {
                $log = "No target table for sync. Ending...";
                self::$L->log($log);
                self::$L->log("End DATABASE SYNC: $syncLogTemplate ");
                return $log;
            }
            
        }

        
        if ($cleanFirst == 1) {
            self::$L->log("Clear all data from target table first...");
            self::truncateTable(array('db' => $targetDB, 'table' => $targetTable));
        }
        
        $count = 0;
        
        $timeStart = microtime(true);
        $timeEnd = microtime(true);        
        while(self::checkIfSyncRequired($params)) {
            $timeEnd = microtime(true);
            $timeDiff = round($timeEnd - $timeStart);
            $timeDiffHuman = self::secondsToHumanTime($timeDiff);
            self::$L->log("Has " . ($count > 0 ? "more":"") . " data to sync [ $timeDiffHuman ]");
            self::_syncTable($params);
            $count++;
            sleep(3);
        }
        self::$L->log("No  " . ($count > 0 ? "more":"") . " data to sync");
        
        $timeEnd = microtime(true);
        $timeDiff = round($timeEnd - $timeStart);
        $timeDiffHuman = self::secondsToHumanTime($timeDiff);
        
        self::$L->log("End DATABASE SYNC: $syncLogTemplate ");
        $timeTaken = "Time taken: $timeDiffHuman ";
        self::$L->log($timeTaken);
        
        if (!empty($sourceDB)) {
            DB::connection($sourceDB)->disconnect();
        }
        if (!empty($targetDB)) {
            DB::connection($targetDB)->disconnect();
        }        
        
        return $timeTaken;
    }    
    public static function _syncTable($params = array()) {
        extract(array_merge(array(
            'sourceDB' => '', 
            'targetDB' => '', 
            'table' => '', 
            'targetTable' => '', 
            'chunk' => 1000, 
            'cleanFirst' => 0,
        ), $params)); // $sourceDB, $targetDB, $table, $chunk, $cleanFirst,
        
        if (empty($chunk)) {
            $chunk = 1000;
        }
        
        if (empty($sourceDB)) {
            $source = DB::connection();
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        }
        else {
            $source = DB::connection($sourceDB);
            DB::connection($sourceDB)->setFetchMode(PDO::FETCH_ASSOC); 
        }
        if (empty($targetDB)) {
            $target = DB::connection();
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        }
        else {
            $target = DB::connection($targetDB);
            DB::connection($targetDB)->setFetchMode(PDO::FETCH_ASSOC); 
        }
        
        $lastID = self::get_last_id($table, $targetDB);

        $q = "SELECT * from $table WHERE id > $lastID LIMIT " . $chunk;
        $data = $source->select($q);
        $target->table($targetTable)->insert($data);
        
        if (empty($sourceDB)) {
            DB::connection()->setFetchMode(PDO::FETCH_CLASS); 
        }
        else {
            DB::connection($sourceDB)->setFetchMode(PDO::FETCH_CLASS); 
        }
        if (empty($targetDB)) {
            DB::connection()->setFetchMode(PDO::FETCH_CLASS); 
        }
        else {
            DB::connection($targetDB)->setFetchMode(PDO::FETCH_CLASS); 
        }
        
    }    
    public static function truncateTable ($params = array()) {
        extract(array_merge(array(
            'db' => '', 
            'table' => ''
        ), $params)); 
        if (is_null($db)) {
            $id = DB::table($table)->truncate();            
        }       
        else {
            $id = DB::connection($db)->table($table)->truncate();
        }
    }
    
    public static function get_last_id($table, $dbname = null) {                
        if (is_null($dbname)) {
            $id = DB::table($table)->orderBy('id', 'desc')->take(1)->value('id');            
        }       
        else {
            $id = DB::connection($dbname)->table($table)->orderBy('id', 'desc')->take(1)->value('id');
        }
        
        if (is_null($id)) {
            $id = 0;
        }
        return $id;
    }    
    
    public static function checkIfSyncRequired($params = array()) {
        extract(array_merge(array(
            'sourceDB' => '', 
            'targetDB' => '', 
            'table' => '', 
            'targetTable' => '', 
            'chunk' => 1000, 
            'cleanFirst' => 0,
        ), $params)); // $sourceDB, $targetDB, $table, $targetTable, $chunk, $cleanFirst,

        $sourceLastID = self::get_last_id($table, $sourceDB);
        $targetLastID = self::get_last_id($targetTable, $targetDB);
                            
        $hasMore = $sourceLastID > $targetLastID;
        
        return $hasMore;        
    }    
    
    /**
     * Function to get system email recipient defined as a configuration in System Email Manager /system/systememailreportmanager
     * 
     * @param string $configName    Name of the Configuration from /system/systememailreportmanager
     * @param number/string $location   [optional] Pass the location id if you want to filter user assigned to that location. Can pass null to skip it
     * @param boolean $isTest   [optional] Pass true to get recipients only from the 'Email recipients while testing' section
     * @return array    {'to' => '<string comma_separated_emails>', 'cc' => '<string comma_separated_emails>', 'bcc' => '<string comma_separated_emails>', }
     */
    public static function getSystemEmailRecipients($configName, $location = null, $isTest = false) {
        $emails = array('configName' => $configName, 'to' => '', 'cc' => '', 'bcc' => '');
        $q = "SELECT * from system_email_report_manager WHERE report_name='$configName' AND is_active=1 order by id desc";
        $data = DB::select($q);
        $groups = array('to' => '', 'cc' => '', 'bcc' => '');
        $locationUsers = array('to' => '', 'cc' => '', 'bcc' => '');
        $users = array('to' => '', 'cc' => '', 'bcc' => '');
        $inclues = array('to' => '', 'cc' => '', 'bcc' => '');
        $excludes = array('to' => '', 'cc' => '', 'bcc' => '');
        if (!empty($data)) {
            $data = $data[0];
            if ($isTest) {
                $emails['to'] = $data->test_to_emails;
                $emails['cc'] = $data->test_cc_emails;
                $emails['bcc'] = $data->test_bcc_emails;
            }
            else {
                
                $location = empty($location) ? null : $location;
                
                $lut = $data->to_email_location_contacts;
                $lucc = $data->cc_email_location_contacts;
                $lubcc = $data->bcc_email_location_contacts;
                $locationUsers['to']  = empty($lut) ? array() : self::getLocationManagersEmails($lut,  $location);
                $locationUsers['cc']  = empty($lucc) ? array() : self::getLocationManagersEmails($lucc,  $location);
                $locationUsers['bcc'] = empty($lubcc) ? array() : self::getLocationManagersEmails($lubcc, $location);
                
                
                $gt = $data->to_email_groups;
                $gcc = $data->cc_email_groups;
                $gbcc = $data->bcc_email_groups;
                $groups['to'] = empty($gt) ? array() : self::getGroupsUserEmails($gt,   $location);
                $groups['cc'] = empty($gcc) ? array() : self::getGroupsUserEmails($gcc,   $location);
                $groups['bcc'] = empty($gbcc) ? array() : self::getGroupsUserEmails($gbcc, $location);
                
                $ut = $data->to_email_individuals;
                $ucc = $data->cc_email_individuals;
                $ubcc = $data->bcc_email_individuals;                
                $users['to'] = empty($ut) ? array() : self::getUserEmails($ut,      $location);
                $users['cc'] = empty($ucc) ? array() : self::getUserEmails($ucc,    $location);
                $users['bcc'] = empty($ubcc) ? array() : self::getUserEmails($ubcc, $location);
                
                $inclues['to'] = FEGSystemHelper::split_trim($data->to_include_emails);
                $inclues['cc'] = FEGSystemHelper::split_trim($data->cc_include_emails);
                $inclues['bcc'] = FEGSystemHelper::split_trim($data->bcc_include_emails);
                
                $excludes['to'] = array_merge(FEGSystemHelper::split_trim(
                        $data->to_exclude_emails), array(null, ''));
                $excludes['cc'] = array_merge(FEGSystemHelper::split_trim(
                        $data->cc_exclude_emails), array(null, ''));
                $excludes['bcc'] = array_merge(FEGSystemHelper::split_trim(
                        $data->bcc_exclude_emails), array(null, ''));
                
                $to = array_diff(array_unique(
                        array_merge($groups['to'], 
                            $locationUsers['to'], $users['to'], $inclues['to'])),
                        $excludes['to']);
                $cc = array_diff(
                        array_unique(array_merge($groups['cc'], 
                            $locationUsers['cc'], $users['cc'], $inclues['cc'])),
                        $excludes['cc']);
                $bcc = array_diff(
                        array_unique(array_merge($groups['bcc'], 
                            $locationUsers['bcc'], $users['bcc'], $inclues['bcc'])),
                        $excludes['bcc']);
                
                $emails['to'] = implode(',', $to);
                $emails['cc'] = implode(',', $cc);
                $emails['bcc'] = implode(',', $bcc);
            }
        }
        return $emails;
    }

    public static function getLocationManagersEmails($fields = '', $location = null) {
//        $q = "SELECT id,contact_id, general_contact_id, field_manager_id,
//            tech_manager_id, merch_contact_id, merchandise_contact_id,
//            technical_contact_id, regional_contact_id, senior_vp_id, district_manager_id
//        FROM location WHERE active=1";
        $emails = array();
        if (!empty(trim($fields))) {
            $ids = self::getLocationManagersIds($fields, $location);
            if (!empty($ids)) {
                $emails = self::getUserEmails(implode(',', $ids));
            }            
        }        
        return $emails;
    }
    public static function getLocationManagersIds($fields = null, $location = null) {
//        $q = "SELECT id,contact_id, general_contact_id, field_manager_id,
//            tech_manager_id, merch_contact_id, merchandise_contact_id,
//            technical_contact_id, regional_contact_id, senior_vp_id, district_manager_id
//        FROM location WHERE active=1";
        if (empty(trim($fields))){
            $fields = 'contact_id, general_contact_id, field_manager_id,
//            tech_manager_id, merch_contact_id, merchandise_contact_id,
//            technical_contact_id, regional_contact_id, senior_vp_id, district_manager_id';
        }
        $q = "SELECT $fields
        FROM location WHERE active=1";
        if ($location) {
            $q .= " AND id IN ($location)";
        }
        $fieldsArr = explode(',', $fields);
        $data = DB::select($q);
        $ids = array();
        foreach($data as $row) {            
            foreach($fieldsArr as $fname) {
                $val = $row->$fname;
                if (!empty($val)) {
                    $ids[] = $val;
                }                
            }        
        }        
        return array_unique($ids);
    }    
    /**
     * 
     * @param type $groups
     * @param type $location
     * @param type $skipIfNoGroup
     * @return type
     */
    public static function getGroupsUserEmails($groups = null, $location = null, $skipIfNoGroup = false) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }        
        $groups = self::split_trim_join($groups);
        if ($skipIfNoGroup && empty($groups)) {
            return [];
        }        
        $q = "SELECT U.id, U.group_id, UL.location_id, U.email FROM users U 
                    LEFT JOIN user_locations UL ON UL.user_id = U.id
                LEFT JOIN tb_groups G ON G.group_id = U.group_id
                LEFT JOIN location L ON L.id = UL.location_id
                WHERE U.active=1 AND L.active=1 ";
        if (!empty($groups)) {
            $q .= " AND G.group_id IN ($groups)";
        }
        if (!empty($location)) {
            $q .= " AND UL.location_id IN ($location)";
        }
        $data = DB::select($q);
        $emails = array();
        foreach($data as $row) {
            $email = $row->email;
            if (!empty($email)) {
                $emails[] =  trim($email);
            }            
        }
        return $emails;
    }
    /**
     * 
     * @param type $groups
     * @param type $location
     * @param type $skipIfNoGroup
     * @return type
     */
    public static function getGroupsUserIds($groups = null, $location = null, $skipIfNoGroup = false) {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $groups = self::split_trim_join($groups);
        if ($skipIfNoGroup && empty($groups)) {
            return [];
        }
        $q = "SELECT U.id, U.group_id, UL.location_id, U.email 
                FROM users U 
                LEFT JOIN tb_groups G ON G.group_id = U.group_id
                LEFT JOIN user_locations UL ON UL.user_id = U.id
                LEFT JOIN location L ON L.id = UL.location_id
                WHERE U.active=1 AND L.active=1 ";
        if (!empty($groups)) {
            $q .= " AND G.group_id IN ($groups)";
        }
        if (!empty($location)) {
            $q .= " AND UL.location_id IN ($location)";
        }
        $data = DB::select($q);
        $uids = array();
        foreach($data as $row) {
            $uid = $row->id;
            if (!empty($uid)) {
                $uids[] = $uid;
            }            
        }
        return array_unique($uids);
    }
    
    /**
     *      * Get user ids which are assigned to a location from a list of users
     * @param type $location
     * @param type $users
     * @param type $skipIfNoUsers
     * @return type
     */
    public static function getLocationUserIds($location = null, $users = null, $skipIfNoUsers = false) {
        if (is_array($users)) {
            $users = implode(',', $users);
        }
        $users = self::split_trim_join($users);
        if ($skipIfNoUsers && empty($users)) {
            return [];
        }        
        $q = "SELECT DISTINCT users.id FROM users 
            LEFT JOIN user_locations ON user_locations.user_id = users.id
            WHERE users.active=1 ";
        if (!empty($users)) {
            $q .= " AND users.id IN ($users)";
        }
        if (!empty($location)) {
            $q .= " AND user_locations.location_id IN ($location)";            
        }
        $data = DB::select($q);
        $ids = array();
        foreach($data as $row) {
            $id = $row->id;
            $ids[] = trim($id);
        }
        return $ids;
    }    
    /**
     * 
     * @param type $users
     * @param type $location
     * @param type $skipIfNoUsers
     * @return type
     */
    public static function getUserEmails($users = null, $location = null, $skipIfNoUsers = false) {
        if (is_array($users)) {
            $users = implode(',', $users);
        }
        $users = self::split_trim_join($users);
        if ($skipIfNoUsers && empty($users)) {
            return [];
        }
        $q = "SELECT DISTINCT email FROM users 
            LEFT JOIN user_locations ON user_locations.user_id = users.id
            WHERE users.active=1 ";
        if (!empty($users)) {
            $q .= " AND users.id IN ($users)";
        }
        if (!empty($location)) {
            $q .= " AND user_locations.location_id IN ($location)";            
        }
        $data = DB::select($q);
        $emails = array();
        foreach($data as $row) {
            $email = $row->email;
            $emails[] = trim($email);
        }
        return $emails;
    }    
    
    /**
     * Wrapper function to send email
     * @param array $options    An associative array containing these keys: 
     *                              from - string 
     *                              to - string comma separated emails
     *                              cc - string comma separated emails
     *                              bcc - string comma separated emails
     *                              subject - string
     *                              message - strnig body of the mail, 
     *                              attach - an index array of paths of the files to be attached
     *                              isTest - boolean - [true=test mode on]whether email will be send to recipients defined in 'Email recipients while testing' in System Email Manager (/system/systememailreportmanager)
     *                              configName - string (same as the configuration name defined in System Email Manager (/system/systememailreportmanager)
     *                                          This name is used in this function to get test email recipient if required (if isTest key is set to true). 
     *                                          This is also used to name the log file which stores the email content as HTML file in test mode
     *                              configNamePrefix - string - prefix added to configName when the log file name is created
     *                              configNameSuffix - string - suffix added to configName when the log file name is created
     *                              
     *                              
     *                              
     */
    public static function sendSystemEmail($options) {  
        
        $lp = 'FEGCronTasks/SystemEmails';
        $lpd = 'FEGCronTasks/SystemEmailsDump';
        $options = array_merge(array(
            'from' => "support@fegllc.com",
            'subject' => "",
            'to' => "",
            'cc' => "",
            'bcc' => "",
            'configName' => "Test",
            'configNamePrefix' => "",
            'configNameSuffix' => "",
        ), $options);
        
        extract($options);
        
        $configNameSanitized = preg_replace('/[\W]/', '-', strtolower($configName));
        $lf = "email-"
                . (empty($configNamePrefix)? "" : "{$configNamePrefix}-")
                . $configNameSanitized
                . (empty($configNameSuffix)? "" : "-{$configNameSuffix}")
                . ".log";
        
        if ($isTest) {
            
            $message =  "
*************** EMAIL START --- DEBUG INFO *******************<br>
[FROM: $from]<br/>
[SUBJECT: $subject]<br/>
[TO: $to]<br/>
[CC: $cc]<br/>
[BCC: $bcc]<br/>                   
***************** DEBUG INFO END *****************************<br><br>
$message    
<br><br>******************************************* EMAIL END ********************************<br>";
            
            $options['message'] = $message;
            $options['subject'] = $subject = "[TEST] ". $subject;
            $emailRecipients = self::getSystemEmailRecipients($configName, null, true);
            $options['to'] = $to = $emailRecipients['to'];
            $options['cc'] = $cc = $emailRecipients['cc'];
            $options['bcc'] = $bcc = $emailRecipients['bcc'];
            if (empty($to)) {
                $to = "e5devmail@gmail.com";
            }
            
//            FEGSystemHelper::logit("to: " .$to, "email-{$configNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("cc: " .$cc, "email-{$configNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("bcc: " .$bcc, "email-{$configNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("subject: " .$subject, "email-{$configNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
              
            //$messageLog = str_ireplace(array("<br />","<br>","<br/>"), "\r\n", $message);           
            //$messageLog = nl2br($message);           
            $messageLog = $message;           
            self::logit($messageLog, "{$lf}.html", $lpd, true);
        }
             
        self::logit("Sending Email", $lf, $lp);
        self::sendEmail($to, $subject, $message, $from, $options);
        self::logit("Email sent", $lf, $lp);
    }

    public static function getOption($optionName, $default = '', $all = false, $skipInactive = false, $details = false) {
        return Options::getOption($optionName, $default, $all, $skipInactive, $details);        
    }
    public static function updateOption($optionName, $value = '', $options = array()) {
        return Options::updateOption($optionName, $value, $options);
    }
    public static function addOption($optionName, $value = '', $options = array()) {
        return Options::addOption($optionName, $value, $options);        
    }    
    
    public static function getUserAvatarUrl($id = null, $file = null) {
		$fileUrl = url()."/silouette.png";
        if (!empty($id)) {
            $file = \App\Models\Core\Users::where('id', $id)->pluck('avatar');
        }
        $filePath = "./uploads/users/$file";
        if (!empty($file) && file_exists($filePath)) {
            $fileUrl = \URL::to("uploads/users/$file");
        }        
        return $fileUrl;
    }
    
    public static function getUserProfileDetails($user) {
        $userID = $user['id'];
        $firstName = empty($user['first_name']) ? '' : $user['first_name'];
        $lastName = empty($user['last_name']) ? '' : $user['last_name'];                                    
        $fullName = $firstName . ' ' . $lastName;
        $avatar = self::getUserAvatarUrl(null, $user['avatar']);
        $userTooltip = 'Username: ' . $user['username'] . '<br/> Email: '. $user['email'];
        
        return [
            'id' => $userID,
            'fullName' => $fullName,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'avatar' => $avatar,
            'tooltip' => $userTooltip,
        ];
    }
        
    public static function getTicketCommentUserProfile($comment, $ticket = []) {
        $userID = $comment->UserID;
        $externalName = empty($comment->USERNAME) ? '' : $comment->USERNAME;
        $firstName = empty($comment->first_name) ? '' : $comment->first_name;
        $lastName = empty($comment->last_name) ? '' : $comment->last_name;                                    
        $fullName = $firstName . ' ' . $lastName;
        $avatar = self::getUserAvatarUrl(null, $comment->avatar);
        $isExternal = empty($userID);

        $userTooltip = 'Username: ' . $comment->username . '<br/> Email: '. $comment->email;
        if ($isExternal) {
            $fullName = $externalName;
            $userTooltip = "Non-FEG User";
        }    
        
        return [
            'id' => $userID,
            'isExternal' => $isExternal,
            'eExternalName' => $externalName,
            'fullName' => $fullName,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'avatar' => $avatar,
            'tooltip' => $userTooltip,
        ];               
    }
    
    public static function getTicketAttachmentDetails($data, $isInitialTicketData = false) {
        $fileNamesCSV = $isInitialTicketData ? $data->file_path : $data->Attachments;
        $datePosted = \DateHelpers::formatDate($isInitialTicketData ? $data->Created : $data->Posted);
        $attachments = [];
        if (!empty($fileNamesCSV)) {
            $files = explode(',', $fileNamesCSV);            
            foreach($files as $file) {
                if (!empty($file)) {
                    $url = url().$file;
                    $fileName =  self::getSanitizedFileNameForTicketAttachments($file);                
                    $attachments[] = [
                        'url' => $url,
                        'fileName' => $fileName,
                        'date' => $datePosted
                    ];                    
                }
                
            }
        }
        
        return $attachments;
    }    

     /**
     * This works on paths relative to public folder
     * 
     * @param type $basename
     * @param type $path
     * @return string The possibly modified filename (without the path)
     */
    public static function possiblyRenameFileToResolveDuplicate($basename, $path) {
        $fileParts = pathinfo($basename);
        $ext = empty($fileParts['extension']) ? '' : $fileParts['extension'];
        $filename = $fileParts['filename'];
        $newBasename = $filename . (empty($ext) ? "" : ('.' . $ext));
        $path = preg_replace('/([^\/]$)/', '$1/', $path);
        $copyCount = 0;
        $filepath = $path . $newBasename;
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        while (File::exists($filepath)) {
            $newBasename = $filename . ('--'.++$copyCount).(empty($ext) ? "" : ('.' . $ext));
            $filepath = $path . $newBasename;            
        }
        return $newBasename;
    }
    
    /**
     * 
     * @param type $path
     * @return string trimmed and slim filename
     */
    public static function getSanitizedFileNameForTicketAttachments($path, $maxLength = 25) {
        $fileParts = pathinfo($path);
        $ext = empty($fileParts['extension']) ? '' : $fileParts['extension'];
        $fileName = $fileParts['filename'];
       
        $fileName  = preg_replace('/--.*$/', '', $fileName);
        $fileName =  substr($fileName, 0, $maxLength);
        
        $newBasename = $fileName . (empty($ext) ? "" : ('.' . $ext));
        return $newBasename;
    }
}
