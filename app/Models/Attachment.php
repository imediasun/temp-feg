<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sximo\Module;

class Attachment extends Sximo
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    public function __construct()
    {
        parent::__construct();
    }

}
