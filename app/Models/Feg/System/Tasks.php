<?php namespace App\Models\Feg\System;

use DB;
use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Library\CronHelpers;
use App\Library\Elm5Tasks;

class tasks extends Sximo  {
	
    protected $table = 'elm5_tasks';
	protected $primaryKey = 'id';
    
	public function __construct() {
		parent::__construct();
	}

	public static function querySelect(  ){
		
		return "  SELECT * FROM elm5_tasks ";
	}	

	public static function queryWhere(  ){
		
		return "   WHERE id IS NOT NULL   ";
	}
	
	public static function queryGroup(){
		return "      ";
	}
    
	public static function getRows( $args, $cond = null )
	{        
		$results = parent::getRows( $args, $cond ); 
        $data = $results['rows'];
        $data = self::processRows($data);
        $results['rows'] = $data;       
        return $results;
    }
    
	public static function processRows( $rows ) {
        $newRows = array();
        foreach($rows as $row) {            
            $id = $row->id;            
            $row->lastSchedule = self::getTaskLastRunAt($id);
            $row->nextSchedule = self::getTaskNextScheduledAt($id);
            $row->isManualRunning = self::getIsManualRunning($id) || self::isTaskRunning($id);
            $newRows[] = $row;
        }       
		return $newRows;
	}      
    
    /**
     * 
     * @param type $ids
     * @return type
     */
    public static function deactivateTaskSchedule($ids) {
        return Elm5Tasks::deactivateTaskSchedule($ids);
    }
    
	public static function getSchedulesForReview ( $id ) {
        return Elm5Tasks::getSchedulesForReview($id);
    }
	public static function getTaskData ( $id ) {
        return Elm5Tasks::getTask($id);
    }
	public static function getTaskCrontab ( $id ) {
        return Elm5Tasks::getTaskCrontab($id);
    }
	public static function getTaskLastRunAt ( $id ) {
        return Elm5Tasks::getTaskLastRunAt($id);
    }
	public static function getTaskNextScheduledAt ( $id ) {
        return Elm5Tasks::getTaskNextScheduledAt($id); 
    }
	public static function isTaskRunning ( $id ) {
        return Elm5Tasks::isTaskRunning($id); 
    }
	public static function getIsManualRunning ( $id ) {
        return Elm5Tasks::isTaskManualRunning($id); 
    }
	public static function addSchedule ( $id ) {
        return Elm5Tasks::addSchedule($id);
    }
	public static function addRunNowSchedule ( $data ) {
        return Elm5Tasks::addSchedule($data, true);
    }
          
}
