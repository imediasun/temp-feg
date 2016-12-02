<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\ReportHelpers;
use App\Library\FEG\System\SyncHelpers;

class ReportGenerator
{  
    
    public static $reportCache = array();
   
    public static function daily($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        $__logger = $_logger;
        $__logger->log("Start Generate Daily Email Reports $date");
        
        $task = $_task;
        $isTest = $task->is_test_mode;
        $params['isTestMode'] = $isTest;
        $params['humanDate'] = $humanDate = self::getHumanDate($date);
        $params['humanDateToday'] = $humanDateToday = self::getHumanDate($today);
        
        // Transfer Basic Status
        $dailyTransferStatusReport = '';
        if (empty($location)) {
            $dailyTransferStatusReport = self::getDailyTransferStatusReport($params);
            $dailyTransferStatus = self::$reportCache['syncStatus'];
            if (!$dailyTransferStatus['1'] || !$dailyTransferStatus['2']) {
                self::dailyTransferFailReportEmail($params);
            }            
            if (!$dailyTransferStatus['1'] && !$dailyTransferStatus['2']) {
               return "Sync Failed for both sacoa and embed";
            }            
        }
        
        self::getRetrySyncSuccessData($params);        
        self::logit("Retry Sync Success Data: ", "daily-transfer.log", "CleanLog");
        self::logit(self::$reportCache['retrySyncSuccessData'], "daily-transfer.log", "CleanLog");        
        
        $__logger->log("Start Game Earnings DB Transfer Report for $date");
        // Transfer report
            // $dailyTransferStatusReport
            // Locations Not Reporting:
        $locationsNotReportingReport = self::getLocationsNotReportingReport($params);
         // Readers Missing Asset Ids
        $readersMissingAssetIds = self::getReadersMissingAssetIdsReport($params);
            // Games Not Played:
        $gamesNotPlayed = self::getGamesNotPlayedReport($params);
        
        $__logger->log("        End processing Game Earnings DB Transfer Report for $date");
        
        $message = $dailyTransferStatusReport .
                "<br><br><b><u>Locations Not Reporting:</u></b><em>(Either Closed or Error in Data Transfer)</em><br>" .
                $locationsNotReportingReport .
                "<br><b><u>Readers Missing Asset Ids:</u></b><br>" .
                $readersMissingAssetIds .
                "<br><b><u>Games Not Played:</u></b><br>" .
                $gamesNotPlayed;
       
        self::sendEmailReport(array(
            'to' => 'nate.smith@fegllc.com, silvia.linter@fegllc.com', 
            'subject' => "Game Earnings DB Transfer Report for $humanDate", 
            'message' => $message, 
            'cc' => '', 
            'bcc' => 'greg@element5digital.com, element5@fegllc.com',             
            'isTest' => $isTest,
        ));
        $__logger->log("        End Email Game Earnings DB Transfer Report for $date");
        $__logger->log("End Game Earnings DB Transfer Report for $date");
        

        // each location report
        $__logger->log("Start Locationwise Report for $date");
        $reportingLocations = isset(self::$reportCache['locationsReportingIds']) ? 
                self::$reportCache['locationsReportingIds'] : self::getLocationsReportingIds($params);
        
        self::logit("Reporting Locations:", "daily-transfer.log", "CleanLog");
        self::logit($reportingLocations, "daily-transfer.log", "CleanLog");
        
        self::logit("Missing Asset IDs:", "daily-transfer.log", "CleanLog");
        self::logit(self::$reportCache['missingAssetIdsPerLocation'], "daily-transfer.log", "CleanLog");        
        
        self::logit("Games Not Played:", "daily-transfer.log", "CleanLog");
        self::logit(self::$reportCache['gamesNotPlayedPerLocation'], "daily-transfer.log", "CleanLog");        
        
        foreach($reportingLocations as $locationId) {
            $__logger->log("    Start Report for Location $locationId for $date");
            $locationParams = array_merge($params, array('location' => $locationId));
            $locationwiseReport = self::getLocationWiseDailyReport($locationParams);
            $hasLocationwiseReport = $locationwiseReport['hasReport'];
            $locationwiseReportMessage = $locationwiseReport['report'];
            $locationParams['dailyReport'] = $locationwiseReportMessage;
            $locationParams['hasDailyReport'] = $hasLocationwiseReport;
            $__logger->log("    End processing Report for Location $locationId for $date");
            self::sendLocationWiseDailyReportEmail($locationParams);
            $__logger->log("    End sending email Report for Location $locationId for $date");
            $__logger->log("    End Report for Location $locationId for $date");
        }
        //$__logger->log("    ** Start Locationwise Report for data adjusted on $today");
        // TODO: send location email for all sync adjustments
        //$__logger->log("    ** End Locationwise Report for data adjusted on $today");
        
        $__logger->log("End Locationwise Report for $date");

        $__logger->log("Start over reporting error Report $date");
        $potentialOverreportingErrorReport = self::getOverreportingReport($params);
        $params['overReporting'] = $potentialOverreportingErrorReport;        
        $__logger->log("  End processing of over reporting error Report $date");
        self::sendOverreportingReportEmail($params);
        unset($params['overReporting']);
        $__logger->log("  End Email of over reporting error Report $date");
        
        //$__logger->log("  ** Start overreporting error Report of adjusted data on $today");
        // TODO: Get all adjusted data's potential over reporting
        //$__logger->log("  ** End overreporting error Report of adjusted data on $today");
        
        $__logger->log("End over reporting error Report $date");
        
        // Adjustment sync (all, sacoa, embed)
        $__logger->log("Start retry sync report  as of $today");
        $retrySyncReportData = self::getRetrySyncReport($params);
        $params['retryReports'] = $retrySyncReportData;
        $__logger->log("    End processing retry sync report as of $today");
        self::sendRetrySyncReportEmail($params);
        $__logger->log("    End sending retry sync report  as of $today");
        unset($params['retryReports']);
        $__logger->log("End retry sync report  as of $today");
        
        // final
        $__logger->log("Start Final Games Summary Report for $date");
        $finalGameSummaryReport = self::getDailyGameSummaryReport($params);
        $__logger->log("    END processing Final Games Summary Report for $date");        
        $params['finalGameSummaryReport'] = $finalGameSummaryReport;
        self::sendDailyGameSummaryReportEmail($params);
        unset($params['finalGameSummaryReport']);
        $__logger->log("    END sending EMAIL of Final Games Summary Report for $date");        
        $__logger->log("END Final Games Summary Report for $date");
        
        $__logger->log("END Generate Daily Email Reports $date");

    }
    
