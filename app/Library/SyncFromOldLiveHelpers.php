<?php

namespace App\Library;

use PDO;
use DB;
use App\Library\MyLog;

class SyncFromOldLiveHelpers
{  
    private static $L;
    private static $limit = 1000;
    
    public static function _livesync() {
        
        self::$L->log("Start Earnings Sync");
        self::live_sync_earnings();
        self::$L->log("End Earnings Sync");
        
        
        self::$L->log("Start Adjustment Sync");
        self::live_sync_adjustment_earnings();
        self::$L->log("End Adjustment Sync");
        
        
        
        self::$L->log("Start Location Summary Sync");
        self::live_sync_location_summary_reports();
        self::$L->log("End Location Summary Sync");
        
        
        self::$L->log("Start Games Summary Sync");
        self::live_sync_game_summary_reports();
        self::$L->log("End Games Summary Sync");
        
    }
    public static function livesync() {
        if (!isset(self::$L)) {
            self::$L = new MyLog("earnings-and-summary.log", "livesync", "Sync");
        }
        
        self::$L->log("Start Live Sync");
        $count = 0;
        while(self::hasMoreToSync()) {
            self::$L->log("has " . ($count > 0 ? "more":"") . " data to sync");
            self::_livesync();
            $count++;
            sleep(3);
        }
        self::$L->log("No  " . ($count > 0 ? "more":"") . " data to sync");
        self::$L->log("End Live Sync");
        DB::connection('livemysql')->disconnect();
    }
    
    public static function live_sync_adjustment_earnings() {
        
        $table = "game_earnings_transfer_adjustments";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $q = "SELECT * from $table WHERE id > $last_sync_id LIMIT " . self::$limit;
        $data = $live_db->select($q);
        DB::table($table)->insert($data);
        
        $last_adjusted = self::get_last_adjusted_on();
        $q = "SELECT * from $table WHERE adjustment_date > '$last_adjusted' AND adjustment_date < date_add('$last_adjusted', INTERVAL 10 day)";
        $data = $live_db->select($q);
        DB::beginTransaction();
        foreach($data as $item) {
            $id = $item['id'];
            unset($item['id']);
            unset($item['date_start']);
            unset($item['loc_id']); 
            DB::table($table)->where('id', $id)->update($item);
        }
        DB::commit();
    }
    
    public static function get_last_adjusted_on($dbname = null) {  
        if (is_null($dbname)) {
            $date = DB::table('game_earnings_transfer_adjustments')->orderBy('adjustment_date', 'desc')->take(1)->value('adjustment_date');
        }  
        else {
            $date = DB::connection($dbname)->table('game_earnings_transfer_adjustments')->orderBy('adjustment_date', 'desc')->take(1)->value('adjustment_date');
        }
        
        return $date;
    }   
    
    public static function live_sync_location_summary_reports() {
        
        $table = "report_locations";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $q = "SELECT * from $table WHERE id > $last_sync_id LIMIT " . self::$limit;
        $data = $live_db->select($q);
        DB::beginTransaction();
        foreach($data as $item) {
            DB::update("UPDATE $table SET record_status=0 WHERE location_id={$item['location_id']} AND date_played='{$item['date_played']}'");
            //DB::table($table)->where([['location_id', '=', $item['location_id']],['date_played', '=', $item['date_played']]])->update(["record_status" => 0]);
            DB::table($table)->insert($item);
        }
        DB::commit();
    }
    
    public static function live_sync_game_summary_reports() {
        
        $table = "report_game_plays";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $q = "SELECT * from $table WHERE id > $last_sync_id LIMIT " . self::$limit;
        $data = $live_db->select($q);
        DB::beginTransaction();
        foreach($data as $item) {
            DB::update("UPDATE $table SET record_status=0 WHERE game_id={$item['game_id']} AND date_played='{$item['date_played']}'");
            //DB::table($table)->where([['game_id', '=', $item['game_id']],['date_played', '=', $item['date_played']]])->update(["record_status" => 0]);
            DB::table($table)->insert($item);
        }
        DB::commit();
    }
    
    public static function live_sync_earnings() {
        $table = "game_earnings";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $updated_info = self::get_live_earnings_meta($live_db, $last_sync_id);
        foreach($updated_info as $info) {
            $location_id = $info['loc_id'];
            $date = $info['date_start'];
            $q = "DELETE from game_earnings WHERE loc_id IN ($location_id) AND date_start = '$date'";
            DB::delete($q);
        }
        
        $q = "SELECT * from game_earnings WHERE id > $last_sync_id LIMIT " . self::$limit;
        $data = $live_db->select($q);
        DB::table('game_earnings')->insert($data);

    }
    
    public static function get_live_earnings_meta($live_db, $last_sync_id) {        
        $q = "SELECT loc_id, date_start
            From game_earnings 
            WHERE id > $last_sync_id
            GROUP BY loc_id, date_start  LIMIT " . self::$limit;
        $data = $live_db->select($q);
        return $data;
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
    
    public static function hasMoreToSync() {
        $liveEarningsID = self::get_last_id('game_earnings', 'livemysql');
        $devEarningsID = self::get_last_id('game_earnings');
        
        $liveAdjID = self::get_last_id('game_earnings_transfer_adjustments', 'livemysql');
        $devAdjID = self::get_last_id('game_earnings_transfer_adjustments');
        
        $last_adjusted = self::get_last_adjusted_on();
        if (!is_null($last_adjusted)) {
            $last_adjusted = strtotime($last_adjusted);
        }
        $live_last_adjusted = self::get_last_adjusted_on('livemysql');
        if (!is_null($live_last_adjusted)) {
            $live_last_adjusted = strtotime($live_last_adjusted);
        }
        
        
        $liveReportLocID = self::get_last_id('report_locations', 'livemysql');
        $devReportLocID = self::get_last_id('report_locations');
        
        $liveReportGamesID = self::get_last_id('report_game_plays', 'livemysql');
        $devReportGamesID = self::get_last_id('report_game_plays');
        
        self::$L->log("PENDING:".
                    " Earnings =>" . ($liveEarningsID > $devEarningsID ? " yes" : " no") . 
                    ", Adj =>" . ($liveAdjID > $devAdjID ? " yes" : " no") . 
                    ", Adj Date =>" . ($live_last_adjusted > $last_adjusted ? " yes" : " no") . 
                    ", Loc Report =>" . ($liveReportLocID > $devReportLocID ? " yes" : " no") . 
                    ", Games Report =>" . ($liveReportGamesID > $devReportGamesID ? " yes" : " no"));
        self::$L->log("Pending Data: [live => dev]".
                    " Earnings:[" . ($liveEarningsID ." => ". $devEarningsID."]") . 
                    " Adj:[" . ($liveAdjID ." => ". $devAdjID."]") . 
                    " Adj Date:[" . (date("Y-m-d", $live_last_adjusted) ." => ". date("Y-m-d", $last_adjusted)."]") . 
                    " Loc Report:[" . ($liveReportLocID ." => ". $devReportLocID."]") . 
                    " Games Report:[" . ($liveReportGamesID ." => ". $devReportGamesID."]")); 

                    
        $hasMore = $liveEarningsID > $devEarningsID || 
                    $liveAdjID > $devAdjID || 
                    $live_last_adjusted > $last_adjusted ||
                    $liveReportLocID > $devReportLocID ||
                    $liveReportGamesID > $devReportGamesID;
        
        return $hasMore;
        
    }
}