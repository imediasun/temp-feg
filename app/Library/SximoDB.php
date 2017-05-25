<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
class SximoDB extends \Illuminate\Support\Facades\DB
{

    public static function insert($query, $bindings = [])
    {
        Sximo::insertLog('Users','insert' , $query);
        return parent::insert($query, $bindings);
    }

    public static function update($query, $bindings = [])
    {
        return parent::update($query, $bindings);
    }


    public static function delete($query, $bindings = [])
    {
        return parent::delete($query, $bindings);
    }

    public static function table($table)
    {
        return parent::table($table);
    }
}
