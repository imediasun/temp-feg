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
		
		return "  ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    public static function getRow( $id )
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
