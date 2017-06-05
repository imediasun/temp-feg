<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class OrderSendDetails extends Sximo  {

    protected $table = 'order_sent_details';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }
    
    public static function saveDetails($id, $data = array()) {
        $emailList = empty($data['emails']) ? []: $data['emails'];
        $insertData = [];
        if (!empty($emailList)) {
            foreach($emailList as $emailType => $emails) {
                if (!empty($emails) && is_array($emails)) {
                    foreach($emails as $email) {
                    $insertData[] = ['order_id' => $id,
                        'email' => trim($email), 'email_type' => $emailType];
                    }
                }                
            }
        }
        $insertedCount = 0;
        if (!empty($insertData)) {
            $insertedCount = self::insert($insertData);
        }
        return $insertedCount;
    }
}
