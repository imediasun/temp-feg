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
        $description = @$filters['Description'];
        
		$where = "   WHERE products.in_development = 1  ";        
        if (!empty($date_start)) {
            $where .= " AND products.date_added >= '$date_start' ";
        }
        if (!empty($date_end)) {
            $where .= " AND products.date_added <= '$date_end 23:59:59' ";
        }
        if (!empty($description)) {
            $where .= " AND products.vendor_description LIKE '%$description%' ";
        }
		
        $sql .= $where;
        
		return $sql;
        
	}	

	public static function queryWhere(  ){
	

	}
	
	public static function queryGroup(){
		return "  ";
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

        $total = 0;
        $totalQuery = self::querySelect(true);
        $totalRows = \DB::select($totalQuery);
        if (!empty($totalRows) && isset($totalRows[0])) {
            $total = $totalRows[0]->totalCount;
        }            
        $offset = ($page-1) * $limit ;
        if ($offset >= $total) {
            $page = ceil($total/$limit);
            $offset = ($page-1) * $limit ;
        }           
        $limitConditional = ($page !=0 && $limit !=0) ? " LIMIT  $offset , $limit" : '';    
            
		$orderConditional = ($sort !='' && $order !='') ?  " ORDER BY {$sort} {$order} " : '';

        $selectQuery = self::querySelect(). " {$orderConditional} {$limitConditional}";
        $rawRows = \DB::select($selectQuery);
        $rows = self::processRows($rawRows);        
                
        if ($total == 0) {
            $messaeg = "To view the contents of this report, please select a date range";
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
            $row->end_date = date("m/d/Y", strtotime($row->end_date));
            $etaEpoch = strtotime($row->ETA);
            if ($etaEpoch !== FALSE && $etaEpoch > 0) {
                $row->ETA = date("m/d/Y", strtotime($row->ETA));
            }
            else {
                $row->ETA = "Unknown";
            }

            $newRows[] = $row;
        }
		return $newRows;
	}     
}
