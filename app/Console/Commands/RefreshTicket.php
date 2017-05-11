<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Google;

class RefreshTicket extends Command
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
        /*\DB::table('yes_no')->insert(
            ['yesno' => 'test',]
        );*/

        $users = \DB::table('users')
            ->find(238);
        //foreach ($users as $key=>$user){
            //echo $user->refresh_token;

            $client = new Client();
            $res = $client->request('POST', 'https://www.googleapis.com/oauth2/v4/token',array('headers'=>array('Content-Type'=>'application/x-www-form-urlencoded'),'form_params'=>array(
                'grant_type'=>'refresh_token',
                'approval_prompt'=>'force',
                'access_type'=>'offline',
                'client_id'=>env('G_ID'),
                'refresh_token'=>$users->refresh_token,
                'client_secret'=>env('G_SECRET'))));
            //$res = $client->request('POST', 'https://accounts.google.com/o/oauth2/auth',array('headers'=>array('Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8'),'form_params'=>array('grant_type'=>'authorization_code','scope=https://www.googleapis.com/auth/calendar+https://www.googleapis.com/auth/plus.me','client_id'=>env('G_ID'),'approval_prompt=force','access_type=offline','response_type=code','redirect_uri'=>url('/').env('G_REDIRECT_2'))));

            $result = $res->getBody();
            $array = json_decode($result, true);
            dd($array);

            return $array;

           /* $user = User::find($user->id);
            if(isset($array['refresh_token']))
            {
                $user->refresh_token = $array['refresh_token'];
            }

            $user->save();*/
        //}

    }
}
