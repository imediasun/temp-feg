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

    public function __construct(ConnectionInterface $connection,
                                Grammar $grammar,
                                Processor $processor)
    {
        $this->grammar = $grammar;
        $this->processor = $processor;
        $this->connection = $connection;
    }

    /*public function update(array $values)
    {
        dd('SximoQueryBuilder',$this->model->getAttributes());
        Sximo::insertLog($this->model->getTable(),'Update' , json_encode($values),json_encode($this->model->getAttributes()));
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        if($method == 'insert' || $method == 'insertGetId')
        {
            Sximo::insertLog($this->model->getTable(),$method, json_encode($parameters),json_encode($this->model->getAttributes()));
        }
        return parent::__call($method,$parameters);
    }

    public function delete($id = null)
    {
        Sximo::insertLog($this->model->getTable(),'delete',$this->model->id, json_encode($this->model->getAttributes()));
        return parent::delete($id);
    }*/
}
