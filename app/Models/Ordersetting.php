<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ordersetting extends Sximo
{

    protected $table = 'ordersettings';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public function orderSettingContent()
    {
        return $this->hasMany('App/Models/ordersettingcontent');
    }

    public static function querySelect()
    {

        return "  SELECT ordersettings.* FROM ordersettings  ";
    }

    public static function queryWhere()
    {

        return "  WHERE ordersettings.id IS NOT NULL ";
    }

    public static function queryGroup()
    {
        return "  ";
    }

}
