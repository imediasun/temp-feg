<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\FEG\System\FEGSystemHelper;
use App\Library\FEG\System\SyncHelpers;


class FEGJobs
{    
    private static $L;
    private static $possibleAdjustments;
    private static $limit = 1000;
    
    public static function cleanSummaryReports($params = array()) {
        global $__logger;
        $lf = 'CleanUpSummaryReports.log';
        $lp = 'FEGCronTasks/Cleanup Summary';
        
        extract(array_merge(array(
            '_logger' => null,
            'location' => null,
        ), $params));        
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'CleanSummaryReports');
        $params['_logger'] = $L;  
        $__logger = $L;
        
        
        
        $L->log("****************START CLEAN SUMMARY ");
        $q = "DELETE FROM report_locations WHERE record_status = 0 " .
            (empty($location) ? "" : " AND location_id in ($location)");
        
        $L->log("Delete inactive locations report");
        $L->log($q);
        $count = DB::delete($q);
        $L->log("Deleted $count");

        $q = "DELETE FROM report_game_plays WHERE record_status = 0" .
            (empty($location) ? "" : " AND location_id in ($location)");
        
        $L->log("Delete inactive game play reports");
        $L->log($q);
        $count = DB::delete($q);
        $L->log("Deleted $count");        
        
        $q = "DELETE FROM report_locations
                USING report_locations
                JOIN location ON location.id=report_locations.location_id
                WHERE report_locations.date_played < location.date_opened
                AND location.date_opened IS NOT NULL 
                AND location.date_opened <> '0000-00-00'" .
            (empty($location) ? "" : " AND report_locations.location_id in ($location)");
        
        $L->log("Delete unwanted (beyond open date) Location reports");
        $L->log($q);
        $count = DB::delete($q);
        $L->log("Deleted $count");
        
        
        $q = "DELETE FROM report_game_plays
                USING report_game_plays
                JOIN location ON location.id=report_game_plays.location_id
                WHERE report_game_plays.date_played < location.date_opened
                AND location.date_opened IS NOT NULL 
                AND location.date_opened <> '0000-00-00'" .
            (empty($location) ? "": " AND report_game_plays.location_id in ($location)");
        
        $L->log("Delete unwanted (beyond Location open date) Game Play reports");
        $L->log($q);
        $count = DB::delete($q);
        $L->log("Deleted $count");
        
        
        $q = "DELETE FROM report_game_plays USING report_game_plays 
                    JOIN game ON game.id=report_game_plays.game_id 
                        WHERE report_game_plays.date_played < game.date_in_service 
                            AND game.date_in_service <> '0000-00-00' 
                            AND report_game_plays.game_revenue IS NULL" .
            (empty($location) ? "": " AND report_game_plays.location_id in ($location)");
        
