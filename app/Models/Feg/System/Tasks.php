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
        
        return $results;
    }
}
