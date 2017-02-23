<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Ticketcomment extends Sximo  {
	
	protected $table = 'sb_ticketcomments';
	protected $primaryKey = 'CommentID';
    public $timestamps = false;
    
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
	
    public static function getCommentsWithUserData($ticketId, $sortBy = 'Posted', $order = 'desc', $fields = array()) {
            
        $data = self::select(
                'sb_ticketcomments.*', 
                'users.username',  
                'users.first_name',  
                'users.last_name',  
                'users.email',  
                'users.avatar',  
                'users.active',  
                'users.group_id'
            )
            ->leftJoin('users', 'users.id', '=', 'sb_ticketcomments.UserID')
            ->where('TicketID', '=', $ticketId)
            ->orderBy($sortBy, $order)
            ->get();
    
        return $data;
    }
}
