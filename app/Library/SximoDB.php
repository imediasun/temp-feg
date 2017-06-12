<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
class SximoDB extends \Illuminate\Support\Facades\DB
{

    public static function insert($query, $bindings = [])
    {
        $myquery = strtolower($query);

        $data = substr($myquery, strpos($myquery, "values") + 6);
        $table = substr($myquery, strpos($myquery, "into") + 4,strpos($myquery, " (") );
        $columns = substr($myquery, strpos($myquery, '(') , strpos($myquery, "values")-6 );

        Sximo::insertLog($table,'insert' , 'SximoDB',$columns,$data);
        return parent::insert($query, $bindings);
    }

    public static function insertGetId(array $values, $sequence = null)
    {

        Sximo::insertLog('Users__','insertGetId' , 'SximoDB','',json_encode($values));
        return parent::insertGetId($values, $sequence);
    }


    public static function update($query, $bindings = [])
    {
        $myquery = strtolower($query);
        $data = substr($myquery, strpos($myquery, "set") + 3,(strpos($myquery, "where")-strpos($myquery, "set") -3));

        $condition = substr($myquery, strpos($myquery, "where") + 5);
        $table = substr($myquery, strpos($myquery, "update") + 6,strpos($myquery, "set") - 6);

        Sximo::insertLog($table,'Update' ,'SximoDB', $condition,$data);
        return parent::update($query, $bindings);
    }


    public static function delete($query, $bindings = [])
    {
        $myquery = strtolower($query);

        $condition = substr($myquery, strpos($myquery, "where") + 5);
        $table = substr($myquery, strpos($myquery, "from") + 4,strlen($condition));
        Sximo::insertLog($table,'Delete' ,'SximoDB', $condition,'');
        return parent::delete($query, $bindings);
    }

    public static function table($table)
    {
        return parent::table($table);
    }
}
