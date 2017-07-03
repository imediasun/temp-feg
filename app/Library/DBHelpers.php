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
    public static function getEnumValues($table, $column) {
      $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ;
      preg_match('/^enum\((.*)\)$/', $type, $matches);
      $enum = array();
      foreach( explode(',', $matches[1]) as $value )
      {
        $v = trim( $value, "'" );
        $enum = array_add($enum, $v, $v);
      }
      return $enum;
    }

    public static function getHighestRecorded($table, $field, $whereClauses = null, $connectionName = '') {
        $database = empty($connectionName) ? DB::connection() : DB::connection($connectionName);
        $q = $database->table($table);
        if ($whereClauses) {
            $q->whereRaw($whereClauses);
        }
        $ret = $q->max($field);
        return $ret;
    }
}