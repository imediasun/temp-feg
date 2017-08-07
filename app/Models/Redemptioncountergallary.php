<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class redemptioncountergallary extends Sximo  {
	
	protected $table = 'img_uploads';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

    public static function querySelect(  ){
        return 'SELECT img_uploads.id,img_uploads.theme_name,CONCAT(L.id, " | ",L.location_name_short) AS Location, img_uploads.users,img_uploads.loc_id,img_uploads.img_rotation
									 FROM img_uploads LEFT JOIN location L on L.id = img_uploads.loc_id ';
    }

    public static function queryWhere($cond = null){
        $qw = " WHERE img_uploads.image_category = 'red'";
        return  $qw;
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public static function getComboselect($params, $limit = null, $parent = null) {
		$tableName = $params[0];
		if($tableName == 'location'){
			return parent::getUserAssignedLocation($params,$limit,$parent);
		}
		else{
			return parent::getComboselect($params,$limit,$parent);
		}
	}
}
