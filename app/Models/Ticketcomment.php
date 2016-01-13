<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Ticketcomment extends Sximo  {
	
	protected $table = 'sb_ticketcomments';
	protected $primaryKey = 'CommentID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT sb_ticketcomments.* FROM sb_ticketcomments  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE sb_ticketcomments.CommentID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
