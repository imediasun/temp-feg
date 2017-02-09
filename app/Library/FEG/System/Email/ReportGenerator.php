<?php

namespace App\Library\FEG\System\Email;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\ReportHelpers;
use App\Library\FEG\System\SyncHelpers;
use App\Library\FEG\System\FEGSystemHelper;

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
        
         
        $lf = "daily-transfer.log";
        $lp = "FEGCronTasks/Skim-Daily-Transfer";
        $__logger = $_logger;
        $__logger->log("Start Generate Daily Email Reports $date");
        $__logger->log("PARAMS:", $params);
        
        $task = $_task;
        $isTest = $task->is_test_mode;
        $params['isTestMode'] = $isTest;
        $params['humanDate'] = $humanDate = FEGSystemHelper::getHumanDate($date);
        $params['humanDateToday'] = $humanDateToday = FEGSystemHelper::getHumanDate($today);
        
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
        FEGSystemHelper::logit("Retry Sync Success Data: ", $lf, $lp);
        FEGSystemHelper::logit(self::$reportCache['retrySyncSuccessData'], $lf, $lp);        
        
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

            $reportName = 'Daily Game Earnings DB Transfer Report';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Game Earnings DB Transfer Report for $humanDate", 
                'message' => $message, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => $date,
            )));

            $__logger->log("        End Email Game Earnings DB Transfer Report for $date");
            $__logger->log("End Game Earnings DB Transfer Report for $date");
        }

        $reportingLocations = isset(self::$reportCache['locationsReportingIds']) ? 
                self::$reportCache['locationsReportingIds'] : self::getLocationsReportingIds($params);
        
        $__logger->log("Reporting Locations:", $reportingLocations);
        FEGSystemHelper::logit("Reporting Locations:", $lf, $lp);
        FEGSystemHelper::logit($reportingLocations, $lf, $lp);
        
        FEGSystemHelper::logit("Missing Asset IDs:", $lf, $lp);
        FEGSystemHelper::logit(self::$reportCache['missingAssetIdsPerLocation'], $lf, $lp);        
        
        FEGSystemHelper::logit("Games Not Played:", $lf, $lp);
        FEGSystemHelper::logit(self::$reportCache['gamesNotPlayedPerLocation'], $lf, $lp);        
        
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
        
        $reportName = 'Daily Transfer Bulk Fail';
        $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
        self::sendEmailReport(array_merge($emailRecipients, array(
            'subject' => "Transfer Failure $humanDate", 
            'message' => $statusReport, 
            'isTest' => $isTest,
            'reportName' => $reportName,
            'reportNameSuffix' => $date,           
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
               
        $lf = "daily-transfer.log";
        $lp = "FEGCronTasks/Skim-Daily-Transfer";
        $report = array();
        $hasReport = false;
        $reportString = "";
        FEGSystemHelper::logit("Location Wise Report: $location, $date ", $lf, $lp);        
        if (!empty($location) && !empty($date))  {
            $missingAssetIdsAll = isset(self::$reportCache['missingAssetIdsPerLocation']) ? 
                    self::$reportCache['missingAssetIdsPerLocation'] : array();
            $missingAssetIds = isset($missingAssetIdsAll[$location]) ? $missingAssetIdsAll[$location] : 
                array();//self::getMissingAssetIds($params);

            $gamesNotPlayedAll = isset(self::$reportCache['gamesNotPlayedPerLocation']) ? 
                    self::$reportCache['gamesNotPlayedPerLocation'] : array();
            $gamesNotPlayed = isset($gamesNotPlayedAll[$location]) ? $gamesNotPlayedAll[$location] : 
                array();//self::getGamesNotPlayed($params);
            
            FEGSystemHelper::logit("Missing Asset ID for Location $location", $lf, $lp);
            FEGSystemHelper::logit($missingAssetIds, $lf, $lp);
            FEGSystemHelper::logit("Games Not Played for Location $location", $lf, $lp);
            FEGSystemHelper::logit($gamesNotPlayed, $lf, $lp);
            
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
            $reportName = 'Daily games summary for each location';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName, $location);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary for location  $location - $humanDate", 
                'message' => $dailyReport, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => "$location - $humanDate",                
            )));              
        }
        else {
            $reportName = 'Daily games summary for each location - No Issue';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName, $location);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary for location  $location - $humanDate  [NO ISSUES]", 
                'message' => $dailyReport, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => "$location - $humanDate",                
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
    public static function getMissingDataReport ($params = array()) {
        $lp = 'FEGCronTasks/Missing-Data-Report';
        extract(array_merge(array(
            'date' => null,
            'date_start' => null,
            'date_end' => null,
            'location' => null,
            'debit' => null,
            'reader' => null,
            'game' => null,
            'sortBy' => '',
            'missing_asset_sortBy' => 'reader_id',
            'bad_reader_sortBy' => 'loc_game_title',
            'bad_asset_sortBy' => 'game_id',
            'order' => '',
            'missing_asset_order' => 'asc',
            'bad_reader_order' => 'asc',
            'bad_asset_order' => 'asc',
            'flatData' => 0,
            '_task' => array(),
            '_logger' => null,
        ), $params));  

        if (empty($date_start) && empty($date_end)) {         
            if (empty($date)) {
                $date = date('Y-m-d', strtotime('-1 day'));
            }
            $date_start = $date_end = $date; 
        }
        else {
            if (empty($date_end)) {
                $date_end = $date_start;
            }
            elseif(empty($date_start)) {
                $date_start = $date_end;
            }
        }
        
        $isFlatData = $flatData == 1;
        $report = array();        
                        
        $missingAssetIdData = array();
        $thisSortBy = empty($missing_asset_sortBy) ? (empty($sortBy) ? 'reader_id': $sortBy) : $missing_asset_sortBy;
        $thisOrderBy = empty($missing_asset_order) ? (empty($order) ? 'asc': $sortBy) : $missing_asset_order;
        $q = ReportHelpers::getReadersMissingAssetIdQuery($date_start, $date_end, 
            $location, $debit, $reader, $thisSortBy, $thisOrderBy);        
        $data = DB::select($q);
        foreach($data as $index => $row) {
            $dateStart = FEGSystemHelper::getHumanDate($row->date_start);
            $locationId = $row->location_id;
            $locationName = $row->location_name_short;
            $locationIDName = $locationName.' ('.$locationId.')';
            $locationGameName = $row->loc_game_title;
            $readerIdOriginal = $row->reader_id;
            $readerId = preg_replace('/^.*\_/', "", $readerIdOriginal);
            $gameTotalOriginal = $row->game_total;
            $gameTotal = number_format(floatval($gameTotalOriginal), 2);
            if (empty($readerId)) {
                $readerId = "MISSING";
            }
            if (empty($locationGameName)) {
                $locationGameName = "NOT RETRIEVED";
            }
            
            $missingAsset = array(               
                "Location ID" => $locationId,
                "Location Name" => htmlentities($locationName),
                "Location Game Name" => htmlentities($locationGameName),
                "Reader ID" => htmlentities($readerId),
                "Date" => FEGSystemHelper::getHumanDate($dateStart),
                "Game Revenue" => $gameTotal,
                "_Game Revenue Original" => $gameTotalOriginal,
                "_Asset ID" => 0,
                "_Reader ID" => $readerIdOriginal,                
            );
            if ($isFlatData) {
                $missingAssetIdData[] = $missingAsset;
            }
            else {
                if (empty($missingAssetIdData[$locationIDName])) {
                    $missingAssetIdData[$locationIDName] = array();
                }
                $missingAssetIdData[$locationIDName][] = $missingAsset;                
            }
            
        }
        if (!empty($missingAssetIdData)) {
            $report['missingAssetIDs'] = $missingAssetIdData;
        }        
        
        $badReadersData = array();
        $thisSortBy = empty($bad_reader_sortBy) ? (empty($sortBy) ? 'loc_game_title': $sortBy) : $bad_reader_sortBy;
        $thisOrderBy = empty($bad_reader_order) ? (empty($order) ? 'asc': $order) : $bad_reader_order;
        $q = ReportHelpers::getMissingReadersQuery($date_start, $date_end, 
            $location, $debit, $game, $thisSortBy, $thisOrderBy);    
        FEGSystemHelper::logit($q, 'getMissingReadersQuery.sql.log', $lp);
        $data = DB::select($q);
        foreach($data as $index => $row) {
            $dateStart = $row->date_start;
            $locationId = $row->location_id;
            $locationName = $row->location_name_short;
            $locationIDName = $locationName.' ('.$locationId.')';
            $locationGameName = $row->loc_game_title;
            $readerId = $row->reader_id;
            $gameId = $row->game_id;
            $gameTotalOriginal = $row->game_total;
            $gameTotal = number_format(floatval($gameTotalOriginal), 2);
            if (empty($gameId)) {
                $gameId = "MISSING";
            }
            if (empty($readerId)) {
                $readerId = "MISSING";
            }
            if (empty($locationGameName)) {
                $locationGameName = "NOT RETRIEVED";
            }
            
            $badReader = array(
                "Location ID" => $locationId,
                "Location Name" => htmlentities($locationName),
                "Location Game Name" => htmlentities($locationGameName),
                "Reader ID" => htmlentities($readerId),
                "Asset ID" => $gameId,
                "Date" => FEGSystemHelper::getHumanDate($dateStart),
                "Game Revenue" => $gameTotal,
                "_Game Revenue Original" => $gameTotalOriginal,
            );
            
            if ($isFlatData) {
                $badReadersData[] = $badReader;
            }
            else {
                if (empty($badReadersData[$locationIDName])) {
                    $badReadersData[$locationIDName] = array();   
                }
                $badReadersData[$locationIDName][] = $badReader;
            }            

        }
        if (!empty($badReadersData)) {
            $report['badReaderIDs'] = $badReadersData;
        }
        
        $badAssetIDData = array();
        $thisSortBy = empty($bad_asset_sortBy) ? (empty($sortBy) ? 'game_id': $sortBy) : $bad_asset_sortBy;
        $thisOrderBy = empty($bad_asset_order) ? (empty($order) ? 'asc': $order) : $bad_asset_order;
        $q = ReportHelpers::getUnknownAssetIdQuery($date_start, $date_end, 
            $location, $debit, $reader, $thisSortBy, $thisOrderBy);
        DB::statement("SET SESSION group_concat_max_len = 1000000;");
        $data = DB::select($q);
        foreach($data as $index => $row) {
            $dateStart = $row->date_start;
            $locationId = $row->location_id;
            $locationName = $row->location_name_short;
            $locationIDName = $locationName.' ('.$locationId.')';
            $locationGameName = $row->loc_game_title;
            $readerId = $row->reader_id;
            $gameId = $row->game_id;
            $gameTotalOriginal = $row->game_total;
            $gameTotal = number_format(floatval($gameTotalOriginal), 2);
            if (empty($gameId)) {
                $gameId = "MISSING";
            }
            if (empty($readerId)) {
                $readerId = "MISSING";
            }
            if (empty($locationGameName)) {
                $locationGameName = "NOT RETRIEVED";
            }

            $badAsset = array(
                "Location ID" => $locationId,
                "Location Name" => htmlentities($locationName),
                "Location Game Name" => htmlentities($locationGameName),
                "Reader ID" => htmlentities($readerId),
                "Asset ID" => $gameId,
                "Date" => FEGSystemHelper::getHumanDate($dateStart),
                "Game Revenue" => $gameTotal,
                "_Game Revenue Original" => $gameTotalOriginal,
            );
            
            if ($isFlatData) {
                $badAssetIDData[] = $badAsset;
            }
            else {
                if (empty($badReadersData[$locationIDName])) {
                    $badAssetIDData[$locationIDName] = array();
                }        
                $badAssetIDData[$locationIDName][] = $badAsset;
            }            
        }
        
        if (!empty($badAssetIDData)) {
            $report['badAssetIDs'] = $badAssetIDData;
        }
        
        return $report;
    }
    public static function missingDataReport ($params = array()) {
        $timeStart = microtime(true);        
        global $__logger;
        $lf = 'MissingDataReports.log';
        $lp = 'FEGCronTasks/Missing-Data-Report';        
        extract(array_merge(array(
            '_task' => array(),
            '_logger' => null,
            'today' => date('Y-m-d'),            
            'date' => null,
            'date_start' => null,
            'date_end' => null,             
            'flatData' => 0,
        ), $params));    
        
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'MissingData');
        $params['_logger'] = $__logger = $L;  
        $__logger = $L;
        
        if (empty($date_start) && empty($date_end)) {         
            if (empty($date)) {
                $date = date('Y-m-d', strtotime('-1 day'));
            }
            $date_start = $date_end = $date; 
        }
        else {
            if (empty($date_end)) {
                $date_end = $date_start;
            }
            elseif(empty($date_start)) {
                $date_start = $date_end;
            }
        }
        $isFlatData = $flatData == 1;
        
        $tableStyle = 'border: 1px solid #888; padding: 0; margin: 0; width: 100%; border-collapse: collapse; font-family: tahoma, arial, sans-serif; font-size: 95%; color: #555;';
        $cellStyle = 'border: 1px solid #888; padding: 5px; margin: 0; border-collapse: collapse;';       
        $thStyle = 'background-color: #ddd; font-weight: bold; border-collapse: collapse;';
        $tdStyle = '';
        
        
        $dStart = new \DateTime($date_start);
        $dEnd  = new \DateTime($date_end);
        $dDiff = $dStart->diff($dEnd);   
        $days = $dDiff->days + 1;
   
        $isTest = $_task->is_test_mode;
        $humanDate = FEGSystemHelper::getHumanDate($date);
        $humanDateToday = FEGSystemHelper::getHumanDate($today);
        $humanDateStart = FEGSystemHelper::getHumanDate($date_start);
        $humanDateEnd = FEGSystemHelper::getHumanDate($date_end);
        if ($date_start == $date_end) {
            $reportPrefix = "";
            $reportSuffix = "$date_start";
            $logInfo = " $date_start";
            $humanDateRange = "$humanDateStart";
        }
        else {
            $reportPrefix = "";
            $reportSuffix = "$date_start - $date_end";
            $logInfo = " $date_start - $date_end ($days days)";
            $humanDateRange = "$humanDateStart - $humanDateEnd ($days days)";
        }
        
        FEGSystemHelper::logit("Start Processing Missing data for - $logInfo", $lf, $lp);
        
        $reportData = self::getMissingDataReport($params);
        FEGSystemHelper::logit("        Data processed");
        
        $messages = array("<div style='font-family: tahoma, arial, sans-serif; color: #333; font-size: 90%;'>");
        $messages[] = "<h2>Missing  Data Report</h2><br/>";
        if (empty($reportData)) {
            FEGSystemHelper::logit("    No missing data found", $lf, $lp);
            $messages[] = "<b style='color:green;'>Congrats! No issues found.</b>";
        }
        else {
            if (!empty($reportData['missingAssetIDs'])) {
                FEGSystemHelper::logit("    Missing Asset ID found", $lf, $lp);
                $messages[] = "<h3 style='color:red;'>Missing Asset IDs</h3>";
                $messages[] = "<p style='color:grey;'>The following Locations have reported 
                    data with missing Asset IDs. Please update the respective 
                    games based on the reader ids or the game names at location with 
                    correct Asset ID</p>";
                
                if ($isFlatData) {
                    $messages[] = FEGSystemHelper::tableFromArray($reportData['missingAssetIDs'], array(
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                        ));
                }
                else {
                    foreach($reportData['missingAssetIDs'] as $locationIDName => $items) {
                        $data = FEGSystemHelper::joinArray($items, ['Location Game Name', 'Reader ID'], 
                                array('Date'), 
                                array('Game Revenue'), 
                                array('Location ID', 'Location Name'));
                        $messages[] = "<h4>Report for Location: $locationIDName</h4>";
                        $messages[] = FEGSystemHelper::tableFromArray($data, array(
                                'cellWidths' => array('Location Game Name' => '40%', 'Reader ID' => '20%', 'Date' => '20%', 'Game Revenue' => '20%'),
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                            ));
                    }
                }
                $messages[] = "<p>&nbsp;</p><hr/><p>&nbsp;</p>";
                
            }
            if (!empty($reportData['badReaderIDs'])) {
                FEGSystemHelper::logit("    Missing or Incorrect Reader ID found", $lf, $lp);
                $messages[] = "<h3 style='color:red;'>Missing or Incorrect Reader IDs</h3>";
                $messages[] = "<p style='color:grey;'>The following Locations have reported 
                    data with incorrect or invalid Reader IDs.</p>";
                
                if ($isFlatData) {
                    $messages[] = FEGSystemHelper::tableFromArray($reportData['badReaderIDs'], array(
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                        ));
                }
                else {
                    $data = array();     
                    foreach($reportData['badReaderIDs'] as $locationIDName => $items) {
                        $data = FEGSystemHelper::joinArray($items, ['Location Game Name', 'Reader ID', 'Asset ID'], 
                                array('Date'), 
                                array('Game Revenue'), 
                                array('Location ID', 'Location Name'));
                        $messages[] = "<h4>Report for Location: $locationIDName</h4>";
                        $messages[] = FEGSystemHelper::tableFromArray($data, array(
                                'cellWidths' => array('Location Game Name' => '40%', 'Reader ID' => '15%', 'Asset ID' => '15%',  'Date' => '15%', 'Game Revenue' => '15%'),
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                            ));
                    }                    
                } 
                $messages[] = "<p>&nbsp;</p><hr/><p>&nbsp;</p>";
            }
            if (!empty($reportData['badAssetIDs'])) {
                FEGSystemHelper::logit("    Unknown Asset ID found", $lf, $lp);
                $messages[] = "<h3 style='color:red;'>Unknown Asset ID</h3>";
                $messages[] = "<p style='color:grey;'>The following Locations have reported 
                    data with Asset IDs not matching in FEG Admin. 
                    Please update the respective 
                    games based on the reader ids or the game names at location with 
                    correct Asset ID</p>";
                
                if ($isFlatData) {
                    $messages[] = FEGSystemHelper::tableFromArray($reportData['badAssetIDs'], array(
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                        ));
                }
                else {
                    $data = array();
                    foreach($reportData['badAssetIDs'] as $locationIDName => $items) {
                        $data = FEGSystemHelper::joinArray($items, ['Location Game Name', 'Asset ID'], 
                                array('Date'), 
                                array('Game Revenue'), 
                                array('Location ID', 'Location Name'));
                        $messages[] = "<h4>Report for Location: $locationIDName</h4>";
                        $messages[] = FEGSystemHelper::tableFromArray($data, array(
                                'cellWidths' => array('Location Game Name' => '40%', 'Reader ID' => '15%', 'Asset ID' => '15%',  'Date' => '15%', 'Game Revenue' => '15%'),
                                'tableStyles' => $tableStyle,
                                'cellStyles' => $cellStyle,
                                'THStyles' => $thStyle,
                                'TDStyles' => $tdStyle,
                        ));
                    }                    
                } 
                $messages[] = "<p>&nbsp;</p><hr/><p>&nbsp;</p>";
            }
        }
        $messages[] = "</div>";
        $message = implode('<br>', $messages);
        
        FEGSystemHelper::logit("    Start sending email", $lf, $lp);
        $reportName = 'Daily Missing Asset ID Reader ID Unknown Asset ID Report';
        $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
        self::sendEmailReport(array_merge($emailRecipients, array(
            'subject' => "FEG Missing Data (Asset ID, Reader ID, Unknown Asset ID) Report for $humanDateRange", 
            'message' => $message, 
            'isTest' => $isTest,
            'reportName' => $reportName,
            'reportNamePrefix' => $reportPrefix,
            'reportNameSuffix' => $reportSuffix,
        ))); 
        FEGSystemHelper::logit("    End sending email", $lf, $lp);
        FEGSystemHelper::logit("End Processing Missing data for - $logInfo", $lf, $lp);
        $timeEnd = microtime(true);
        $timeDiff = round($timeEnd - $timeStart);
        $timeDiffHuman = FEGSystemHelper::secondsToHumanTime($timeDiff);
        $timeTaken = "Time taken: $timeDiffHuman ";
        return $timeTaken;
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
        $rowIndex = 0;
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
                $rowIndex++;                
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
            $reportName = 'Daily Potential Over-reporting Errors Report';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Potential Over-reporting Errors Check for $humanDate", 
                'message' => $message, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => "$humanDate",
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
                
                $prefix = "" . @$debitTypeTemplate[$status][$debitType];
                if ($notes == "CLOSED") {
                    $prefix = preg_replace('/"color:.+?"/', '"color:orange"', $prefix);
                }
                
                $reportString = "$prefix $dateOfPlay - $locId: $notes </span> <br>";                
                
                $reportAllString .= $reportString;
                if (!empty($debitType)) {
                    $report[$debitType] .= $reportString;
                }
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
            
            $reportName = 'Daily Sacoa Data Transfer Failure and Status';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Sacoa->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $sacoaReport, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => $humanDateToday,
                
            )));            
        }
        
        if ($noRetrySyncEmbed != 1 && !empty($retryReportEmbed)) {
            $embedReport = "<b><u>Missing Data for the Following Locations:</u></b><br>
                    <b>$retryReportEmbed</b>
                    <br><br>
                    Thanks,<br>
                    Nate";

            $reportName = 'Daily Embed Data Transfer Failure and Status';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Embed->FEG: Data Transfer Failure status as of $humanDateToday", 
                'message' => $embedReport, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => $humanDateToday,
            )));              
        }
        
        if ($noRetrySync != 1) { 
            $reportName = 'Daily Data Transfer Failure Summary';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Data Transfer Failure Summary as of $humanDateToday", 
                'message' => $retryReportAll, 
                'isTest' => $isTest,
                'reportName' => $reportName, 
                'reportNameSuffix' => $humanDateToday,
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
            $reportName = 'Daily Games Summary';
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName); 
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "Games Summary - $humanDate", 
                'message' => $finalGameSummaryReport, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => $humanDate,
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
        $params['humanDate'] = $humanDate = FEGSystemHelper::getHumanDate($date);
        $params['humanDateToday'] = $humanDateToday = FEGSystemHelper::getHumanDate($today);
        $params['humanDateStart'] = $humanDateStart = FEGSystemHelper::getHumanDate($date_start);
        $params['humanDateEnd'] = $humanDateEnd = FEGSystemHelper::getHumanDate($date_end);
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
        $reportName = 'Weekly games summary';
        $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName);
            self::sendEmailReport(array_merge($emailRecipients, array(
                'subject' => "FEG Weekly Games Summary | $humanDateRange", 
                'message' => $message, 
                'isTest' => $isTest,
                'reportName' => $reportName,
                'reportNameSuffix' => $humanDateRange,
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
                $playDateHuman = FEGSystemHelper::getHumanDate($playDate);
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
            $th = FEGSystemHelper::getHumanDate($date);
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
    
    public static function sendEmailReport($options) {  
        
        $lp = 'FEGCronTasks/SystemEmails';
        $lpd = 'FEGCronTasks/SystemEmailsDump';
        extract(array_merge(array(
            'from' => "support@fegllc.com",
            'reportName' => "Test",
            'reportNamePrefix' => "",
            'reportNameSuffix' => "",
        ), $options));
        
        $reportNameSanitized = preg_replace('/[\W]/', '-', strtolower($reportName));
        $lf = "email-"
                . (empty($reportNamePrefix)? "" : "{$reportNamePrefix}-")
                . $reportNameSanitized
                . (empty($reportNameSuffix)? "" : "-{$reportNameSuffix}")
                . ".log";
        
        if ($isTest) {
            
            $message =  "
*************** EMAIL START --- DEBUG INFO *******************<br>
[SUBJECT: $subject]<br/>
[TO: $to]<br/>
[FROM: $from]<br/>
[CC: $cc]<br/>
[BCC: $bcc]<br/>                   
***************** DEBUG INFO END *****************************<br><br>
$message
******************************************* EMAIL END ********************************<br>";
            
            $subject = "[TEST] ". $subject;
            $emailRecipients = FEGSystemHelper::getSystemEmailRecipients($reportName, null, true);
            $to = $emailRecipients['to'];
            $cc = $emailRecipients['cc'];
            $bcc = $emailRecipients['bcc'];
            if (empty($to)) {
                $to = "e5devmail@gmail.com";
            }
            
            $messageLog = str_ireplace(array("<br />","<br>","<br/>"), "\r\n", $message);
            
//            FEGSystemHelper::logit("to: " .$to, "email-{$reportNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("cc: " .$cc, "email-{$reportNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("bcc: " .$bcc, "email-{$reportNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
//            FEGSystemHelper::logit("subject: " .$subject, "email-{$reportNameSanitized}.log", "FEGCronTasks/SystemEmailsDump");
            
            FEGSystemHelper::logit($messageLog, "{$lf}.html", $lpd, true);
        }
        
        $opt = array();
        if (!empty($cc)) {
            $opt['cc'] = $cc;
        }
        if (!empty($bcc)) {
            $opt['bcc'] = $bcc;
        }        
        FEGSystemHelper::logit("Sending Email", $lf, $lp);
        FEGSystemHelper::sendEmail($to, $subject, $message, $from, $opt);
        FEGSystemHelper::logit("Email sent", $lf, $lp);
    }


}
