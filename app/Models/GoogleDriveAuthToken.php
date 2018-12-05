<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class GoogleDriveAuthToken extends Model
{
    protected $table = 'google_drive_auth_tokens';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public $timestamps = false;


    public static function verifyOAuthTokenIsValid($oauthToken){
        if(empty($oauthToken)){
            return false;
        }

        try
        {
            $client = new Client();
            $res = $client->request('GET', "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=$oauthToken");
            $result = $res->getBody();
            $result = json_decode($result, true);
        }
        catch (ClientException $e)
        {
            $result['error'] = true;
        }

        if(isset($result['error'])){
            return false;
        }
        else
        {
            return true;
        }
    }


    public static function refreshOAuthToken($refreshToken){

        $client = new Client();
        $res = $client->request('POST', 'https://www.googleapis.com/oauth2/v4/token',array('headers'=>array('Content-Type'=>'application/x-www-form-urlencoded'),'form_params'=>array(
            'grant_type'=>'refresh_token',
//            'approval_prompt'=>'force',
            'access_type'=>env('GOOGLE_DRIVE_ACCESS_TYPE'),
            'client_id'=>env('GOOGLE_DRIVE_CLIENT_ID'),
            'refresh_token'=>$refreshToken,
            'client_secret'=>env('GOOGLE_DRIVE_CLIENT_SECRET')
            )
        ));
        $result = $res->getBody();
        $array = json_decode($result, true);
        if(!empty($array)){
            return $array;
        }
        else
        {
            return false;
        }
    }


    public function updateRefreshToken($data){

        $userWithRelatedOAuthEmails = $this->where('oauth_email',$this->oauth_email)->get();
        //$L->log(count($users_with_related_oauthemail).' Users with same oauth email Found');
        foreach ($userWithRelatedOAuthEmails as $related)
        {
            $related->oauth_token = $data['access_token'];
            if(isset($data['refresh_token'])){
                $related->refresh_token = $data['refresh_token'];
            }
            $related->save();
            //$L->log('ID '.$related->id.' User (with same oauth email) oauth token updated');
        }
        $this->oauth_token = $data['access_token'];
        if(isset($data['refresh_token'])){
            $this->refresh_token = $data['refresh_token'];
        }
        $this->oauth_refreshed_at = date('Y-m-d H:i:s');
        $this->save();
    }

}
