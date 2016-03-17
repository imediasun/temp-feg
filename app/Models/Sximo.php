<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sximo extends Model {


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
	    $result = \DB::select( self::querySelect() . self::queryWhere(). "
				{$params} ". self::queryGroup() ." {$orderConditional}  {$limitConditional} ");

		if($key =='' ) { $key ='*'; } else { $key = $table.".".$key ; }	
		$counter_select = preg_replace( '/[\s]*SELECT(.*)FROM/Usi', 'SELECT count('.$key.') as total FROM', self::querySelect() );
		//total query becomes too huge
		if($table == "orders")
		{
			$total = 2000;
		}
		else
		{
			$total = \DB::select( self::querySelect() . self::queryWhere(). "
				{$params} ". self::queryGroup() ." {$orderConditional}  ");
			$total = count($total);
		}
		//$total = 1000;
		return $results = array('rows'=> $result , 'total' => $total);

	}	

	public static function getRow( $id )
	{
       $table = with(new static)->table;
	   $key = with(new static)->primaryKey;

		$result =  \DB::select(
				self::querySelect() . 
				self::queryWhere().
				" AND ".$table.".".$key." = '{$id}' ".
				self::queryGroup()
			);	
		if(count($result) <= 0){
			$result = array();		
		} else {

			$result = $result[0];
		}
		return $result;		
	}	

	public  function insertRow( $data , $id)
	{

       $table = with(new static)->table;
	   $key = with(new static)->primaryKey;
	    if($id == NULL )
        {

            // Insert Here 
			if(isset($data['createdOn'])) $data['createdOn'] = date("Y-m-d H:i:s");	
			if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
			 $id = \DB::table( $table)->insertGetId($data);

        } else {
            // Update here 
			// update created field if any
            if(isset($data['createdOn'])) unset($data['createdOn']);
			if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");			
			 \DB::table($table)->where($key,$id)->update($data);
        }    
        return $id;    
	}			
 function intersectCols($arr1,$arr2)
{



}
	static function makeInfo( $id )
	{
		$row =  \DB::table('tb_module')->where('module_name', $id)->get();
		$data = array();
		foreach($row as $r)
		{
			$langs = (json_decode($r->module_lang,true));
			$data['id']		= $r->module_id; 
			$data['title'] 	= \SiteHelpers::infoLang($r->module_title,$langs,'title'); 
			$data['note'] 	= \SiteHelpers::infoLang($r->module_note,$langs,'note'); 
			$data['table'] 	= $r->module_db; 
			$data['key'] 	= $r->module_db_key;
			$data['config'] = \SiteHelpers::CF_decode_json($r->module_config);
			$field = array();	
			foreach($data['config']['grid'] as $fs)
			{
				foreach($fs as $f)
					$field[] = $fs['field']; 	
									
			}
			$data['field'] = $field;	
			$data['setting'] = array(
				'gridtype'		=> (isset($data['config']['setting']['gridtype']) ? $data['config']['setting']['gridtype'] : 'native'  ),
				'orderby'		=> (isset($data['config']['setting']['orderby']) ? $data['config']['setting']['orderby'] : $r->module_db_key),
				'ordertype'		=> (isset($data['config']['setting']['ordertype']) ? $data['config']['setting']['ordertype'] : 'asc'  ),
				'perpage'		=> (isset($data['config']['setting']['perpage']) ? $data['config']['setting']['perpage'] : '10'  ),
				'frozen'		=> (isset($data['config']['setting']['frozen']) ? $data['config']['setting']['frozen'] : 'false'  ),
	            'form-method'   => (isset($data['config']['setting']['form-method'])  ? $data['config']['setting']['form-method'] : 'native'  ),
	            'view-method'   => (isset($data['config']['setting']['view-method'])  ? $data['config']['setting']['view-method'] : 'native'  ),
	            'inline'        => (isset($data['config']['setting']['inline'])  ? $data['config']['setting']['inline'] : 'false'  ),				
				
			);			
					
		}
		return $data;
			
	
	} 

    static function getComboselect( $params , $limit =null, $parent = null)
    {   
        $limit = explode(':',$limit);
        $parent = explode(':',$parent);

        if(count($limit) >=3)
        {
            $table = $params[0]; 
            $condition = $limit[0]." `".$limit[1]."` ".$limit[2]." ".$limit[3]." "; 
            if(count($parent)>=2 )
            {
            	$row =  \DB::table($table)->where($parent[0],$parent[1])->get();
            	 $row =  \DB::select( "SELECT * FROM ".$table." ".$condition ." AND ".$parent[0]." = '".$parent[1]."'");
            } else  {
	           $row =  \DB::select( "SELECT * FROM ".$table." ".$condition);
            }        
        }else{

            $table = $params[0]; 
            if(count($parent)>=2 )
            {
            	$row =  \DB::table($table)->where($parent[0],$parent[1])->get();
            } else  {
	            $row =  \DB::table($table)->get();
            }	           
        }

        return $row;
    }	

	public static function getColoumnInfo( $result )
	{
		$pdo = \DB::getPdo();
		$res = $pdo->query($result);
		$i =0;	$coll=array();	
		while ($i < $res->columnCount()) 
		{
			$info = $res->getColumnMeta($i);			
			$coll[] = $info;
			$i++;
		}
		return $coll;	
	
	}	


	function validAccess( $id)
	{

		$row = \DB::table('tb_groups_access')->where('module_id','=', $id)
				->where('group_id','=', \Session::get('gid'))
				->get();
		
		if(count($row) >= 1)
		{
			$row = $row[0];
			if($row->access_data !='')
			{
				$data = json_decode($row->access_data,true);
			} else {
				$data = array();
			}	
			return $data;		
			
		} else {
			return false;
		}			
	
	}	

	static function getColumnTable( $table )
	{	  
        $columns = array();
	    foreach(\DB::select("SHOW COLUMNS FROM $table") as $column)
        {
           //print_r($column);
		    $columns[$column->Field] = '';
        }
	  

        return $columns;
	}	

	static function getTableList( $db ) 
	{
	 	$t = array(); 
		$dbname = 'Tables_in_'.$db ; 
		foreach(\DB::select("SHOW TABLES FROM {$db}") as $table)
        {
		    $t[$table->$dbname] = $table->$dbname;
        }	
		return $t;
	}	
	
	static function getTableField( $table ) 
	{
        $columns = array();
	    foreach(\DB::select("SHOW COLUMNS FROM $table") as $column)
		    $columns[$column->Field] = $column->Field;
        return $columns;
	}

	public static function searchOperation( $operate)
	{
		$val = '';
		switch ($operate) {
			case 'equal':
				$val = '=' ;
				break;
			case 'bigger_equal':
				$val = '>=' ;
				break;
			case 'smaller_equal':
				$val = '<=' ;
				break;
			case 'smaller':
				$val = '<' ;
				break;
			case 'bigger':
				$val = '>' ;
				break;
			case 'not_null':
				$val = 'not_null' ;
				break;

			case 'is_null':
				$val = 'is_null' ;
				break;

			case 'like':
				$val = 'like' ;
				break;

			case 'between':
				$val = 'between' ;
				break;

			default:
				$val = '=' ;
				break;
		}
		return $val;
	}
    public function checkModule($config_name,$module_id)
    {
        $id=\DB::table('user_module_config')->where('config_name','=',$config_name)->where('module_id','=',$module_id)->pluck('id');
        return $id;
    }

    public function getModuleConfig($module_id,$config_id)
    {

        $res = \DB::table('user_module_config')->where('module_id','=',$module_id)->where('id','=',$config_id)->get();

        return $res;
    }
    public function getLocations($id)
    {
        $locations=array();
        $i=0;
        $selected_loc=\DB::table('user_locations')->select('location_id')->where('user_id','=',$id)->get();
       foreach($selected_loc as $loc)
       {
           $locations[$i]=$loc->location_id;
           $i++;
       }
        return implode(',',$locations);
    }
    public function inserLocations($locations,$userid,$id)
    {

        $loc="";
        $i=0;
        foreach($locations as $location)
        {
            $loc[$i]=array('user_id'=>$userid,'location_id'=>$location);
            $i++;
        }
        if($id!=NULL) {
            \DB::table('user_locations')->where('user_id', '=',$userid)->delete();
        }

        \DB::table('user_locations')->insert($loc);
    }
    function getLocationDetails($id)
    {
        $locations = \DB::table('user_locations')
            ->join('location', 'user_locations.location_id', '=', 'location.id')
            ->select('location.*')
            ->where('user_locations.user_id','=',$id)
            ->get();
       return $locations;
    }
    function getLocation($location_id)
    {
        $row= \DB::table('location')
            ->join('region', 'location.region_id', '=', 'region.id')
            ->join('company','location.company_id','=','company.id')
            ->select('location.*','region.region','company.company_name_short')
            ->where('location.id','=',$location_id)
            ->get();
        return $row;
    }




}
