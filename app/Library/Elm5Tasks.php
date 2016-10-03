<?php
namespace App\Library;

use PDO;
use DB;
use App\Library\MyLog;
//use App\Library\TaskHelpers;
use App\Library\SyncHelpers;
use App\Library\ReportHelpers;
//use App\Library\ReportEmailHelpers;

class Elm5Tasks
{   
    public static $L = null;
    public static $CL = null;
    
    private static function log($message = '', $data = '') {
        if (is_null(self::$L)) {
            self::$L = new MyLog("task-manager.log", "elm5Tasks", "Elm5Tasks");
        }
        self::$L->log($message, $data);
    }
    private static function cronlog($message = '', $data = '') {
        if (is_null(self::$CL)) {
            self::$CL = new MyLog("task-manager-cron.log", "elm5Tasks", "Elm5Tasks");
        }
        self::$L->log($message, $data);
    }
    public static function manageTasks() {
        
    }
    
    

    public static function addTask() {
        
    }
    public static function updateTask() {
        
    }
    public static function deleteTask() {
        
    }
    public static function deactivateTask() {
        
    }
    public static function getTasks($includeAll = false) {
        
    }
    public static function runTasks() {
        //$tasks = self::getTasks(true);
        
    }
    public static function runTask() {
        
    }
    
    public static function addSchedule() {
        
    }
    public static function updateSchedule() {
        
    }
    public static function deleteSchedule() {
        
    }
    public static function deactivateSchedule() {
        
    }
    public static function getSchedules() {
        
    }
    public static function runSchedules() {
        
    }
    public static function runSchedule() {
        
    }

}
