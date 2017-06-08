<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
class SximoBuilder extends Builder
{
    public function update(array $values)
    {
        Sximo::insertLog($this->model->getTable(),'Update' , implode(',',$values));
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        Sximo::insertLog($this->model->getTable(),$method, implode(',',$parameters));
        return parent::__call($method,$parameters);
    }
}
