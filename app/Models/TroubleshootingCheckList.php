<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class troubleshootingchecklist extends Sximo  {
	
	protected $table = 'troubleshooting_check_lists';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT troubleshooting_check_lists.* FROM troubleshooting_check_lists  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE troubleshooting_check_lists.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

    public function scopeIsActive($query){
        return $query->where('is_active',1);
    }

}
