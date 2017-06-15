<?php namespace App\Models\Core;

use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Groups extends Sximo  {
	
	protected $table = 'tb_groups';
	protected $primaryKey = 'group_id';
    const USER = 1;
    const PARTNER = 2;
    const MERCH_MANAGER = 3;
    const FIELD_MANAGER = 4;
    const OFFICE_MANAGER = 5;
    const DISTRICT_MANAGER = 6;
    const FINANCE_MANAGER = 7;
    const PARTNER_PLUS = 8;
    const GUEST = 9;
    const SUPPER_ADMIN = 10;
    const TECHNICAL_MANAGER = 11;
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		
		return " SELECT  
	tb_groups.group_id,
	tb_groups.name,
	tb_groups.description,
	tb_groups.level


FROM tb_groups  ";
	}
	public static function queryWhere(  ){
		
		return "  WHERE tb_groups.group_id IS NOT NULL    ";
	}
	
	public static function queryGroup(){
		return "    ";
	}
	

}
