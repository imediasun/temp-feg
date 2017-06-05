<?php

namespace App\Library\FEG\System;

use Event;
use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\DBHelpers;
use App\Library\FEG\System\FEGSystemHelper;

class SyncHelpers
{    
    public static function transferEarnings($params = array()) {              
        global $__logger;
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('now -1 day')),
            'location' => null,
            'debit_type' => null,
            'chunkSize' => 500,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        $debitTypeName = self::getDebitTypeName($debit_type);
        $logData = " for $date ". 
                (empty($debit_type)? "" : ", Debit Type: $debitTypeName").
                (empty($location)? "" : ", Location: $location");
        $__logger = $_logger;
        $__logger->log("Start Earnings Transfer $logData");
        
        self::deleteEarningsGeneric($date, $location);
        
        if (!empty($location)) {
            $debitTypeId = self::getLocationDebitType($location);
            self::TransferEarningsGeneric($debitTypeId, $date, $location, $chunkSize);
        }
        else {
            self::TransferEarningsGeneric(1, $date, $location, $chunkSize);
            self::TransferEarningsGeneric(2, $date, $location, $chunkSize);              
        }
        
        $__logger->log("Record Missing Earnings details $logData");
        self::reportMissingEarningsData($date, $location, $debit_type);
        $__logger->log("Record Missing Asset IDs $logData");
        self::recordMissingAssetIds($date, $location, $debit_type);
        $__logger->log("Record Unknown Asset IDs $logData");
        self::recordUnknownAssetIds($date, $location, $debit_type);
        $__logger->log("Record Missing Readers $logData");
        self::recordMissingReaders($date, $location, $debit_type);
        $__logger->log("Record Unknown Readers $logData");
        self::recordUnknownReaders($date, $location, $debit_type);
            
        $__logger->log("End Earnings Transfer $logData");

    }  
    
    public static function retryTransferEarnings($params = array()) {
        global $__logger;
        $table = "game_earnings_transfer_adjustments";
        extract(array_merge(array(
            'chunkSize' => 500,
            'date' => null,
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        $__logger = $_logger;
        $originalDate = $date;
        $originalDatestamp = strtotime($originalDate);
        $__logger->log("Start finding pending transfers");
        $q = "SELECT * from $table WHERE status=1";
        $data = DB::select($q);
        if (!empty($data)) {
            $__logger->log("Found pending transfers - start retry");
            foreach($data as $item) {
                $location = $item->loc_id;
                $date = $item->date_start;
                $datestamp = strtotime($date);
                if (!empty($originalDate) && $datestamp > $originalDatestamp) {
                    $__logger->log("Skipping retry for $date ($location) as the retry date is in future to the given date $originalDate");
                    continue;
                }

                $logData = " for $date ". 
                    (empty($location)? "" : ", Location: $location");
                $__logger = $_logger;
                $__logger->log("Start Retry Earnings Transfer $logData");

                $debitTypeId = self::getLocationDebitType($location);
                if ($debitTypeId == '') {
                    $isValidZeroSync = true;
                }
                else {
                    $isValidZeroSync = self::check_valid_zero_sync($date, $location, $debitTypeId);
                }
                $hasSyncData = self::hasSyncData($date, $location);

                if ($isValidZeroSync) {
                    $__logger->log("Valid Zero Sync - location was closed: $logData");
                    self::syncedMissingEarningsData($date, $location, "CLOSED");
                }
                elseif ($hasSyncData) {
                    $__logger->log("Pending Data found: $logData");
                    $__logger->log("Delete existing data: $logData");
                    self::deleteEarningsGeneric($date, $location);
                    $__logger->log("Transfer data on retry: $logData");
                    $count = self::TransferEarningsGeneric($debitTypeId, $date, $location, $chunkSize);

                    self::generateDailySummary(array_merge($params, array(
                                'date' => $date,
                                'location' => $location,
                            )));

                    $__logger->log("Update sync status of pending Earnings: $logData");
                    self::syncedMissingEarningsData($date, $location);

                    $__logger->log("Retry: Record Missing Asset IDs $logData");
                    self::recordMissingAssetIds($date, $location);
                    $__logger->log("Retry: Record Unknown Asset IDs $logData");
                    self::recordUnknownAssetIds($date, $location);
                    $__logger->log("Retry: Record Missing Readers $logData");
                    self::recordMissingReaders($date, $location);
                    $__logger->log("Retry: Record Unknown Readers $logData");
                    self::recordUnknownReaders($date, $location);

                    $__logger->log("Transferred data on retry:  $logData");
                }            
                else {
                    $__logger->log("No Data found yet for transfer:  $logData");
                }
                $__logger->log("End Retry Earnings Transfer $logData");
            }            
        }
        else {
            $__logger->log("No pending transfers found");
        }
                
    }
    
    
    public static function check_valid_zero_sync($date, $location = "", $debitType = 1) {
        
        //success_code, sync_not_required, retry_count, fegtransfer_required 
        $isValidZeroSync = false;
        $sourceDBName = self::getDebitTypeDBName($debitType);

        $q = "SELECT sync_not_required, success_code 
            FROM PastSyncDetails 
            WHERE
            location_id= '$location' AND 
            date_start >= '$date' AND 
            date_start < DATE_ADD('$date', INTERVAL 1 DAY)                  
                ORDER BY create_date DESC LIMIT 1";
        
        $data = DB::connection($sourceDBName)->select($q);
        $pastSyncRecordedCount = count($data);        
        if ($pastSyncRecordedCount > 0) {
            $row = $data[0];
            $pastSyncNotRequired = $row->sync_not_required;
            $pastSyncSuccess = $row->success_code;
        }
        
        $q = "SELECT sync_not_required, success_code 
                FROM ZeroSyncDetails 
                WHERE 
                location_id= '$location' AND 
                date_start >= '$date' AND 
                date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                
                ORDER BY create_date DESC LIMIT 1 ";
        $data = DB::connection($sourceDBName)->select($q);
        $zeroSyncRecordedCount = count($data);        
        if ($zeroSyncRecordedCount > 0) {
            $row = $data[0];
            $zeroSyncNotRequired = $row->sync_not_required;
            $zeroSyncSuccess = $row->success_code;
        }        
        
        if ($pastSyncRecordedCount > 0) {
            $isValidZeroSync = $pastSyncSuccess == 0 && $pastSyncNotRequired == 1;
        }
        elseif ($zeroSyncRecordedCount > 0) {
            $isValidZeroSync = $zeroSyncSuccess == 0 && $zeroSyncNotRequired == 1;
        }
        
        return $isValidZeroSync;
    }
    
    public static function report_archive_duplicate_game_summary_data($date = "", $location = "") {
        $sql = "UPDATE report_game_plays SET record_status=0 WHERE date_played='$date'" . (empty($location) ? "" : " AND location_id IN ($location)");
        return DB::update($sql);
    }

    public static function report_archive_duplicate_location_summary_data($date = "", $location = "") {
        $sql = "UPDATE report_locations SET record_status=0 WHERE date_played='$date'" . (empty($location) ? "" : " AND location_id IN ($location)");
        return DB::update($sql);        
    }
    
    public static function report_daily_location_summary($params = array()) {
        global $__logger;
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('now -1 day')),
            'location' => null,
            'skipLastValidityCheckOfPlayDate' => 0,
            'skipLastPlayedDetection' => 0,
            'skipZeroAssetIds' => false,
            '_task' => array(),
            '_logger' => null,
        ), $params));         

        $yesterdayStamp = strtotime('now -1 day');
        $yesterday = date("Y-m-d", $yesterdayStamp);
        $date = date("Y-m-d", strtotime($date));
        $datePlayedStamp = strtotime($date);
        $today = date("Y-m-d");
        $nextDate = date("Y-m-d", strtotime($date." +1 day"));
        $isPastData = $datePlayedStamp < $yesterdayStamp;        
        
        self::report_archive_duplicate_location_summary_data($date, $location);
        
        //
        $sql = "SELECT 
                    L.id AS location_id, 
                    '$date' as date_played," . 
                    ($skipLastPlayedDetection == 1 ? "E.date_last_played," : 
                    "(SELECT max(E.date_played) FROM report_locations E WHERE E.location_id = L.id and E.date_played <= '$date') as date_last_played,").
                    "L.debit_type_id,
                    '$today' as report_date,
                    E.sync_record_count, 
                    G.games_count,
                    E.games_played_count,
                    E.games_revenue,
                    E.games_total_std_plays,
                    L.date_opened,
                    1 as record_status

                FROM location L

                LEFT JOIN  (
                SELECT 	loc_id, 
                        debit_type_id, 
                        '$date' as date_last_played,
                        COUNT(*) AS sync_record_count, 
                        SUM(CASE WHEN debit_type_id = 1 THEN total_notional_value ELSE std_actual_cash END) AS games_revenue, 
                        SUM(std_plays) AS games_total_std_plays,
                        COUNT(DISTINCT game_id) AS games_played_count
                    FROM game_earnings 
                    WHERE date_start>='$date' 
                        AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                        " . (!!$skipZeroAssetIds ? " AND game_id <> 0 ":"") . "
                    GROUP BY loc_id
                ) E ON L.id = E.loc_id 

                LEFT JOIN  (
                    SELECT location_id, COUNT(*) AS games_count FROM game GROUP BY location_id
                ) G ON G.location_id = L.id 

                WHERE " . (empty($location) ? "L.reporting = 1" : "L.id IN ($location)");

        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        $data = DB::select($sql);
        if (!empty($data)) {
            DB::beginTransaction();
            foreach ($data as $row) {
                $revenue = $row['games_revenue'];
                $dateOpened = $row['date_opened'];
                $dateOpenedstamp = strtotime($dateOpened);
                $row['report_status'] = (is_null($revenue) || empty($revenue) || $revenue === "0") ? 0 : 1;
                if ($skipLastPlayedDetection != 1) {
                    if (!empty($dateOpenedstamp) && $dateOpenedstamp > 0) {
                        $row['date_last_played'] = $dateOpened;
                    }
                }
                $validData = true;
                if ($skipLastValidityCheckOfPlayDate != 1) {
                    $validData = $datePlayedStamp >= $dateOpenedstamp;
                }
                if ($validData) {
                    unset($row['date_opened']);
                    DB::table('report_locations')->insert($row);
                    $row = null;
                }
                
            }
            DB::commit();            
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
    }
    
    public static function generateMissingDatesForLocationSummary($date) {
        if (empty($date)) {
            return true;
        }
        global $_scheduleId;
        global $__logger;
        $L = $__logger;
        $lf = 'GenerateMissingDates.log';
        $lp = 'FEGCronTasks/GenerateMissingDates';        
        if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
            FEGSystemHelper::logit("Location Last Played date USER TERMINATED !!!!", $lf, $lp);
            return false;
        }
        $dateValue = strtotime($date);
        $updateSQL = "UPDATE report_locations SET date_last_played=? WHERE id=?";
        
        $q = "SELECT id, location_id 
            FROM report_locations 
            WHERE date_played='$date' 
                AND record_status=1 AND report_status = 0"; 
                //AND record_status=1 AND report_status = 0 AND date_last_played IS NULL"; 
            
        $items = DB::select($q);
        DB::beginTransaction();
        
        FEGSystemHelper::logit("--------------------------------------------- Finding Last Played date of closed locations for $date ------------------------------", $lf, $lp);        
        foreach($items as $item) {
            
            if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
                FEGSystemHelper::logit("LLP USER TERMINATED !!!! - rolling back", $lf, $lp);
                DB::rollBack();
                return false;
            }            

            $foundLastPlayed = null;
            $id = $item->id;
            $location = $item->location_id;            
            
            FEGSystemHelper::logit("    Location: $location: Search LAST PLAYED DATE from Report Locations", $lf, $lp);                    
            $Q = "SELECT max(date_played) as dateLastPlayed 
                FROM report_locations 
                WHERE location_id =$location 
                    AND date_played <= '$date' 
                    AND report_status=1
                    AND record_status=1";
            
            $data = DB::select($Q);
            
            if (empty($data)) {
                FEGSystemHelper::logit("        --!!  NOT FOUND date in Report Locations", $lf, $lp);                
                $foundLastPlayed = DB::table('location')
                        ->where('id', $location)->value('date_opened');
                FEGSystemHelper::logit("            -- HENCE -- FOUND LOCATION date WHEN OPENED: '$foundLastPlayed'", $lf, $lp);
            }
            else {
                $row = $data[0];
                $foundLastPlayed = $row->dateLastPlayed;
                FEGSystemHelper::logit("        : : FOUND '$foundLastPlayed' date from Report Locations: $foundLastPlayed", $lf, $lp);
            }
            
            $locationStartDatestamp = strtotime($foundLastPlayed);
            if (empty($locationStartDatestamp) || $locationStartDatestamp < 0 || $locationStartDatestamp > $dateValue) {
                $foundLastPlayed = null;
                FEGSystemHelper::logit("            >> '$foundLastPlayed' date is not valid!! Hence setting to null", $lf, $lp);
            }
            
            DB::update($updateSQL, [$foundLastPlayed,$id]);
            FEGSystemHelper::logit("    LLP Update Location Summary's last played for $location with Last Played as $foundLastPlayed\r\n", $lf, $lp);
            
            if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
                FEGSystemHelper::logit("Location Last Played USER TERMINATED !!!! - rolling back", $lf, $lp);
                DB::rollBack();
                return false;
            }             
        }
        DB::commit();
        FEGSystemHelper::logit("------------------------------ [ END Finding Last Played date of closed locations for $date ]", $lf, $lp);        
        return true;
        
    }
    public static function generateMissingLocationAndDatesForGamePlaySummary($date, $chunkSize = 50) {
        global $__logger;
        global $_scheduleId;
        $L = $__logger;        
        $lf = 'GenerateMissingDates.log';
        $lp = 'FEGCronTasks/GenerateMissingDates'; 
        if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
            FEGSystemHelper::logit("Game Play Date Last played USER TERMINATED !!!!", $lf, $lp);
            return false;
        }          
        if (empty($date)) {
            return true;
        }
        FEGSystemHelper::logit("*********************************  SEARCH FOR LAST PLAYED DATES FOR UNPLAYED GAMES on $date ************************************", $lf, $lp);
        $dateValue = strtotime($date);
        $rowcount = 0;
        $chunkCount = 0;
        
