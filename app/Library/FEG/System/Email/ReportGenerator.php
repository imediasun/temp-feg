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
             
            'noTransferStatus' => 0,
             
            'noTransferSummary' => 0,
            'noDumpStatus' => 0,
            'noClosed' => 0,
            'noDownGames' => 0,
            'noMissingAssetIds' => 0,
             
            'noLocationwise' => 0,
             
            'noOverReporting' => 0,
             
            'noRetrySync' => 0,
            'noRetrySyncSacoa' => 0,
            'noRetrySyncEmbed' => 0,
             
            'noDailyGameSummary' => 0,
            'noDailyGameSummaryClosed' => 0,
            'noDailyGameSummaryDownGames' => 0,
            'noDailyGameSummaryTop25' => 0,
             
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
            $__logger->log("        daily Transfer Status Report: ", $dailyTransferStatusReport);
            $__logger->log("        dailyTransferStatus array: ", $dailyTransferStatus);
            if (!$dailyTransferStatus['1'] || !$dailyTransferStatus['2']) {
                if ($noTransferStatus != 1) {
                    self::dailyTransferFailReportEmail($params);
                }
                
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
        if ($noClosed != 1) {
            $locationsNotReportingReport = self::getLocationsNotReportingReport($params);            
        }
         // Readers Missing Asset Ids
        if ($noMissingAssetIds != 1) {        
            $readersMissingAssetIds = self::getReadersMissingAssetIdsReport($params);
        }
        // Games Not Played:
        if ($noDownGames != 1) {   
            $gamesNotPlayed = self::getGamesNotPlayedReport($params);
        }
        
        $__logger->log("        End processing Game Earnings DB Transfer Report for $date");
        
        if ($noTransferSummary != 1) {
            $message = $dailyTransferStatusReport .
                    "<br><br><b><u>Locations Not Reporting:</u></b><em>(Either Closed or Error in Data Transfer)</em><br>" .
                    @$locationsNotReportingReport .
                    "<br><b><u>Readers Missing Asset Ids:</u></b><br>" .
                    @$readersMissingAssetIds .
                    "<br><b><u>Games Not Played:</u></b><br>" .
                    @$gamesNotPlayed;

            $emailRecipients = self::getSystemReportEmailRecipients('Daily Game Earnings DB Transfer Report');
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Game Earnings DB Transfer Report for $humanDate", 
                'message' => $message, 
                'isTest' => $isTest,
            )));

            $__logger->log("        End Email Game Earnings DB Transfer Report for $date");
            $__logger->log("End Game Earnings DB Transfer Report for $date");
        }

        $reportingLocations = isset(self::$reportCache['locationsReportingIds']) ? 
                self::$reportCache['locationsReportingIds'] : self::getLocationsReportingIds($params);
        
        $__logger->log("Reporting Locations:", $reportingLocations);
        self::logit("Reporting Locations:", "daily-transfer.log", "CleanLog");
        self::logit($reportingLocations, "daily-transfer.log", "CleanLog");
        
        self::logit("Missing Asset IDs:", "daily-transfer.log", "CleanLog");
        self::logit(self::$reportCache['missingAssetIdsPerLocation'], "daily-transfer.log", "CleanLog");        
        
        self::logit("Games Not Played:", "daily-transfer.log", "CleanLog");
        self::logit(self::$reportCache['gamesNotPlayedPerLocation'], "daily-transfer.log", "CleanLog");        
        
        if ($noLocationwise != 1) {
            // each location report
            $__logger->log("Start Locationwise Report for $date");
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
        }
        
        if ($noOverReporting != 1) {
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
        }
        
        if ($noRetrySync != 1) {
            // Adjustment sync (all, sacoa, embed)
            $__logger->log("Start retry sync report  as of $today");
            $retrySyncReportData = self::getRetrySyncReport($params);
            $params['retryReports'] = $retrySyncReportData;
            $__logger->log("    End processing retry sync report as of $today");
            self::sendRetrySyncReportEmail($params);
            $__logger->log("    End sending retry sync report  as of $today");
            unset($params['retryReports']);
            $__logger->log("End retry sync report  as of $today");
        }
        // final
        if ($noDailyGameSummary != 1) {
            $__logger->log("Start Final Games Summary Report for $date");
            $finalGameSummaryReport = self::getDailyGameSummaryReport($params);
            $__logger->log("    END processing Final Games Summary Report for $date");        
            $params['finalGameSummaryReport'] = $finalGameSummaryReport;
            self::sendDailyGameSummaryReportEmail($params);
            unset($params['finalGameSummaryReport']);
            $__logger->log("    END sending EMAIL of Final Games Summary Report for $date");        
            $__logger->log("END Final Games Summary Report for $date");
        }
        
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
            'status' => @self::$reportCache['syncStatus'],
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
        
        $emailRecipients = self::getSystemReportEmailRecipients('Daily Transfer Bulk Fail'); 
        self::sendEmailReport(array_merge($emailRecipients, array(
            'subject' => "Transfer Failure $humanDate", 
            'message' => $statusReport, 
            'isTest' => $isTest,
        )));       
        
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
            $emailRecipients = self::getSystemReportEmailRecipients('Daily games summary for each location', $location);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary for location  $location - $humanDate", 
                'message' => $dailyReport, 
                'isTest' => $isTest,
            )));              
        }
        else {
            $emailRecipients = self::getSystemReportEmailRecipients('Daily games summary for each location - No Issue', $location);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary for location  $location - $humanDate  [NO ISSUES]", 
                'message' => $dailyReport, 
                'isTest' => $isTest,
            )));             
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
            $gameTotal = number_format(floatval($gameTotalOriginal), 2);
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
                null, null, null, "all", "", "", "", "days_not_played", "DESC");
        
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
            'debit' => null,
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
        
        $data = DB::select(ReportHelpers::getPotentialOverReportingErrorQuery($startDate, $endDate, $location, $debit));
        
        $report = array();
        foreach($data as $row) {
            $dateStart = date("Y-m-d", strtotime($row->date_start));
            $locationId = $row->location_id;
            $locationName = $row->location_name;
            $gameId = $row->game_id;
            $gameOnTest = $row->game_on_test == "Yes";
            $gameTitleOriginal = $row->game_name;
            $gameTitle = ($gameOnTest ? "**TEST** " : "") . $gameTitleOriginal;                        
            $earnings =  number_format(floatval($row->game_total), 2);
            
            $report[] = ($startDate == $endDate ? "" : "[$dateStart]") .
                "<b>$gameId | $gameTitle</b> on 
                <b>$locationId ($locationName)</b> reported 
                    <b style='color:red;'>\${$earnings}</b> in earnings<br>";            
        }
        
        $reportString = implode("", $report);
        if (empty($reportString)) {
            $reportString = "None";
        }
        
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
        
        //"Daily Potential Over-reporting Errors Report"
        if (!empty($overReporting) && $overReporting != "None") {
            $message = "<b><u>Potential Over-reporting Errors:</u></b><br><br>$overReporting";
            $emailRecipients = self::getSystemReportEmailRecipients('Daily Potential Over-reporting Errors Report'); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Potential Over-reporting Errors Check for $humanDate", 
                'message' => $message, 
                'isTest' => $isTest,
            )));          
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
                
                if ($status == 1 && $notes != "CLOSED") {
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
            'noRetrySync' => 0,
            'noRetrySyncSacoa' => 0,
            'noRetrySyncEmbed' => 0,             
            '_task' => array(),
            '_logger' => null,
        ), $params));

        $task =$_task;
        $isTest = $task->is_test_mode;
        
        $retryReportAll = $retryReports['all'];
        $retryReportSacoa = $retryReports[1];
        $retryReportEmbed = $retryReports[2];
        
        if ($noRetrySyncSacoa != 1 && !empty($retryReportSacoa)) {
            $sacoaReport = "<b><u>Missing Data for the Following Locations:</u></b><br>
					    <b>$retryReportSacoa</b>
					    <br><br>
					    Thanks,<br>
					    Nate";
            
            $emailRecipients = self::getSystemReportEmailRecipients('Daily Sacoa Data Transfer Failure and Status'); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Sacoa->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $sacoaReport, 
                'isTest' => $isTest,
            )));            
        }
        
        if ($noRetrySyncEmbed != 1 && !empty($retryReportEmbed)) {
            $embedReport = "<b><u>Missing Data for the Following Locations:</u></b><br>
                    <b>$retryReportEmbed</b>
                    <br><br>
                    Thanks,<br>
                    Nate";

            $emailRecipients = self::getSystemReportEmailRecipients('Daily Embed Data Transfer Failure and Status'); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Embed->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $embedReport, 
                'isTest' => $isTest,
            )));              
        }
        
        if ($noRetrySync != 1) {            
            $emailRecipients = self::getSystemReportEmailRecipients('Daily Data Transfer Failure Summary'); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Data Transfer Failure Summary as of $humanDateToday", 
                'message' => $retryReportAll, 
                'isTest' => $isTest,
            )));            
        }
 
    }        
    
    public static function getDailyGameSummaryReport($params = array()) {
        global $__logger;
         extract(array_merge(array(
            'date' => date('Y-m-d', strtotime('-1 day')),
            'today' => date('Y-m-d'),
            'location' => null,
            'noDailyGameSummaryClosed' => 0,
            'noDailyGameSummaryDownGames' => 0,
            'noDailyGameSummaryTop25' => 0,               
            '_task' => array(),
            '_logger' => null,
        ), $params)); 
         
        
        $report = array();
        $report[] = '<h5>Daily Games Earnings Summary</h5><br><br>';
        
        //Locations Not Reporting: Either Closed or Error in Data Transfer (cashed)
        if ($noDailyGameSummaryClosed != 1) {
            $report[] = '<b style="text-decoration:underline">Locations Not Reporting</b>: 
                <em>Either Closed or Error in Data Transfer</em><br>';
            $locationsNotReportingReport = isset(self::$reportCache['locationsNotReportingReport']) ?
                    self::$reportCache['locationsNotReportingReport'] : self::getLocationsNotReportingReport($params);
            $report[] = $locationsNotReportingReport;
        }
        
        // Games Down for 7+ Days (cache)
        if ($noDailyGameSummaryDownGames != 1) {        
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
        if ($noDailyGameSummaryTop25 != 1) {              
            $report[] = '<br><b style="text-decoration:underline">Top 25 - 7 Day Period</b><br>';        
            $dateStart = date("Y-m-d", strtotime("$date -6 days"));
            $q = ReportHelpers::getGameRankQuery($dateStart, $date, $location,
                                    "", "", "all", "", "game_average", "desc");
            $q .= " LIMIT 25";
            $top25GamesThisWeekData = DB::select($q);
            $topGamesTable = array(); 
            $blackBrdStyle = "style='border:1px solid black; padding: 5px; margin: 0;'";
            $greyBrdStyle = "style='border:1px solid silver; padding: 5px; margin: 0;'";        
            $greyBrdTrStyle = "style='border:1px solid silver;'";        
            if (!empty($top25GamesThisWeekData)) {
                $topGamesTable[] = "<table style='margin:0px auto; 
                    width:100%; border:1px solid white; color:black;'>";
                $topGamesTable[] = "<tr $greyBrdTrStyle>
                        <th $blackBrdStyle>No.</th>
                        <th $blackBrdStyle width='200'>Game</th>
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
                    $gameAverageRevenue =  number_format(floatval($row->game_average),2);
                    $gameTotalRevenue = number_format(floatval($row->game_total),2);
                    $locationIds = $row->location_id;
                    $locationNames = $row->location_name;
                    $gameIds = $row->game_ids;

                $topGamesTable[] = "<tr style='color:black;'>
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
        }
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
            
            $emailRecipients = self::getSystemReportEmailRecipients('Daily Games Summary'); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary - $humanDate", 
                'message' => $finalGameSummaryReport, 
                'isTest' => $isTest,
            )));        
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
            'debit' => null,
             
            'noAttraction' => 1,
            'noTop' => 0,
            'noBottom' => 0,
            'noTest' => 0,
            'noOverreporting' => 0,
            'noRanking' => 0,
            'noClosed' => 0,
             
            'topGames' => 40,
            'topAttractions' => 20,
            'topTest' => 30,
            'bottomGames' => 25,
             
            '_task' => array(),
            '_logger' => null,
        ), $params));
         
        $report = array();
        $report[] = '<b style="color:red;">**Temporarily removed Attractions part while feed totals are being addressed**</b><br>';
        
        $dStart = new \DateTime($date_start);
        $dEnd  = new \DateTime($date_end);
        $dDiff = $dStart->diff($dEnd);   
        $days = $dDiff->days + 1;
   
        $task = $_task;
        $logInfo = " $date_start - $date_end ($days days)";
        $isTest = $task->is_test_mode;
        $params['isTestMode'] = $isTest;
        $params['humanDate'] = $humanDate = self::getHumanDate($date);
        $params['humanDateToday'] = $humanDateToday = self::getHumanDate($today);
        $params['humanDateStart'] = $humanDateStart = self::getHumanDate($date_start);
        $params['humanDateEnd'] = $humanDateEnd = self::getHumanDate($date_end);
        $humanDateRange = "$humanDateStart - $humanDateEnd ($days days)";
        
        $__logger = $_logger;        
        $__logger->log("Start Weekly Email Report $logInfo");
        
        //1. Potential Over-reporting Errors
        if ($noOverreporting != 1) {
        $report[] = '<br><b style="text-decoration:underline">Potential Over-reporting Errors ('. 
                $humanDateRange . ')</b><br>';
        $__logger->log("Start over reporting error Report $logInfo");
        $report[] = self::getOverreportingReport($params);         
        $__logger->log("  End processing of over reporting error Report $logInfo");
        }
        
        if ($noAttraction != 1) {
        //2. Top attractions - (disabled)
        $__logger->log("Start Top Attractions Report $logInfo");
        $report[] = '<br><br><b style="text-decoration:underline">Top '.$topAttractions.
                ' Attractions - '. $humanDateRange . '</b><br>';
        $q = ReportHelpers::getGameRankQuery($date_start, $date_end, $location,
                        $debit, "", "attractions", "notest", "game_average", "desc");
        $q .= " LIMIT $topAttractions";
        $gamesData = DB::select($q);
        $gamesTable = array("None"); 
        if (!empty($gamesData)) {
            $gamesTable = array();
            foreach($gamesData as $index => $row) {
                $rowIndex = $index+1;
                $gameTitle = $row->game_name;
                $gameCount = $row->game_count;
                $gameAverageRevenue = number_format(floatval($row->game_average), 2);
                $gameTotalRevenue = number_format(floatval($row->game_total), 2);
                $locationIds = $row->location_id;
                $locationNames = $row->location_name;
                
                $gamesTable[] = "$rowIndex.) $gameTitle [
                    <b style='color:blue'>$gameCount</b> <em>games averaged</em> 
                    <b style='color:green'>\${$gameAverageRevenue}</b> 
                    and totaled 
                    <b style='color:green'>\${$gameTotalRevenue}</b> ]
                    <span style='font-size:.9em; color:gray;'>
                    ($locationIds) ($locationNames)</span>";
            }
        }
        $report[] = implode("<br/>", $gamesTable);        
        $__logger->log("End processing Top Attractions Report $logInfo");
        }
        
        //3. Top Games 
        if ($noTop != 1) {
        $__logger->log("Start Top Games Report $logInfo");
        $report[] = '<br><br><b style="text-decoration:underline">Top '.$topGames.
                ' Non Attraction Games - '. $humanDateRange . '</b><br>';

        $q = ReportHelpers::getGameRankQuery($date_start, $date_end, $location,
                        $debit, "", "not_attractions", "notest", "game_average", "desc");
        $q .= " LIMIT $topGames";
        $gamesData = DB::select($q);
        $gamesTable = array("None"); 
        if (!empty($gamesData)) {
            $gamesTable = array();
            foreach($gamesData as $index => $row) {
                $rowIndex = $index+1;
                $gameTitle = $row->game_name;
                $gameCount = $row->game_count;
                $gameAverageRevenue = number_format(floatval($row->game_average), 2);
                $gameTotalRevenue = number_format(floatval($row->game_total), 2);
                $locationIds = $row->location_id;
                $locationNames = $row->location_name;
                
                $gamesTable[] = "$rowIndex.) $gameTitle [
                    <b style='color:blue'>$gameCount</b> <em>games averaged</em> 
                    <b style='color:green'>\${$gameAverageRevenue}</b> 
                    and totaled 
                    <b style='color:green'>\${$gameTotalRevenue}</b> ]
                    <span style='font-size:.9em; color:gray;'>
                    ($locationIds) ($locationNames)</span>";
            }
        }
        $report[] = implode("<br/>", $gamesTable);
        $__logger->log("End processing Top Games Report $logInfo");
        }
        
        //4. Top Games on Test 
        if ($noTest != 1) {
        $__logger->log("Start Test Games Report $logInfo");
        $report[] = '<br><br><b style="text-decoration:underline">Top '.$topTest.
                ' Games on Test - '. $humanDateRange . '</b><br>';
        $q = ReportHelpers::getGamesOnTestRankQuery($date_start, $date_end, $location, $debit);
        $q .= " LIMIT $topTest";
        $testGamesData = DB::select($q);
        $gamesTable = array("None"); 
        if (!empty($testGamesData)) {
            $gamesTable = array();
            foreach($testGamesData as $index => $row) {
                $rowIndex = $index+1;
                $gameId = $row->game_id;
                $gameTitle = $row->game_name;
                $locationId = $row->location_id;
                $locationName = $row->location_name;
                $gameTotalRevenue = number_format(floatval($row->game_total), 2);
                
                $gamesTable[] = "$rowIndex.) $gameId - $gameTitle - totaled 
                    <b style='color:green'>\${$gameTotalRevenue}</b> 
                    <span style='font-size:.9em; color:gray;'>
                    [$locationId - $locationName]</span>";
            }
        }
        $report[] = implode("<br/>", $gamesTable);
        $__logger->log("End processing Test Games Report $logInfo");          
        }
        
        //5. Bottom Games 
        if ($noBottom != 1) {
        $__logger->log("Start Bottom Games Report $logInfo");
        $report[] = '<br><br><b style="text-decoration:underline">Bottom '.$bottomGames.
                ' Games - '. $humanDateRange . '</b><br>';
        $q = ReportHelpers::getGameRankQuery($date_start, $date_end, $location,
                        $debit, "", "all", "notest", "game_average", "asc");
        $q .= " LIMIT $bottomGames";
        $gamesData = DB::select($q);
        $gamesTable = array("None"); 
        if (!empty($gamesData)) {
            $gamesTable = array();
            foreach($gamesData as $index => $row) {
                $rowIndex = $index+1;
                $gameTitle = $row->game_name;
                $gameCount = $row->game_count;
                $gameAverageRevenue = number_format(floatval($row->game_average), 2);
                $gameTotalRevenue = number_format(floatval($row->game_total), 2);
                $locationIds = $row->location_id;
                $locationNames = $row->location_name;
                
                $gamesTable[] = "$rowIndex.) $gameTitle [
                    <b style='color:blue'>$gameCount</b> <em>games averaged</em> 
                    <b style='color:red'>\${$gameAverageRevenue}</b> 
                    and totaled 
                    <b style='color:red'>\${$gameTotalRevenue}</b> ]
                    <span style='font-size:.9em; color:gray;'>
                    ($locationIds) ($locationNames)</span>";
            }
        }
        $report[] = implode("<br/>", $gamesTable);
        $__logger->log("End processing Bottom Games Report $logInfo");        
        }
        
        //6. Game Play Ranking by Location
        if ($noRanking != 1) {
            $__logger->log("Start Location Ranking Report $logInfo");
            $report[] = "<br><br><b style='text-decoration:underline'>
                Game Play Ranking by Location - Per Game Per Day (PGPD) Average 
                - $humanDateRange</b><br>";
            $q = ReportHelpers::getLocationRanksQuery($date_start, $date_end, 
                    $location, $debit, "pgpd_avg", "desc");
            $locationRanksData = DB::select($q);
            $rankTable = array("None"); 
            
            $trHeadStyle = "color:black; border:thin black solid";        
            $thStyle = "padding-left:3px; border:thin black solid;";        
            $bodyTrStyle = "color:black; vertical-align:top";        
            $tdStyle = "border: thin silver solid; ";        
            if (!empty($locationRanksData)) {
                $rankTable = array();
                $rankTable[] = "<table style='margin:0px auto; 
                    width:100%; border:1px solid white; color:black;'>";
                $rankTable[] = "<tr style='$trHeadStyle'>
                        <th style='$thStyle'>#</th>
                        <th style='$thStyle'>Locations</th>
                        <th style='$thStyle'>Debit System</th>
                        <th style='$thStyle'>PGPD AVG</th>                        
                        <th style='$thStyle'>Location Total</th>
                        <th style='$thStyle'>Game Count</th>
                        <th style='$thStyle'>Days Reported</th>
                    </tr>";    
                foreach($locationRanksData as $index => $row) {
                    $rowIndex = $index+1;
                    
                    $locationId = $row->id;
                    $locationName = $row->location_name;
                    $debitSystem = $row->debit_system;
                    
                    $total = number_format(floatval($row->location_total), 2);
                    $daysPlayed = $row->days_reported_count;
                    $totalDays = $days;//$row->days_count;
                    $games = $row->game_count;
                    $avg = number_format(floatval($row->pgpd_avg), 2);

                    $dateCountText = "<b style='color:green;'>FULL ($daysPlayed)</b>";
                    if($daysPlayed < $totalDays) {
                        $dateCountText = "<b style='color:red;'>PART ($daysPlayed)</b>";
                    }


                $rankTable[] = "<tr style='color:black;'>
                        <td style='$tdStyle;padding-left:3px; padding-right:2px;'>
                        $rowIndex</td>
                        <td style='$tdStyle;padding-left:3px;'>$locationId | 
                        $locationName</td>
                        <td style='$tdStyle;text-align:center;'>$debitSystem</td>
                        <td style='$tdStyle;padding-right:3px; text-align:right;'>\${$avg}</td>
                        <td style='$tdStyle;padding-right:3px; text-align:right;'>\${$total}</td>
                        <td style='$tdStyle;text-align:center;'>$games</td>
                        <td style='$tdStyle;text-align:center;'>$dateCountText</td>
                    </tr>";                

                }
                $rankTable[] = "</table>";                
            }            
            $report[] = implode("\r\n", $rankTable);
            
            $__logger->log("End processing Location Ranking Report $logInfo");
        }
        //7. Locations Not Reporting: Either Closed or Error in Data Transfer
        if ($noClosed != 1) {
            $__logger->log("Start Locations Not Reporting Report $logInfo");
            $report[] = "<br><br><b style='text-decoration:underline'>
                Locations Not Reporting - $humanDateRange</b>: 
                    <em>Either Closed or Error in Data Transfer</em><br>";
            $q = ReportHelpers::getLocationNotReportingQuery($date_start, $date_end, 
                    $location, $debit);
            $closedData = DB::select($q);             
            $closedTable = self::getClosedLocationWeeklyReport($closedData, $date_start, $date_end);  
            $report[] = $closedTable;
            $__logger->log("End processing Locations Not Reporting Report $logInfo");
        }
        
        $__logger->log("End Processing weekly Email Report $logInfo");
        $__logger->log("sending weekly email report $logInfo");
        
        $message = implode("", $report);
        $emailRecipients = self::getSystemReportEmailRecipients('Weekly games summary');
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "FEG Weekly Games Summary | $humanDateRange", 
                'message' => $message, 
                'isTest' => $isTest,
            )));
        
        $__logger->log("End sending weekly email report $logInfo");
        $__logger->log("End Weekly Email Report $logInfo");

    }
    
    public static function getClosedLocationWeeklyReport($data, $date_start, $date_end) {       
        $trHeadStyle = "color:black; border:thin black solid; vertical-align:top";        
        $thStyle = "padding-left:5px; border:thin black solid; text-align:center;";        
        $bodyTrStyle = "color:black; vertical-align:top";        
        $tdStyle = "padding-left:5px; border:1px solid silver; text-align:center;"; 

        $table = array("None");
        $header = array();
        $body = array();
        
        if (!empty($data)) {
            $table = array();
            foreach($data as $row) {
                $playDate = $row->not_reporting_date;
                $playDateHuman = self::getHumanDate($playDate);
                $debitType = $row->debit_system;
                $locId = $row->id;
                $locName = $row->location_name;
                
                if (empty($body[$playDate])) {
                    $body[$playDate] = "";
                }
                $body[$playDate] .= "$locId $locName <span style='color:red;'>
                    ($debitType)</span><br/>";
            }
        }  
        
        $dateStartTimestamp = strtotime($date_start);
        $dateEndTimestamp = strtotime($date_end);
        $currentDate = $dateStartTimestamp;
        $date = $date_start; 
        
        $table[] = "<table style='margin:0px auto; width:100%;'>";
        $table[] = "<tr style='$trHeadStyle'>";                
        while($currentDate <= $dateEndTimestamp) {
            $th = self::getHumanDate($date);
            $header[] = $date;
            $table[] = "<th style='$thStyle'>$th</th>";
            $currentDate = strtotime($date . " +1 day");
            $date = date("Y-m-d", $currentDate);
        }
        $table[] = "</tr>";
        
        if (count($header) > 0) {
            $table[] = "<tr style='$bodyTrStyle'>";
            foreach($header as $date) {
                $table[] = "<td style='$tdStyle'>";   
                if (isset($body[$date])) {
                    $td = $body[$date];
                    $table[] = $td; 
                }
                $table[] = "</td>";
            }
            $table[] = "</tr>";                
        }
        
        $table[] = "</table>";                
       
        $tableString = implode("\r\n", $table);
        
        return $tableString;
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
    
    public static function sendEmailReport($options) {        
        extract(array_merge(array(
            'from' => "support@fegllc.com",
            'reportName' => "Test",
        ), $options));
        
        if ($isTest) {
            
            $message =  "
*************** EMAIL START --- DEBUG INFO *******************<br>
[SUBJECT: $subject]<br>
[TO: $to]<br>
[FROM: $from]<br/>
[CC: $cc]<br>
[BCC: $bcc]<br>                   
***************** DEBUG INFO END *****************************<br><br>
$message
******************************************* EMAIL END ********************************<br>";
            
            $subject = "[TEST] ". $subject;
            $emailRecipients = self::getSystemReportEmailRecipients($reportName, null, true);
            $to = $emailRecipients['to'];
            $cc = $emailRecipients['cc'];
            $bcc = $emailRecipients['bcc'];
            if (empty($to)) {
                $to = "e5devmail@gmail.com";
            }
            
            $messageLog = str_ireplace(array("<br />","<br>","<br/>"), "\r\n", $message);
            
            $reportNameSanitized = preg_replace('/[\W]/', '-', strtolower($reportName));
            self::logit("to: " .$to, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
            self::logit("cc: " .$cc, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
            self::logit("bcc: " .$bcc, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
//            self::logit("subject: " .$subject, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
            self::logit(url(), "email-{$reportNameSanitized}.log", "SystemEmailsDump");
            self::logit(strpos(url(), "localhost") >= 0, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
            
            self::logit($messageLog, "email-{$reportNameSanitized}.log", "SystemEmailsDump");
//            if (strpos(url(), "localhost") >= 0) {
//                return;
//            }
            
        }
        
        $opt = array();
        if (!empty($cc)) {
            $opt['cc'] = $cc;
        }
        if (!empty($bcc)) {
            $opt['bcc'] = $bcc;
        }        
        self::logit("Sending Email", "email-{$reportNameSanitized}.log", "SystemEmailsDump");
        self::sendEmail($to, $subject, $message, $from, $opt);
        self::logit("Email sent", "email-{$reportNameSanitized}.log", "SystemEmailsDump");
    }
    public static function getSystemReportEmailRecipients($reportName, $location = null, $isTest = false) {
        $emails = array('reportName' => $reportName, 'to' => '', 'cc' => '', 'bcc' => '');
        $q = "SELECT * from system_email_report_manager WHERE report_name='$reportName' AND is_active=1 order by id desc";
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
                $users['to'] = empty($ut) ? array() : self::getUserEmails($ut);
                $users['cc'] = empty($ucc) ? array() : self::getUserEmails($ucc);
                $users['bcc'] = empty($ubcc) ? array() : self::getUserEmails($ubcc);
                
                $inclues['to'] = self::split_trim($data->to_include_emails);
                $inclues['cc'] = self::split_trim($data->cc_include_emails);
                $inclues['bcc'] = self::split_trim($data->bcc_include_emails);
                
                $excludes['to'] = array_merge(self::split_trim(
                        $data->to_exclude_emails), array(null, ''));
                $excludes['cc'] = array_merge(self::split_trim(
                        $data->cc_exclude_emails), array(null, ''));
                $excludes['bcc'] = array_merge(self::split_trim(
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

    public static function getLocationManagersEmails($fields, $location = null) {
//        $q = "SELECT id,contact_id, general_contact_id, field_manager_id,
//            tech_manager_id, merch_contact_id, merchandise_contact_id,
//            technical_contact_id, regional_contact_id, senior_vp_id, district_manager_id
//        FROM location WHERE active=1";
        $emails = array();
            if (!empty(trim($fields))) {
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
            if (!empty($ids)) {
                $emails = self::getUserEmails(implode(',', $ids));
            }
            
        }
        
        return $emails;
    }
        
    
    public static function getGroupsUserEmails($groups = null, $location = null) {
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
            $emails[] =  trim($email);
        }
        return $emails;
    }
    public static function getUserEmails($users = null) {
        $q = "SELECT DISTINCT email FROM users WHERE active=1 ";
        if (!empty($users)) {
            $q .= " AND id IN ($users)";
        }
        $data = DB::select($q);
        $emails = array();
        foreach($data as $row) {
            $email = $row->email;
            $emails[] = trim($email);
        }
        return $emails;
    }
    
    private static function getHumanDate($date = "") {
        $hDate = "";
        if (!empty($date)) {
            $hDate = date("l, F d Y", strtotime($date));
        }
        return $hDate;
    }
    private static function split_trim($txt, $delim = ',', $trimChar = null) {
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
