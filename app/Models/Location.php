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

	public static function querySelect(  ){
        $roleSQL = \SiteHelpers::getUniqueLocationUserAssignmentMeta('sql');
        $sql = "SELECT ".$roleSQL['select'] . ", location.* 
            FROM location " .$roleSQL['join'];
		return $sql;
	}	

	public static function queryWhere(  ){
		
		return " Where location.id is not null  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
  /*  public static function getRow( $id )
    {
        $row= \DB::table('location')
            ->join('region', 'location.region_id', '=', 'region.id')
            ->join('company','location.company_id','=','company.id')
            ->select('location.*','region.region','company.company_name_short')
            ->where('location.id','=',$id)
            ->get();
        return $row;
    }*/
    public static function getRow($id)
    {
        $roleSQL = \SiteHelpers::getUniqueLocationUserAssignmentMeta('sql');
        $sql = "SELECT ".$roleSQL['select'] . ", location.* 
            FROM location " .$roleSQL['join'];
        $row=\DB::select($sql." WHERE location.id='$id'");
        return $row;
    }

}
