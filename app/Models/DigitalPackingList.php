<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DigitalPackingList extends Sximo
{
    protected $table = 'digital_packing_lists';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }
}
