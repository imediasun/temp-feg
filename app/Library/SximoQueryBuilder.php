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
        $allwheres = [];
        if(isset($this->wheres[0]['value']))
        {
            foreach ($this->wheres as $i => $where)
            {
                $allwheres[$i]['column'] = $where['column'];
                $allwheres[$i]['value'] = $where['value'];
            }
        }

        Sximo::insertLog($this->from,'Update' , 'SximoQueryBuilder',json_encode($allwheres),json_encode($values));
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        if($method == 'insert' || $method == 'insertGetId')
        {
            if(isset($this->wheres[0]['value']))
            {
                foreach ($this->wheres as $i => $where)
                {
                    $allwheres[$i]['column'] = $where['column'];
                    $allwheres[$i]['value'] = $where['value'];
                }
            }
            Sximo::insertLog($this->from,'Insert/InsertGetId' , 'SximoQueryBuilder',json_encode($allwheres),json_encode($parameters));
        }
        return parent::__call($method,$parameters);
    }

    public function delete($id = null)
    {
        if(isset($this->wheres[0]['value']))
        {
            foreach ($this->wheres as $i => $where)
            {
                $allwheres[$i]['column'] = $where['column'];
                $allwheres[$i]['value'] = $where['value'];
            }
        }
        Sximo::insertLog($this->from,'Delete' , 'SximoQueryBuilder',json_encode($allwheres),json_encode($id));

        return parent::delete($id);
    }
}