//        $q = "SELECT id, game_id, location_id, debit_type_id 
//            FROM report_game_plays 
//            WHERE date_played='$date' 
//                AND record_status=1 AND report_status = 0"; 
            //AND date_last_played IS NULL
            // AND report_status = 0
        $query = DB::table('report_game_plays')
            ->select('id', 'game_id', 'location_id', 'debit_type_id')
            ->whereRaw("date_played='$date' AND record_status=1 AND report_status=0 AND game_status != 3");
            //->whereRaw("date_played='$date' AND record_status=1 AND report_status=0 AND date_last_played IS NULL");
        DB::beginTransaction();
        $result = $query->chunk($chunkSize, 
                function($data)  use ($date, $dateValue, &$rowcount, &$chunkCount){
                    global $_scheduleId;
                    global $__logger;
                    $L = $__logger;
                    $lf = 'GenerateMissingDates.log';
                    $lp = 'FEGCronTasks/GenerateMissingDates'; 
                    
                    try {

                    $updateSQL = "UPDATE report_game_plays SET 
                                date_last_played=?,
                                location_id = ?,
                                debit_type_id = ?
                                WHERE id=?";
                    
                    if (!empty($data)) {
                        
                        $dataSize = count($data);
                        $chunkCount++;
                        $rowcount += $dataSize;
                        $L->log("Game data chunk #$chunkCount of size $dataSize. Total items received so far: $rowcount");    
                        
                        foreach ($data as $item) {
                            
                            if (FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
                                FEGSystemHelper::logit("GLP{CLoZr} USER TERMINATED !!!!", $lf, $lp);
                                return false;
                            }                             
                            $possibleLocation = $foundLocation = null;
                            $foundDebitType = null;
                            $minLocationDatestamp = $minLocationDate = null;
                            $minGameDate = $minGameDatestamp = 
                            $gameMoveStartDate = $gameMoveStartDatestamp = null;
                            $foundDate = $foundDatestamp = $foundLastPlayed = null;
                            $locationFromGameTable = $moveHistoryTop = $moveHistorySubsequent = false;
                             
                            $id = $item->id;
                            $game_id = $item->game_id;
                            $location = $item->location_id;
                            $debitTypeId = $item->debit_type_id;            
                            $gameLog = "[Game: $game_id,  Loc: $location (DebitType: $debitTypeId), Date: $date]";
                            $location = null;
                            
                            FEGSystemHelper::logit("    |||||||||||||||||||||||||   $gameLog  |||||||||||||||||||||||||||||||", $lf, $lp);
                                                        
                            FEGSystemHelper::logit("         --- Search LOCATION in Game Move History", $lf, $lp);
                            if (empty($location)) {
                                                                
                                $q = "select from_loc, to_loc, 
                                    date_format(from_date, '%Y-%m-%d') as from_date
                                    from game_move_history WHERE game_id = $game_id order by from_date ASC";
                                $data = DB::select($q);
                                
                                if (!empty($data)) {                    
                                    foreach ($data as $moveCount => $row) {
                                        $from = $row->from_date;
                                        $tloc = $row->to_loc;
                                        $floc = $row->from_loc;
                                        $fromValue = strtotime($from);
                                        if ($moveCount == 0 && $dateValue < $fromValue) {
                                            $possibleLocation = $floc;  
                                            $moveHistoryTop = true;
                                            FEGSystemHelper::logit("            --- Top History item found location '$floc' where game ran before '$from'", $lf, $lp);
                                            break;
                                        }
                                        if ($dateValue >= $fromValue) {
                                            $possibleLocation = $tloc; 
                                            $moveHistorySubsequent = true;
                                            $gameMoveStartDate = $from;
                                            $gameMoveStartDatestamp = $fromValue;
                                        }
                                    }
                                    if (!empty($possibleLocation) && !$moveHistoryTop) {
                                        FEGSystemHelper::logit("            ---  History item found location '$possibleLocation' where game ran on and after '$gameMoveStartDate'", $lf, $lp);
                                    }
                                }
                
                                // fallback to present day game location 
                                // when no data found from game move history
                                if (empty($possibleLocation)) {                                    
                                    FEGSystemHelper::logit("            !!! >> History item NOT FOUND - LOCATION NOT FOUND", $lf, $lp);
                                    FEGSystemHelper::logit("                << << Get default location from game table", $lf, $lp);
                                    $possibleLocation = DB::table('game')->where('id', $game_id)->value('location_id');
                                    FEGSystemHelper::logit("                    >> >> Default location from game table is '$possibleLocation'", $lf, $lp);
                                    
                                    if (!empty($possibleLocation)) {
                                        $locationFromGameTable = true;
                                    }                    
                                }
                                if (!empty($possibleLocation)) {
                                    $location = $foundLocation = $possibleLocation;
                                }
                            }
            
                            if (!empty($location)) {                
                                $debitTypeId = $foundDebitType = self::getLocationDebitType($location);
                                FEGSystemHelper::logit("    ### Location found: '$location' (debit type: $debitTypeId)", $lf, $lp);
                            }
            
                            if (!empty($location)) {

                                //1: try to find last played date from game earnings
//                                $q = "SELECT date_format(date_start, '%Y-%m-%d') as dateLastPlayed 
//                                        FROM game_earnings
//                                        WHERE
//                                            date_start <= '$date 23:59:59'
//                                            AND game_id IN ($game_id)
//                                            AND loc_id=$location 
//                                        ORDER BY date_start DESC LIMIT 1";
                                FEGSystemHelper::logit("        %%%%%% Game: $game_id: Search LAST PLAYED DATE from Report Game Plays", $lf, $lp);     
                                $q = "select max(date_played) as dateLastPlayed
                                        from report_game_plays 
                                        WHERE game_id=$game_id 
                                            AND location_id=$location
                                            AND date_played <= '$date'
                                            AND report_status=1 
                                            AND record_status=1";
                                $data = DB::select($q);        
                                if (!empty($data)) {
                                    $row = $data[0];
                                    $foundLastPlayed = $row->dateLastPlayed;
                                    $foundDatestamp = strtotime($foundLastPlayed);
                                    FEGSystemHelper::logit("        : : Date '$foundLastPlayed' found in Report Game Plays", $lf, $lp);     
                                }
                                
                                // 1 NOT FOUND: in game earnings -> set game's first date, location's first date
                                if (empty($foundDatestamp) || $foundDatestamp < 0) {
                                    FEGSystemHelper::logit("   << << NOT FOUND date in Report Game Plays. ", $lf, $lp);

                                    // 2: if present in subsequent move history 
                                    if ($moveHistorySubsequent) {
                                        FEGSystemHelper::logit("   >> >> Trying to set game movement (first date in location) date - '$gameMoveStartDate'", $lf, $lp);
                                        if (!empty($gameMoveStartDatestamp) && $gameMoveStartDatestamp > 0 && $gameMoveStartDatestamp <= $dateValue) {
                                            $foundLastPlayed = $gameMoveStartDate;
                                            $foundDatestamp = $gameMoveStartDatestamp; 
                                            FEGSystemHelper::logit("    << << Yes! '$gameMoveStartDate' - first date in location IS the last played date", $lf, $lp);
                                        }
                                    }

                                    // 3. If not found in subsequent move history
                                    // check head move entry - i.e. either game_in_service or location's start date
                                    if (empty($foundDatestamp) || $foundDatestamp < 0) {
                                        FEGSystemHelper::logit("   %% %% Not found in move history - fallback to either location's first date or game's intial date", $lf, $lp);
                                        $minGameDate = DB::table('game')->where('id', $game_id)->value('date_in_service');
                                        FEGSystemHelper::logit("           -- Game's first date ($minGameDate)", $lf, $lp);
                                        $minGameDatestamp = strtotime($minGameDate);
                                        $isMinGameDate = !empty($minGameDatestamp) && $minGameDatestamp > 0 && $minGameDatestamp <= $dateValue;

                                        $minLocationDate = DB::table('location')->where('id', $location)->value('date_opened');
                                        $minLocationDatestamp = strtotime($minLocationDate);
                                        $isMinLocationDate = !empty($minLocationDatestamp) && $minLocationDatestamp > 0 && $minLocationDatestamp <= $dateValue;
                                        FEGSystemHelper::logit("           -- Location's first date ($minLocationDate)", $lf, $lp);

                                        if ($isMinGameDate && $isMinLocationDate) {
                                            $foundDatestamp = max($minGameDatestamp, $minLocationDatestamp);
                                            $foundLastPlayed = date("Y-m-d", $foundDatestamp);
                                            FEGSystemHelper::logit("            -- >> Consider the higher of location and game dates - '$foundLastPlayed'", $lf, $lp);
                                        }
                                        elseif ($isMinGameDate) {
                                            $foundLastPlayed = $minGameDate;
                                            $foundDatestamp = $minGameDatestamp;
                                            FEGSystemHelper::logit("            -- >> Consider the Game start date - '$foundLastPlayed'", $lf, $lp);
                                        }
                                        elseif($isMinLocationDate) {
                                            $foundLastPlayed = $minLocationDate;
                                            $foundDatestamp = $minLocationDatestamp;                                      
                                            FEGSystemHelper::logit("            -- >> Consider the location's start date - '$foundLastPlayed'", $lf, $lp);
                                        }
                                    }    
                                }
                            }   
                            
                            if (!empty($location)) {   
                                FEGSystemHelper::logit("    FINAL: DB UPDATED [game: $game_id], '$foundLocation'(debit type: $foundDebitType) date: '$foundLastPlayed' \r\n", $lf, $lp);
                                DB::update($updateSQL, [$foundLastPlayed, $foundLocation, $foundDebitType, $id]);                            
                            }
                            else {
                                FEGSystemHelper::logit("    FINAL: [game: $game_id] Location not found hence skipping [DATE: '$foundLastPlayed', LOC: '$foundLocation' ($foundDebitType), DBID: $id]\r\n", $lf, $lp);
                            }
                            
                        }
                    }                    
                    } 
                    catch (Exception $ex) {
                        $errorFile = $ex->getFile();
                        $errorLine = $ex->getLine();                
                        $errorMessage = $ex->getMessage() . " - $errorFile at line $errorLine";
                        \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                        \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                        \App\Library\Elm5Tasks::log("Error: ".$errorMessage);
                        $L->log($errorMessage);
                        exit();
                    }
                    
                }
            );  
                
        if (!$result || FEGSystemHelper::session_pull("terminate_elm5_schedule_$_scheduleId") == 1) {
            FEGSystemHelper::logit("GLP USER TERMINATED !!!! - rolling back", $lf, $lp);
            DB::rollBack();
            return false;
        }          
        DB::commit();
        
        FEGSystemHelper::logit("*********************************  [ END ] SEARCH FOR LAST PLAYED DATES FOR UNPLAYED GAMES on $date ", $lf, $lp);
        
        return true;
    }

    public static function report_daily_game_summary($params = array()) {
        global $__logger;
        $chunkSize = 500;
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('now -1 day')),
            'location' => null,
            'skipLastValidityCheckOfPlayDate' => 0,
            'skipLastPlayedDetection' => 0,
            '_task' => array(),
            '_logger' => null,
        ), $params));         
        
        $qL = new MyLog('report_daily_game_summary.log', 'generated-sql', 'SQL');

        
        $table = "report_game_plays";
        
        $date = date("Y-m-d", strtotime($date));
        $today = date("Y-m-d");
        $todayStamp = strtotime($today);
        
        $yesterdayStamp = strtotime('now -1 day');
        $yesterday = date("Y-m-d", $yesterdayStamp);
        
        self::report_archive_duplicate_game_summary_data($date, $location);

        $query = DB::table('game')
                    ->select(DB::raw("game.id AS game_id, 
                                '$date' as date_played,
                                E.date_last_played,
                                
                                E.total_plays,
                                E.actual_cash,
                                E.card_cash,
                                E.card_bonus,
                                E.time_plays,
                                E.product_plays,
                                E.product_notional_value,
                                E.courtesy_plays,
                                E.product_and_courtesy_plays,
                                E.grand_total,
                                E.location_id as location_id,
                                E.debit_type_id as debit_type_id,
                    
                                E.game_revenue,
                                E.game_std_plays,
                                location.id as llocation_id,
                                game.prev_location_id,
                                game.location_id as glocation_id,                    
                                location.debit_type_id as ldebit_type_id,
                                game.game_title_id,
                                game_title.game_type_id,
                                IF(game.date_sold <> '0000-00-00' AND game.date_sold IS NOT NULL AND game.date_sold <= '$date', 3, game.status_id) as game_status,                                     
                                IF(game.date_sold <> '0000-00-00' AND game.date_sold IS NOT NULL AND game.date_sold <= '$date', 1, 0) as game_is_sold,
                                game.test_piece as game_on_test,
                                game.not_debit as game_not_debit,
                                '$today' as report_date,
                                1 as record_status,
                                '' as notes"))
                    ->leftJoin(DB::raw(" (
                                SELECT 	game_id,
                                        loc_id as location_id,
                                        '$date' as date_played,
                                        '$date' as date_last_played,
                                        debit_type_id, 
                                        
                                        IF(debit_type_id = 1,(SUM(std_card_credit) + SUM(std_card_credit_bonus) + SUM(courtesy_plays)),SUM(std_plays)) AS total_plays,
                                        SUM(std_actual_cash) AS actual_cash,
                                        IF(debit_type_id = 1,SUM(std_card_credit * 1),SUM(std_card_dollar)) AS card_cash,
                                        IF(debit_type_id = 1,SUM(std_card_credit_bonus * 1),SUM(std_card_dollar_bonus)) AS card_bonus,
                                        IF(debit_type_id = 1,(SUM(std_actual_cash)+
                                                                SUM(std_card_credit * 1)+
                                                                SUM(std_card_credit_bonus * 1)
                                                            ),
                                                               (SUM(std_actual_cash)+
                                                                SUM(std_card_dollar)+
                                                                SUM(std_card_dollar_bonus))
                                                            ) AS CardTotal,
                                        SUM(time_plays) AS time_plays,
                                        SUM(product_plays) AS product_plays,
                                        ROUND(SUM(product_plays * (std_card_dollar / std_plays)),2) AS product_notional_value,
                                        SUM(courtesy_plays) AS courtesy_plays,
                                        SUM(product_plays + courtesy_plays) AS product_and_courtesy_plays,
                                        ROUND(SUM(CASE WHEN debit_type_id = 1 THEN total_notional_value ELSE std_actual_cash END
                                        ),2) AS grand_total,   

                                        SUM(CASE WHEN debit_type_id = 1 THEN total_notional_value ELSE std_actual_cash END) AS game_revenue, 
                                        SUM(std_plays) AS game_std_plays
                                    FROM game_earnings 
                                    WHERE date_start>='$date' 
                                        AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                                        AND game_id <> 0 
                                    GROUP BY game_id
                                ) E"), 'E.game_id', '=', 'game.id')
                    ->leftJoin('location', 'location.id', '=', 'game.location_id')
                    ->leftJoin('game_title', 'game_title.id', '=', 'game.game_title_id')
                    ->whereRaw(empty($location) ? "location.reporting = 1 OR E.date_played IS NOT NULL" : "location.id IN ($location)");
                
//        $sql = $query->toSql();
//        $qL->log("Date for query: $date");
//        $qL->log($sql);
        
        $rowcount = 0;
        $chunkCount = 0;
        $insertArray = array();
        $insertCount = 0;
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        $query->chunk($chunkSize, 
                function($data)  use ($date, $yesterdayStamp, &$rowcount, &$chunkCount, &$insertCount, &$insertArray, $skipLastPlayedDetection, $skipLastValidityCheckOfPlayDate){
                    global $__logger;
                    global $_scheduleId;
                    
                    try {                        
                    if (!empty($data)) {
                        $dataSize = count($data);
                        $chunkCount++;
                        $rowcount += $dataSize;
                        //$__logger->log("Data received chunk #$chunkCount of size $dataSize. Total items received so far: $rowcount");        
                        foreach ($data as $row) {
                            $gameStatus = $row['game_status'];
                            $gameSold = $row['game_is_sold'];
                            $game_id = $row['game_id'];
                            $revenue = $row['game_revenue'];       
                            
                            if (empty($revenue) && $gameSold == 1) {
                                continue;
                            }
                            
                            $lastPlayed = $row['date_last_played'];
                            $datePlayedStamp = strtotime($date);
                            $isPastData = $datePlayedStamp < $yesterdayStamp;
                            
                            $row['report_status'] = 1;
                            if (is_null($revenue) || empty($revenue) || $revenue == "0") {
                                $row['report_status'] = 0;
                                if ($revenue == "0") {
                                    $row['notes'] = "NO REVENUE";
                                }
                            }
                            if (empty($row['debit_type_id']) && !empty($row['ldebit_type_id'])) {
                                $row['debit_type_id'] = $row['ldebit_type_id'];
                            }
                            
                            $gameLocationID = 0;
                            $llocation_id = $row['llocation_id'];
                            $glocation_id = $row['glocation_id'];
                            $location_id = $row['location_id'];
                            if (!empty($location_id)) {
                                $gameLocationID = $location_id;
                            }
                            else {
                                if ($isPastData) {
                                    if ($skipLastPlayedDetection != 1) {
                                        $possibleLocation = self::getPossibleHistoricalLocationOfGame($game_id, $date, $glocation_id);
                                    }                                    
                                }
                                else {
                                    $possibleLocation = $glocation_id;
                                }
                                if (!empty($possibleLocation)) {
                                    $gameLocationID = $possibleLocation;
                                }
                                if (!empty($gameLocationID)) {
                                    if ($skipLastPlayedDetection != 1) {
                                        $row['debit_type_id'] = self::getLocationDebitType($gameLocationID);
                                    }                                     
                                }
                            }
                            if ($skipLastPlayedDetection != 1 && empty($lastPlayed)) {
                                $lastPlayed = self::getPossibleLastPlayedDateOfGame($game_id, $date, $gameLocationID);
                                if (!empty($lastPlayed) && strtotime($lastPlayed) > 0) {
                                    $row['date_last_played'] = $lastPlayed; 
                                }
                            }

                            $row['location_id'] = $gameLocationID;
                            
                            unset($row['glocation_id']);
                            unset($row['prev_location_id']);
                            unset($row['llocation_id']);
                            unset($row['ldebit_type_id']); 
                            
                            $validData = true;
                            if ($skipLastValidityCheckOfPlayDate != 1 && $isPastData) {
                                $gameStartDate = strtotime(DB::table('game')->where('id', $game_id)->value('date_in_service'));
                                $locationStartDate = strtotime(DB::table('location')->where('id', $gameLocationID)->value('date_opened'));
                                if (!empty($gameStartDate) && $gameStartDate > 0) {
                                    $validData = $datePlayedStamp >= $gameStartDate;
                                }
                                if (!empty($locationStartDate) && $locationStartDate > 0) {
                                    $validData = $datePlayedStamp >= $locationStartDate;
                                }
                            }
                            if ($validData) {
                                $insertArray[$insertCount] = $row;
                                $insertCount++;
                            }
                        }                                    
                    }  
                    
                    } catch (Exception $ex) {
                        $errorFile = $ex->getFile();
                        $errorLine = $ex->getLine();                
                        $errorMessage = $ex->getMessage() . " - $errorFile at line $errorLine";
                        \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                        \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                        \App\Library\Elm5Tasks::log("Error: ".$errorMessage);
                        exit();
                    }
                    
                              
                });          
                
                                
        DB::beginTransaction();
        foreach($insertArray as $item) {
            DB::table($table)->insert($item);
        }                        
        DB::commit();
        
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
    }
    
     public static function generateDailySummary($params = array()) {
        global $__logger;
        $params = array_merge(array(
            'date' => date('Y-m-d', strtotime('now -1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
            'cleanup' => 1,
        ), $params); 
        extract($params);
        $__logger = $_logger;
                
        $__logger->log("Start Generate Daily LOCATION Summary $date - $location");
        self::report_daily_location_summary($params);
        $__logger->log("END Generate Daily LOCATION Summary $date - $location");
        $__logger->log("Start Generate Daily GAME Summary $date - $location");
        self::report_daily_game_summary($params);
        $__logger->log("END Generate Daily GAME Summary $date - $location");
        if ($cleanup == 1) {
            self::cleanDailyReport($params);
        }        
    }       
     public static function generateDailySummaryDateRange($params = array()) {
        global $__logger;
        extract(array_merge(array(
            'date_start' => null,
            'date_end' => null,
            'count' => 0,
            'reverse' => 0,
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params)); 
        $__logger = $_logger;
        
        if (empty($date_start) || empty($date_end)) {
            $__logger->log("No date range specified.");
            return "No date range specified.";
        }
        
        if ($count == 0) {
            
        }
        
        if ($reverse == 1) {
            $dateStartTimestamp = strtotime($date_start);
            $dateEndTimestamp = strtotime($date_end);
            $currentDate = $dateEndTimestamp;
            $date = $date_end; 
            $dateCount = 1;
            while($currentDate >= $dateStartTimestamp) {
                $__logger->log("DATE: $date ($dateCount/$count days)");
                $cParams = array_merge($params, array("date" => $date));
                $__logger->log("Start Generate Daily LOCATION Summary");
                self::report_daily_location_summary($cParams);
                $__logger->log("END Generate Daily LOCATION Summary");
                $__logger->log("Start Generate Daily GAME Summary");
                self::report_daily_game_summary($cParams);
                $__logger->log("END Generate Daily GAME Summary");            

                $currentDate = strtotime($date . " -1 day");
                $date = date("Y-m-d", $currentDate);
                $dateCount++;
            }
            
        }        
        else  {
            $dateStartTimestamp = strtotime($date_start);
            $dateEndTimestamp = strtotime($date_end);
            $currentDate = $dateStartTimestamp;
            $date = $date_start; 
            $dateCount = 1;
            while($currentDate <= $dateEndTimestamp) {
                $__logger->log("DATE: $date ($dateCount/$count days)");
                $cParams = array_merge($params, array("date" => $date));
                $__logger->log("Start Generate Daily LOCATION Summary");
                self::report_daily_location_summary($cParams);
                $__logger->log("END Generate Daily LOCATION Summary");
                $__logger->log("Start Generate Daily GAME Summary");
                self::report_daily_game_summary($cParams);
                $__logger->log("END Generate Daily GAME Summary");            

                $currentDate = strtotime($date . " +1 day");
                $date = date("Y-m-d", $currentDate);
                $dateCount++;
            }
            
        }

    }       
 
    public static function reportMissingEarningsData($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        $q = "SELECT id from location 
            WHERE reporting = 1 AND
            id NOT IN (
                SELECT distinct loc_id FROM game_earnings                 
                WHERE 
                    date_start >= '$date_start' 
                    AND date_start < date_add('$date_start', INTERVAL 1 DAY)
                )" . 
                (empty($location) ? '' : " AND id IN ($location)").
                (empty($debit_type) ? '' : " AND debit_type_id IN ($debit_type)");
        
        $items = DB::select($q);
        if (!empty($items)) {
            foreach($items as $item) {
                $id = $item->id;
                DB::beginTransaction();
                self::recordMissingEarningsData($date_start, $id);
                DB::commit();
            }
        }
    }    
    public static function recordMissingEarningsData($date_start = "", $location = "") {
        $table = "game_earnings_transfer_adjustments";
        global $__logger;
        $date = date("Y-m-d", strtotime($date_start));
        $dataExists = DBHelpers::exists($table, [
                                ["date_start", $date],
                                ["loc_id", $location]
            ]);
        if ($dataExists === false) {
            $__logger->log("MISSING DATA for $location on $date [record added]");
            DB::table($table)->insert(array(
                'date_start' => $date,
                'loc_id' =>$location,
                'status' => 1,
            ));
        }
        else {
            $__logger->log("MISSING DATA for $location on $date [record updated]");
            DB::table($table)
                    ->where('id', $dataExists)
                    ->update(array('status' => 1));
        }
        
    }
    public static function syncedMissingEarningsData($date_start = "", $location = "", $notes = "ADJUSTED") {
        $table = "game_earnings_transfer_adjustments";
        global $__logger;
        $date = date("Y-m-d", strtotime($date_start));
        $dataExists = DBHelpers::exists($table, [
                                ["date_start", $date],
                                ["loc_id", $location]
            ]);
        
        if ($dataExists !== false) {
            $__logger->log("MISSING DATA for $location on $date [record updated]");
            DB::table($table)
                    ->where('id', $dataExists)
                    ->update(array(
                        'status' => 0, 
                        'notes' => $notes, 
                        'adjustment_date' => date('Y-m-d')
                    ));
        }
        
    }
    
    public static function recordMissingAssetIds($date_start = "", $location = "") {
        global $__logger;

        
    }
    public static function reportMissingAssetIds($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }
    
    public static function reportUnknownAssetIds($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }    
    public static function recordUnknownAssetIds($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }
    
    public static function reportMissingReaders($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }    
    public static function recordMissingReaders($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }
    
    public static function reportUnknownReaders($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }
    public static function recordUnknownReaders($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        
    }
    
    
    public static function getLocationDebitType($location) {
        
        $q = "SELECT debit_type_id from location WHERE id = $location";
        $item = DB::select($q);
        $debitType = '';
        if (!empty($item)) {
            $data = $item[0];
            if (is_array($data)) {
                $debitType = $data['debit_type_id'];
            }
            else {
                $debitType = $data->debit_type_id;
            }
            
        }        
        return $debitType;                
    }
    
    public static function getDebitTypeName($debitType) {
        $debitTypes = array("1" => "Sacoa", "2" => "Embed");
        return isset($debitTypes[$debitType]) ? $debitTypes[$debitType] : null;
    }
    
    public static function getDebitTypeDBName($debitType) {
        $debitTypes = array("1" => "sacoa_sync", "2" => "embed_sync");
        return isset($debitTypes[$debitType]) ? $debitTypes[$debitType] : null;
    }
    
    public static function hasSyncData($date, $location) {
        global $__logger;
        $table = "game_earnings";
        $nextDate = date("Y-m-d", strtotime($date." +1 day"));
        $debitType = self::getLocationDebitType($location); 
        $sourceDBName = self::getDebitTypeDBName($debitType);            
        $q = "SELECT count(*) as count from $table 
                WHERE date_start >= '$date' AND date_start < '$nextDate' 
                AND loc_id IN ($location)";
        
        $count = 0;
        $item = DB::connection($sourceDBName)->select($q);
        //$__logger->log("has sync data: ", $item);
        if (!empty($item)) {
            $count = $item[0]->count;
        }
        
        return $count > 0;        
    }
    public static function hasSyncDataOnDate($date, $debitType) {
        global $__logger;
        $table = "game_earnings";
        $nextDate = date("Y-m-d", strtotime($date." +1 day"));
        $sourceDBName = self::getDebitTypeDBName($debitType);            
        $q = "SELECT count(*) as count from $table 
                WHERE date_start >= '$date' AND date_start < '$nextDate'";
        
        $count = 0;
        $item = DB::connection($sourceDBName)->select($q);
        //$__logger->log("has sync data: ", $item);
        if (!empty($item)) {
            $count = $item[0]->count;
        }
        
        return $count > 0;        
    }    
    
    public static function getReaderExclude($debit_type_id = null, $location = null, $encapsulateQuotes = true) {
        $excluded = array();
        $q = "SELECT reader_id FROM reader_exclude WHERE id IS NOT NULL " .
                (!empty($debit_type_id) ?  " AND debit_type_id IN ($debit_type_id)" : "") .
                (!empty($location) ?  " AND loc_id IN ($location)" : "");
                
        $items = DB::select($q);
        foreach ($items as $exclude_row) {
            $readerId = $exclude_row->reader_id;
            if ($encapsulateQuotes) {
                $readerId = "'".$readerId."'";
            }
            $excluded[] = $readerId;
        }
        return $excluded;
    }
    
    public static function getReaderExcludeQuery($debit_type_id = null, $location = null, $field = "reader_id") {
        $q = '';
        $readerExcludes = self::getReaderExclude($debit_type_id, $location);
        if (!empty($readerExcludes)) {
            $q = " AND $field NOT IN (" . implode(",", $readerExcludes). ") ";
        }
        return $q;        
    }
        
    public static function TransferEarningsGeneric($debit_type, $date_start, $location = "", $chunkSize = 500) {
        global $__logger;
        $table = "game_earnings";
        
        $sourceDBName = self::getDebitTypeDBName($debit_type);
      
        DB::connection($sourceDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        //DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        $sourceDB = DB::connection($sourceDBName);
        $date_end = date("Y-m-d", strtotime($date_start . ' +1 day'));
        $locations = explode(',', $location);
        
        $logDetails = "$date_start " . (empty($location) ? "" : " Location: $location");

        $readerExclude = self::getReaderExclude($debit_type, $location, false);
        //$readerExcludeQuery = self::getReaderExcludeQuery($debit_type, $location);
        $query = $sourceDB->table($table);
        $query->select(DB::raw("           
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
                        loc_game_title"));
//        
//        $query->select('debit_type_id',
//                        'loc_id',
//                        'game_id',
//                        'reader_id',
//                        'play_value',
//                        'total_notional_value',
//                        'std_plays',
//                        'std_card_credit',
//                        'std_card_credit_bonus',
//                        'std_actual_cash',
//                        'std_card_dollar',
//                        'std_card_dollar_bonus',
//                        'time_plays',
//                        'time_play_dollar',
//                        'time_play_dollar_bonus',
//                        'product_plays',
//                        'service_plays',
//                        'courtesy_plays',
//                        'date_start',
//                        'date_end',
//                        'ticket_payout',
//                        'ticket_value',
//                        'loc_game_title');
        
        $query->where('date_start', '>=', $date_start)
              ->where('date_start', '<',$date_end);
        
        if (!empty(trim($location))) {
            $query->whereIn('loc_id', $locations);
        }
        if (!empty($readerExclude)) {
            $query->whereNotIn('reader_id', $readerExclude);
        }
         
        $rowcount = 0;
        $__logger->log("Syncing $sourceDBName");
        $query->chunk($chunkSize, 
                function($data)  use ($table, &$rowcount, &$chunkCount){
                    global $__logger;
                    global $_scheduleId;
                    
                    try {
                        if (!empty($data)) {
                             $dataSize = count($data);
                             $chunkCount++;
                             $rowcount += $dataSize;
                             $__logger->log("Data received chunk #$chunkCount of size $dataSize. Total items received so far: $rowcount");        
                             $__logger->log("Adding data to local");
                             DB::table($table)->insert($data);
                         }
                         else {
                             self::$L->log("NO data to add");
                         }                            
                    } 
                    catch (Exception $ex) {
                        $errorFile = $ex->getFile();
                        $errorLine = $ex->getLine();                
                        $errorMessage = $ex->getMessage() . " - $errorFile at line $errorLine";
                        \App\Library\Elm5Tasks::errorSchedule($_scheduleId);
                        \App\Library\Elm5Tasks::updateSchedule($_scheduleId, array("results" => $errorMessage, "notes" => $errorMessage));
                        \App\Library\Elm5Tasks::log("Error: ".$errorMessage);
                        $__logger->log($errorMessage);
                        exit();
                    }                            
                });              
        $__logger->log("End Syncing $sourceDBName");
  
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        DB::connection($sourceDBName)->setFetchMode(PDO::FETCH_CLASS);
        if ($rowcount > 0) {
            $__logger->log("Transferred $rowcount data items for : $logDetails");
            return $rowcount;
        }
        else {
            $__logger->log("Transferred no data items for : $logDetails");
        }
                
        return false;     

    }
    public static function deleteEarningsGeneric($date_start = "", $location = "", $debit_type = "") {
        global $__logger;
        $sql = "DELETE from game_earnings 
                WHERE date_start >= '$date_start' 
                    AND date_start < DATE_ADD('$date_start', INTERVAL 1 DAY)";
        if (!empty($location)) {
            $hasSyncData = self::hasSyncData($date_start, $location);
            if (!$hasSyncData) {
                return;
            }
            $sql .= " AND loc_id in($location)";
        }
        if (!empty($debit_type)) {
            $sql .= " AND debit_type_id in($debit_type)";
        }
        $affected_rows = DB::delete($sql); 
        
         if ($affected_rows > 0) {
            $debitTypeName = self::getDebitTypeName($debit_type);
            $dL = new MyLog('transfer-delete-existingdata.log', 
                    'FEGCronTasks/daily-transfer', 'DeleteData');
            $log = "Deleted existing records for Date: $date_start " . 
                    (empty($location) ? "" : ", Location: $location") .
                    (empty($debit_type) ? "" : ", Debit Type: $debitTypeName");
            $__logger->log($log);
            $dL->log($log);           
        }
        
        return $affected_rows;
    }        
    

    public static function getPossibleLastPlayedDateOfGame($game_id, $date, $location = 0) {
        $possibleDate = null;
        $moveHistoryTop = false;
        $moveHistorySubsequent = false;
        $dateValue = strtotime($date);
        //$l = new MyLog('getPossibleLastPlayedDateOfGame.log', 'Test', 'DATE');  
        //$l->log("Game: $game_id, date: $date, location: $location");
                                
        $q = "select max(date_played) as dateLastPlayed
                FROM report_game_plays 
                WHERE game_id=$game_id 
                    AND location_id=$location
                    AND date_played <= '$date'
                    AND report_status=1 
                    AND record_status=1";
        
        $data = DB::select($q);
        
        if (!empty($data)) {
            $row = $data[0];
            if (is_array($row)) {
                $possibleDate = $row['dateLastPlayed'];
            }
            else {
                $possibleDate = $row->dateLastPlayed;
            }
            //$l->log("Step 1 [from game earnings] Possible Date: ", $possibleDate);
        }
        
        $possibleDateValue = strtotime($possibleDate);
        $isPossibleDateEmpty = empty($possibleDateValue) || $possibleDateValue < 0 || $possibleDateValue > $dateValue; 
        
        // 2. try move history
        if ($isPossibleDateEmpty) {            
            $q = "select from_loc, to_loc, 
                    date_format(from_date, '%Y-%m-%d') as from_date
                    from game_move_history WHERE game_id = $game_id order by from_date ASC";
            $data = DB::select($q);
            if (!empty($data)) {                
                foreach ($data as $moveCount => $row) {
                    if (is_array($row)) {
                        $from = $row['from_date'];
                        $tloc = $row['to_loc'];
                        $floc = $row['from_loc'];
                    }
                    else {
                        $from = $row->from_date;
                        $tloc = $row->to_loc;
                        $floc = $row->from_loc;
                    }
                    $fromValue = strtotime($from);
                    // if the move history's from date is greater than the given date
                    if ($moveCount == 0 && $dateValue < $fromValue) {
                        $moveHistoryTop = true;
                        //$possibleDate = $gameStartDate;
                        //$l->log("Step 2.1 [move history TOP] Possible Date:", $possibleDate);
                        break;
                    }
                    if ($dateValue >= $fromValue) {
                        $gameMoveStartDate = $from;
                        $gameMoveStartDatestamp = $fromValue;
                        $moveHistorySubsequent = true;
                       // $l->log("Step 2.2x [move history rest] Possible Date: ", $possibleDate);
                    }                    
                }            
            }
                
            // 3 NOT FOUND in move history -> set game's first date, location's first date
            if (empty($gameMoveStartDatestamp) || $gameMoveStartDatestamp < 0) {

                $minGameDate = DB::table('game')->where('id', $game_id)->value('date_in_service');
                $minGameDatestamp = strtotime($minGameDate);
                $isMinGameDate = !empty($minGameDatestamp) && $minGameDatestamp > 0 && $minGameDatestamp <= $dateValue;

                $minLocationDate = DB::table('location')->where('id', $location)->value('date_opened');
                $minLocationDatestamp = strtotime($minLocationDate);
                $isMinLocationDate = !empty($minLocationDatestamp) && $minLocationDatestamp > 0 && $minLocationDatestamp <= $dateValue;

                if ($isMinGameDate && $isMinLocationDate) {
                    $possibleDateValue = max($minGameDatestamp, $minLocationDatestamp);
                    $possibleDate = date("Y-m-d", $possibleDateValue);

                }
                elseif ($isMinGameDate) {
                    $possibleDate = $minGameDate;
                    $possibleDateValue = $minGameDatestamp;
                }
                elseif($isMinLocationDate) {
                    $possibleDate = $minLocationDate;
                    $possibleDateValue = $minLocationDatestamp;                                      
                }
            }
            else {
                $possibleDate = $gameMoveStartDate;
            }
        }
            
        $possibleDateValue = strtotime($possibleDate);
        $isPossibleDateEmpty = empty($possibleDateValue) || $possibleDateValue <= 0 || $possibleDateValue > $dateValue; 
        
        if ($isPossibleDateEmpty) {
            $possibleDate = null;
        }
        //$l->log("FINAL possible date: ", $possibleDate);
        return $possibleDate;
    }
    
    public static function getPossibleHistoricalLocationOfGame($game_id, $date, $location = 0) {
        //$l = new MyLog('getPossibleHistoricalLocationOfGame.log', 'Test', 'LOCATION');  
        //$l->log("Game: $game_id, date: $date, location: $location");
        $possibleLocation = false;
        $dateValue = strtotime($date);
        $q = "select from_loc, to_loc, 
                    date_format(from_date, '%Y-%m-%d') as from_date
                    from game_move_history WHERE game_id = $game_id order by from_date ASC";
        $data = DB::select($q);
        if (!empty($data)) {
            foreach ($data as $moveCount => $row) {
                if (is_array($row)) {
                    $from = $row['from_date'];
                    $tloc = $row['to_loc'];
                    $floc = $row['from_loc'];
                }
                else {
                    $from = $row->from_date;
                    $tloc = $row->to_loc;
                    $floc = $row->from_loc;
                }
                $fromValue = strtotime($from);
                if ($moveCount == 0 && $dateValue < $fromValue) {
                    $possibleLocation = $floc;
                    //$l->log("STEP 1.1 [move history top] possible location: ", $possibleLocation);
                    break;
                }
                if ($dateValue >= $fromValue) {
                    $possibleLocation = $tloc;
                    //$l->log("STEP 1.2 [move history rest] possible location: ", $possibleLocation);
                }                
            }            
        }
        
        if ($possibleLocation === false) {
            $possibleLocation = $location;
            //$l->log("STEP 2 [default] possible location: ", $possibleLocation);
        }
        
        //$l->log("FINAL possible location: ", $possibleLocation);
        return $possibleLocation;
    }
    
    public static function cleanDailyReport($params = array()) {
        global $__logger;
        $lf = 'CleanUpSummaryReports.log';
        $lp = 'FEGCronTasks/Cleanup Summary';
        
        extract(array_merge(array(
            'date' => null,
            'location' => null,            
            '_logger' => null,
        ), $params));
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'CleanSummaryReports');
        $params['_logger'] = $L;  
        $__logger = $L;
        
        $q = "DELETE FROM report_locations WHERE record_status = 0";
        if (!empty($date)) {
            $q .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $q .= " AND location_id=$location";
        }
        $return = DB::delete($q);
        
        $q = "DELETE FROM report_game_plays WHERE record_status = 0";
        if (!empty($date)) {
            $q .= " AND date_played='$date'";
        }
        if (!empty($location)) {
            $q .= " AND location_id=$location";
        }
        $return2 = DB::delete($q);  
        return "Deleted records: ". ($return + $return2);
    }
    
    public static function migrate($params = array()) {
        $L = new MyLog('database-migration.log', 'GoLiveMigration', 'Data');
        $L->log("****** Start Database Migration ********");
//        $L->log("       game start");
//        // update game
//        ////DB::statement('ALTER TABLE `game` CHANGE `product_id` `product_id` TEXT NOT NULL; ');
//        DB::update("UPDATE game G 
//                    INNER JOIN game_title GT ON GT.id=G.game_title_id
//                    SET G.game_type_id=GT.game_type_id");
//        DB::update("update `game` set product_id = concat('[\"',product_id ,'\"]') 
//            WHERE product_id NOT LIKE '[\"%' AND game_type_id = 3");
//        
//        $L->log("       game_title start");
//        // game title
//        DB::update("UPDATE `game_title` SET img = CONCAT(id,'.jpg')");
//        
//        $L->log("       user_locations start");
//        // copy data from users to user location
//        DB::table("user_locations")->truncate();
//        $q="INSERT into user_locations(user_id, location_id)
//            SELECT id as user_id, loc_1 as loc FROM `users` where loc_1<>0
//            UNION
//            SELECT id as user_id, loc_2 as loc FROM `users` where loc_2<>0
//            UNION
//            SELECT id as user_id, loc_3 as loc FROM `users` where loc_3<>0
//            UNION
//            SELECT id as user_id, loc_4 as loc FROM `users` where loc_4<>0
//            UNION
//            SELECT id as user_id, loc_5 as loc FROM `users` where loc_5<>0
//            UNION
//            SELECT id as user_id, loc_6 as loc FROM `users` where loc_6<>0
//            UNION
//            SELECT id as user_id, loc_7 as loc FROM `users` where loc_7<>0
//            UNION
//            SELECT id as user_id, loc_8 as loc FROM `users` where loc_8<>0
//            UNION
//            SELECT id as user_id, loc_9 as loc FROM `users` where loc_9<>0
//            UNION
//            SELECT id as user_id, loc_10 as loc FROM `users` where loc_10<>0
//            order by user_id";
//        DB::insert($q);
        
        $L->log("-------- location_budget migration starts");
        //From location.[id,<montth_year>] to location_budget.[location_id,budget_date,budget_value]        
        $q = "SELECT id, Jan_2012,Feb_2012,Mar_2012,Apr_2012,May_2012,
            Jun_2012,Jul_2012,Aug_2012,Sep_2012,Oct_2012,Nov_2012,Dec_2012,
            Jan_2013,Feb_2013,Mar_2013,Apr_2013,May_2013,Jun_2013,Jul_2013,
            Aug_2013,Sep_2013,Oct_2013,Nov_2013,Dec_2013,
            Jan_2014,Feb_2014,Mar_2014,Apr_2014,May_2014,Jun_2014,Jul_2014,
            Aug_2014,Sep_2014,Oct_2014,Nov_2014,Dec_2014,
            Jan_2015,Feb_2015,Mar_2015,Apr_2015,May_2015,Jun_2015,Jul_2015,
            Aug_2015,Sep_2015,Oct_2015,Nov_2015,Dec_2015,
            Jan_2016,Feb_2016,Mar_2016,Apr_2016,May_2016,Jun_2016,Jul_2016,
            Aug_2016,Sep_2016,Oct_2016,Nov_2016,Dec_2016,
            Jan_2017,Feb_2017,Mar_2017,Apr_2017,May_2017,Jun_2017,Jul_2017,
            Aug_2017,Sep_2017,Oct_2017,Nov_2017,Dec_2017,
            Jan_2018,Feb_2018,Mar_2018,Apr_2018,May_2018,Jun_2018,Jul_2018,
            Aug_2018,Sep_2018,Oct_2018,Nov_2018,Dec_2018,
            Jan_2019,Feb_2019,Mar_2019,Apr_2019,May_2019,Jun_2019,Jul_2019,
            Aug_2019,Sep_2019,Oct_2019,Nov_2019,Dec_2019,
            Jan_2020,Feb_2020,Mar_2020,Apr_2020,May_2020,Jun_2020,Jul_2020,
            Aug_2020,Sep_2020,Oct_2020,Nov_2020,Dec_2020

            FROM location";

        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        DB::table("location_budget")->truncate();
        $data = DB::select($q);
        DB::beginTransaction();
        foreach($data as $row) {
            $id = $row['id'];
            foreach($row as $fieldName => $value) {
                if ($fieldName != "id" && !empty($value)) {
                    $date = date("Y-m-d", strtotime(str_replace('_', ' ', $fieldName)));
                    $q = "INSERT INTO location_budget
                        (location_id, budget_date, budget_value)
                        VALUES (?, ?, ?)";
                    $c = DB::insert($q, [$id, $date, $value]);
                    $L->log("-------- ---- $c records added for Loc: $id, Date: $date, Amount: $value");
                }
            }
        }
        DB::commit();
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        $L->log("-------- location_budget migration ends");
        

/*
        
        $L->log("======== Location User Assignments Starts");
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        $sql = "DELETE FROM user_locations WHERE group_id IS NOT NULL";
        DB::delete($sql);

        $templateData = DB::select("SELECT * from location_user_roles_master");
        $template = [];
        foreach($templateData as $tItem) {
            $template[$tItem->role_title] = $tItem;
        }

        $data = DB::select("SELECT r.dist_mgr_id, l.id, l.location_name, l.region_id FROM location l
                	LEFT JOIN region r ON r.id=l.region_id
                    WHERE r.dist_mgr_id IS NOT NULL AND r.dist_mgr_id <> 0
                    AND l.region_id > 1");

        $runData = array_keys($template);

        if ($data) {
            DB::beginTransaction();
            foreach($data as $item) {
                $assignment = [];
                $location = $item->id;
                $assignment['General Manager'] = !empty($item->field_manager) ? $item->field_manager : 0;
                $assignment['Regional Manager'] = !empty($item->dist_mgr_id) ? $item->dist_mgr_id : 0;
                $assignment['Contact'] = !empty($item->contact_id) ? $item->contact_id : 0;
                $assignment['Merchandise Contact'] = !empty($item->merch_contact_id) ? $item->merch_contact_id : 0;

                $assignment['Technical'] = !empty($item->tech_manager_id) ? $item->tech_manager_id : 0;
                $assignment['VP'] = !empty($item->senior_vp_id) ? $item->senior_vp_id : 0;

                $sql = "INSERT INTO user_locations (location_id, user_id, group_id) VALUES(?,?,?)";
                foreach($runData as $runField) {
                    if (!empty($assignment[$runField])) {
                        DB::insert($sql, [$location, $assignment[$runField], $template[$runField]->group_id]);
                    }
                }
            }
            DB::commit();
        }
        $L->log("======== Location User Assignments ends");
//        return true;
*/
        $L->log("######## freight_orders migration starts");
        //freight_orders => freight_location_to
        //
        //
        //freight_orders => freight_order_location_games
        //
        //
        //freight_orders => freight_pallet_details
        //()
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $fData = DB::select("SELECT * from freight_orders");
        DB::table("freight_location_to")->truncate();
        DB::table("freight_order_location_games")->truncate();
        DB::table("freight_pallet_details")->truncate();
        //DB::beginTransaction();
        foreach($fData as $dataCount => $row) {
            $id = $row['id'];
            
            foreach(range(1,5) as $keyIndex) {
                $description = $row['description_'.$keyIndex];
                $dimensions = $row['dimensions_'.$keyIndex];
                $ship_exception = $row['ship_exception_'.$keyIndex];
                $new_ship_date = $row['new_ship_date_'.$keyIndex];
                $new_ship_date_stamp = strtotime($new_ship_date);
                $new_ship_date_valid = \FEGHelp::isValidDate($new_ship_date);
                $new_ship_reason = $row['new_ship_reason_'.$keyIndex];
                
                if (!empty(trim($description)) || !empty(trim($dimensions)) ||
                        !empty(trim($ship_exception)) || $new_ship_date_valid ||
                        !empty(trim($new_ship_reason))) {
                    
                    $q = "INSERT INTO freight_pallet_details 
                        (freight_order_id, description, dimensions, 
                        ship_exception, new_ship_date, new_ship_reason)
                        VALUES (?, ?, ?, ?, ?, ?)";
                    DB::insert($q, [$id, $description, $dimensions, 
                        $ship_exception, $new_ship_date, $new_ship_reason]);
                    
                }
            }
            foreach(range(1,10) as $keyIndex) {
                $loc_to = $row['loc_to_'.$keyIndex];
                $loc_pro = $row['loc_'.$keyIndex.'_pro'];
                $loc_quote = $row['loc_'.$keyIndex.'_quote'];
                $loc_quote_is_empty = empty(floatval($row['loc_'.$keyIndex.'_quote']));
                $loc_trucking_co = $row['loc_'.$keyIndex.'_trucking_co'];
                $freight_company = $row['freight_company_'.$keyIndex];
                
//                if ($dataCount == 25) {
//                    $L->log("Data($keyIndex): ", array($loc_to, $loc_pro, $loc_quote, $loc_trucking_co, $freight_company));
//                    $L->log("Are Empty? ", array(empty($loc_to), empty($loc_pro), $loc_quote_is_empty, empty($loc_trucking_co), empty($freight_company)));
//                }
                if (!empty(trim($loc_to)) || !empty(trim($loc_pro)) ||
                        !$loc_quote_is_empty || !empty($loc_trucking_co) ||
                        !empty($freight_company)) {
                                       
                    $freight_loc_to_id = DB::table("freight_location_to")
                            ->insertGetId([
                               "freight_order_id" => $id, 
                               "location_id" => $loc_to, 
                               "location_pro" => $loc_pro, 
                               "location_quote" => $loc_quote, 
                               "location_trucking_co" => $loc_trucking_co, 
                               "freight_company" => $freight_company
                            ]);
                    
                     foreach(range(1,5) as $keySubIndex) {
                         $loc_game = $row['loc_'.$keyIndex.'_game_'.$keySubIndex];
                         if (!empty($loc_game)) {
                            $q = "INSERT INTO freight_order_location_games 
                                    (freight_loc_to_id, game_id)
                                    VALUES (?, ?)";
                            DB::insert($q, [$freight_loc_to_id, $loc_game]);    
                         }
                     }
                    
                    
                }
            
            }
        }
        //DB::commit();
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        $L->log("######## freight_orders migration Ends");
        $L->log("****** End Database Migration **************");
        
    }
    
    
}