    public static function getDailyTransferStatusReport($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
                 
        $dailyTransferStatus = self::dailyTransferStatus($params);        
        $dailyTransferStatusReport = self::dailyTransferStatusReport($params);        
        
        return $dailyTransferStatusReport;
    }    
    public static function dailyTransferStatus($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        $hasSacoaData = SyncHelpers::hasSyncDataOnDate($date, 1);
        $hasEmbedData = SyncHelpers::hasSyncDataOnDate($date, 2);
        $status = array(
            '1' => $hasSacoaData,
            '2' => $hasEmbedData,
        );
        self::$reportCache['syncStatus'] = $status;
        return $status;
    }
    public static function dailyTransferStatusReport($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            'status' => array(),
            '_task' => array(),
            '_logger' => null,
        ), $params));
        $report = array();
        if (!empty($status) && is_array($status)) {
            if ($status['1'] === true) {
                $report[] = '<br>Sacoa: <b style="color:green">Dump Succeeded</b>';
            }
            elseif ($status['1'] === false) {
                $report[] = '<br>Sacoa: <b style="color:red">Dump Failed</b>';
            }
            if ($status['2'] === true) {
                $report[] = '<br>Embed: <b style="color:green">Dump Succeeded</b>';
            }
            elseif ($status['2'] === false) {
                $report[] = '<br>Embed: <b style="color:red">Dump Failed</b>';
            }
        }        
        $reportString = implode("", $report);
        self::$reportCache['syncStatusReport'] = $reportString;   
        return $reportString;
    }
    public static function dailyTransferFailReportEmail($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'status' => null,
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        if (empty($_logger)) {
            if (empty($__logger)) {
                $_logger = new MyLog('daily-report-transfer-failure.log', 'daily-transfer-reports', 'Reports');
            }
            else {
               $_logger = $__logger;
            }            
        }
        
        $statusReport = self::$reportCache['syncStatusReport'];
        
        $_logger->log("Failed Sync on $date", $statusReport);
        
        $task =$_task;
        $isTest = $task->is_test_mode;
        
        self::sendEmailReport(array(
            'to' => 'nate.smith@fegllc.com', 
            'subject' => "Transfer Failure $humanDate", 
            'message' => $statusReport, 
            'cc' => '', 
            'bcc' => 'greg@element5digital.com, element5@fegllc.com',             
            'isTest' => $isTest,
        ));
        
        
    }


    public static function getLocationWiseDailyReport($params) {
        //Missing Asset IDs
        //Games Not Played Yesterday
        //Games Not on Debit Card
        //Readers Used for Game Accessories or Non-FEG Equipment        
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));  
        
        $report = array();
        $hasReport = false;
        $reportString = "";
        self::logit("Location Wise Report: $location, $date ", "daily-transfer.log", "CleanLog");        
        if (!empty($location) && !empty($date))  {
            $missingAssetIdsAll = isset(self::$reportCache['missingAssetIdsPerLocation']) ? 
                    self::$reportCache['missingAssetIdsPerLocation'] : array();
            $missingAssetIds = isset($missingAssetIdsAll[$location]) ? $missingAssetIdsAll[$location] : 
                array();//self::getMissingAssetIds($params);

            $gamesNotPlayedAll = isset(self::$reportCache['gamesNotPlayedPerLocation']) ? 
                    self::$reportCache['gamesNotPlayedPerLocation'] : array();
            $gamesNotPlayed = isset($gamesNotPlayedAll[$location]) ? $gamesNotPlayedAll[$location] : 
                array();//self::getGamesNotPlayed($params);
            
            self::logit("Missing Asset ID for Location $location", "daily-transfer.log", "CleanLog");
            self::logit($missingAssetIds, "daily-transfer.log", "CleanLog");
            self::logit("Games Not Played for Location $location", "daily-transfer.log", "CleanLog");
            self::logit($gamesNotPlayed, "daily-transfer.log", "CleanLog");
            
            $gamesOffDebitCardData = DB::select(ReportHelpers::getGamesNotOnDebitCardQuery($location));
            
            $nonFEGReadersData = DB::select(ReportHelpers::getExcludedReadersQuery($location));

            if (empty($missingAssetIds)) {
                $report[] = '<b style="color:green">No reader issues! Thank you.</b><br><br>';
            } 
            else {
                $reportMissingAssetIds = array();
                foreach($missingAssetIds as $row) {
                    
                    $locationGameName = $row['loc_game_title'];
                    $readerId = $row['reader_id'];
                    $gameTotal = $row['game_total'];
                    
                    if ($readerId !== "0") {
                        $reportMissingAssetIds[] = "$readerId <span style='color:black'> [Game Title: $locationGameName
                                 - <em>Earnings: \${$gameTotal}</em>]</span><br>";
                    }         
                }
                $missingAssetIdString = implode("", $reportMissingAssetIds);
                $report[] = '<b><u>Missing Asset IDs for the Following Readers:</u></b>
                        <b style="color:red"> ADDRESS IMMEDIATELY!</b> <br> 
                        <em>Instructions: In your card system\'s Game
                        Manager Screen (Embed or Sacoa), find the Reader 
                        IDs listed below and add that game\'s 8-digit 
                        FEG asset number.
                        This number can be found on the game\'s physical 
                        asset tag. If your game is missing tags, 
                        you can find the Asset ID listed online</em> <br> <b
                        style="color:red">' . $missingAssetIdString . ' </b> <br>';

                $hasReport = true;
            }

            if (empty($gamesNotPlayed)) {
                $report[] = '<br><b style="color:green">All games played yesterday! Thank you.</b><br><br>';
            }
            else {
                $locationGamesNotPlayed = array();
                foreach($gamesNotPlayed as $index => $row) {
                    $gameId = $row['game_id'];
                    $gameTitle = $row['game_name'];           
                    $downForText = $row['days_not_played_text'];
                    
                    $locationGamesNotPlayed[] = "<b>$gameId | $gameTitle</b> $downForText<br>";
                }                
                
                $locationGamesNotPlayedString = implode("", $locationGamesNotPlayed);
                $report[] = '<br><b><u>Games Not Played Yesterday</u></b><b style="color:red"> - Test that these games are fully functional.</b><br>' 
                        . $locationGamesNotPlayedString;
                $hasReport = true;                
            }

            if (!empty($gamesOffDebitCardData)) {
                $gameOffDebit = array();
                foreach($gamesOffDebitCardData as $index => $row) {
                    $gameId = $row->id;
                    $gameTitle = $row->game_title;           
                    $reason = $row->not_debit_reason;
                    if (empty($reason)) {
                        $reason = "<b style=\"color:red\">Please Reply with Reason</b>";
                    }                    
                    $gameOffDebit[] = "$gameId |  $gameTitle - $reason<br>";
                }                
                
                $gameOffDebitString = implode("", $gameOffDebit);
                $report[] = "<br><b><u>Games <em>Not</em> on Debit Card</u></b>
							   <br>$gameOffDebitString";
            }

            if (!empty($nonFEGReadersData)) {
                $nonFEGReaders = array();
                foreach($nonFEGReadersData as $index => $row) {
                    $readerIdOriginal = $row->reader_id;           
                    $readerId = preg_replace('/^.*\_/', "", $readerIdOriginal);
                    $reason = $row->reason;
                    if (empty($reason)) {
                        $reason = "<b style=\"color:red\">Please Reply with Reason</b>";
                    }                    
                    $nonFEGReaders[] = "$readerId - $reason<br>";
                }                
                
                $nonFEGReadersString = implode("", $nonFEGReaders);
                $report[] = "<br><b><u>Readers Used for Game Accessories or Non-FEG Equipment</u></b>
							   <br>$nonFEGReadersString";
            }            

        }
        $reportString = implode("", $report);
        return array("hasReport" => $hasReport, "report" => $reportString);        
    }
    public static function sendLocationWiseDailyReportEmail($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'dailyReport' => '',
            'hasDailyReport' => false,
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
         
        if (empty($_logger)) {
            if (empty($__logger)) {
                $_logger = new MyLog('daily-report-location.log', 'daily-transfer-reports', 'Reports');
            }
            else {
               $_logger = $__logger;
            }            
        }
        
        $task =$_task;
        $isTest = $task->is_test_mode;
        
        if ($hasDailyReport) {
            $emailRecipients = self::getLocationSystemEmailRecipients($location);
            self::sendEmailReport(array(
                'to' => $emailRecipients, 
                'subject' => "Games Summary for location  $location - $humanDate", 
                'message' => $dailyReport, 
                'cc' => '', 
                'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
                'isTest' => $isTest,
            ));              
        }
        else {
            self::sendEmailReport(array(
                'to' => 'greg@element5digital.com,element5@fegllc.com', 
                'subject' => "Games Summary for location  $location - $humanDate  [NO ISSUES]", 
                'message' => $dailyReport, 
                'cc' => '', 
                'bcc' => '',             
                'isTest' => $isTest,
            ));             
        }
 
    }
    
     public static function getLocationsReportingIds($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));  
        
        $q = "SELECT location_id as id from report_locations 
            WHERE date_played='$date' 
                AND report_status=1 AND record_status=1
                ORDER BY location_id";
        
        $data = DB::select($q);
        $locationsReportingIds = array();
        foreach($data as $row) {            
            $locationsReportingIds[] = $row->id;
        }        
        self::$reportCache['locationsReportingIds'] = $locationsReportingIds;
        return $locationsReportingIds;
    }
    
    public static function getLocationsNotReportingIds($params = array()) {
        extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        $q = ReportHelpers::getLocationNotReportingQuery($date, $date, null, null, 'L.debit_type_id');
        $data = DB::select($q);
        $locationsNotReportingIds = array();
        foreach($data as $row) {
            $locationsNotReportingIds[] = $row->id;
        }        
        self::$reportCache['locationsNotReportingIds'] = $locationsNotReportingIds;
        return $locationsNotReportingIds;
    }
    public static function getLocationsNotReportingReport($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));  
        
        $debitTypeStyle = array(
            "1" => " <b style='color:pink'> (sacoa)</b>",
            "2" => " <b style='color:blue'> (embed)</b>",
        );
        $q = ReportHelpers::getLocationNotReportingQuery($date, $date, null, null, 'L.debit_type_id');
        $data = DB::select($q);
        $locationsNotReportingIds = array();
        $report = array();
        foreach($data as $row) {
            $locationId = $row->id;
            $locationName = $row->location_name;
            $debitTypeId = $row->debit_type_id;
            
            $locationsNotReportingIds[] = $locationId;
            $report[] = "$locationId - $locationName " . 
                    $debitTypeStyle[$debitTypeId] . "<br>";
        }
        
        self::$reportCache['locationsNotReportingIds'] = $locationsNotReportingIds;
        
        $reportString = 'N/A (all locations reporting)<br>';
        if (!empty($report)) {
            $reportString = implode("", $report);
        }
        
        self::$reportCache['locationsNotReportingReport'] = $reportString;
        
        return $reportString;
    }
    
    public static function getReadersMissingAssetIdsReport($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));  
        
        $q = ReportHelpers::getReadersMissingAssetIdQuery($date, $date);
        $data = DB::select($q);
        $report = array();        
        $missingAssetIdData = array();
        $missingAssetIdFlatData = array();
        foreach($data as $index => $row) {
            $locationId = $row->location_id;
            $locationName = $row->location_name_short;
            $locationGameName = $row->loc_game_title;
            $readerIdOriginal = $row->reader_id;
            $readerId = preg_replace('/^.*\_/', "", $readerIdOriginal);
            $gameTotalOriginal = $row->game_total;
            $gameTotal = number_format(floatval($gameTotalOriginal));
            $rowNumber = $index + 1;
            
            $missingAssetId = array(
                "location_id" => $locationId,
                "location_name" => $locationName,
                "loc_game_title" => $locationGameName,
                "reader_id" => $readerId,
                "reader_id_original" => $readerIdOriginal,
                "game_total_original" => $gameTotalOriginal,
                "game_total" => $gameTotal,
            );
            $missingAssetIdFlatData[] = $missingAssetId;
            $missingAssetIdData[$locationId][] = $missingAssetId;
            $report[] = "$rowNumber.) $locationId - $locationName - <b> $readerId</b>" .                     
                    "<span  style='color:black'> [Game Title: $locationGameName".
                    " - <em>Earnings: \${$gameTotal}</em>]</span><br>";
        }
        
        self::$reportCache['missingAssetIdsPerLocation'] = $missingAssetIdData;
        self::$reportCache['missingAssetIds'] = $missingAssetIdFlatData;
        
        $reportString = '<br>';
        if (!empty($report)) {
            $reportString = implode("", $report);
        }
        return $reportString;        
    }
    
    public static function getGamesNotPlayedReport($params = array()) {
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'location' => null,
            'locationsNotReportingIds' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));  
        
        if ($locationsNotReportingIds === null) {
            if (!isset(self::$reportCache['locationsNotReportingIds'])) {
                $locationsNotReportingIds = self::getLocationsNotReportingIds($params);
                self::$reportCache['locationsNotReportingIds'] = $locationsNotReportingIds;
            }
            else {
                $locationsNotReportingIds = self::$reportCache['locationsNotReportingIds'];
            }
        }
        
        $q = ReportHelpers::getGamesNotPlayedQuery($date, $date, 
                null, null, null, "all", "", "", "", "");
        
        //$q .= " AND E.location_id NOT IN (" . implode(',', $locationsNotReportingIds). ")";
        
        $data = DB::select($q);
        $report = array();  
        $notPlayedGames = array();
        $notPlayedGamesFlat = array();
        $notPlayedMoreThanAWeek = array();
        foreach($data as $index => $row) {
            $locationId = $row->location_id;
            $locationName = $row->location_name;
            $gameId = $row->game_id;
            $isOnTest = $row->game_on_test == 'Yes';
            $gameTitleOriginal = $row->game_name;
            $gameTitle = ($isOnTest ? "**TEST** " : "") . $gameTitleOriginal;
            
            $daysNotPlayed = $row->days_not_played;
            $downForText = $daysNotPlayed > 1 ? 
                    "<em style=\"color:red\"> - down for <b>$daysNotPlayed days<b></em>" : "";
            $rowIndex = $index+1;
            
            $game = array(
                "location_id" => $locationId,
                "location_name" => $locationName,
                "game_id" => $gameId,
                "game_on_test" => $isOnTest,
                "game_name" => $gameTitle,
                "game_name_original" => $gameTitleOriginal,
                "days_not_played" => $daysNotPlayed,
                "days_not_played_text" => $downForText,
            );
            $notPlayedGamesFlat[] = $game;
            $notPlayedGames[$locationId][] = $game;            
            if ($daysNotPlayed > 6) {
                $notPlayedMoreThanAWeek[] = $game;
            }
            
            if (!in_array($locationId, $locationsNotReportingIds)) {
                $report[] = "$rowIndex.) <b>$gameId | $gameTitle</b>" . 
                    " at <b>$locationId | $locationName</b> $downForText<br>";                
            }

        }
                    
        self::$reportCache['gamesNotPlayedMoreThanAWeek'] = $notPlayedMoreThanAWeek;
        self::$reportCache['gamesNotPlayedPerLocation'] = $notPlayedGames;
        self::$reportCache['gamesNotPlayed'] = $notPlayedGamesFlat; 
        $reportString = '';
        if (!empty($report)) {
            $reportString = implode("", $report);
        }
        return $reportString;        
    }
    
    public static function getOverreportingReport($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'startDate' => null,
            'endDate' => null,
            'today' => date('Y-m-d'),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        if (empty($startDate)) {
            if (empty($date)) {
                $startDate = date('Y-m-d', strtotime('-1 day'));
            }
            else {
                $startDate = $date;
            }
        }
        if (empty($endDate)) {
            $endDate = $startDate;//date("Y-m-d", strtotime($startDate." +1 day"));
        }
        
        $data = DB::select(ReportHelpers::getPotentialOverReportingErrorQuery($startDate, $endDate, $location));
        
        $report = array();
        foreach($data as $row) {
            $dateStart = date("Y-m-d", strtotime($row->date_start));
            $locationId = $row->location_id;
            $locationName = $row->location_name;
            $gameId = $row->game_id;
            $gameOnTest = $row->game_on_test == "Yes";
            $gameTitleOriginal = $row->game_name;
            $gameTitle = ($isOnTest ? "**TEST** " : "") . $gameTitleOriginal;                        
            $earnings = $row->game_total;
            
            $report[] = ($startDate == $endDate ? "" : "[$dateStart]") .
                "<b>$gameId | $gameTitle</b> on 
                <b>$locationId ($locationName)</b> reported 
                    <b style='color:red;'>\${$earnings}</b> in earnings<br>";            
        }
        
        $reportString = implode("", $report);
        
        return $reportString;
         
    }
    public static function sendOverreportingReportEmail($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),            
            'location' => null,
            'overReporting' => "",
            '_task' => array(),
            '_logger' => null,
        ), $params));

        $task =$_task;
        $isTest = $task->is_test_mode;        
        
        if (!empty($overReporting)) {
            $message = "<b><u>Potential Over-reporting Errors:</u></b><br><br>$overReporting";
            self::sendEmailReport(array(
                'to' => 'support@fegllc.com', 
                'subject' => "Potential Over-reporting Errors Check for $humanDate", 
                'message' => $message, 
                'cc' => '', 
                'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
                'isTest' => $isTest,
            ));             
        }
    }
    
    public static function getRetrySyncSuccessData($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
         
        $data = DB::select(ReportHelpers::getRetrySyncSuccessQuery($today));
        self::$reportCache['retrySyncSuccessData'] = $data;
        return $data;
    }
    public static function getRetrySyncReport($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
         
        $adjustment_date = $today ;
        $data = DB::select(ReportHelpers::getRetrySyncStatusQuery($adjustment_date));
        
        $debitTypeTemplate = array(
            array(
                "1" => 'Sacoa: <span style="color:green">',
                "2" => 'Embed: <span style="color:green">',
            ),
            array(
                "1" => 'Sacoa: <span style="color:red">',
                "2" => 'Embed: <span style="color:red">',
            ),
            
        );
        
        $reportAllString = "";
        $report = array("all" => "", "1" => "", "2" => "");
        
        if (!empty($data) && count($data) > 0) {

            foreach($data as $row) {
                $dateOfPlay = $row->date_start;
                $dateOfAdj = $row->adjustment_date;
                $status = $row->status;
                $notes = $row->notes;
                $locId = $row->loc_id;
                $debitType = $row->debit_type_id;
                
                if ($status == 1) {
                    $notes .= " OR Location is closed";
                }
                
                $prefix = $debitTypeTemplate[$status][$debitType];
                if ($notes == "CLOSED") {
                    $prefix = preg_replace('/"color:.+?"/', '"color:orange"', $prefix);
                }
                
                $reportString = "$prefix $dateOfPlay - $locId: $notes </span> <br>";                
                
                $reportAllString .= $reportString;
                $report[$debitType] .= $reportString;
                
            }
        }
        else {
            $reportAllString = "No Adjustments Were Needed!";              
        }
        
        $report['all'] = $reportAllString;
        
        return $report;
    }
    public static function sendRetrySyncReportEmail($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),            
            'location' => null,
            'retryReports' => array(),
            '_task' => array(),
            '_logger' => null,
        ), $params));

        $task =$_task;
        $isTest = $task->is_test_mode;
        
        $retryReportAll = $retryReports['all'];
        $retryReportSacoa = $retryReports[1];
        $retryReportEmbed = $retryReports[2];
        
        
        
        if (!empty($retryReportSacoa)) {
            $sacoaReport = "<b><u>Missing Data for the Following Locations:</u></b><br>
					    <b>$retryReportSacoa</b>
					    <br><br>
					    Thanks,<br>
					    Nate";
            self::sendEmailReport(array(
                'to' => 'support@fegllc.com', 
                'subject' => "Sacoa->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $sacoaReport, 
                'cc' => '', 
                'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
                'isTest' => $isTest,
            ));              
        }
        if (!empty($retryReportEmbed)) {
            $embedReport = "<b><u>Missing Data for the Following Locations:</u></b><br>
                    <b>$retryReportEmbed</b>
                    <br><br>
                    Thanks,<br>
                    Nate";
            self::sendEmailReport(array(
                'to' => 'support@fegllc.com', 
                'subject' => "Embed->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $embedReport, 
                'cc' => '', 
                'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
                'isTest' => $isTest,
            ));              
        }
        
        self::sendEmailReport(array(
            'to' => 'support@fegllc.com', 
            'subject' => "DB Transfer Failure and Adjustment Summary as of $humanDateToday", 
            'message' => $retryReportAll, 
            'cc' => '', 
            'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
            'isTest' => $isTest,
        ));              
 
    }        
    
    public static function getDailyGameSummaryReport($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params)); 
         
        
        $report = array();
        //Locations Not Reporting: Either Closed or Error in Data Transfer (cashed)
        $report[] = '<h5>Daily Games Earnings Summary</h5><br><br>
            <b style="text-decoration:underline">Locations Not Reporting</b>: 
            <em>Either Closed or Error in Data Transfer</em><br>';
        $locationsNotReportingReport = isset(self::$reportCache['locationsNotReportingReport']) ?
                self::$reportCache['locationsNotReportingReport'] : self::getLocationsNotReportingReport($params);
        $report[] = $locationsNotReportingReport;
        
        // Games Down for 7+ Days (cache)
        $report[] = '<br><b style="text-decoration:underline">Games Down for 7+ Days:</b><br>';
        
        $gamesDownMoreThanAWeek = self::$reportCache['gamesNotPlayedMoreThanAWeek'];
        if (!empty($gamesDownMoreThanAWeek)) {
            $countIndex = 0;
            foreach($gamesDownMoreThanAWeek as $row) {
                $countIndex++;
                $gameId = $row['game_id'];
                $gameTitle = $row['game_name'];
                $locationId = $row['location_id'];
                $locationName = $row['location_name'];
                $downForText = $row['days_not_played_text'];

                $report[] = "$countIndex.) <b>$gameId | $gameTitle</b> 
                        at <b>$locationId ($locationName)</b> $downForText<br>";
            }            
        }
        else {
            $report[] = "<br>None<br>"; 
        }        
        
