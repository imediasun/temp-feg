<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\Core\Users;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;


class ExtractGoogleDriveFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:googledrivefiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the google drive files from nate.smith@fegllc.com under the Location Debit Card Reports folder.';

    protected $L = null;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->L = new MyLog('google-drive.log', 'google-drive', 'GoogleDrive');

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('DONT_GET_GOOGLE_DRIVE_FILES', false)) {
            return;
        }
        $this->L->log('------------Command Started.-------------');
        echo 'Command Executed.';




        $user = User::find(15002);
        $token = $user->oauth_token;

        $files = $this->getFilesFromDrive();

        foreach ($files as $file){

        }

    }

    function getFilesFromDrive($parentId = '1lgiyuKBI1BczHh2RMGPIFxyUGKAjy_td'){
        $client = new \Google_Client();
        $var = $client->setAccessToken('ya29.GltnBhUiRqXskTLMVvOHHQCbTpAXMEGaOJmZyGrRfvQSPHKUQZkKZyuWscmYBzEc12s8nno0P-60IlX9_GPSyO_L1VvTySzfuMBuBaMGGs9AHsSdkargS2jIbfIz');



        $this->L->log('Google Client', $client->getAccessToken());

//        print_r($client);
//        exit();

        $drive = new \Google_Service_Drive($client);

        $parameters = [
            'q' => "trashed = false and '$parentId' in parents",
            'pageSize' => 1000,0,
            'fields' => 'nextPageToken, files(id, name, fileExtension, fullFileExtension, kind, mimeType, createdTime, modifiedTime, iconLink, webViewLink, webContentLink, parents)',

        ];

        $result = $drive->files->listFiles($parameters);
        print_r($result);
        if($result){
            $files = $result->files;
        }
        foreach ($files as $file){
            if($file->mimeType == 'application/vnd.google-apps.folder'){
                $this->getFilesFromDrive($file->id);
            }
            $this->L->log('--------------  File Detail ----------------');
            $this->L->log('File Id: '.$file->id);
            $this->L->log('File Name: '.$file->name);
            $this->L->log('File Icon: '.$file->iconLink);
            $this->L->log('File Web Link View: '.$file->webViewLink);
            $this->L->log('File File Extension: '.$file->fileExtension);
            $this->L->log('File Mime Type: '.$file->mimeType);
            $this->L->log('File Created Time: '.$file->createdTime);
            $this->L->log('File Modified Time: '.$file->modifiedTime);
            $this->L->log('File Parent ID: '.$file->parents[0]);
        }
        return $files;
    }
}
