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
        if(!($this->from == 'users' && isset($values['last_activity'])))
        {
            Sximo::insertLog($this->from,'Update' , 'SximoQueryBuilder',json_encode($allwheres),json_encode($values));
        }
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        if($method == 'insert' || $method == 'insertGetId')
        {
            Sximo::insertLog($this->from,'Insert/InsertGetId' , 'SximoQueryBuilder','',json_encode($parameters));
        }
        return parent::__call($method,$parameters);
    }

    public function delete($id = null)
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
        Sximo::insertLog($this->from,'Delete' , 'SximoQueryBuilder',json_encode($allwheres),json_encode($id));

        return parent::delete($id);
    }
    public function insertGetId(array $values, $sequence = null)
    {
        if(!isset($values['auditID']))
        {
            Sximo::insertLog($this->from,'InsertGetId' , 'SximoQueryBuilder','',json_encode($values));
        }

        return parent::insertGetId($values,$sequence);
    }
    public function insert(array $values)
    {
        Sximo::insertLog($this->from,'Insert' , 'SximoQueryBuilder','',json_encode($values));
        return parent::insert($values);
    }

}
