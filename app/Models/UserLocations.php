<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class UserLocations extends Sximo  {
	
	protected $table = 'user_locations';
	protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['group_id', 'location_id', 'user_id'];
    
	public function __construct() {
		parent::__construct();		
	}
    
    public static function updateRoleAssignment($locationId, $userId, $role) {
        
        if (!is_numeric($role)) {
            $roles = \SiteHelpers::getUniqueLocationUserAssignmentMeta('field-id');
            $role = isset($roles[$role]) ? $roles[$role] : null;
        }
        if (empty($role)) {
            return false;
        }
        if (is_null($userId)) {            
            self::where(['location_id' => $locationId, 'group_id' => $role])->delete();
            return false;
        }
        self::updateOrCreate([
            'location_id' => $locationId, 'group_id' => $role
        ], [
            'user_id' => $userId, 'location_id' => $locationId, 'group_id' => $role
        ]);
        
        return;
        $locationRoleData = self::whereRaw(" location_id=$locationId AND (group_id=$role OR user_id=$userId) ")
                ->orderBy('id', 'desc')->get();
        
        if ($locationRoleData->count() == 0) {
            self::insert(['location_id' => $locationId, 'user_id' => $userId, 'group_id' => $role]);
        }
        else {
            $ids = [];
            foreach($locationRoleData as $item) {
                $LR_id = $item->id;
                $LR_user = $item->user_id;
                $LR_group = $item->group_id;
                $updated = false;
                if (!empty($LR_group)) {
                    self::where('id', $LR_id)->update(['user_id' => $userId]);
                    $updated = true;
                }
                if (!empty($LR_user) && empty($LR_group)) {
                    self::where('id', $LR_id)->update(['group_id' => $role]);
                    $updated = true;
                }
                if (!empty($LR_user) && !empty($LR_group)) {
                    $updated = true;
                }
                if ($updated) {
                    self::where(['location_id' => $locationId, 'group_id' => $role])
                            ->where('id', '!=', $LR_id)
                            ->delete();
                    self::where(['location_id' => $locationId, 'user_id' => $userId, 'group_id' => null])->delete();
                    break;
                }
            }
        }
    }
    
    public static function getRoleAssignment($locationId, $role) {
        
        if (!is_numeric($role)) {
            $roles = \SiteHelpers::getUniqueLocationUserAssignmentMeta('field-id');
            $role = isset($roles[$role]) ? $roles[$role] : null;
        }
        if (empty($role)) {
            return null;
        }
        
        $data = self::where(['location_id' => $locationId, 'group_id' => $role])
                ->pluck('user_id');
        return $data;
    }
    public static function getRoleAssignments($locationId) {
        $data = self::where('location_id', $locationId)
                ->where('group_id', '!=', null)
                ->get();
        return $data;
    }

    /**
     * @param string $locationNameColumnAs
     * @return mixed
     */
    public static function getUserAssignedLocations($locationNameColumnAs = 'location.location_name'){
        return  self::select('location.id',$locationNameColumnAs)
            ->Join('location','location.id','=','user_locations.location_id')
            ->where('location.active',1)
            ->where('user_locations.user_id',Session::get('uid'))->orderBy('location.id','asc');
    }
}
