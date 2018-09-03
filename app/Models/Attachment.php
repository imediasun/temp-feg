<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sximo\Module;

class Attachment extends Sximo
{
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $fillable = ['name','path','extension'];
    public function __construct()
    {
        parent::__construct();
    }

    public function attachable(){
        return $this->morphTo();
    }
}
