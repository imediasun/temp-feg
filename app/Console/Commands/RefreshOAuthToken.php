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

        $L = $this->L = FEGSystemHelper::setLogger($this->L, "refresh-oauth-token.log", "FEGOAuthTokenCron1/RefreshOAuthToken1", "REFRESH_OAUTH");
        $L->log('Start Refreshing Oauth Tokens');

        $users = Users::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->get();
        $count = count($users);
        $L->log($count.' Users with Oauth Refresh Token Found');
        $client = new Client();
        foreach ($users as $key=>$user){
            //echo $user->refresh_token;
           $nextRefreshTime = 0;
           if(!empty($user->oauth_refreshed_at)){
               $refreshedAt = \DateTime::createFromFormat("Y-m-d H:i:s",$user->oauth_refreshed_at)->getTimestamp();
               $nextRefreshTime = $refreshedAt + (55*60.00);//add 55 minutes to last refresh time
           }
           $now = new \DateTime();
           $now = $now->getTimestamp();
           if($now >= $nextRefreshTime || !User::verifyOAuthTokenIsValid($user->oauth_token)){
               try{

                   $array = Users::refreshOAuthToken($user->refresh_token);
                   $user->updateRefreshToken($array);
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
           else
           {
               $L->log('ID '.$user->id.' Recently refreshed at '.$user->oauth_refreshed_at);
               $count--;
           }

        }
        $L->log($count .' users of '. count($users). ' Users refresh token updated');
        $L->log('Cron job refresh token End');
        echo $count .' users of '. count($users). ' Users refresh token updated';
        return true;
    }
}
