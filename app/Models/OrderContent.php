<?php namespace App\Models;


class OrderContent extends Sximo  {

    protected $table = 'order_contents';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public function product(){
        return $this->belongsTo("App\Models\product");
    }

}
