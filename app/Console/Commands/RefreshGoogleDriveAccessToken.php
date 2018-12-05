<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\GoogleDriveAuthToken;
use App\Models\googledriveearningreport;
use Illuminate\Console\Command;
use App\Models\location;
use GuzzleHttp\Client;


class RefreshGoogleDriveAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:googledriveaccesstoken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh google drive access token before expire';

    protected $L = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->L = new MyLog('google-drive-access-token.log', 'google-drive-access-token', 'GoogleDriveAccessToken');

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('DONT_REFRESH_GOOGLE_DRIVE_ACCESS_TOKEN', false)) {
            return;
        }

        $this->L->log('------------Command Started.-------------');

        $this->info('Command Executed.');

        $this->L->log('Start Refreshing Oauth Tokens');

        $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->first();
        $this->L->log('User: ', $user);
        $gClient = new Client();
        $tokenArray = [];
        if($user){
            //echo $user->refresh_token;
            $nextRefreshTime = 0;
            if(!empty($user->oauth_refreshed_at)){
                $refreshedAt = \DateTime::createFromFormat("Y-m-d H:i:s",$user->oauth_refreshed_at)->getTimestamp();
                $nextRefreshTime = $refreshedAt + (55*60.00);//add 55 minutes to last refresh time
            }
            $this->info('Token refresh at: '. $nextRefreshTime);
            $now = new \DateTime();
            $now = $now->getTimestamp();
            if($now >= $nextRefreshTime || !GoogleDriveAuthToken::verifyOAuthTokenIsValid($user->oauth_token)){
                try{
                    $this->info('Token refresh: '. $user->refresh_token);
                    $tokenArray = GoogleDriveAuthToken::refreshOAuthToken($user->refresh_token);
                    $this->L->log('Refreshed auth token: ',$tokenArray);
                    $user->updateRefreshToken($tokenArray);
                    $this->L->log('ID '.$user->id.' User oauth token updated');
                    $this->L->log('Google Api (Refresh token) response for user id '.$user->id.' '.json_encode($tokenArray));
                    print_r($tokenArray);
                }
                catch (ClientException $e)
                {

                    $user->oauth_token = null;
                    $user->refresh_token = null;
                    $user->save();
                    echo 'User ID '.$user->id . ' token could not be updated..';

                    $this->L->log('Google Api (Refresh token) error response for user id '.$user->id.' '.$e);
                }
            }
            else
            {
                $this->L->log('ID '.$user->id.' Recently refreshed at '.$user->oauth_refreshed_at);

            }
        }


    }

}