        $L->log("Delete unwanted (beyond Game open date) Game Play reports");
        $L->log($q);
        $count = DB::delete($q);
        $L->log("Deleted $count");
        $L->log("**************** END CLEAN SUMMARY ");
    }
    
    public static function findDuplicateTransferredEarnings($params=array()) {
        $lfu = 'findDuplicateTransferredEarnings-updates.log';
        $lfd = 'findDuplicateTransferredEarnings-deletes.log';
        $lf = 'findDuplicateTransferredEarnings.log';
        $lp = 'FEGCronTasks/DuplicateTransferredEarnings';
        extract(array_merge(array(
            '_logger' => null
        ), $params));        
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'EARNINGS');
        $params['_logger'] = $L;
        
        $L->log("***************************** START FIND DUPLICATE ********************************");
        FEGSystemHelper::logit("***************************** START FIND DUPLICATE ********************************", $lf, $lp);
        
        $endDate = "2014-12-15"; //[2017-01-03 10:32:46] 2014-12-15, 30007444_103000146A00, ERP: 2, TEMP: 1
        $endDateValue = strtotime($endDate);
        $startDate = DB::table('game_earnings')->orderBy('date_start', 'desc')->take(1)->value('date_start');
        $startDateValue = strtotime($startDate);
        $date = date("Y-m-d", $startDateValue);
        $dateValue = strtotime($date);
        $L->log("Start: $startDate, End: $endDate => going backwards");
        FEGSystemHelper::logit("Start: $startDate, End: $endDate => going backwards", $lf, $lp);
        while ($dateValue >= $endDateValue) {
            
            $L->log("DATE: $date");
            
            $q = "SELECT loc_id, game_id, reader_id, group_concat(id) as ids, 
                    count(game_id) recordCount 
                FROM game_earnings 
                WHERE date_start >= '$date' 
                    AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                GROUP BY loc_id, game_id, reader_id;
                    ";
            DB::statement("SET SESSION group_concat_max_len = 1000000;");
            $dataERP = DB::select($q);
            $data = array();            
            foreach($dataERP as $row) {
                $key = $row->loc_id."::".$row->game_id."::".trim($row->reader_id);
                $data[$key] = array('count' => $row->recordCount, 'ids' => $row->ids);
            }
            $dataERP = null;
            DB::connection('sacoa_sync')->statement("SET SESSION group_concat_max_len = 1000000;");
            $dataSacoaTemp = DB::connection('sacoa_sync')->select($q);
            $dataTemp = array();
            foreach($dataSacoaTemp as $row) {
                $key = $row->loc_id."::".$row->game_id."::".trim($row->reader_id);
                $dataTemp[$key] = array('db' => 'sacoa_sync', 'count' => $row->recordCount, 'ids' => $row->ids);
            }
            $dataSacoaTemp = null;
            DB::connection('embed_sync')->statement("SET SESSION group_concat_max_len = 1000000;");
            $dataEmbedTemp = DB::connection('embed_sync')->select($q);
            foreach($dataEmbedTemp as $row) {
                $key = $row->loc_id."::".$row->game_id."::".trim($row->reader_id);
                $dataTemp[$key] = array('db' => 'embed_sync', 'count' => $row->recordCount, 'ids' => $row->ids);
            }
            $dataEmbedTemp = null;            
            
            foreach($data as $key => $erpItem) {
                $erpCount = $erpItem['count'];
                $erpIds = $erpItem['ids'];
                $keyReadable = str_replace("::", ',', $key);
                
                if (isset($dataTemp[$key])) {
                    $tempItem = $dataTemp[$key];
                    $tempDB = $tempItem['db'];
                    $tempIds = $tempItem['ids'];
                    $tempCount = $tempItem['count'];
                    
                    if ($erpCount > $tempCount) {
                        $log = "$date, $keyReadable, ERP: $erpCount, TEMP: $tempCount, ERPIDS: $erpIds, TEMPIDS: $tempIds";
                        FEGSystemHelper::logit($log, $lf, $lp);
                        $L->log($log);
                        
                        
                        $log = "DELETING FROM ERP, $date, $keyReadable, count: $erpCount, IDS: $erpIds";
                        FEGSystemHelper::logit($log, $lfu, $lp);
                        
                        $q = "DELETE FROM game_earnings WHERE id in ($erpIds)";
                        DB::delete($q);
                        
                        $tq = "SELECT 
                                debit_type_id,
                                loc_id,
                                game_id,
                                trim(both '\t' from trim(both ' ' from reader_id)) as reader_id,
                                play_value,
                                total_notional_value,
                                std_plays,
                                std_card_credit,
                                std_card_credit_bonus,
                                std_actual_cash,
                                std_card_dollar,
                                std_card_dollar_bonus,
                                time_plays,
                                time_play_dollar,
                                time_play_dollar_bonus,
                                product_plays,
                                service_plays,
                                courtesy_plays,
                                date_start,
                                date_end,
                                ticket_payout,
                                ticket_value,
                                loc_game_title
                        FROM game_earnings WHERE id IN($tempIds)";
                        DB::connection($tempDB)->setFetchMode(PDO::FETCH_ASSOC); 
                        $tempData = DB::connection($tempDB)->select($tq);
                        if (!empty($tempData)) {
                            DB::table('game_earnings')->insert($tempData);
                            $log = "RESYNC FROM TEMP, $date, $keyReadable, count: $tempCount, IDS: $tempIds";
                            FEGSystemHelper::logit($log, $lfu, $lp);
                        }
                        DB::connection($tempDB)->setFetchMode(PDO::FETCH_CLASS); 
                        
                    }
                    unset($dataTemp[$key]);                        
                }
                else {
                    $log = "$date, $keyReadable, ERP: $erpCount, TEMP: NONE, ERPIDS: $erpIds, TEMPIDS: NONE";
                    FEGSystemHelper::logit($log, $lf, $lp);
                    $L->log($log);                    
                    
                    $log = "DELETING FROM ERP ONLY, $date, $keyReadable, count: $erpCount, IDS: $erpIds";
                    FEGSystemHelper::logit($log, $lfd, $lp);                    
                    $q = "DELETE FROM game_earnings WHERE id in ($erpIds)";
                    DB::delete($q);
                    
                }
                unset($data[$key]);                
            }
            
            $dateValue = strtotime($date.' -1 day');
            $date = date("Y-m-d", $dateValue);
            //break;
        }
        
        
        FEGSystemHelper::logit("***************************** END FIND DUPLICATE ********************************", $lf, $lp);
        $L->log("***************************** END FIND DUPLICATE ********************************");
    }
    
    public static function findMissingTransferredEarnings($params=array()) {
        global $_scheduleId;
        global $__logger;
        $lfu = 'findMissingTransferredEarnings-updates.log';
        $lfd = 'findMissingTransferredEarnings-deletes.log';
        $lf = 'findMissingTransferredEarnings.log';
        $lp = 'FEGCronTasks/MissingTransferredEarnings';
        extract(array_merge(array(
            '_logger' => null
        ), $params));        
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'EARNINGS');
        $params['_logger'] = $L;
        
        $L->log("***************************** START FIND MISSING ********************************");
        FEGSystemHelper::logit("***************************** START FIND MISSING ********************************", $lf, $lp);
        
        $endDate = "2014-12-15"; //[2017-01-03 10:32:46] 2014-12-15, 30007444_103000146A00, ERP: 2, TEMP: 1
        $endDateValue = strtotime($endDate);
        $startDate = DB::table('game_earnings')->orderBy('date_start', 'desc')->take(1)->value('date_start');
        $startDateValue = strtotime($startDate);
        $date = date("Y-m-d", $startDateValue);
        $dateValue = strtotime($date);
        $L->log("Start: $startDate, End: $endDate => going backwards");
        FEGSystemHelper::logit("Start: $startDate, End: $endDate => going backwards", $lf, $lp);
        $sessionLog[] = "Job initiated - Start: $startDate, End: $endDate => going backwards";
        FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);
        
        $totalCount = 0;
        while ($dateValue >= $endDateValue) {
            
            $L->log("DATE: $date");
            $sessionLog = array();
            $sessionLog[] = "Working on date - $date";
            FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);
            
            $q = "SELECT loc_id, count(game_id) recordCount 
                FROM game_earnings 
                WHERE date_start >= '$date' 
                    AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                GROUP BY loc_id;
                    ";
            
            $dataSacoaTemp = DB::connection('sacoa_sync')->select($q);
            $dataTemp = array();
            foreach($dataSacoaTemp as $row) {
                $key = $row->loc_id;
                $dataTemp[$key] = array('db' => 'sacoa_sync', 'count' => $row->recordCount);
            }
            $dataSacoaTemp = null;
            
            $dataEmbedTemp = DB::connection('embed_sync')->select($q);
            foreach($dataEmbedTemp as $row) {
                $key = $row->loc_id;
                $dataTemp[$key] = array('db' => 'embed_sync', 'count' => $row->recordCount);
            }
            $dataEmbedTemp = null;            
            $dataERP = DB::select($q);
            $data = array();            
            foreach($dataERP as $row) {
                $key = $row->loc_id;
                $data[$key] = array('count' => $row->recordCount);
            }
            $dataERP = null;
            
            foreach($dataTemp as $key => $tempItem) {
                $tempCount = $tempItem['count'];
                $keyReadable = str_replace("::", ',', $key);
                $tempDB = $tempItem['db'];
                
                if (!isset($data[$key])) {
                    $totalCount++;
                    $erpItem = $data[$key];
                    $erpCount = $data['count'];
                    $log = "$totalCount.), Need to retransfer, Date, $date, Location, $key, from, $tempDB, ($tempCount records)";
                    FEGSystemHelper::logit($log, $lf, $lp);
                    $L->log($log);
                    FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $log);                    
                    SyncHelpers::recordMissingEarningsData($date, $key);
                    unset($dataTemp[$key]);                        
                }
                else {
                    
                }
                unset($data[$key]);                
            }
            
            $dateValue = strtotime($date.' -1 day');
            $date = date("Y-m-d", $dateValue);
            //break;

            $terminateSignal = FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1;
            if ($terminateSignal) {
                $errorMessage = "User Terminated";
                \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                exit();
                break;
            }            
        }
        
        $sessionLog = "Job ended - $totalCount items added to retry sync request.";
        FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);
        
        FEGSystemHelper::logit($sessionLog);
        $L->log($sessionLog);
        FEGSystemHelper::logit("***************************** END FIND MISSING ********************************", $lf, $lp);
        $L->log("***************************** END FIND MISSING ********************************");
    }
    
    
    public static function generateMissingDatesForSummary($params = array()) {
        global $_scheduleId;
        global $__logger;
        extract(array_merge(array(
            '_logger' => null,
            'reverse' => 1,
            'chunkSize' => 50,
        ), $params));
        $lf = 'generateMissingDatesForSummary.log';
        $lp = 'FEGCronTasks/Generate Missing Dates in Summary';
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'SummaryReportDates');
        $params['_logger'] = $__logger = $L;  
        
        $timeStart = microtime(true);
        
        $q = "select date_format(max(date_played), '%Y-%m-%d') as maxd, 
            date_format(min(date_played), '%Y-%m-%d') as mind, 
            datediff(max(date_played), min(date_played)) as ndays
            from report_game_plays WHERE record_status=1 AND report_status=0";
        
        $data = DB::select($q);
        if (!empty($data)) {
            $max = $data[0]->maxd;
            $min = $data[0]->mind;
            $count = $data[0]->ndays;
        }
        
        if (empty($min) || empty($max)) {
            $L->log("No date range specified.");
            return "No date range specified.";
        }
        
        $L->log("---------------------------------------------------------------------------------------");   
        $L->log("From {$max} to {$min}");   
        $params['date_start'] = $min;
        $params['date_end'] = $max;
        $params['count'] = $count;
        $params['reverse'] = $reverse;

        if ($reverse == 1) {
            $dateStartTimestamp = strtotime($min);
            $dateEndTimestamp = strtotime($max);
            $currentDate = $dateEndTimestamp;
            $date = $max; 
            $dateCount = 1;
            while($currentDate >= $dateStartTimestamp) {
                $sessionLog = array();
                $sessionLog[] = "Start processing: $date ($dateCount/$count days) [Schedule id: $_scheduleId]";
                
                $L->log("DATE: $date ($dateCount/$count days)");
                $L->log("Start finding missing date for locations - $date");
                $result = SyncHelpers::generateMissingDatesForLocationSummary($date);
                $terminateSignal = FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1;
                if (!$result || $terminateSignal) {
                    $errorMessage = "User Terminated";
                    \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                    \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                    \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                    \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                    FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                    exit();
                    break;
                }
                $L->log("END finding missing date for locations - $date");
                
                $L->log("START finding missing date for game plays - $date");
                $result = SyncHelpers::generateMissingLocationAndDatesForGamePlaySummary($date, $chunkSize);
                $terminateSignal = FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1;
                if (!$result || $terminateSignal) {
                    $errorMessage = "User Terminated at games report";
                    \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                    \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                    \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                    \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                    FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                    exit();
                    break;
                }
                
                $timeEnd = microtime(true);
                $timeDiff = round($timeEnd - $timeStart);
                $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);                                
                $sessionLog[] = "Completed processing $date";
                $sessionLog[] = "Time passed: $timeDiffHuman ";
                FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);
        
                $L->log("END finding missing date for game plays - $date");

                $currentDate = strtotime($date . " -1 day");
                $date = date("Y-m-d", $currentDate);
                $dateCount++;
            }
            
        }        
        else  {
            $dateStartTimestamp = strtotime($min);
            $dateEndTimestamp = strtotime($max);
            $currentDate = $dateStartTimestamp;
            $date = $min; 
            $dateCount = 1;
            while($currentDate <= $dateEndTimestamp) {
                $sessionLog = array();
                $sessionLog[] = "Start processing: $date ($dateCount/$count days) [Schedule id: $_scheduleId]";
                
                $L->log("DATE: $date ($dateCount/$count days)");
                $L->log("Start finding missing date for locations - $date");
                $result = SyncHelpers::generateMissingDatesForLocationSummary($date);
                if (!$result) {
                    $errorMessage = "User Terminated";
                    \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                    \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                    \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                    \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                    FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                    exit();
                    break;
                }                
                $L->log("END finding missing date for locations - $date");
                $L->log("Start finding missing date for game plays - $date");
                $result = SyncHelpers::generateMissingLocationAndDatesForGamePlaySummary($date, $chunkSize);
                if (!$result) {
                    $errorMessage = "User Terminated at games report";
                    \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                    \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                    \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                    \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                    FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                    exit();
                    break;
                }                
                
                $timeEnd = microtime(true);
                $timeDiff = round($timeEnd - $timeStart);
                $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);                                
                $sessionLog[] = "Completed processing for $date";
                $sessionLog[] = "Time passed: $timeDiffHuman ";
                FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);
                
                $L->log("END finding missing date for game plays - $date");    

                $currentDate = strtotime($date . " +1 day");
                $date = date("Y-m-d", $currentDate);
                $dateCount++;
            }
            
        }        
                
        $timeEnd = microtime(true);
        $timeDiff = round($timeEnd - $timeStart);
        $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);
        $timeTaken = "Time taken: $timeDiffHuman ";
        $L->log($timeTaken);        
        $L->log("END generateMissingDatesForSummary");
        FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, "Completed all. $timeTaken");
        return $timeTaken;
    }
    
    public static function bulkDailyTransfer($params=array()) {
        global $__logger;
        global $_scheduleId;
        $lf = 'BulkDailyTransfer.log';
        $lp = 'FEGCronTasks/BulkDailyTransfer';        
        extract(array_merge(array(
            'startDate' => null,
            'endDate' => null,           
            'skipAdjustmentMeta' => 0,
            'chunkSize' => 500,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'BulkDaily');
        $params['_logger'] = $L;  
        $__logger = $L;
        
        $max = !empty($dateEnd);
        $min = !empty($dateStart);
        if (empty($max) || empty($min)) {
            $L->log("NO date range given. ENDING.");
            return;            
        }
        
        $L->log("Start Loop");
        $timeStart = microtime(true);
        
        $dateStartTimestamp = strtotime($min);
        $dateEndTimestamp = strtotime($max);
        $currentDate = $dateStartTimestamp;
        $date = $min; 
        $dateCount = 1;
        
        if ($skipAdjustmentMeta != 1) {
        $L->log("Sync and Clean earnings adjustment meta");
        $aParams = array_merge($params, array("date" => $min));
        $result = \App\Library\SyncFromOldLiveHelpers::syncGameEarningsAdjMetaFromLive($aParams);
        if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
            $errorMessage = "User Terminated befor transfer of $date";
            \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
            \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
            \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
            \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
            FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
            exit();
        }                
        }

        while($currentDate <= $dateEndTimestamp) {
            
            $cParams = array_merge($params, array('date' => $date));
            $sessionLog = array();
            $sessionLog[] = "Start transfer: $date ({$dateCount}th day) [Schedule id: $_scheduleId]";

            $L->log("DATE: $date ({$dateCount}th day)");
            $L->log("END Clean adjustment meta");
            $L->log("Start Transfer");
            $result = SyncHelpers::transferEarnings($cParams);
            $L->log("End transfer");    
            if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
                $errorMessage = "User Terminated after transfer of $date";
                \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                exit();
                break;
            }                
            $sessionLog[] = "Completed transfer for $date";
            $L->log("Start Summary");    
            $sessionLog[] = "Start Summary: $date ({$dateCount}th day) [Schedule id: $_scheduleId]";
            $result = SyncHelpers::generateDailySummary($cParams);
            $L->log("End Summary");    
            $sessionLog[] = "Completed Summary for $date";
            if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
                $errorMessage = "User Terminated after summary of $date";
                \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                \App\Library\Elm5Tasks::logScheduleFatalError($errorMessage, $_scheduleId);
                \App\Library\Elm5Tasks::log("User force-termimated task with schedule ID: $_scheduleId");
                FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $errorMessage);
                exit();
                break;
            }            

            $timeEnd = microtime(true);
            $timeDiff = round($timeEnd - $timeStart);
            $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);                                
            $sessionLog[] = "Completed transfer for $date";
            $sessionLog[] = "Time passed: $timeDiffHuman ";
            FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, $sessionLog);


            $currentDate = strtotime($date . " +1 day");
            $date = date("Y-m-d", $currentDate);
            $dateCount++;
        }
        $L->log("End Loop");
                
        $timeEnd = microtime(true);
        $timeDiff = round($timeEnd - $timeStart);
        $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);
        $timeTaken = "Time taken: $timeDiffHuman ";
        $L->log($timeTaken);        
        $L->log("END generateMissingDatesForSummary");
        FEGSystemHelper::session_put('status_elm5_schedule_'.$_scheduleId, "Completed all. $timeTaken");
        return $timeTaken;        
        
    }
    
}
