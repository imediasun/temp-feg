<?php
namespace App;

class Googl
{
    public function client()
    {
        $client = new \Google_Client();
        $client->setClientId('801435515970-lr2gl663b2f4mv82h3b58sc65incprqt.apps.googleusercontent.com');
        $client->setClientSecret('sdGQ8t7f23s1yHCWSoYU7AWt');
        $client->setRedirectUri('http://localhost:8000/login');
        $client->setScopes(explode(',', ('email,profile,https://www.googleapis.com/auth/drive,https://www.googleapis.com/auth/drive.metadata,https://www.googleapis.com/auth/plus.me')));
        $client->setApprovalPrompt('force');
        $client->setAccessType('offline');
        return $client;
    }


    public function drive($client)
    {
        $drive = new \Google_Service_Drive($client);
        return $drive;
    }
}