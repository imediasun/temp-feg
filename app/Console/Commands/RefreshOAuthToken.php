<?php

namespace App\Console\Commands;

use App\Models\Core\Users;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Library\FEG\System\FEGSystemHelper;

class RefreshOAuthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:token';

    protected $L = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh OAuth token.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('DONT_REFRESH_OAUTH_TOKEN', false) === true) {
            return;
        }
        global $__logger;
        $L = $this->L = $__logger = FEGSystemHelper::setLogger($this->L, "refresh-oauth-token.log", "FEGOAuthTokenCron/RefreshOAuthToken", "REFRESH_OAUTH");
        $L->log('Start Refreshing Oauth Tokens');

        $users = Users::whereNotNull('refresh_token')->orWhere('refresh_token','!=','')->get();
        $count = count($users);
        $L->log($count.' Users with Oauth Refresh Token Found');
        $client = new Client();
        foreach ($users as $key=>$user){
            //echo $user->refresh_token;
            try{
                $res = $client->request('POST', 'https://www.googleapis.com/oauth2/v4/token',array('headers'=>array('Content-Type'=>'application/x-www-form-urlencoded'),'form_params'=>array(
                    'grant_type'=>'refresh_token',
                    'approval_prompt'=>'force',
                    'access_type'=>'offline',
                    'client_id'=>env('G_ID'),
                    'refresh_token'=>$user->refresh_token,
                    'client_secret'=>env('G_SECRET'))));
                $result = $res->getBody();
                $array = json_decode($result, true);
                $users_with_related_oauthemail = Users::where('oauth_email',$user->oauth_email)->get();
                $L->log(count($users_with_related_oauthemail).' Users with same oauth email Found');
                foreach ($users_with_related_oauthemail as $related)
                {
                    $related->oauth_token = $array['access_token'];
                    if(isset($array['refresh_token'])){
                        $related->refresh_token = $array['refresh_token'];
                    }
                    $related->save();
                    $L->log('ID '.$related->id.' User (with same oauth email) oauth token updated');
                }
                $user->oauth_token = $array['access_token'];
                if(isset($array['refresh_token'])){
                    $user->refresh_token = $array['refresh_token'];
                }

                $user->save();
                $L->log('ID '.$user->id.' User oauth token updated');
                $L->log('Google Api (Refresh token) response for user id '.$user->id.' '.json_encode($array));
                print_r($array);
            }
            catch (ClientException $e)
            {

                    $user->oauth_token = null;
                    $user->refresh_token = null;
                    $user->save();
                    echo 'User ID '.$user->id . ' token could not be updated..';
                    $count--;
                $L->log('Google Api (Refresh token) error response for user id '.$user->id.' '.$e);
            }

        }
        $L->log($count .' users of '. count($users). ' Users refresh token updated');
        $L->log('Cron job refresh token End');
        echo $count .' users of '. count($users). ' Users refresh token updated';
        return true;
    }
}
