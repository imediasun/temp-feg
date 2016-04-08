<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class bottomgame extends topgame  {

	
	protected $table = 'game_earnings';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}
	public static function getRows( $args,$cond=null )
	{
		$table = with(new static)->table;
		$key = with(new static)->primaryKey;

		extract( array_merge( array(
			'page' 		=> '0' ,
			'limit'  	=> '0' ,
			'sort' 		=> 'Average' ,
			'order' 	=> 'desc' ,
			'params' 	=> '' ,
			'global'	=> 1
		), $args ));
		//hardcoded sorting
		$sort = "Average";
		$order = "Asc";

		$offset = ($page-1) * $limit ;
		$limitConditional = ($page !=0 && $limit !=0) ? "LIMIT  $offset , $limit" : '';
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

		// Update permission global / own access new ver 1.1
		$table = with(new static)->table;
		if($global == 0 )
			$params .= " AND {$table}.entry_by ='".\Session::get('uid')."'";
		// End Update permission global / own access new ver 1.1

		$rows = array();
		$selectQuery = self::querySelect() . self::queryWhere()." {$orderConditional}  {$limitConditional} ";

		$result = \DB::select($selectQuery);
		$cleanResult = array();
		foreach($result as $data)
		{
			if(!empty($data->id))
			{
				$cleanResult[] = $data;
			}
		}
		$result = $cleanResult;

		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );

		//total query becomes too huge
		$total = \DB::select( self::querySelect() . self::queryWhere() ." {$orderConditional}  ");
		$total = count($total);
		return $results = array('rows'=> $result , 'total' => $total);


	}


}
