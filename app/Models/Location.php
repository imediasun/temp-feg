<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use SiteHelpers;

class location extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

    public static function getQuery( ) {
        $roleSQL = \SiteHelpers::getUniqueLocationUserAssignmentMeta('sql');
        $sql = "SELECT ".$roleSQL['select'] . ", location.* 
            FROM location " .$roleSQL['join'];
        return $sql;
    }
	public static function querySelect(  ){        
		return self::getQuery();
	}	

	public static function queryWhere(  ){
		
		return " Where location.id is not null  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public static function getRow($id)
    {       
        if (empty($id)) {
            return false;
        }
        $sql = self::querySelect();
        $rows = \DB::select($sql." WHERE location.id='$id'");
        return $rows;
    }

}
