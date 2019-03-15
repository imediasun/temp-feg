<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemEmailConfigName extends Model
{

    protected $fillable = ['email_sender_credentials_id'];

    public function email_sender(){
        return $this->belongsTo(EmailSenderCredential::class, 'email_sender_credentials_id');
    }
}
