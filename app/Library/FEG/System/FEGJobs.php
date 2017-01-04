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
            
            $q = "SET SESSION group_concat_max_len = 1000000;
                SELECT loc_id, game_id, reader_id, group_concat(id) as ids, 
                    count(game_id) recordCount 
                FROM game_earnings 
                WHERE date_start >= '$date' 
                    AND date_start < DATE_ADD('$date', INTERVAL 1 DAY) 
                GROUP BY loc_id, game_id, reader_id";
            $dataERP = DB::select($q);
            $data = array();            
            foreach($dataERP as $row) {
                $key = $row->loc_id."::".$row->game_id."::".trim($row->reader_id);
                $data[$key] = array('count' => $row->recordCount, 'ids' => $row->ids);
            }
            $dataERP = null;
            $dataSacoaTemp = DB::connection('sacoa_sync')->select($q);
            $dataTemp = array();
            foreach($dataSacoaTemp as $row) {
                $key = $row->loc_id."::".$row->game_id."::".trim($row->reader_id);
                $dataTemp[$key] = array('db' => 'sacoa_sync', 'count' => $row->recordCount, 'ids' => $row->ids);
            }
            $dataSacoaTemp = null;
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
    
}
