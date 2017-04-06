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
		return 'SELECT img_uploads.id,img_uploads.theme_name,CONCAT(L.id, " | ",L.location_name_short) AS Location, img_uploads.users,img_uploads.batch
									 FROM img_uploads LEFT JOIN location L on L.id = img_uploads.loc_id ';
	}	

	public static function queryWhere($cond = null){
        $qw = " WHERE img_uploads.image_category = 'mer'";
        return  $qw;
	}
	
	public static function queryGroup(){
		return "  ";
	}
}
