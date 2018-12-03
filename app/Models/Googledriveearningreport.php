<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class googledriveearningreport extends Sximo  {
	
	protected $table = 'google_drive_earning_reports';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT google_drive_earning_reports.* FROM google_drive_earning_reports  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE google_drive_earning_reports.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
