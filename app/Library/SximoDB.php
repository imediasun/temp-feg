<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
class SximoDB extends \Illuminate\Support\Facades\DB
{

    public static function insert($query, $bindings = [])
    {
        $myquery = strtolower($query);

        $queryArray = explode(' ',$myquery);
        if(!self::copyOperation($myquery,$queryArray))
        {
            $data = substr($myquery, strpos($myquery, "values") + 6);
            //$table = substr($myquery, strpos($myquery, "into") + 4,strpos($myquery, " (") );
            $table = $queryArray[2];
            $columns = substr($myquery, strpos($myquery, '(') , strpos($myquery, "values")-6 );

            Sximo::insertLog($table,'insert' , 'SximoDB',$columns,$data);
        }
        return parent::insert($query, $bindings);
    }

    public static function copyOperation($query,$queryArray)
    {
        $tablename = $queryArray[2];
        $key = array_search('select',$queryArray);
        $tablename2 = $queryArray[$key+3];
        if($tablename == $tablename2)
        {
            $condition = substr($query, strpos($query, "where") + 5);
            Sximo::insertLog($tablename,'copy' , 'SximoDB',$condition,$query);
            return true;
        }
        return false;
    }

    public static function insertGetId(array $values, $sequence = null)
    {

        Sximo::insertLog('Users__','insertGetId' , 'SximoDB','',json_encode($values));
        return parent::insertGetId($values, $sequence);
    }


    public static function update($query, $bindings = [])
    {
        $myquery = strtolower($query);
        $queryArray = explode(' ',$myquery);
        $data = substr($myquery, strpos($myquery, "set") + 3,(strpos($myquery, "where")-strpos($myquery, "set") -3));

        $condition = substr($myquery, strpos($myquery, "where") + 5);
        //$table = substr($myquery, strpos($myquery, "update") + 6,strpos($myquery, "set") - 6);
        $table = $queryArray[1];

        Sximo::insertLog($table,'Update' ,'SximoDB', $condition,$data);
        return parent::update($query, $bindings);
    }


    public static function delete($query, $bindings = [])
    {
        $myquery = strtolower($query);
        $queryArray = explode(' ',$myquery);
        $condition = substr($myquery, strpos($myquery, "where") + 5);
        //$table = substr($myquery, strpos($myquery, "from") + 4,strlen($condition));
        $table = $queryArray[2];
        Sximo::insertLog($table,'Delete' ,'SximoDB', $condition,'');
        return parent::delete($query, $bindings);
    }

    public static function table($table)
    {
        return parent::table($table);
    }
}
