<?php namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ticketsetting extends Sximo  {
	
	protected $table = 'sbticket_setting';
	protected $primaryKey = '';

	public function __construct() {
		parent::__construct();
	}

	public static function querySelect(  ){
		return "  SELECT sbticket_setting.* FROM sbticket_setting  ";
	}

	public static function queryWhere(  ){
		return "   ";
	}
	public static function queryGroup(){
		return "  ";
	}


	public static function getUserPermissions($id = null){
        $gid = \SiteHelpers::getUserGroup($id);
        if (empty($id)) {
            $id = \Session::get('uid');
        }        
		$data = self::getAllPermissions();
        $permissions = ["omniscient" => false, "followAllInLocation" => false, "newTicketNotificaitonInLocationOnly" => false];
        $oGroups = $data['omniscient']['groups'];
        $oUsers = $data['omniscient']['users'];
        $fGroups = $data['followAllInLocation']['groups'];
        $fUsers = $data['followAllInLocation']['users'];
        $nGroups = $data['newTicketNotificaitonInLocationOnly']['groups'];
        $nUsers = $data['newTicketNotificaitonInLocationOnly']['users'];
                        
        $permissions['omniscient'] = in_array($gid, $oGroups) || in_array($id, $oUsers);
        $permissions['followAllInLocation'] = in_array($gid, $fGroups) || in_array($id, $fUsers);
        $permissions['newTicketNotificaitonInLocationOnly'] = in_array($gid, $nGroups) || in_array($id, $nUsers);
        
        return $permissions;
        
	}
	public static function getAllPermissions(){
		$data = DB::table('sbticket_setting')->get();
        $omniscients = ['groups' => [], 'users' => ''];
        $followAllInLocation = ['groups' => [], 'users' => ''];
        $newTicketNotificaitonInLocationOnly = ['groups' => [], 'users' => ''];
        if (!empty($data)) {
            $data = $data[0];
        }
        $role1 = $data->role1;
        $role2 = $data->role2;
        $role3 = $data->role3;
        $role4 = $data->role4;
        $role5 = $data->role5;
        $user1 = $data->individual1;
        $user2 = $data->individual2;
        $user3 = $data->individual3;
        $user4 = $data->individual4;
        $user5 = $data->individual5;
        
        $omniscients['groups'] = explode(',', $role1);
        $followAllInLocation['groups'] = explode(',', $role2);
        $newTicketNotificaitonInLocationOnly['groups'] = explode(',', $role4);
        
        $omniscients['users'] = explode(',', $user1);
        $followAllInLocation['users'] = explode(',', $user2);
        $newTicketNotificaitonInLocationOnly['users'] = explode(',', $user4);
        
        $permissions = ["omniscient" => $omniscients, 
            "followAllInLocation" => $followAllInLocation, 
            "newTicketNotificaitonInLocationOnly" => $newTicketNotificaitonInLocationOnly
        ];
        return $permissions;
	}
	public static function isUserOmniscient($id = null){
        $permissions = self::getUserPermissions($id);        
        return $permissions['omniscient'];
	}
	public static function getGlobalSubscribers($location = null){
		return "  ";
	}
	public static function getNewTicketSubscribers($location = null){
		return "  ";
	}
	

}
