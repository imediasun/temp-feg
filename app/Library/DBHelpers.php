<?php
namespace App\Library;

use PDO;
use DB;

class DBHelpers
{
    public static function exists($table, $where, $returnField = "id", $dbName = "", $multipleReturn = false) {
        $database = empty($dbName) ? DB::connection() : DB::connection($dbName);
        $q = "SELECT $returnField as returnField FROM $table WHERE ";
        foreach($where as $clause) {
            $clauseA[] = $clause[0].
                    (isset($clause[2])?$clause[1]:'=').
                    "'".(isset($clause[2])?$clause[2]:$clause[1])."'";
        }
        $clauseQ = implode(" AND ", $clauseA);
        $q .= $clauseQ;
                
        $data = $database->select($q);
        
        $result = false;
        if(!empty($data) && count($data) > 0) {
            if ($multipleReturn) {
                $result = array();
                foreach($data as $item) {
                    $result[] = $item->returnField;
                }
            }
            else {
                $item = $data[0];
                $result = $item->returnField;
            }
        }
        
        return $result;
    }
}