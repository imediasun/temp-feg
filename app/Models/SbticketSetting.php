<?php namespace App\Models;

use App\Models\Core\Users;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class sbticketsetting extends Sximo  {

    protected $table = 'sbticket_setting';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT sbticket_setting.* FROM sbticket_setting  ";
    }

    public static function queryWhere(  ){

        return "  WHERE sbticket_setting.id IS NOT NULL ";
    }

    public static function queryGroup(){
        return "  ";
    }

    public static function getPartRequestUsers()
    {
        $allowedUsersGroups = self::select(['user_permission_groups', 'user_permission'])->where('setting_type', 'game-related')->first();
        $data = ['allowed_users' => [], 'allowed_user_groups', 'user_email_addresses'];
        if ($allowedUsersGroups->count() > 0) {
            $users = !empty($allowedUsersGroups->user_permission) ? explode(',', $allowedUsersGroups->user_permission) : [];
            $Groups = !empty($allowedUsersGroups->user_permission_groups) ? explode(',', $allowedUsersGroups->user_permission_groups) : [];
            $userEmail = Users::select('email')->whereIn('group_id',$Groups)->orWhereIn('id',$users)->get()->pluck('email')->toArray();

            if(count($userEmail) > 0) {
                $userEmail = array_unique($userEmail);
            }
            $data = ['allowed_users' => $users, 'allowed_user_groups' => $Groups,'user_email_addresses'=>$userEmail];
        }
        return $data;
    }


}
