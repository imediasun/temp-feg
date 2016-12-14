<?php namespace App\Models\Feg\System;

use DB;
use App\Models\Sximo;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class systememailreportmanager extends Sximo  {
	
    protected $table = 'system_email_report_manager';
	protected $primaryKey = 'id';
    
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT system_email_report_manager.* FROM system_email_report_manager  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE system_email_report_manager.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "      ";
	}
    
    public static function getUserEmails($id = null) {
        $q = "SELECT id, first_name, last_name, username, email, group_id from users ";
        if (!empty($id)) {
            $q .= "WHERE id in ($id)";
        }
        return DB::select($q);
    }
    public static function getGroupNames($id = null) {
        $q = "SELECT group_id as id, `name` as group_name, level from tb_groups ";
        if (!empty($id)) {
            $q .= "WHERE group_id in ($id)";
        }
        return DB::select($q);        
    }
    public static function getUserEmailsIDAssociated() {
        $data = self::getUserEmails();
        $ret = array();
        foreach($data as $row) {
            $id = $row->id;
            $ret[$id] = $row;
        }
        return $ret;
    }
    public static function getGroupNamesIDAssociated() {
        $data = self::getGroupNames();
        $ret = array();
        foreach($data as $row) {
            $id = $row->id;
            $ret[$id] = $row;
        }
        return $ret;
    }
    public static function getUsersOnGroupNamesIDAssociated() {
        $data = self::getUserEmails();
        $ret = array();
        foreach($data as $row) {
            $gid = $row->group_id;
            $id = $row->id;
            if (!isset($ret[$gid])) {
                $ret[$gid] = array();
            }
            $ret[$gid][$id] = $row;
        }
        return $ret;
    }
    
}