//        //Games Down for 7+ Days (from database)        
//        $report[] = '<br><b style="text-decoration:underline">Games Down for 7+ Days:</b><br>';// 
//        $q = ReportHelpers::getGamesNotPlayedQuery($date, $date, 
//                null, null, null, "all", "", "", "", "");
//        $gamesDownMoreThanAWeek = DB::select($q);
//        if (!empty($gamesDownMoreThanAWeek)) {
//            $countIndex = 0;
//            foreach($gamesDownMoreThanAWeek as $row) {
//
//                $daysNotPlayed = $row->days_not_played;
//                
//                if ($daysNotPlayed > 6) {
//                    $countIndex++;
//                    $locationId = $row->location_id;
//                    $locationName = $row->location_name;
//                    $gameId = $row->game_id;
//                    $isOnTest = $row->game_on_test == 'Yes';
//                    $gameTitleOriginal = $row->game_name;
//                    $gameTitle = ($isOnTest ? "**TEST** " : "") . $gameTitleOriginal;
//                    
//                    $downForText = "<em style=\"color:red\"> - down for <b>$daysNotPlayed days<b></em>";
//                    
//                    $report[] = "$countIndex.) <b>$gameId | $gameTitle</b> 
//                            at <b>$locationId ($locationName)</b> $downForText<br>";
//                }
//            }            
//        }
//        else {
//            $report[] = "<br>None<br>"; 
//        }
        
        // Top 25 - 7 Day Period (new)
        $report[] = '<br><b style="text-decoration:underline">Top 25 - 7 Day Period</b><br>';        
        $dateStart = date("Y-m-d", strtotime("$date -6 days"));
        $q = ReportHelpers::getGameRankQuery($dateStart, $date, $location,
                                "", "", "all", "", "game_average", "desc");
        $q .= " LIMIT 25";
        $top25GamesThisWeekData = DB::select($q);
        $topGamesTable = array(); 
        $blackBrdStyle = "style='border:1px solid black;'";
        $greyBrdStyle = "style='border:1px solid grey;'";        
        if (!empty($top25GamesThisWeekData) && count($top25GamesThisWeekData) > 0) {
            $topGamesTable[] = "<table style='margin:0px auto; 
                width:100%; border:1px solid black; color:black;'>";
            $topGamesTable[] = "<tr $blackBrdStyle>
                    <th $blackBrdStyle>No.</th>
                    <th $blackBrdStyle>Game</th>
                    <th $blackBrdStyle>Average</th>
                    <th $blackBrdStyle>Total</th>
                    <th $blackBrdStyle>Game Count</th>
                    <th $blackBrdStyle>Location Ids</th>
                    <th $blackBrdStyle>Location Names</th>
                    <th $blackBrdStyle>Game IDs</th>
                </tr>";
            foreach($top25GamesThisWeekData as $index => $row) {
                $rowIndex = $index+1;
                $gameTitle = $row->game_name;
                $gameCount = $row->game_count;
                $gameAverageRevenue = $row->game_average;
                $gameTotalRevenue = $row->game_total;
                $locationIds = $row->location_id;
                $locationNames = $row->location_name;
                $gameIds = $row->game_ids;
                
            $topGamesTable[] = "<tr style='color:black; border:thin black solid;'>
                    <td $greyBrdStyle>$rowIndex</td>
                    <td $greyBrdStyle>$gameTitle</td>
                    <td $greyBrdStyle>\${$gameAverageRevenue}</td>
                    <td $greyBrdStyle>\${$gameTotalRevenue}</td>
                    <td $greyBrdStyle>$gameCount</td>
                    <td $greyBrdStyle>$locationIds</td>
                    <td $greyBrdStyle>$locationNames</td>
                    <td $greyBrdStyle>$gameIds</td>
                </tr>";                
                
            }
            $topGamesTable[] = "</table>";
        }
        $topGamesTableString = implode("", $topGamesTable);
        $report[] = $topGamesTableString;
        $reportString = implode("", $report);
        
        return $reportString;
    }
    public static function sendDailyGameSummaryReportEmail($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),            
            'location' => null,
            'finalGameSummaryReport' => "",
            '_task' => array(),
            '_logger' => null,
        ), $params));

        $task =$_task;
        $isTest = $task->is_test_mode;        
        
        if (!empty($finalGameSummaryReport)) {
            self::sendEmailReport(array(
                'to' => 'nate.smith@fegllc.com,
                        rich.pankey@fegllc.com,
                        john.vaughn@fegllc.com, 
                        mark.nesfeder@fegllc.com,
                        tom.revolinsky@fegllc.com,
                        steve.paris@fegllc.com', 
                'subject' => "Games Summary - $humanDate", 
                'message' => $finalGameSummaryReport, 
                'cc' => '', 
                'bcc' => 'greg@element5digital.com,element5@fegllc.com',             
                'isTest' => $isTest,
            ));             
        }
    }
        
    
    public static function weekly($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'date_end' => date('Y-m-d', strtotime('-1 day')),
            'date_start' => date('Y-m-d', strtotime('-7 day')),
            'location' => null,
            '_task' => array(),
            '_logger' => null,
        ), $params));
        
        $task =$_task;
        $isTest = $task->is_test_mode;
        $params['isTestMode'] = $isTest;
        $params['humanDate'] = $humanDate = self::getHumanDate($date);
        $params['humanDateToday'] = $humanDateToday = self::getHumanDate($today);
        $params['humanDateStart'] = $humanDate = self::getHumanDate($date_start);
        $params['humanDateEnd'] = $humanDateToday = self::getHumanDate($date_end);
        
        $__logger = $_logger;        
        $__logger->log("Start Weekly Email Report $date_start - $date_end");
        //Potential Over-reporting Errors
        //Top 40 Games - 7 Day Period
        // Top 20 attractions - 7 day period (disabled)
        //Top Games on Test - 7 Day Period
        //Bottom 25 Games - 7 Day Period
        //Game Play Ranking by Location
        // Locations Not Reporting: Either Closed or Error in Data Transfer
        $__logger->log("End Weekly Email Report $date_start - $date_end");

    }
    
    
    
    
    
    
    public static function phpMail($to, $subject, $message, $from = "support@fegllc.com", $options = array()) {
        if (empty($from)) {
            $from = "support@fegllc.com";
        }
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
    
    public static function sendEmailReport($options) {        
        extract(array_merge(array(
            'from' => null,
        ), $options));
        
        if ($isTest) {
            //$testRecipients = self::getTestRecipients();
            $message =  "
*************** EMAIL START --- DEBUG INFO *******************<br>
[SUBJECT: $subject]<br>
[TO: $to]<br>
[CC: $cc]<br>
[BCC: $bcc]<br>                   
***************** DEBUG INFO END *****************************<br><br>
$message
******************************************* EMAIL END ********************************<br>";
            
            $subject .= "[TEST] ". $subject;
            $to = "e5devmail@gmail.com";
            $cc = "";
            $bcc = "";
            
            $message = str_ireplace(array("<br />","<br>","<br/>"), "\r\n", $message);
            
            self::logit($message, "email.log", "SystemEmailsDump");
            return;
        }
        
        $opt = array();
        if (!empty($cc)) {
            $opt['cc'] = $cc;
        }
        if (!empty($bcc)) {
            $opt['bcc'] = $bcc;
        }        
        
        self::phpMail($to, $subject, $message, $from, $opt);
    }
    public static function getLocationSystemEmailRecipients($location) {
        return "";
    }
    
    private static function getHumanDate($date = "") {
        $hDate = "";
        if (!empty($date)) {
            $hDate = date("l, F d Y", strtotime($date));
        }
        return $hDate;
    }
    

    private static function logit($obj = "", $file = "system-email-report.log", $pathsuffix ="") {
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
    private static function set_log_path($file = "system-email-report.log", $pathsuffix = "") {
        $fileprefix = "log-" . date("Ymd") . "-";
        $path = realpath(storage_path() . '/logs/').(empty($pathsuffix) ? "" : '/'.$pathsuffix);        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $filepath = $path . '/'. $fileprefix . $file;        
        return $filepath;
    }     
    
}
