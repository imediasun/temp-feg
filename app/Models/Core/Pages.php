<?php namespace App\Models\Core;

use App\Models\Sximo;
use \App\Models\Sximo\Module;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Pages extends Sximo  {
	
	protected $table = 'tb_pages';
	protected $primaryKey = 'pageID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_pages.* FROM tb_pages  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_pages.pageID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
    
	public static function canIEdit($page = null, $user = null) {
        $canEdit = false;
        if (empty($user)) {
            $user = \Session::get('uid');
        }
        if (empty($user)) {
            return $canEdit;
        }
        $group = \SiteHelpers::getUserGroup($user);

        $module_id = Module::name2id('pages');
        $pass = \FEGSPass::getMyPass($module_id, $user);
        $canEditGeneral = !empty($pass['See Page Edit Link In View']);

        if (empty($page)) {
            return $canEditGeneral;
        }
        
        $pageData = self::where('pageID', $page)->first();
        if (empty($pageData) || empty($pageData->count())) {
            return $canEdit;
        }

        $pageEditGroups = empty($pageData->direct_edit_groups) ? [] :
                        explode(',', $pageData->direct_edit_groups);
        $pageEditUsers = empty($pageData->direct_edit_users) ? [] :
                        explode(',', $pageData->direct_edit_users);
        $pageEditExcludedUsers = empty($pageData->direct_edit_users_exclude) ?
                    [] : explode(',', $pageData->direct_edit_users_exclude);
        
        if (empty($pageEditGroups) && empty($pageEditExcludedUsers) && empty($pageEditUsers)) {
            return $canEditGeneral;
        }

        $canEdit = !in_array($user, $pageEditExcludedUsers) &&
            (in_array($user, $pageEditUsers) || in_array($group, $pageEditGroups));

        return $canEdit;
        
    }

}
