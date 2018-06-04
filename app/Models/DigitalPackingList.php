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
    public function truncateString($string)
    {
        if (strlen($string) < 50 || strlen($string) == 50 ) {
           return $string;
        }
        else{
            $string = substr($string,0,50);
            return $string;
        }
    }
}
