<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class productsindevelopmentreport extends Sximo  {
	
	protected $table = 'products';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect( $isCount = false ){
        
        $selectFields = " products.id,
    		products.date_added AS DateAdded, 
    		V.vendor_name AS Vendor	,
    		products.vendor_description AS Description,
    		products.size AS Size,
    		products.num_items AS Items,
    		products.case_price AS CasePrice,
    		T.order_type AS ProductType,
    		D.type_description AS ProductSubType,
    		products.details AS Details,
    		products.eta AS ETA,
    		products.date_added as start_date,
    		products.date_added as end_date ";
        
        
        $sql = "SELECT " . ($isCount ? " count(*) as totalCount " : $selectFields);
        
        $sql .= " FROM products 
            LEFT JOIN vendor V ON V.id = products.vendor_id
            LEFT JOIN order_type T ON T.id = products.prod_type_id
            LEFT JOIN product_type D ON D.id = products.prod_sub_type_id "; 
        
        $filters = self::getSearchFilters();
        $date_start = @$filters['start_date'];
        $date_end = @$filters['end_date'];
        
		$where = "   WHERE products.in_development = 1  ";        
        if (!empty($date_start)) {
            $where .= " AND products.date_added >= '$date_start' ";
        }
        if (!empty($date_end)) {
            $where .= " AND products.date_added <= '$date_end 23:59:59' ";
        }
		
        $sql .= $where;
        
		return $sql;
        
	}	

	public static function queryWhere(  ){
	

	}
	
	public static function queryGroup(){
		return "  ";
	}
	
    public static function getSearchFilters() {
        $finalFilter = array();
        if (isset($_GET['search'])) {
            $filters_raw = trim($_GET['search'], "|");
            $filters = explode("|", $filters_raw);

            foreach($filters as $filter) {
                $columnFilter = explode(":", $filter);
                if (isset($columnFilter) && isset($columnFilter[0]) && isset($columnFilter[2])) {
                    $finalFilter[$columnFilter[0]] = $columnFilter[2];
                }
            }
        }
        return $finalFilter;
    }
    
    
	public static function getRows( $args,$cond=null )
	{
		$table = with(new static)->table;
		$key = with(new static)->primaryKey;
        $topMessage = "";
        $bottomMessage = "";
        $message = "";
                

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

        $selectQuery = self::querySelect(). " {$orderConditional} {$limitConditional}";
        $rawRows = \DB::select($selectQuery);
        $rows = self::processRows($rawRows);        
        
        $total = 0;
        $totalQuery = self::querySelect(true);
        $totalRows = \DB::select($totalQuery);
        if (!empty($totalRows) && isset($totalRows[0])) {
            $total = $totalRows[0]->totalCount;
        }
		
		return $results = array(
                    'topMessage' => $topMessage,
                    'bottomMessage' => $bottomMessage,
                    'message' => $message,
                    
                    'rows'=> $rows, 
                    'total' => $total
                );


	} 
	public static function processRows( $rows ){
        $newRows = array();
        foreach($rows as $row) {
            
            $row->DateAdded = date("m/d/Y h:i:s A", strtotime($row->DateAdded));
            $row->start_date = date("m/d/Y", strtotime($row->start_date));
            $row->start_date = date("m/d/Y", strtotime($row->start_date));
            $etaEpoch = strtotime($row->ETA);
            if ($etaEpoch !== FALSE && $etaEpoch > 0) {
                $row->ETA = date("m/d/Y", strtotime($row->ETA));
            }
            else {
                $row->ETA = "unknown";
            }

            $newRows[] = $row;
        }
		return $newRows;
	}     
}
