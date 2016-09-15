<?php
namespace App\Library;
use PDO;
use DB;
use App\Library\MyLog;
class SyncHelpers
{    
    public static function livesync() {
        $L = new MyLog("earnings", "livesync", "Sync");
        $L->log("Start Live Sync");
        $L->log("Start Earnings Sync");
        self::live_sync_earnings();
        $L->log("End Earnings Sync");
        $L->log("Start Adjustment Sync");
        self::live_sync_adjustment_earnings();
        $L->log("End Adjustment Sync");
        $L->log("Start Location Summary Sync");
        self::live_sync_location_summary_reports();
        $L->log("End Location Summary Sync");
        $L->log("Start Games Summary Sync");
        self::live_sync_game_summary_reports();
        $L->log("End Games Summary Sync");
        $L->log("End Live Sync");
    }
    
    public static function live_sync_adjustment_earnings() {
        
        $table = "game_earnings_transfer_adjustments";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $q = "SELECT * from $table WHERE id > $last_sync_id";
        $data = $live_db->select($q);
        DB::table($table)->insert($data);
        
        $last_adjusted = self::get_last_adjusted_on();
        $q = "SELECT * from $table WHERE adjusted_date > '$last_adjusted'";
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
    
    public static function get_last_adjusted_on() {        
        $date = DB::table('game_earnings_transfer_adjustments')->orderBy('adjustment_date', 'desc')->take(1)->value('adjustment_date');
        return $date;
    }   
    
    public static function live_sync_location_summary_reports() {
        
        $table = "report_locations";
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC); 
        DB::connection('livemysql')->setFetchMode(PDO::FETCH_ASSOC); 
        
        $last_sync_id = self::get_last_id($table);
        $live_db = DB::connection('livemysql');
        
        $q = "SELECT * from $table WHERE id > $last_sync_id";
        $data = $live_db->select($q);
        DB::beginTransaction();
        foreach($data as $item) {
            DB::table($table)->where([['location_id', $item['location_id']],['date_played', $item['date_played']]])->update(["record_status" => 0]);
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
        
        $q = "SELECT * from $table WHERE id > $last_sync_id";
        $data = $live_db->select($q);
        DB::beginTransaction();
        foreach($data as $item) {
            DB::table($table)->where([['game_id', $item['game_id']],['date_played', $item['date_played']]])->update(["record_status" => 0]);
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
        
        $q = "SELECT * from game_earnings WHERE id > $last_sync_id";
        $data = $live_db->select($q);
        DB::table('game_earnings')->insert($data);

    }
    
    public static function get_live_earnings_meta($live_db, $last_sync_id) {        
        $q = "SELECT loc_id, date_start
            From game_earings 
            WHERE id > $last_sync_id
            GROUP BY loc_id, date_start";                
        $data = $live_db->select($q);
        return $data;
    }
    
    public static function get_last_id($table) {        
        $id = DB::table($table)->orderBy('id', 'desc')->take(1)->value('id');
        return $id;
    }

}
