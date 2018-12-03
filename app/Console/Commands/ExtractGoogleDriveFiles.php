<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\Core\Users;
use App\Models\googledriveearningreport;
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
    protected $path = '';
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

        $client = new \Google_Client();
        $client->setAccessToken('ya29.GltnBp1PkYXk4xWkaSRcSogic3o8OqaujdTqSXWeYUqs5mPBZzBx-jFxoc1Wmq7Tp8n5PMjwjRLyV0op-fIljtE6jGQavsp0wXhR8p6vVylhTWVZ2DS3QnexyidL');

        $parentId = '1lgiyuKBI1BczHh2RMGPIFxyUGKAjy_td'; //Location Debit Card Reports folder

        $this->L->log('Google Client', $client->getAccessToken());
        $drive = new \Google_Service_Drive($client);

        $files = $this->getAllLocationFoldersFromDrive($drive, $parentId);

        $locations = [];
        foreach ($files as $file){
            if($file->mimeType == 'application/vnd.google-apps.folder'){
                $locationObj = new \stdClass();
                $locationObj->locationFolderId = $file->id;
                $locationObj->locationFolderName = $file->name;
                $this->L->log('--------------  File Detail ----------------');
                $this->L->log('File Id: '.$file->id);
                $this->L->log('File Name: '.$file->name);
                echo 'Getting Files from '.$file->name;
                $locations[] = $locationObj;
            }
        }

        foreach ($locations as $location){
            $this->path = $location->locationFolderName;
            $this->L->log('Extracting Files From: '. $location->locationFolderName);
            $this->getFilesFromLocationFolder($drive,$location->locationFolderId, $location);
        }


    }

    function getAllLocationFoldersFromDrive(\Google_Service_Drive $drive, $parentId = '1lgiyuKBI1BczHh2RMGPIFxyUGKAjy_td'){
        $parameters = [
            'q' => "trashed = false and '$parentId' in parents",
            'pageSize' => 1000,0,
            'fields' => 'nextPageToken, files(id, name, fileExtension, fullFileExtension, kind, mimeType, createdTime, modifiedTime, iconLink, webViewLink, webContentLink, parents)',

        ];

        $result = $drive->files->listFiles($parameters);
        if($result){
            $files = $result->files;
        }
        return $files;
    }


    function getFilesFromLocationFolder(\Google_Service_Drive $drive, $parentId, $location){

        $parameters = [
            'q' => "trashed = false and '$parentId' in parents",
            'pageSize' => 1000,0,
            'fields' => 'nextPageToken, files(id, name, fileExtension, fullFileExtension, kind, mimeType, createdTime, modifiedTime, iconLink, webViewLink, webContentLink, parents)',

        ];

        $result = $drive->files->listFiles($parameters);
        if($result){
            $files = $result->files;
        }


        foreach ($files as $file){

            if($file->mimeType == 'application/vnd.google-apps.folder'){
                $this->path .= '/'.$file->name;
                $this->getFilesFromLocationFolder($drive, $file->id, $location);
            }else {

                $this->L->log('--------------  File Detail ----------------');
                $this->L->log('File Id: ' . $file->id);
                $this->L->log('File Name: ' . $file->name);
                $this->L->log('File Icon: ' . $file->iconLink);
                $this->L->log('File Web Link View: ' . $file->webViewLink);
                $this->L->log('File File Extension: ' . $file->fileExtension);
                $this->L->log('File Mime Type: ' . $file->mimeType);
                $this->L->log('File Created Time: ' . $file->createdTime);
                $this->L->log('File Modified Time: ' . $file->modifiedTime);
                $this->L->log('File Parent ID: ' . $file->parents[0]);
                $this->path .= '/'.$file->name;
                $storeFileObject = new googledriveearningreport();
                $storeFileObject->createOrUpdateFile($file, $location->locationFolderName, $this->path);
                $this->path = $location->locationFolderName;
            }
        }
        return $files;
    }




}
