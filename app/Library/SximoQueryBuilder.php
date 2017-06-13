<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
class SximoQueryBuilder extends Builder
{


    public function update(array $values)
    {
        Sximo::insertLog($this->from,'Update' , 'SximoQueryBuilder',json_encode($this->wheres),json_encode($values));
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        if($method == 'insert' || $method == 'insertGetId')
        {
            Sximo::insertLog($this->from,'Insert/InsertGetId' , 'SximoQueryBuilder',json_encode($this->wheres),json_encode($parameters));
        }
        return parent::__call($method,$parameters);
    }

    public function delete($id = null)
    {
        Sximo::insertLog($this->from,'Delete' , 'SximoQueryBuilder',json_encode($this->wheres),json_encode($id));

        return parent::delete($id);
    }
}
