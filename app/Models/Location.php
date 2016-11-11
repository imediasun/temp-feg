<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class location extends Sximo  {
	
	protected $table = 'location';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT location.* FROM location  ";
	}	

	public static function queryWhere(  ){
		
		return " Where location.id is not null and location.active=1  ";
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
        $row=\DB::select('SELECT L.*,
									  U.first_name,
									  U.last_name,
									  C.company_name_short,
									  R.region
								 FROM location L
						    LEFT JOIN users U ON U.id = L.contact_id
						    LEFT JOIN company C ON C.id = L.company_id
						    LEFT JOIN region R ON R.id = L.region_id
								WHERE L.id='.$id.'');
        return $row;
    }


}
