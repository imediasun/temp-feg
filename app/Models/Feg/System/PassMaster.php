<?php namespace App\Models\Feg\System;

use Illuminate\Database\Eloquent\Model;
use App\Library\FEG\System\FEGSystemHelper;

class PassMaster extends Model  {
	protected $table = 'feg_special_permissions_master';
	protected $childTable = 'feg_special_permissions';

    public function pass() {
        return $this->hasMany('App\Models\Feg\System\Pass', 'permission_id');
    }
}
