<?php

namespace App\Models;

use App\Models\Sximo;
use Illuminate\Database\Eloquent\Model;

class PartRequest extends Sximo
{
    protected $table = 'part_requests';
    protected $primaryKey = 'id';
    public $timestamps = false;
    const STATUS_IDS =  [2,3];

    public function service_request(){
        return $this->belongsTo(Servicerequests::class, 'ticket_id', 'TicketID');
    }

}
