<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class reports extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect()
	{
		$date_start = date('Y-m-d');
		$date_end = "DATE_ADD('$date_start', INTERVAL 1 DAY)";
		$today_text = "Tue";
		$locationCondition = "";
		$debitTypeCondition = "";
		$locationId = null;
		$debitTypeId = null;
		if(isset($_GET['search'])) {
			$filters = explode("|", trim($_GET['search'], "|"));
			$columnFilters = array();
			foreach ($filters as $filter) {
				$columnFilter = explode(":", $filter);
				if (!empty($columnFilter)) {
					if ($columnFilter[0] === "date_opened") {
						$date_start = $columnFilter[2];
					}
					if ($columnFilter[0] === "date_closed") {
						$date_end = '"'.$columnFilter[2].'"';
					}
					if ($columnFilter[0] === "id") {
						$locationId = $columnFilter[2];
					}
					if ($columnFilter[0] === "debit_type_id") {
						$debitTypeId = $columnFilter[2];
					}
				}
			}
		}
		return self::getLocationNotRespondingQuery($date_start, $date_end, $locationId, $debitTypeId);
	}

	public static function getLocationNotRespondingQuery($startDate, $endDate, $locationId = null, $debitTypeId = null, $todayText = null)
	{
		$locationCondition = "";
		$debitTypeCondition = "";

		if(is_null($endDate))
		{
			$endDate = "DATE_ADD('$startDate', INTERVAL 1 DAY)";
		}
		else
		{
			$endDate = '"'.$endDate.'"';
		}
		if(!is_null($locationId))
		{
			$locationCondition = " AND L.id = ".$locationId;
		}

		if(!is_null($debitTypeId))
		{
			$debitTypeCondition = " AND L.debit_type_id = ".$debitTypeId;
		}

		if(is_null($todayText))
		{
			$start = \DateTime::createFromFormat("Y-m-d", $startDate);
			$todayText = $start->format('D');
		}

		return 'SELECT L.id,
				L.location_name_short,
				L.debit_type_id,
			   (SELECT COUNT(E.id)
				  FROM game_earnings E
				 WHERE E.date_start BETWEEN "'.$startDate.'" AND '.$endDate.'
				   AND E.loc_id = L.id
				   AND E.game_id !=0) AS EntryCount
		   FROM location L
		  WHERE L.reporting = 1
			AND not_reporting_'.$todayText.' = 0
			'.$locationCondition.'
			'.$debitTypeCondition.'
			AND NOT EXISTS (SELECT E.loc_id
							  FROM game_earnings E
							 WHERE E.date_start BETWEEN "'.$startDate.'" AND '.$endDate.'
							   AND E.loc_id = L.id
							   AND E.game_id !=0)';

	}

    public static function getLocationNotRespondingData($startDate, $endDate, $locationId = null, $debitTypeId = null, $todayText = null)
    {
        return \DB::select(self::getLocationNotRespondingQuery($startDate, $endDate, $locationId, $debitTypeId, $todayText));
    }

	public static function queryWhere(){
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public static function gettRows( $args, $cond=null )
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

		$query = self::querySelect() ." {$orderConditional}  {$limitConditional} ";
		//echo $query;

		//$result = mysqli_query($connect, $query);

		$result = \DB::select($query);

		//var_dump($result);
		//exit;


		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );

		$totalQuery =  self::querySelect() . self::queryWhere(). " ". self::queryGroup() ." {$orderConditional}  ";
		//$total = mysqli_query($connect, $totalQuery);
		//var_dump($total->fetch_all());
		//exit;
		//$count = 0;
		$totalResult = \DB::select($totalQuery);
		$total = count($totalResult);

		$data = array();
		/*
		if($result)
		{
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
		}
		*/
		if(!empty($result))
		{
			foreach($result as $object)
			{
				if($object->debit_type_id == 1)
				{
					$object->debit_type_id = "Sacoa";
				}
				elseif($object->debit_type_id == 2)
				{
					$object->debit_type_id = "Embed";
				}
				$data[] = $object;
			}
		}

		$result = $data;


		return $results = array('rows'=> $result , 'total' => $total);
	}

}
