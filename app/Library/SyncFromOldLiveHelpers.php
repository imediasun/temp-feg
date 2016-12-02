<?php

namespace App\Library;

use PDO;
use DB;
use App\Library\MyLog;
use App\Library\FEG\System\SyncHelpers;

class SyncFromOldLiveHelpers
{  
    private static $L;
    private static $possibleAdjustments;
    private static $limit = 1000;

        
    /* Common functions */
    public static function live_sync_game_titles($params = array()) {
        extract($params);
        self::$L = $_logger;
        $chunkSize = 500;
        $table = "game_title";
        $liveSystemDBName = "livemysql";
        DB::connection($liveSystemDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($liveSystemDBName);
        $local_db = DB::connection();

        self::$L->log("Start copying Game Title");   
        $chunkCount = 0;
        $local_db->table($table)->truncate();
        $live_db->table($table)
                ->chunk($chunkSize, 
                        function($data)  use ($local_db, $table, $chunkSize, &$chunkCount){
                            $chunkCount++;
                            self::$L->log("Game Title: received chunk of $chunkSize #$chunkCount");        
                            if (!empty($data) && count($data) > 0) {
                                self::$L->log("Adding data to local");
                                $local_db->table($table)->insert($data);
                            }
                            else {
                                self::$L->log("Game Title: NO data to add");
                            }            
                        });   
        
        $local_db->update("UPDATE `game_title` SET img = CONCAT(id,'.jpg') where img !=''");
        
        self::$L->log("End copying Game Title");  

    } 
    public static function live_sync_games($params = array()) {
        extract($params);
        self::$L = $_logger;
        $chunkSize = 500;
        $table = "game";
        $liveSystemDBName = "livemysql";
        DB::connection($liveSystemDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($liveSystemDBName);
        $local_db = DB::connection();

        self::$L->log("Start copying Game");   
        $chunkCount = 0;
        $local_db->table($table)->truncate();
        $live_db->table($table)
                ->chunk($chunkSize, 
                        function($data)  use ($local_db, $table, $chunkSize, &$chunkCount){
                            $chunkCount++;
                            self::$L->log("Game: received chunk of $chunkSize #$chunkCount");        
                            if (!empty($data) && count($data) > 0) {
                                self::$L->log("Adding data to local");
                                $local_db->table($table)->insert($data);
                            }
                            else {
                                self::$L->log("Game: NO data to add");
                            }            
                        });   
        $local_db->update("update `game` set product_id = concat('[\"',product_id ,'\"]') where game.game_type_id = 3");
        self::$L->log("End copying Game");  
        
    } 
    public static function live_sync_reader_exclude($params = array()) {
        extract($params);
        self::$L = $_logger;
        $chunkSize = 500;
        $table = "reader_exclude";
        $liveSystemDBName = "livemysql";
        DB::connection($liveSystemDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($liveSystemDBName);
        $local_db = DB::connection();

        self::$L->log("Start copying Reader Excludes");   
        $chunkCount = 0;
        $local_db->table($table)->truncate();
        $live_db->table($table)
                ->chunk($chunkSize, 
                        function($data)  use ($local_db, $table, $chunkSize, &$chunkCount){
                            $chunkCount++;
                            self::$L->log("Reader Excludes: received chunk of $chunkSize #$chunkCount");        
                            if (!empty($data) && count($data) > 0) {
                                self::$L->log("Adding data to local");
                                $local_db->table($table)->insert($data);
                            }
                            else {
                                self::$L->log("NO data to add");
                            }            
                        });   
                       
        self::$L->log("End copying Reader Excludes");  
    }     
    public static function live_sync_requests($params = array()) {
        extract($params);
        self::$L = $_logger;
        
        $chunkSize = 500;
        $table = "requests";
        $liveSystemDBName = "livemysql";
        DB::connection($liveSystemDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($liveSystemDBName);
        $local_db = DB::connection();
        $last_synced_id = self::get_last_id($table, $local_db);
        $last_live_id = self::get_last_id($table, $live_db);
        
        if ($last_live_id > $last_synced_id) {
            self::$L->log("Start copying requests");   
            $chunkCount = 0;
            $live_db->table($table)->where('id', '>', $last_synced_id)
                    ->chunk($chunkSize, 
                            function($data)  use ($local_db, $table, $chunkSize, &$chunkCount){
                                $chunkCount++;
                                self::$L->log("Reader Excludes: received chunk of $chunkSize #$chunkCount");        
                                if (!empty($data) && count($data) > 0) {
                                    self::$L->log("Adding data to local");
                                    $local_db->table($table)->insert($data);
                                }
                                else {
                                    self::$L->log("Game: NO data to add");
                                }            
                            });   

            self::$L->log("End copying requests");            
        }
        else {
            self::$L->log("Requests sync not required. Ending.");   
        }
  
    }     
    public static function commonSync($params = array()) {
        
        extract($params);
        self::$L = $_logger;
        
        self::$L->log("Start Games Title Sync");
        self::live_sync_game_titles($params);
        self::$L->log("End Games Title Sync");
        
        self::$L->log("Start Games Sync");
        self::live_sync_games($params = array());
        self::$L->log("End Games Sync");   

        self::$L->log("Start Reader Exclude Sync");
        self::live_sync_reader_exclude($params = array());
        self::$L->log("End Reader Exclude Sync");        
        
        self::$L->log("Start Requests Sync");
        self::live_sync_requests($params = array());
        self::$L->log("End Requests Sync");          
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
    
    /* Sync From Temp DB [START] */
    public static function syncFromLiveTempDB($params = array()) {        
        extract(array_merge(array(
            '_task' => array(),
            '_logger' => null,
        ), $params));
                
        if (!isset(self::$L)) {
            if (empty($_logger)) {
                $_logger = new MyLog("temp-earnings.log", "live-temp-sync", "Sync");                
            }
            self::$L = $_logger;
        }
        
        self::$L->log("Start Live Sync");
        self::_syncFromLiveTempDB($params);        
        self::$L->log("End Live Sync");
        
        DB::connection('livemysql_embed')->disconnect();
        DB::connection('livemysql_sacoa')->disconnect();
    }    
    
    public static function _syncFromLiveTempDB($params = array()) {
        self::commonSync($params);
        self::$L->log("Start Sacoa Sync");
        self::live_sync_temp_earnings('livemysql_sacoa', 'sacoa_sync');
        self::$L->log("End Sacoa Sync");        
        self::$L->log("Start Embed Sync");
        self::live_sync_temp_earnings('livemysql_embed', 'embed_sync');
        self::$L->log("End Embed Sync");        
        self::$L->log("Adjustments start");        
        self::live_sync_temp_earnings_adj('livemysql_embed', 'livemysql_sacoa');
        self::$L->log("Adjustments End");        
    }

    public static function live_sync_temp_earnings_adj($embedDBName, $sacoatDBName) {
        $chunkSize = 500;
        $table = "game_earnings";
        $adMetaTable = "game_earnings_transfer_adjustments";
        $liveSystemDBName = "livemysql";
        DB::connection($embedDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection($sacoatDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection($liveSystemDBName)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($liveSystemDBName);
        $sourceDB = null;        
        $local_db = DB::connection();
        
        //$last_synced_date = self::get_last_date_in_game_earnings($local_db, $table);
        //$last_live_date = self::get_last_date_in_game_earnings($live_db, $table);
        
        $date = date("Y-m-d");

        self::$L->log("Start copying Adjustment metadata");   
        $chunkCount = 0;
        $local_db->table($adMetaTable)->truncate();
        $live_db->table($adMetaTable)->orderBy('id')
                ->chunk($chunkSize, 
                        function($data)  use ($local_db, $adMetaTable, $chunkSize, &$chunkCount){
                            $chunkCount++;
                            self::$L->log("Adj meta data: received chunk of $chunkSize #$chunkCount");        
                            if (!empty($data) && count($data) > 0) {
                                self::$L->log("Adding data to local");
                                $local_db->table($adMetaTable)->insert($data);
                            }
                            else {
                                self::$L->log("Adj meta data: NO data to add");
                            }            
                        });   
        
        self::$L->log("reset to be pending adjustment date");        
        
        $newAdj = self::$possibleAdjustments;
        if (!empty($newAdj) && is_array($newAdj)) {
            foreach($newAdj as $item) {
                $date=$item['date_start'];
                $loc=$item['loc_id'];
                $q = "UPDATE $adMetaTable SET
                    adjustment_date=NULL, status=1, notes='STILL PENDING'
                    WHERE adjustment_date = '$date' AND loc_id=$loc";
                $affected = $local_db->update($q);    
                if ($affected > 0) {
                     self::$L->log("reset to pending $loc - $date");  
                }
            }
        }
                
        self::$L->log("End copying Adjustment metadata");  
        
//        $q = "SELECT ga.id, ga.loc_id, L.debit_type_id 
//            FROM $adMetaTable ga
//                INNER JOIN location L ON L.id=ga.loc_id
//            WHERE ga.notes='ADJUSTED'";        
//        
//        $adjData = $live_db->select($q);
//        
//        if(!empty($adjData) && count($adjData)> 0) {
//            self::$L->log("Start fetching Adj data");        
//            foreach ($adjData as $item) {                
//                $loc = $item['loc_id'];
//                $debitType = $item['debit_type_id'];
//                $dbName = SyncHelpers::getDebitTypeDBName($debitType);
//                $debitTypeName = SyncHelpers::getDebitTypeName($debitType);
//                $sdate = $item['start_date'];
//                
//                $sourceDB = DB::connection($dbName);
//                
//                
//                self::$L->log("DELETE LOCAL DATA Location: $loc  date: $sdate"); 
//                $q = "DELETE from $table WHERE loc_id=$loc AND 
//                    date_start >= '$sdate' 
//                    and date_start < DATE_ADD('$sdate', INTERVAL 1 DAY)";
//                $local_db->delete($q);                
//                
//                self::$L->log("FETCH DATA for Loc: $loc  date: $sdate");        
//                $q = "SELECT * from $table WHERE loc_id=$loc AND 
//                    date_start >= '$sdate' 
//                    and date_start < DATE_ADD('$sdate', INTERVAL 1 DAY)";
//                $aData = $sourceDB->select($q);
//                if (!empty($aData) && count($aData) > 0) {
//                    self::$L->log("STORE IN DATA for Loc: $loc  date: $sdate");        
//                    $local_db->table($table)->insert($aData);
//                }                
//
//            }
//            
// 
//            self::$L->log("End fetching Adj data");        
//        }

    } 
    
    public static function live_sync_temp_earnings($sourceDB, $destDB) {
        $chunkSize = 500;
        $table = "game_earnings";
        DB::connection($sourceDB)->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection($destDB)->setFetchMode(PDO::FETCH_ASSOC); 
        $live_db = DB::connection($sourceDB);
        $local_db = DB::connection($destDB);
        
        $last_synced_id = self::get_last_id($table, $destDB);
        $last_live_id = self::get_last_id($table, $sourceDB);
        
        self::$L->log("     Last Sync ID: $last_synced_id (local), $last_live_id (live)");
        
        self::$L->log("DELETE existing data if any");
        $q = "SELECT date_start, loc_id from $table WHERE id > $last_synced_id group by date_start, loc_id";
        $syncInfo = $live_db->select($q);
        self::$possibleAdjustments = $syncInfo;
        foreach($syncInfo as $item) {
            $date = $item['date_start'];
            $loc = $item['loc_id'];
            $q = "DELETE from $table WHERE date_start = '$date' AND loc_id=$loc";
            $a = $local_db->delete($q);
            if ($a > 0) {
                self::$L->log("DELETED existing data for $loc dated $date");
            }
        }
        self::$L->log("END DELETE existing data if any");
        
        self::$L->log("FETCH unsynced DATA");               
        $chunkCount = 0;
        $dataCount = 0;
        $live_db->table($table)->where('id', '>', $last_synced_id)
                ->chunk($chunkSize, 
                        function($data)  use ($local_db, $table, $chunkSize, &$chunkCount, &$dataCount){
                            $chunkCount++;
                            $dataCount+=count($data);
                            self::$L->log("Data received chunk of size $chunkSize: #$chunkCount");        
                            if (!empty($data) && count($data) > 0) {
                                self::$L->log("Adding data to local");
                                $local_db->table($table)->insert($data);
                            }
                            else {
                                self::$L->log("NO data to add");
                            }            
                        }); 
        self::$L->log("Added $dataCount data items to local");
        self::$L->log("END FETCH unsynced DATA");
    }    
     /* Sync From Temp DB [END] */

    
    /* Sync From LIVE ERP DB (Processed data) [START] */
    public static function _livesync($params = array()) {
        
        self::commonSync($params);
        
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
    public static function livesync($params = array()) {        
        extract($params);
        self::$L = $_logger;
        
        self::$L->log("Start Live Sync");
        $count = 0;
        while(self::hasMoreToSync()) {
            self::$L->log("has " . ($count > 0 ? "more":"") . " data to sync");
            self::_livesync($params);
            $count++;
            sleep(3);
        }
        self::$L->log("No  " . ($count > 0 ? "more":"") . " data to sync");
        self::$L->log("End Live Sync");
        
        self::$L->log("Clean Games Summary Sync");
        self::live_sync_clean_summary_reports();
        self::$L->log("Clean Games Summary Sync");
        
        
        DB::connection('livemysql')->disconnect();
    }
    
    public static function live_sync_clean_summary_reports($location = null) {
        
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        
        $q = "SELECT id, date_opened from location WHERE reporting=1 " .
            (empty($location) ? "": " AND id in ($location)");
        $data = DB::select($q);
        foreach($data as $item) {
            $id = $item['id'];
            $date = $item['date_opened'];
            $sql = "DELETE FROM report_locations WHERE location_id = $loc and date_played < '$date'";
            DB::delete($sql);        
            $sql = "DELETE FROM report_game_plays WHERE location_id = $loc and date_played < '$date'";
            DB::delete($sql);

            $sql = "DELETE FROM report_locations WHERE record_status = 0";
            if (!empty($location)) {
                $sql .= " AND location_id in ($location)";
            }
            DB::delete($sql);
            
            $sql = "DELETE FROM report_game_plays WHERE record_status = 0";

            if (!empty($location)) {
                $sql .= " AND location_id in ($location)";
            }
            DB::delete($sql);        
        }
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
    /* Sync From LIVE ERP DB (Processed data) [START] */
}