<?php namespace App\Models\Feg\System;
use Cron;
use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class tasks extends Sximo  {
	
    protected $table = 'elm5_tasks';
	protected $primaryKey = 'id';
    
	public function __construct() {
		parent::__construct();
	}

	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
		
            $row->date_start = date("m/d/Y", strtotime($row->date_start));
            $row->date_end = date("m/d/Y", strtotime($row->date_end));
		          
            $row->game_average = '$' . number_format($row->game_average,2);
            $row->game_total = '$' . number_format($row->game_total,2);
                       
            $newRows[] = $row;
        }
		return $newRows;
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
        
        // Works with predefined scheduling definitions
        $cron = Cron\CronExpression::factory('@daily');
        $cron->isDue();
        echo $cron->getNextRunDate()->format('Y-m-d H:i:s');
        echo $cron->getPreviousRunDate()->format('Y-m-d H:i:s');

        // Works with complex expressions
        $cron = Cron\CronExpression::factory('3-59/15 2,6-12 */15 1 2-5');
        echo $cron->getNextRunDate()->format('Y-m-d H:i:s');

        // Calculate a run date two iterations into the future
        $cron = Cron\CronExpression::factory('@daily');
        echo $cron->getNextRunDate(null, 2)->format('Y-m-d H:i:s');

        // Calculate a run date relative to a specific time
        $cron = Cron\CronExpression::factory('@monthly');
        echo $cron->getNextRunDate('2010-01-12 00:00:00')->format('Y-m-d H:i:s');

        return $results;
    }
}
