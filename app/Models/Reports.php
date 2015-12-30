<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class reports extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(){
		$date_start = "2015-12-26";
		$today_text = "Tue";
		return 'SELECT L.id,
												L.location_name_short,
												L.debit_type_id,
											   (SELECT COUNT(E.id)
												  FROM game_earnings E
												 WHERE E.date_start BETWEEN "'.$date_start.'" AND DATE_ADD("'.$date_start.'", INTERVAL 1 DAY)
												   AND E.loc_id = L.id
												   AND E.game_id !=0) AS EntryCount
										   FROM location L
										  WHERE L.reporting = 1
										    AND not_reporting_'.$today_text.' = 0
										  	AND NOT EXISTS (SELECT E.loc_id
															  FROM game_earnings E
															 WHERE E.date_start BETWEEN "'.$date_start.'" AND DATE_ADD("'.$date_start.'", INTERVAL 1 DAY)
															   AND E.loc_id = L.id
															   AND E.game_id !=0)';
		//return "  SELECT location.* FROM location  ";
	}	

	public static function queryWhere(  ){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public static function getRows( $args )
	{
		$table = with(new static)->table;
		$key = with(new static)->primaryKey;

		extract( array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> '' ,
			'order' 	=> '' ,
			'params' 	=> '' ,
			'global'	=> 1
		), $args ));

		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1

		$rows = array();
		$connect = mysqli_connect("localhost","root","","fegllc_fegsys");
		$query = self::querySelect() . self::queryWhere(). "
				{$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ";
		$result = mysqli_query($connect, $query);
		/*
		$result = \DB::select( self::querySelect() . self::queryWhere(). "
				{$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ");

		var_dump($result);exit;
		*/

		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );

		$total =  self::querySelect() . self::queryWhere(). "
				{$params} ". self::queryGroup() ." {$orderConditional}  ";
		$total = mysqli_query($connect, $total);
		$total = count($total->fetch_all());

		$data = array();
		while($object = $result->fetch_object())
		{
			if($object->debit_type_id === '1')
			{
				$object->debit_type_id = "Sacoa";
			}
			elseif($object->debit_type_id === '2')
			{
				$object->debit_type_id = "Embed";
			}
			$data[] = $object;
		}
		$result = $data;


		return $results = array('rows'=> $result , 'total' => $total);
	}

}
