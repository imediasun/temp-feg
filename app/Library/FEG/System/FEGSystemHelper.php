<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use File;
use Carbon\Carbon;
use App\Library\MyLog;
use PHPMailer;
use Mail;
use PHPMailerOAuth;
use App\Models\Feg\System\Options;
use App\Models\Core\Users;
use Log;


class FEGSystemHelper
{
    private static $L;

    public static function setLogger($_logger, $name = "elm5-general-log.log", $path = "FEGCronTasks/ELM5GeneralLog", $id = "LOG")
    {
        global $__logger;
        if (empty($_logger)) {
            $_logger = new MyLog($name, $path, $id);
        }
        return $_logger;
    }

    public static function secondsToHumanTime($seconds)
    {
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
    public static function logit($obj = "", $file = "elm5-system-log.log", $pathsuffix = "FEGCronTasks/ELM5SkimLog", $skipDate = false)
    {
        $path = self::set_log_path($file, $pathsuffix);
        $date = "[" . date("Y-m-d H:i:s") . "] ";
        if (is_array($obj) || is_object($obj)) {
            $log = json_encode($obj);
        } else {
            $log = $obj;
        }
        if (!$skipDate) {
            $log = $date . $log;
        }
        file_put_contents($path, $log . "\r\n", FILE_APPEND);
    }

    public static function set_log_path($file = "elm5-system-log.log", $pathsuffix = "")
    {
        $fileprefix = "log-" . date("Ymd") . "-";
        $path = realpath(storage_path() . '/logs/') . (empty($pathsuffix) ? "" : '/' . $pathsuffix);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filepath = $path . '/' . $fileprefix . $file;
        return $filepath;
    }

    public static function getUniqueFile($file = "elm5-system-log.log", $pathsuffix = "", $rootPath = null)
    {
        $fileSuffix = "-" . date("Ymd-His");
        if (empty($rootPath)) {
            $rootPath = realpath(storage_path() . '/logs/');
        }
        $path = $rootPath . (empty($pathsuffix) ? "" : '/' . $pathsuffix);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $fileParts = pathinfo($file);
        $count = 0;
        do {
            $fileName = $fileParts['filename']
                . $fileSuffix
                . ($count > 0 ? "-$count" : '')
                . '.' . $fileParts['extension'];

            $filepath = $path . '/' . $fileName;
            $count++;
        } while (file_exists($filepath));

        return $fileName;
    }

    public static function getUniqueFilePath($file = "elm5-system-log.log", $pathsuffix = "", $rootPath = null)
    {
        $file = self::getUniqueFile($file, $pathsuffix, $rootPath);
        $fileSuffix = "-" . date("YmdHis");
        if (empty($rootPath)) {
            $rootPath = realpath(storage_path() . '/logs/');
        }
        $path = $rootPath . (empty($pathsuffix) ? "" : '/' . $pathsuffix);
        $filepath = $path . '/' . $file;
        return $filepath;
    }

    public static function set_session_log_path($file = "session.log", $isReadonly = false)
    {
        $path = realpath(storage_path()) . "/logs/ELM5_Sessions";
        if (!$isReadonly && !file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filepath = $path . '/' . $file;
        return $filepath;
    }

    public static function session_get($var, $default = '', $deleteFile = false)
    {
        $path = self::set_session_log_path($var . '.log', true);
        $value = $default;
        if (file_exists($path)) {
            $value = file_get_contents($path);
            if ($deleteFile) {
                unlink($path);
            }
        }
        return $value;
    }

    public static function session_put($var, $value = '')
    {
        $path = self::set_session_log_path($var . '.log');
        if (is_array($value) || is_object($value)) {
            $log = json_encode($value);
        } else {
            $log = $value;
        }
        file_put_contents($path, $log);
        return $log;
    }

    public static function session_pull($var, $default = '')
    {
        return self::session_get($var, $default, true);
    }

    public static function session_forget($var)
    {
        $path = self::set_session_log_path($var, true);
        if (file_exists($path)) {
            return unlink($path);
        }
    }


    public static function tableFromArray($array = array(), $options = array())
    {

        extract(array_merge(array(
            'humanifyTitle' => false,
            'skipUnderscoreHeaders' => true,
            'skip' => [],
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

            'TDClasses' => array(),

        ), $options));

        $tablehtml = "";
        if (empty($array)) {
            return $tablehtml;
        }
        $htmlArr = array();
        $htmlArr[] = "<table class='$tableClass' style='$tableStyles'>";

        $count = 0;
        foreach ($array as $id => $item) {

            if ($count == 0) {
                $htmlArr[] = "<thead>";
                $htmlArr[] = "<tr class='' style='$TRStyles $headTRStyles'>";
                foreach ($item as $title => $col) {
                    if (!empty($skip) && in_array($title, $skip)) {
                        continue;
                    }
                    if ($skipUnderscoreHeaders) {
                        if (strpos($title, '_') === 0) {
                            continue;
                        }
                    }
                    $th = "th class='' style='$cellStyles $THStyles' ";
                    if (!empty($cellWidths)) {
                        if (!empty($cellWidths[$title])) {
                            $th .= " width='" . $cellWidths[$title] . "' ";
                        }
                    }

                    if ($title == 'checkbox') {
                        $htmlArr[] = "<{$th}><input type='checkbox' class='checkUncheckAll' /></th>";
                    } elseif ($title == 'Count') {
                        $htmlArr[] = "<{$th}>#</th>";
                    } else {
                        if (!empty($humanifyTitle)) {
                            $title = self::desanitizeTitleId($title);
                        }
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
                if (!empty($skip) && in_array($title, $skip)) {
                    continue;
                }

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
                } else {
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

    public static function sanitizeTitleToId($title)
    {
        $sTitle = preg_replace('/\W/', '', strtolower($title));
        return $sTitle;
    }

    public static function desanitizeTitleId($title)
    {
        $sTitle = ucwords(str_replace('_', ' ', $title));
        return $sTitle;
    }

    public static function joinArray($array, $groupOn = '', $concatOn = array(), $sumOn = array(), $ignore = array(), $options = array())
    {
        $options = array_merge(array(), $options);
        extract($options);

        $data = array();
        foreach ($array as $cell) {

            if (!is_array($groupOn)) {
                $groupOn = array($groupOn);
            }
            $groupValues = array();
            foreach ($groupOn as $groupOnItem) {
                $groupValues[] = $cell[$groupOnItem];
            }
            $groupValue = implode('-', $groupValues);

            if (empty($data[$groupValue])) {
                $data[$groupValue] = array();
            }
            foreach ($cell as $key => $value) {
                if (!in_array($key, $ignore)) {
                    if (!in_array($key, $sumOn)) {
                        if (empty($data[$groupValue][$key])) {
                            $data[$groupValue][$key] = array();
                        }
                        $data[$groupValue][$key][] = $value;
                    } else {
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


    public static function phpMail($to, $subject, $message, $from = "support@fegllc.com", $options = array())
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        if (isset($options['fromName'])) {
            $headers .= 'From: ' . $options['fromName'] . ' <' . $from . '>' . "\r\n";
        } else {
            $headers .= 'From: ' . $from . "\r\n";
        }

        if (!empty($options)) {
            if (!empty($options['cc'])) {
                if (isset($options['ccName'])) {
                    $headers .= 'Cc: ' . $options['ccName'] . ' <' . $options['cc'] . '>' . "\r\n";
                } else {
                    $headers .= 'Cc: ' . $options['cc'] . "\r\n";
                }
            }
            if (!empty($options['bcc'])) {
                if (isset($options['bccName'])) {
                    $headers .= 'Bcc: ' . $options['bccName'] . ' <' . $options['bcc'] . '>' . "\r\n";
                } else {
                    $headers .= 'Bcc: ' . $options['bcc'] . "\r\n";
                }
            }
            if (!empty($options['replyTo'])) {
                if (isset($options['replyToName'])) {
                    $headers .= 'Reply-To: ' . $options['replyToName'] . ' <' . $options['replyTo'] . '>' . "\r\n";
                } else {
                    $headers .= 'Reply-To: ' . $options['replyTo'] . "\r\n";
                }
            }
            if (!empty($options['sender'])) {
                if (isset($options['senderName'])) {
                    $headers .= 'Sender: ' . $options['senderName'] . ' <' . $options['sender'] . '>' . "\r\n";
                } else {
                    $headers .= 'Sender: ' . $options['sender'] . "\r\n";
                }
            }
        }
        mail($to, $subject, $message, $headers);
    }

    public static function phpMailer($to, $subject, $message, $from = "support@fegllc.com", $options = array())
    {
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
        $mail->Body = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    public static function googleOAuthMail($to, $subject, $message, $userDetail, $options = array())
    {

        if (!empty($userDetail->oauth_token) && !empty($userDetail->refresh_token)) {

            $mail = new PHPMailerOAuth();

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->SMTPDebug = 0;
            $mail->IsSMTP(); // enable SMTP
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587; // or 587
            $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
            $mail->SMTPAuth = true; // authentication enabled*/
            $mail->oauthUserEmail = $userDetail->oauth_email;
            $mail->oauthClientId = env('G_ID');
            $mail->oauthClientSecret = env('G_SECRET');
            $mail->oauthRefreshToken = $userDetail->oauth_token;
            $mail->AuthType = 'XOAUTH2';

            $mail->smtpConnect();

            //Send HTML or Plain Text email
            $mail->isHTML(true);

            $mail->SetFrom($userDetail->email);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $tos = explode(',', $to);
            foreach ($tos as $t) {
                $mail->addAddress($t);
            }

            if (isset($options['cc'])) {
                $cc = explode(',', $options['cc']);
                foreach ($cc as $c) {
                    $mail->addCC($c);
                }
            }

            if (isset($options['bcc'])) {
                $bcc = explode(',', $options['bcc']);
                foreach ($bcc as $bc) {
                    $mail->addBCC($bc);
                }
            }
            if (isset($options['replyTo'])) {
                $mail->addReplyTo($options['replyTo']);
            } else {
                $mail->addReplyTo($userDetail->email);

            }

            if (isset($options['attach'])) {
                if (isset($options['filename']) && !is_array($options['attach'])) {
                    $mail->addAttachment($options['attach'], $options['filename'], isset($options['encoding']) ? $options['encoding'] : 'base64', isset($options['type']) ? $options['type'] : '');
                }
                if (is_array($options['attach'])) {
                    foreach ($options['attach'] as $file) {
                        $mail->addAttachment($file, substr($file, strrpos($file, '/') + 1), isset($options['encoding']) ? $options['encoding'] : 'base64', isset($options['type']) ? $options['type'] : '');
                    }
                }
            }
            if ($mail->Send()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function configLaravelMail($mail, $options)
    {
        extract($options);

        $mail->subject($subject);
        $mail->setBody($message, 'text/html');

        $toArray = explode(',', $to);
        if (count($toArray) == 1 && isset($toName)) {
            $mail->to($toArray[0], $toName);
        } else {
            $mail->to($toArray);
        }

        if (isset($cc) && !empty(trim($cc))) {
            $ccArray = explode(',', $cc);
            if (count($ccArray) == 1 && isset($ccName)) {
                $mail->cc($ccArray[0], $ccName);
            } else {
                $mail->cc($ccArray);
            }
        }
        if (isset($bcc) && !empty(trim($bcc))) {
            $bccArray = explode(',', $bcc);
            if (count($bccArray) == 1 && isset($bccName)) {
                $mail->bcc($bccArray[0], $bccName);
            } else {
                $mail->bcc($bccArray);
            }
        }
        if (isset($sender) && !empty(trim($sender))) {
            $senderArray = explode(',', $sender);
            if (count($senderArray) == 1 && isset($senderName)) {
                $mail->sender($senderArray[0], $senderName);
            } else {
                $mail->sender($senderArray);
            }
        }
        if (isset($from) && !empty(trim($from))) {
            $fromArray = explode(',', $from);
            if (count($fromArray) == 1 && isset($fromName)) {
                $mail->from($fromArray[0], $fromName);
            } else {
                $mail->from($fromArray);
            }
        }
        if (isset($replyTo) && !empty(trim($replyTo))) {
            $replyToArray = explode(',', $replyTo);
            if (count($replyToArray) == 1 && isset($replyToName)) {
                $mail->replyTo($replyToArray[0], $replyToName);
            } else {
                $mail->replyTo($replyTo);
            }
        }

        if (isset($attach)) {
            if (is_array($attach)) {
                foreach ($attach as $attachment) {
                    if (file_exists($attachment)) {
                        $mail->attach($attachment);
                    } else {
                        \Log::info("Attachment file not found: $attachment");
                    }
                }
            } else {
                $mail->attach($attach);
            }
        }
    }

    public static function laravelMail($to, $subject, $message, $from = "support@fegllc.com", $options = array())
    {
        $view = empty($options['view']) ? '' : $options['view'];
        //Todo i have uncommented these why these were commented before
        $options['to'] = $to;
        $options['subject'] = $subject;
        $options['message'] = $message;
        $options['from'] = $from;

        if (!empty($view)) {
            Mail::send($view, $options, function ($mail) use ($options) {
                self::configLaravelMail($mail, $options);
            });
        } else {
            Mail::send([], $options, function ($mail) use ($options) {
                self::configLaravelMail($mail, $options);
            });
//            Mail::raw($message, function ($mail) use ($options) {
//                self::configLaravelMail($mail, $options);
//            });
        }

        $failureCount = count(Mail::failures());
        return $failureCount == 0;
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
    public static function sendEmail($to, $subject, $message, $from = "support@fegllc.com", $options = array(), $sendEmailFromMerchandise = false)
    {
        //support@fegllc.com
        if (empty($from)) {
            //$from = "support@fegllc.com";
            //$from = "support@element5digital.com";
            $from = "support@fegllc.com";
        }

        $preventEmailSendingSetting = env('PREVENT_FEG_SYSTEM_EMAIL', false);
        if (!$preventEmailSendingSetting) {
            $usePhpMail = !empty($options['usePHPMail']);
            $preferGoogleSend = !empty($options['preferGoogleOAuthMail']);
            //$useLaravelMail = !empty($options['useLaravelMail']) || !empty($options['attach']);
            if ($usePhpMail) {
                return self::phpMail($to, $subject, $message, $from, $options);
            } else {
                if ($preferGoogleSend && !empty(Auth()->user()->oauth_token) && !empty(Auth()->user()->refresh_token)) {
                    $user = Users::find(Auth()->user()->id);
                    if (!$user->isOAuthRefreshedRecently() || !Users::verifyOAuthTokenIsValid($user->oauth_token)) {

                        $googleResponse = Users::refreshOAuthToken($user->refresh_token);
                        $user->updateRefreshToken($googleResponse);
                    }
                    return self::googleOAuthMail($to, $subject, $message, $user, $options);
                } else {

                    if($sendEmailFromMerchandise){
                        $config = array(
                            'username' => env('MAIL_MERCH_USERNAME'),
                            'password' => env('MAIL_MERCH_PASSWORD'),
                            'driver' => env('MAIL_DRIVER'),
                            'host' => env('MAIL_HOST'),
                            'port' => env('MAIL_PORT'),
                            'from' => array('address' => env('MAIL_MERCH_FROM_EMAIL'), 'name' => env('MAIL_NAME')),
                            'encryption' => env('MAIL_ENCRYPTION'),
                            'sendmail' => '/usr/sbin/sendmail -bs',
                            'pretend' => false,
                        );
                        \Config::set('mail', $config);
                        $from = env('MAIL_MERCH_FROM_EMAIL');
                    }

                    return self::laravelMail($to, $subject, $message, $from, $options);
                }
            }
        } else {
            return 'Email Could not be sent because prevented';
        }
    }

    public static function getHumanDate($date = "")
    {
        $hDate = "";
        if (!empty($date)) {
            $hDate = date("l, F d Y", strtotime($date));
        }
        return $hDate;
    }

    public static function split_trim($txt, $delim = ',', $trimChar = null)
    {
        $arr = array();
        if (empty($txt)) {
            $txt = "";
        }
        $data = explode($delim, $txt);
        foreach ($data as $val) {
            $val = empty($trimChar) ? trim($val) : trim($val, $trimChar);
            if (!empty($val)) {
                $arr[] = $val;
            }
        }
        return $arr;
    }

    public static function split_trim_join($txt, $delim = ',', $trimChar = null)
    {
        $arr = self::split_trim($txt, $delim, $trimChar);
        $joined = implode($delim, $arr);
        return $joined;
    }

    public static function syncTable($params = array())
    {
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
        while (self::checkIfSyncRequired($params)) {
            $timeEnd = microtime(true);
            $timeDiff = round($timeEnd - $timeStart);
            $timeDiffHuman = self::secondsToHumanTime($timeDiff);
            self::$L->log("Has " . ($count > 0 ? "more" : "") . " data to sync [ $timeDiffHuman ]");
            self::_syncTable($params);
            $count++;
            sleep(3);
        }
        self::$L->log("No  " . ($count > 0 ? "more" : "") . " data to sync");

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

    public static function _syncTable($params = array())
    {
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
        } else {
            $source = DB::connection($sourceDB);
            DB::connection($sourceDB)->setFetchMode(PDO::FETCH_ASSOC);
        }
        if (empty($targetDB)) {
            $target = DB::connection();
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $target = DB::connection($targetDB);
            DB::connection($targetDB)->setFetchMode(PDO::FETCH_ASSOC);
        }

        $lastID = self::get_last_id($table, $targetDB);

        $q = "SELECT * from $table WHERE id > $lastID LIMIT " . $chunk;
        $data = $source->select($q);
        $target->table($targetTable)->insert($data);

        if (empty($sourceDB)) {
            DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        } else {
            DB::connection($sourceDB)->setFetchMode(PDO::FETCH_CLASS);
        }
        if (empty($targetDB)) {
            DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        } else {
            DB::connection($targetDB)->setFetchMode(PDO::FETCH_CLASS);
        }

    }

    public static function truncateTable($params = array())
    {
        extract(array_merge(array(
            'db' => '',
            'table' => ''
        ), $params));
        if (is_null($db)) {
            $id = DB::table($table)->truncate();
        } else {
            $id = DB::connection($db)->table($table)->truncate();
        }
    }

    public static function get_last_id($table, $dbname = null)
    {
        if (is_null($dbname)) {
            $id = DB::table($table)->orderBy('id', 'desc')->take(1)->value('id');
        } else {
            $id = DB::connection($dbname)->table($table)->orderBy('id', 'desc')->take(1)->value('id');
        }

        if (is_null($id)) {
            $id = 0;
        }
        return $id;
    }

    public static function checkIfSyncRequired($params = array())
    {
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
     * @param string $configName Name of the Configuration from /system/systememailreportmanager
     * @param number /string $location   [optional] Pass the location id if you want to filter user assigned to that location. Can pass null to skip it
     * @param boolean $isTest [optional] Pass true to get recipients only from the 'Email recipients while testing' section
     * @param boolean $sanitizeEmails [optional] If set to true then removes all invalid emails (default: true)
     * @return array    {'to' => '<string comma_separated_emails>', 'cc' => '<string comma_separated_emails>', 'bcc' => '<string comma_separated_emails>', }
     */
    public static function getSystemEmailRecipients($configName, $location = null, $isTest = false, $sanitizeEmails = true)
{
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
            } else {
                if ($data->has_locationwise_filter) {
                    $location = empty($location) ? null : $location;
                } else {
                    //overwriting location with null if location wise filter in system email manager is off
                    $location = null;
                }


                $lut = $data->to_email_location_contacts;
                $lucc = $data->cc_email_location_contacts;
                $lubcc = $data->bcc_email_location_contacts;
                $locationUsers['to'] = self::getLocationContactsEmails($lut, $location, true);
                $locationUsers['cc'] = self::getLocationContactsEmails($lucc, $location, true);
                $locationUsers['bcc'] = self::getLocationContactsEmails($lubcc, $location, true);


                $gt = $data->to_email_groups;
                $gcc = $data->cc_email_groups;
                $gbcc = $data->bcc_email_groups;
                $groups['to'] = self::getGroupsUserEmails($gt, $location, true);
                $groups['cc'] = self::getGroupsUserEmails($gcc, $location, true);
                $groups['bcc'] = self::getGroupsUserEmails($gbcc, $location, true);

                $ut = $data->to_email_individuals;
                $ucc = $data->cc_email_individuals;
                $ubcc = $data->bcc_email_individuals;
                $users['to'] = self::getUserEmails($ut, $location, true);
                $users['cc'] = self::getUserEmails($ucc, $location, true);
                $users['bcc'] = self::getUserEmails($ubcc, $location, true);

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

                if ($sanitizeEmails) {
                    $to = self::sanitiseEmails($to);
                    $cc = self::sanitiseEmails($cc);
                    $bcc = self::sanitiseEmails($bcc);
                }

                $emails['to'] = implode(',', $to);
                $emails['cc'] = implode(',', $cc);
                $emails['bcc'] = implode(',', $bcc);
            }
        }
        return $emails;
    }

    /**
     * @param array $emails preferably indexed array of emails
     * @return array
     */
    public static function sanitiseEmails($emails = []) {
        if (count($emails) > 0) {
            $newEmails = [];
            foreach ($emails as $key => $email) {
                $invalid = filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE;
                if (!$invalid) {
                    $newEmails[$key] = $email;
                }
            }
            return $newEmails;
        }
        return $emails;
    }
    
    public static function getLocationContactsEmails($fields = '', $location = null, $skipIfNoGroup = false)
    {
        $emails = [];
        if (!empty(trim($fields))) {
            $ids = self::getLocationContactsUserIds($fields, $location, $skipIfNoGroup);
            if (!empty($ids)) {
                $emails = self::getUserEmails(implode(',', $ids), $location, $skipIfNoGroup);
            }
        }
        return $emails;
    }

    public static function getLocationContactsUserIds($groups = null, $location = null, $skipIfNoGroup = false)
    {
        if (is_array($groups)) {
            $groups = implode(',', $groups);
        }
        $groups = self::split_trim_join($groups);
        if ($skipIfNoGroup && empty($groups)) {
            return [];
        }

        $q = "SELECT u.id
                FROM user_locations ul
                LEFT JOIN users u ON u.id = ul.user_id
                WHERE u.active=1";

        if (!empty($groups)) {
            $q .= " AND ul.group_id IN ($groups)";
        }
        if ($location) {
            $q .= " AND UL.location_id IN ($location)";
        }

        $data = DB::select($q);
        $uids = array();
        foreach ($data as $row) {
            $uid = $row->id;
            if (!empty($uid)) {
                $uids[] = $uid;
            }
        }
        return array_values(array_unique($uids));
    }

    /**
     *
     * @param type $groups
     * @param type $location
     * @param type $skipIfNoGroup
     * @return type
     */
    public static function getGroupsUserEmails($groups = null, $location = null, $skipIfNoGroup = false)
    {
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
        foreach ($data as $row) {
            $email = $row->email;
            if (!empty($email)) {
                $emails[] = trim($email);
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
    public static function getGroupsUserIds($groups = null, $location = null, $skipIfNoGroup = false)
    {
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
        foreach ($data as $row) {
            $uid = $row->id;
            if (!empty($uid)) {
                $uids[] = $uid;
            }
        }
        return array_values(array_unique($uids));
    }

    /**
     *      * Get user ids which are assigned to a location from a list of users
     * @param type $location
     * @param type $users
     * @param type $skipIfNoUsers
     * @return type
     */
    public static function getLocationUserIds($location = null, $users = null, $skipIfNoUsers = false)
    {
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
        foreach ($data as $row) {
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
    public static function getUserEmails($users = null, $location = null, $skipIfNoUsers = false)
    {
        if (is_array($users)) {
            $users = implode(',', $users);
        }
        $users = self::split_trim_join($users);
        if ($skipIfNoUsers && empty($users)) {
            return [];
        }
        $q = "SELECT DISTINCT email FROM users ";

        $q .= " LEFT JOIN user_locations ON user_locations.user_id = users.id ";

        $q .= " WHERE users.active=1 ";
        if (!empty($users)) {
            $q .= " AND users.id IN ($users)";
        }

        if (!empty($location)) {
            $q .= " AND user_locations.location_id IN ($location)";
        }
        $data = DB::select($q);
        $emails = array();

        foreach ($data as $row) {
            $email = $row->email;
            $emails[] = trim($email);
        }
        return $emails;
    }

    /**
     * Wrapper function to send email
     * @param array $options An associative array containing these keys:
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
    public static function sendSystemEmail($options, $sendEmailFromMerch = false)
    {
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
            . (empty($configNamePrefix) ? "" :  preg_replace('/[\W]/', '-', "{$configNamePrefix}-"))
            . $configNameSanitized
            . (empty($configNameSuffix) ? "" :  preg_replace('/[\W]/', '-', "-{$configNameSuffix}"))
            . ".log";

        if ($isTest) {
            $attachments = isset($attach) ? $attach : '';
            // this code will store attachments name in $attachments instead of path
            /*if(is_array($attachments))
            {
                foreach ($attachments as $key => $file)
                {
                    $attachments[$key] = substr($file, strrpos($file, '/') + 1);
                }
            }*/
            if(isset($attach) && is_array($attach)){
                $attachmentContent = implode("<li>", $attach);
            }else{
                $attachmentContent =isset($attach) ? $attach: '';
            }

            $message = "
*************** EMAIL START --- DEBUG INFO *******************<br>
[FROM: $from]<br/>
[SUBJECT: $subject]<br/>
[TO: $to]<br/>
[CC: $cc]<br/>
[BCC: $bcc]<br/>

***************** DEBUG INFO END *****************************<br><br>
$message" .
                (isset($attach) ? "<br><br> ================ ATTACHMENTS ===================================<br><ul><li>" . ($attachmentContent) . '</ul>' : '') .
                "<br><br>******************************************* EMAIL END ********************************<br><br/>";

            $options['message'] = $message;
            $options['subject'] = $subject = "[TEST] " . $subject;
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
            //$messageLog = $message;
            //self::logit($messageLog, "{$lf}.html", $lpd, true);
        }

        $messageLog = $message;
        self::logit($messageLog, "{$lf}.html", $lpd, true);


        self::logit("Sending Email", $lf, $lp);
        self::logit($options, $lf, $lp);
        $status = self::sendEmail($to, $subject, $message, $from, $options, $sendEmailFromMerch);
        self::logit("Email sent Status = " . $status, $lf, $lp);
        self::logit("Email sent", $lf, $lp);
        return $status;
    }

    public static function getOption($optionName, $default = '', $all = false, $skipInactive = false, $details = false)
    {
        return Options::getOption($optionName, $default, $all, $skipInactive, $details);
    }

    public static function updateOption($optionName, $value = '', $options = array())
    {
        return Options::updateOption($optionName, $value, $options);
    }

    public static function addOption($optionName, $value = '', $options = array())
    {
        return Options::addOption($optionName, $value, $options);
    }

    public static function getUserAvatarUrl($id = null, $file = null)
    {
        $fileUrl = url() . "/silouette.png";
        if (!empty($id)) {
            $file = \App\Models\Core\Users::where('id', $id)->pluck('avatar');
        }
        $filePath = "./uploads/users/$file";
        if (!empty($file) && file_exists($filePath)) {
            $fileUrl = \URL::to("uploads/users/$file");
        }
        return $fileUrl;
    }

    public static function getUserProfileDetails($user)
    {
        $userID = $user['id'];
        $firstName = empty($user['first_name']) ? '' : $user['first_name'];
        $lastName = empty($user['last_name']) ? '' : $user['last_name'];
        $fullName = $firstName . ' ' . $lastName;
        $avatar = self::getUserAvatarUrl(null, $user['avatar']);
        $userTooltip = 'Username: ' . $user['username'] . '<br/> Email: ' . $user['email'];

        return [
            'id' => $userID,
            'fullName' => $fullName,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'avatar' => $avatar,
            'tooltip' => $userTooltip,
        ];
    }

    public static function getTicketCommentUserProfile($comment, $ticket = [])
    {
        $userID = $comment->UserID;
        $externalName = empty($comment->USERNAME) ? '' : $comment->USERNAME;
        $firstName = empty($comment->first_name) ? '' : $comment->first_name;
        $lastName = empty($comment->last_name) ? '' : $comment->last_name;
        $fullName = $firstName . ' ' . $lastName;
        $avatar = self::getUserAvatarUrl(null, $comment->avatar);
        $isExternal = empty($userID);

        $userTooltip = 'Username: ' . $comment->username . '<br/> Email: ' . $comment->email;
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

    public static function getTicketAttachmentDetails($data, $isInitialTicketData = false)
    {
        $fileNamesCSV = $isInitialTicketData ? $data->file_path : $data->Attachments;
        $datePosted = \DateHelpers::formatDate($isInitialTicketData ? $data->Created : $data->Posted);
        $attachments = [];
        if (!empty($fileNamesCSV)) {
            $files = explode(',', $fileNamesCSV);
            foreach ($files as $file) {
                if (!empty($file)) {
                    $url = url() . $file;
                    $fileName = self::getSanitizedFileNameForTicketAttachments($file);
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
    public static function possiblyRenameFileToResolveDuplicate($basename, $path)
    {
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
            $newBasename = $filename . ('--' . ++$copyCount) . (empty($ext) ? "" : ('.' . $ext));
            $filepath = $path . $newBasename;
        }
        return $newBasename;
    }

    /**
     *
     * @param type $path
     * @return string trimmed and slim filename
     */
    public static function getSanitizedFileNameForTicketAttachments($path, $maxLength = 25)
    {
        $fileParts = pathinfo($path);
        $ext = empty($fileParts['extension']) ? '' : $fileParts['extension'];
        $fileName = $fileParts['filename'];

        $fileName = preg_replace('/--.*$/', '', $fileName);
        $fileName = substr($fileName, 0, $maxLength);

        $newBasename = $fileName . (empty($ext) ? "" : ('.' . $ext));
        return $newBasename;
    }

    /**
     *
     * @param string $path
     * @param string $getWhat
     * @return array or string
     */
    public static function getSanitisedPublicUploadPath($path = '', $getWhat = '')
    {

        $replaces = [[], []];

        // remove multiple /
        $replaces[0][] = '/\/{2,}/';
        $replaces[1][] = '/';

        // remove './public/' or '/public/'
        $replaces[0][] = '/^[\.\/]*public\//';
        $replaces[1][] = '/';

        // replace path that begings in / with ./
        $replaces[0][] = '/^\//';
        $replaces[1][] = './';

        // add a slash at the end
        $replaces[0][] = '/([^\/])$/';
        $replaces[1][] = '$1/';

        //remove multiple /
        $replaces[0][] = '/\/{2,}/';
        $replaces[1][] = '/';

        // sanitise and remove public folder
        $target = self::sanitiseString($path, $replaces);

        // retain public folder
        $replaces[1][1] = 'public/';
        $real = base_path(self::sanitiseString($path, $replaces));
        $url = preg_replace('/^[\.\/]*/', '/', $target);

        $paths = [
            'url' => $url,
            'target' => $target,
            'real' => $real,
        ];

        if (empty($getWhat) || empty($paths[$getWhat])) {
            return $paths;
        }
        return $paths[$getWhat];
    }

    /**
     *
     * @param string $string
     * @param array $replaceRegExp
     * @return string
     */
    public static function sanitiseString($string = '', $replaceRegExp = [])
    {
        $newString = $string;
        if (!empty($replaceRegExp[0] && $replaceRegExp[1])) {
            $newString = preg_replace($replaceRegExp[0], $replaceRegExp[1], $string);
        }
        return $newString;
    }

    /**
     *
     * @param type $string
     * @param type $variableDelimiter
     * @param type $valueDelimiter
     * @param type $defaults
     * @return type
     */
    public static function parseStringToArray($string = '', $variableDelimiter = '|', $valueDelimiter = ':', $defaults = array())
    {
        $valueArray = $defaults;
        $newArray = [];

        if (!empty($string)) {
            $variables = explode($variableDelimiter, $string);
            foreach ($variables as $variable) {
                list($key, $value) = explode($valueDelimiter, $variable . $valueDelimiter);
                if (strpos($variable, $valueDelimiter) === false) {
                    $value = $key;
                }
                $newArray[$key] = $value;
            }
//            $string = str_replace($variableDelimiter, '&', $string);
//            $string = str_replace($valueDelimiter, '=', $string);
//            parse_str($string, $valueArray);
            $valueArray = array_merge($defaults, $newArray);
        }
        return $valueArray;
    }

    /**
     *
     * @param type $value
     * @param type $options associative array with (value => label)
     * @param type $default
     * @return type
     */
    public static function getLabelFromOptions($value, $options, $default = '')
    {
        $label = $default;
        if (isset($options[$value])) {
            $label = $options[$value];
        }
        return $label;
    }

    public static function specialPermissionFormatter($value, $fieldItem, $options = [])
    {

        $row = $options['row'];
        $id = isset($row->id) ? $row->id : '';
        $fieldOptions = $fieldItem['options'];
        $isMultiselect = isset($fieldOptions['multiple']) && $fieldOptions['multiple'] == true;
        $fieldArrayLiteral = (!empty($id) ? "[$id]" : '[0]') . ($isMultiselect ? '[]' : '');
        $fieldName = $fieldItem['field'];
        $isTextHideDefault = isset($fieldOptions['hideText']) ? $fieldOptions['hideText'] : true;
        $isTextHide = isset($options['hideText']) && !is_null($options['hideText']) ? $options['hideText'] : $isTextHideDefault;

        $fieldOptionsMap = [
            'type' => 'opt_type',
            'table' => 'lookup_table',
            'options' => 'lookup_query',
            'key' => 'lookup_key',
            'value' => 'lookup_value',
            'search' => 'lookup_search',
            'multiple' => 'select_multiple_inline',
            'inputTooltip' => 'tooltip',
            'attribute' => 'attribute',
            'extend_class' => 'extend_class',
        ];
        $hideStyle = " style='display: none;' ";
        $data = [
            'fieldClass' => 'permissionCell ' . $fieldName,
            'formattedValue' => $value,
            'value' => $value,
            'input' => '',
            'hideText' => $isTextHide ? $hideStyle : '',
            'hideInput' => $isTextHide ? '' : $hideStyle,
            'tooltip' => isset($fieldOptions['tooltip']) ? $fieldOptions['tooltip'] : '',
            'textTooltip' => isset($fieldOptions['textTooltip']) ? $fieldOptions['textTooltip'] : '',
            'inputTooltip' => isset($fieldOptions['inputTooltip']) ? $fieldOptions['inputTooltip'] : '',
            'editOnDBClick' => isset($fieldOptions['editOnDBClick']) ? 'editOnDBClick' : '',
            'editAllOnDBClick' => isset($fieldOptions['editAllOnDBClick']) ? 'editAllOnDBClick' : '',
        ];


        $required = empty($fieldOptions['required']) ? '' : $fieldOptions['required'];
        $fieldItem['required'] = $required === true ? 'required' : $required;
        $inputOptions = [];
        foreach ($fieldOptionsMap as $key => $optionMap) {
            $inputOptions[$optionMap] = isset($fieldOptions[$key]) ? $fieldOptions[$key] : '';
        }
        $fieldItem['option'] = $inputOptions;
        $data['input'] = \SiteHelpers::transInlineForm($fieldName, [$fieldItem], $fieldArrayLiteral, $value);

        $gridAttributes = [
            'image' => ['active' => ''],
            'formater' => ['active' => 0, 'value' => ''],
            'hyperlink' => ['active' => '', 'link' => '', 'target' => '', 'html' => '']
        ];
        $gridFormatter = ['valid' => '', 'db' => '', 'key' => '', 'display' => '', 'multiple' => '',];

        $formatter = isset($fieldOptions['formatter']) ? $fieldOptions['formatter'] : '';
        $needsFormatting = false;
        if (!empty($formatter)) {
            $needsFormatting = true;
            $gridAttributes['formater']['active'] = 1;
            $gridAttributes['formater']['value'] = $formatter;
        }

        $hyperlink = isset($fieldOptions['hyperlink']) ? $fieldOptions['hyperlink'] : '';
        if (!empty($hyperlink)) {
            $needsFormatting = true;
            $gridAttributes['hyperlink']['active'] = 1;
            $gridAttributes['hyperlink']['value'] = $hyperlink;
            $gridAttributes['hyperlink']['value'] = isset($fieldOptions['hyperlinkTraget']) ? $fieldOptions['hyperlinkTraget'] : '';
        }

        $dbConnection = !empty($fieldOptions['table']) ? $fieldOptions['table'] : '';
        if (!empty($dbConnection)) {
            $needsFormatting = true;
            $gridFormatter['valid'] = 1;
            $gridFormatter['db'] = $dbConnection;
            $gridFormatter['key'] = isset($fieldOptions['key']) ? $fieldOptions['key'] : '';;
            $gridFormatter['display'] = isset($fieldOptions['value']) ? $fieldOptions['value'] : '';;
            $gridFormatter['multiple'] = isset($fieldOptions['multiple']) ? $fieldOptions['multiple'] : '';
        }

        $dataOptions = !empty($fieldOptions['options']) ? $fieldOptions['options'] : '';
        if (!empty($dataOptions)) {
            $needsFormatting = true;
            $gridFormatter['datalist'] = 1;
            $gridFormatter['options'] = $dataOptions;
        }

        if ($needsFormatting) {
            $data['formattedValue'] = \AjaxHelpers::gridFormater($value, $row, $gridAttributes, $gridFormatter);
        }

        return $data;
    }

    public static function removeDuplicateUserLocations()
    {
        $result = [];
        $sql = "SELECT COUNT(id) AS  cid, GROUP_CONCAT(CONCAT('', id) ORDER BY id) as ids,
            location_id, user_id, GROUP_CONCAT(CONCAT(IFNULL(group_id, '0'),'')  ORDER BY id) as gids
            FROM user_locations
            GROUP BY location_id, user_id
            HAVING COUNT(id)>1
            ORDER BY cid DESC";
        $data = \DB::select($sql);
        $q = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $user = $item->user_id;
                $location = $item->location_id;
                $ids = explode(',', $item->ids);
                $gs = explode(',', $item->gids);
                foreach ($ids as $key => $id) {
                    $gid = $gs[$key];
                    $q[$location . '-' . $user][$gid] = $id;
                }

            }
        }
        $sql = "SELECT COUNT(id) AS  cid, GROUP_CONCAT(CONCAT('', id) ORDER BY id) AS ids,
            location_id, GROUP_CONCAT(CONCAT(IFNULL(user_id, '0'),'')  ORDER BY id) AS uids,
            group_id

            FROM user_locations
            WHERE group_id IS NOT NULL
            GROUP BY location_id, group_id
             HAVING COUNT(id)>1
            ORDER BY cid DESC";
        $data = \DB::select($sql);
        $q2 = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $gid = $item->group_id;
                $location = $item->location_id;
                $ids = explode(',', $item->ids);
                foreach ($ids as $key => $id) {
                    $q2[$location . '-' . $gid] = $id;
                }
            }
        }

        \DB::beginTransaction();
        $result[] = "<strong>Delete User's Duplicate Location Assignments</strong>";
        foreach ($q as $key => $groups) {
            $ids = [];
            foreach ($groups as $gid => $id) {
                $ids[] = $id;
            }
            $sql = "DELETE FROM user_locations WHERE location_id=" . str_replace('-', ' AND user_id=', $key) . ' AND id NOT IN (' . implode(',', $ids) . ')';
            $result[] = \DB::delete($sql) . ' => ' . $sql;
        }
        $result[] = "<strong>Delete Location's Special Assignments</strong>";
        foreach ($q2 as $key => $id) {
            $sql = "DELETE FROM user_locations WHERE location_id=" . str_replace('-', ' AND group_id=', $key) . ' AND id NOT IN (' . $id . ')';
            $result[] = \DB::delete($sql) . ' => ' . $sql;
        }
        \DB::commit();
        return $result;
    }

    /**
     * @param string $query
     */
    public static function probeDatesInSearchQuery($query = '')
    {
        $dates = [];
        if (!empty($query)) {
            $query = urldecode($query);
            list($dateStart, $dateEnd) = explode("-", $query . "-");
            $dateStart = trim($dateStart);
            $dateEnd = trim($dateEnd);
            $dateStartValue = strtotime($dateStart);
            $dateEndValue = strtotime($dateEnd);
            if ($dateStartValue !== false) {
                $dates[0] = date("Y-m-d H:i:s", $dateStartValue);
            }
            if ($dateEndValue !== false) {
                $dates[1] = date("Y-m-d H:i:s", $dateEndValue);
                if (strpos($dates[1], "00:00:00") > 0) {
                    $dates[1] = date("Y-m-d H:i:s", strtotime($dates[1] . " +1 day -1 second"));
                }
            }
            if (empty($dates[0]) && !empty($dates[1])) {
                $dates = [$dates[1]];
            }
        }
        return $dates;
    }

    public static function getEnumTable($table, $key, $label, $limit = '')
    {
        $sql = "SELECT $key, $label from $table $limit";
        $data = DB::select($sql);
        $enum = [];
        foreach ($data as $row) {
            $enum[$row->$key] = $row->$label;
        }
        return $enum;
    }

    public static function stringBuilder($string, $data = [])
    {
        $search = [];
        $replacer = [];
        foreach ($data as $index => $val) {
            $search[] = '{' . $index . '}';
            $replacer[] = $val;
        }
        $string = str_replace($search, $replacer, $string);
        $string = preg_replace('/\{\d+?\}/', '', $string);
        return $string;
    }

    public static function isValidDate($date, $format = 'Y-m-d', $isTimeIncldued = false, $timeFormat = 'H:i:s')
    {

        $fullformat = $format . ($isTimeIncldued ? (' ' . $timeFormat) : '');
        $d = \DateTime::createFromFormat($fullformat, $date);
        return $d && $d->format($fullformat) === $date;
    }

    public static function strip_html_tags($str)
    {
        $str = preg_replace('/(<|>)\1{2}/is', '', $str);
        $str = preg_replace(
            array(// Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
            ),
            "", //replace above with nothing
            $str);
        $str = self::replaceWhitespace($str);
        $str = strip_tags($str);
        return $str;
    }

    //To replace all types of whitespace with a single space
    public static function replaceWhitespace($str)
    {
        $result = $str;
        foreach (array(
                     "  ", " \t", " \r", " \n",
                     "\t\t", "\t ", "\t\r", "\t\n",
                     "\r\r", "\r ", "\r\t", "\r\n",
                     "\n\n", "\n ", "\n\t", "\n\r",
                 ) as $replacement) {
            $result = str_replace($replacement, $replacement[0], $result);
        }
        return $str !== $result ? self::replaceWhitespace($result) : $result;
    }

    public static function retainHTMLBody($html)
    {

        $html = preg_replace('/\<[\s\S]*\<body\>/is', '', $html);
        $html = preg_replace('/\<\/body[\s\S]*\/html\>/is', '', $html);
        return $html;
    }

    public static function getDOMElementById($content, $ElementId)
    {
        $doc = new \DOMDocument();
        $doc->loadHTML($content);
        $Element = $doc->getElementById($ElementId);
        return $Element;
    }

    public static function DOMinnerHTML(\DOMElement $element)
    {
        return $element->nodeValue;
        /*$innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;*/
    }



    public static function updateProductMeta($product, $data = [], $options = []) {

        $productId = $product->id;
        $master = $product->master();
//        $variations = $product->getProductVariations();
//
//        if (empty($variations) || $variations->isEmpty()) {
//            return "Variations not found";
//        }
        if (empty($master)) {
            $master = $product;
            //return "Master not found";
        }

        $matsterVariationProductId = $master->id;
        $variationId = \SiteHelpers::encryptID($matsterVariationProductId);

        $createData = [
            'product_id' => $productId,
            'variation_id' => $variationId,
            'variation_master_product_id' => $matsterVariationProductId,
        ];
        if (isset($comm )) {
            $comm->line("Product ID: $productId");
            $comm->line("Master ID: $matsterVariationProductId");
            $comm->line("VID: $variationId");
//            $comm->line("V count: ". $variations->count());
            $comm->line(json_encode($createData));
        }

        \App\Models\ProductMeta::updateOrCreate(
            [
                'product_id' => $productId
            ],
            $createData
        );

        /** @var \App\Models\ProductMeta $meta */
        $meta =  \App\Models\ProductMeta::where('product_id', $productId)->first();

        $productAllActive = self::getOption('all_product_active_in_api', 0);
        $activeLimit = self::getOption('product_active_in_api_till', '24 hours');

        if ($productAllActive == 1) {
            $data['posted_to_api_at'] = null;
        }
        else {

            if (!isset($data['posted_to_api_at'])) {

                if (empty($options['order'])) {
                    $thresholdTimestamp = date("Y-m-d H:i:s", strtotime("-".$activeLimit));
                    $order =  \App\Models\order::where('is_api_visible', 1)
                        ->where('api_created_at', '>=', $thresholdTimestamp)
                        ->whereHas('contents.product', function ($query) use($productId) {
                            $query->where('id', $productId);
                        })
                        ->orderBy('api_created_at', 'desc')
                        ->first();

                } else {
                    $order = $options['order'];
                }


                if (!empty($order)) {
                    $postedToApi = date('Y-m-d H:i:s', strtotime($order->api_created_at));
                    $data['posted_to_api_at'] = $postedToApi;

                } else {
                    $data['posted_to_api_at'] = null;
                }
            }

            if (!isset($data['posted_to_api_expired_at']) && !empty($data['posted_to_api_at'])) {
                $expirationTimestamp = date("Y-m-d H:i:s", strtotime($data['posted_to_api_at']." +".$activeLimit));
                $data['posted_to_api_expired_at'] = $expirationTimestamp;
            }

        }

        if ($data['posted_to_api_at'] == null) {
            $data['posted_to_api_expired_at'] = null;
        }

        if (!empty($data)) {
            $meta->update($data);
        }

        return $meta;
    }

    public static function updateMetaFromOrder($orderID, $data = [], $options = []) {
        $order = \App\Models\order::where('id', $orderID)->first();
        $options['order'] = $order;
        if (!empty($order)) {
            $contents = $order->orderedContent;
            foreach($contents as $item) {
                $product = $item->product;
                $meta = self::updateProductMeta($product, $data, $options);
            }
        }
    }


    public static function cleanProductMeta($params = []) {
        $table = "product_meta";
        $errorMessage = [];
        $messages = [];
        extract(array_merge(array(
            '_task' => array(),
            '_logger' => null,
        ), $params));

        /** @var  $commandObj */
        /** @var  $_logger */
        /** @var  $_task */

        $q = "UPDATE $table SET posted_to_api_at=null, posted_to_api_expired_at=null WHERE posted_to_api_expired_at < NOW()";
        DB::update($q);

        return $messages;

    }

    public static function logPlus($message = '', $logger = null, $obj = null, $command = null, $msgType = 'line') {
        if (!is_null($logger)) {
            if (is_null($obj)) {
                $logger->log($message);
            }
            else {
                $logger->log($message, $obj);
            }
        }
        if (!is_null($command)) {
            if (!is_string($message)) {
                $message = json_encode($message, JSON_PRETTY_PRINT);
            }
            if (!method_exists($command, $msgType)) {
                $msgType = 'line';
            }
            call_user_func(array($command, $msgType), $message);
            if (!is_null($obj)) {
                $message = json_encode($obj, JSON_PRETTY_PRINT);
                call_user_func(array($command, $msgType), $message);
            }
        }
    }

    /**
     * @param $name
     * @param array $filterREGXs
     * @param bool $addExtension
     * @return mixed|string
     */

    public static function senitizeAttachmentName($name, $filterREGXs=[], $addExtension = true)
    {
        $extension = substr($name,strpos($name,".")+1,strlen($name));
        $prefix = substr($name,0,strpos($name,"."));
        foreach ($filterREGXs as $filterRegx){
            $prefix = preg_replace($filterRegx, '', $prefix);
        }
        return ($addExtension == true) ? $prefix.".".$extension: $prefix;
    }

    /**
     * @param $commentData
     * @return array
     */

    public static function getCommentAttachmentDetails($commentData)
    {
        $attachmentsDetail = [];
        if($commentData->attachments) {
          $userData = self::getTicketCommentUserProfile($commentData);
            foreach ($commentData->attachments as $attachment) {
                $attachmentsDetail[] = [
                    'fullName' => $userData['fullName'],
                    'date' => \DateHelpers::formatDate($attachment->created_at),
                    'url' => $attachment->path,
                    'fileName' => self::senitizeAttachmentName($attachment->name, ['/--.*$/']),
                ];
            }
        }
        return $attachmentsDetail;
    }

}
