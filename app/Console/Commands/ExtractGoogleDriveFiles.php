<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\googledriveearningreport;
use Illuminate\Console\Command;


class ExtractGoogleDriveFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:googledrivefiles {period}';

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

        $this->info('Command Executed.');
        $period = $this->argument('period');
        $this->info('Period: '.$period);
//        exit();

        $client = new \Google_Client();
        $client->setAccessToken('ya29.GlxnBpB7uLzr32eFbTbGuzQYmORlFWrU48yTxCPJQLjujwi3lv2ZDXMMl68pNqQ9HtL9mS8zgmPaunn8Hg0q2DfMvm3lMzh4qR6Gs0FAgLFaU6cP5Ubum8-d79Vf8w');

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
                $this->info('Getting Files from '.$file->name);

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
        $files = $this->getFiles($drive, $parentId);//get file from drive
        return $files;
    }


    function getFilesFromLocationFolder(\Google_Service_Drive $drive, $parentId, $location){

        $files = $this->getFiles($drive, $parentId);//get file from drive

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


    function getFiles(\Google_Service_Drive $drive, $parentId){
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

}
