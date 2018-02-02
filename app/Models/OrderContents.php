<?php namespace App\Models;


class OrderContents extends Sximo  {

    protected $table = 'order_contents';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

}
