<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class excludedreaders extends Sximo  {
	
	protected $table = 'reader_exclude';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){

        return " SELECT reader_exclude.*
FROM reader_exclude
  LEFT JOIN location
    ON location.id = reader_exclude.loc_id
  LEFT JOIN debit_type
    ON debit_type.id = reader_exclude.debit_type_id ";
	}	

	public static function queryWhere(  ){

        return " WHERE reader_exclude.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

}
