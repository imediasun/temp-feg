<?php

namespace App\Console\Commands;

use App\Models\Core\Users;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Google;

class RefreshOAuthToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:token';

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

        $users = Users::whereNotNull('refresh_token')->get();
        foreach ($users as $key=>$user){
            //echo $user->refresh_token;
            $count = count($users);
            $client = new Client();
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

                $user->oauth_token = $array['access_token'];
                if(isset($array['refresh_token'])){
                    $user->refresh_token = $array['refresh_token'];
                }

                $user->save();
                print_r($array);
            }
            catch (ClientException $e)
            {

                    $user->oauth_token = null;
                    $user->refresh_token = null;
                    $user->save();
                    echo 'User ID '.$user->id . ' token could not be updated..';
                    $count--;

            }

        }
        echo $count .' users of '. count($users). ' Users refresh token updated';
        return true;
    }
}