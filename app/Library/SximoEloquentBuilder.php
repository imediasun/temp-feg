<?php
namespace App\Library;

use App\Models\Sximo;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
class SximoEloquentBuilder extends Builder
{
    public function update(array $values)
    {
        Sximo::insertLog($this->model->getTable(),'Update' ,'SximoEloquentBuilder', json_encode($values),json_encode($this->model->getAttributes()));
        return parent::update($values);
    }

    public function __call($method, $parameters)
    {
        if($method == 'insert' || $method == 'insertGetId')
        {
            Sximo::insertLog($this->model->getTable(),$method,'SximoEloquentBuilder', json_encode($parameters),json_encode($this->model->getAttributes()));
        }
        return parent::__call($method,$parameters);
    }

    public function delete()
    {
        Sximo::insertLog($this->model->getTable(),'delete','SximoEloquentBuilder',$this->model->id, json_encode($this->model->getAttributes()));
        return parent::delete();
    }
}
