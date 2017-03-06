<?php namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticketfollowers;
use App\Models\Core\TicketMailer;


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
    
    public static function getSettings() {
        return self::first()->toArray();
    }

	public static function getUserPermissions($id = null){
        $gid = \SiteHelpers::getUserGroup($id);
        if (empty($id)) {
            $id = \Session::get('uid');
        }        
		$data = self::getAllPermissions();
        $permissions = [
            "canChangeStatus" => false, 
            "omniscient" => false, 
            "followAllInLocation" => false, 
            "newTicketNotificaitonInLocationOnly" => false
        ];
        
        $sGroups = $data['canChangeStatus']['groups'];
        $sUsers = $data['canChangeStatus']['users'];
        $oGroups = $data['omniscient']['groups'];
        $oUsers = $data['omniscient']['users'];
        $fGroups = $data['followAllInLocation']['groups'];
        $fUsers = $data['followAllInLocation']['users'];
        $nGroups = $data['newTicketNotificaitonInLocationOnly']['groups'];
        $nUsers = $data['newTicketNotificaitonInLocationOnly']['users'];
                        
        $permissions['canChangeStatus'] = (!empty($gid) && !empty($sGroups) && in_array($gid, $sGroups)) || 
                (!empty($id) && !empty($sUsers) && in_array($id, $sUsers));
        $permissions['omniscient'] = (!empty($gid) && !empty($oGroups) && in_array($gid, $oGroups)) || 
                (!empty($id) && !empty($oUsers) && in_array($id, $oUsers));
        $permissions['followAllInLocation'] =  (!empty($gid) && !empty($fGroups) && in_array($gid, $fGroups)) || 
                (!empty($id) && !empty($fUsers) && in_array($id, $fUsers));               
        $permissions['newTicketNotificaitonInLocationOnly'] = (!empty($gid) && !empty($nGroups) && in_array($gid, $nGroups)) || 
                (!empty($id) && !empty($nUsers) && in_array($id, $nUsers));
        
        return $permissions;        
	}
    
	public static function getAllPermissions(){
		$data = self::getSettings();
        if (is_null($data)) {
            return [
                "nodata" => true, 
                "canChangeStatus" => false, 
                "omniscient" => false, 
                "followAllInLocation" => false, 
                "newTicketNotificaitonInLocationOnly" => false
            ];
        }
        $omniscients = ['groups' => [], 'users' => ''];
        $followAllInLocation = ['groups' => [], 'users' => ''];
        $newTicketNotificaitonInLocationOnly = ['groups' => [], 'users' => ''];

        $role1 = $data['role1'];
        $role2 = $data['role2'];
        $role3 = $data['role3'];
        $role4 = $data['role4'];
        $role5 = $data['role5'];
        $user1 = $data['individual1'];
        $user2 = $data['individual2'];
        $user3 = $data['individual3'];
        $user4 = $data['individual4'];
        $user5 = $data['individual5'];        
        
        $statusChangers['groups'] = explode(',', $role3);
        $omniscients['groups'] = explode(',', $role1);
        $followAllInLocation['groups'] = explode(',', $role2);
        $newTicketNotificaitonInLocationOnly['groups'] = explode(',', $role4);
        
        $statusChangers['users'] = explode(',', $user3);
        $omniscients['users'] = explode(',', $user1);
        $followAllInLocation['users'] = explode(',', $user2);
        $newTicketNotificaitonInLocationOnly['users'] = explode(',', $user4);
        
        $permissions = [
            "canChangeStatus" => $statusChangers, 
            "omniscient" => $omniscients, 
            "followAllInLocation" => $followAllInLocation, 
            "newTicketNotificaitonInLocationOnly" => $newTicketNotificaitonInLocationOnly
        ];
        return $permissions;
	}
	public static function isUserOmniscient($id = null){
        $permissions = self::getUserPermissions($id);           
        return $permissions['omniscient'];
	}
	public static function canUserChangeStatus($id = null){
        $permissions = self::getUserPermissions($id);           
        return $permissions['canChangeStatus'];
	}
}
