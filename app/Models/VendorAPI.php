<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class VendorAPI extends Sximo
{

    protected $table = 'vendor';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();

    }

    public static function querySelect()
    {


        return "SELECT vendor.* FROM vendor ";
    }

    public static function queryWhere()
    {
        //
        return "  WHERE vendor.status = 1 AND vendor.hide = 0 AND id IS NOT NULL";
    }

    public static function queryGroup()
    {
        return "  ";
    }

    public static function processApiData($json, $param = null)
    {

        //loop over all records and check if website is not empty then add http:// prefix for it
        $data = array();
        foreach ($json as $record) {
            if (!empty($record['website'])) {
                if (strpos($record['website'], 'http') === false) {
                    $record['website'] = 'http://' . $record['website'];
                }
            }

            if (!empty($record['country_id'])) {
                $record['country_name'] = self::vendorCountry($record['country_id'])[0]->country_name;
            } else {
                $record['country_name'] = '';
            }
            $data[] = $record;
        }
        return $data;
    }

    public static function vendorCountry($country_id)
    {
        $result = \DB::select('select * from countries where id=' . $country_id);
        return $result;
    }


}
