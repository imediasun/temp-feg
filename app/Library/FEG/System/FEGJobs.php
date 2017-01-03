<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\FEG\System\FEGSystemHelper;


class FEGJobs
{    
    private static $L;
    private static $possibleAdjustments;
    private static $limit = 1000;
    
    public static function cleanSummaryReports($params = array()) {
        global $__logger;
        $L = isset($params['_logger']) ? $params['_logger'] : 
                isset($__logger) ? $__logger :
                    new MyLog('cleanup-summary.log', 'earnings-summary', 
                                'EarningsSummary');

        $params['_logger'] = $L;
        if (empty($__logger)) {            
            $__logger = $L;
        }
        
        extract(array_merge(array(
            'location' => null,
        ), $params));
                
        $q = "DELETE FROM report_locations WHERE record_status = 0 " .
            empty($location) ? "" : " AND location_id in ($location)";
        DB::delete($q);

        $q = "DELETE FROM report_game_plays WHERE record_status = 0".
            empty($location) ? "" : " AND location_id in ($location)";
        DB::delete($q);        
        
        $q = "DELETE FROM report_locations
                USING report_locations
                JOIN location ON location.id=report_locations.location_id
                WHERE report_locations.date_played < location.date_opened
                AND location.date_opened IS NOT NULL 
                AND location.date_opened <> '0000-00-00'" .
            (empty($location) ? "": " AND report_locations.location_id in ($location)");
        DB::delete($q);
        
        $q = "DELETE FROM report_game_plays
                USING report_game_plays
                JOIN location ON location.id=report_game_plays.location_id
                WHERE report_game_plays.date_played < location.date_opened
                AND location.date_opened IS NOT NULL 
                AND location.date_opened <> '0000-00-00'" .
            (empty($location) ? "": " AND report_game_plays.location_id in ($location)");
        DB::delete($q);
        
        
        $q = "DELETE FROM report_game_plays USING report_game_plays 
                    JOIN game ON game.id=report_game_plays.game_id 
                        WHERE report_game_plays.date_played < game.date_in_service 
                            AND game.date_in_service <> '0000-00-00' 
                            AND report_game_plays.game_revenue IS NULL".
            (empty($location) ? "": " AND report_game_plays.location_id in ($location)");
        
        DB::delete($q); 
    }
    
    public static function findDuplicateTransferredEarnings($params=array()) {
        $lf = 'findDuplicateTransferredEarnings.log';
        $lp = 'FEGCronTasks/DuplicateTransferredEarnings';
        extract(array_merge(array(
            '_logger' => null
        ), $params));        
        $L = FEGSystemHelper::setLogger($_logger, $lf, $lp, 'EARNINGS');
        $params['_logger'] = $L;
        
        $L->log("***************************** START FIND DUPLICATE ********************************");
        FEGSystemHelper::logit("***************************** START FIND DUPLICATE ********************************", $lf, $lp);
        
        $endDate = "2014-01-01";
        $endDateValue = strtotime($endDate);
        $startDate = DB::table('game_earnings')->orderBy('date_start', 'desc')->take(1)->value('date_start');
        $startDateValue = strtotime($startDate);
        $date = date("Y-m-d", $startDateValue);
        $dateValue = strtotime($date);
        $L->log("Start: $startDate, End: $endDate => going backwards");
        FEGSystemHelper::logit("Start: $startDate, End: $endDate => going backwards", $lf, $lp);
        while ($dateValue >= $endDateValue) {
            
            $L->log("DATE: $date");
            
            $q = "SELECT game_id, reader_id, count(game_id) recordCount from game_earnings 
                WHERE date_start >= '$date' 
                    AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                GROUP BY game_id, reader_id";
            $dataERP = DB::select($q);
            $data = array();            
            foreach($dataERP as $row) {
                $data[$row->game_id.'_'.$row->reader_id] = $row->recordCount;
            }
            $dataERP = null;
            $dataSacoaTemp = DB::connection('sacoa_sync')->select($q);
            $dataTemp = array();
            foreach($dataSacoaTemp as $row) {
                $dataTemp[$row->game_id.'_'.$row->reader_id] = $row->recordCount;
            }
            $dataSacoaTemp = null;
            $dataEmbedTemp = DB::connection('embed_sync')->select($q);
            foreach($dataEmbedTemp as $row) {
                $dataTemp[$row->game_id.'_'.$row->reader_id] = $row->recordCount;
            }
            $dataEmbedTemp = null;            
            
            foreach($data as $gameId_readerId => $count) {
                if (isset($dataTemp[$gameId_readerId])) {
                    if ($count > $dataTemp[$gameId_readerId]) {
                        $log = "$date, $gameId_readerId, ERP: $count, TEMP: $dataTemp[$gameId_readerId]";
                        FEGSystemHelper::logit($log, $lf, $lp);
                        $L->log($log);
                    }
                    unset($dataTemp[$gameId_readerId]);                        
                }
                else {
                        $log = "$date, $gameId_readerId, ERP: $count, TEMP: NOT FOUND IN TEMP";
                        FEGSystemHelper::logit($log, $lf, $lp);
                        $L->log($log);                    
                }
                unset($data[$gameId_readerId]);                
            }
            
            $dateValue = strtotime($date.' -1 day');
            $date = date("Y-m-d", $dateValue);
            break;
        }
        
        
        FEGSystemHelper::logit("***************************** END FIND DUPLICATE ********************************", $lf, $lp);
        $L->log("***************************** END FIND DUPLICATE ********************************");
    }
    
}
