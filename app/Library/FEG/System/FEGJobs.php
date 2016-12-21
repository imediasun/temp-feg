<?php

namespace App\Library\FEG\System;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;


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
    
}
