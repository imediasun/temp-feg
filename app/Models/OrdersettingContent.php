<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ordersettingcontent extends Sximo
{

    protected $table = 'ordersettings_contents';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    /*
     *
     */
    public function ordersetting()
    {
        return $this->belongsTo("App\Models\ordersetting", "ordersetting_id");
    }

    public static function querySelect()
    {

        return "  SELECT ordersettings_contents.* FROM ordersettings_contents  ";
    }

    public static function queryWhere()
    {

        return "  WHERE ordersettings_contents.id IS NOT NULL ";
    }

    public static function queryGroup()
    {
        return "  ";
    }

}
