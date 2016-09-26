<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class merchindisetheminggallary extends Sximo  {
	
	protected $table = 'img_uploads';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		return 'SELECT I.id,I.theme_name,CONCAT(L.id, " | ",L.location_name_short) AS Location, I.users
									 FROM img_uploads I LEFT JOIN location L on L.id = I.loc_id ';
	}	

	public static function queryWhere($cond = null){
        $qw = " WHERE I.image_category = 'mer'";
		$filters = self::getSearchFilters(array('theme_name'));
        extract($filters);        
		
        if (!empty($theme_name)) {
            $qw .= " AND I.theme_name LIKE '%$theme_name%' ";
        }
        return  $qw;
	}
	
	public static function queryGroup(){
		return "  ";
	}
}
