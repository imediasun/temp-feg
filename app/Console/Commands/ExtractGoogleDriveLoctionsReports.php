<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\GoogleDriveAuthToken;
use App\Models\googledriveearningreport;
use Illuminate\Console\Command;
use App\Models\location;
use GuzzleHttp\Client;


class ExtractGoogleDriveLoctionsReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:googledrivelocations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the google drive locations files (Daily, weekly, monthly, 13 weeks) from nate.smith@fegllc.com under the Location Debit Card Reports folder.';

    protected $L = null;
    protected $path = '';
    protected $drive;
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
        try {
            $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token', '!=', '')->first();

            $this->L->log('------------Command Started.-------------');

             $this->info('Command Executed.');
            $this->drive = $this->getGoogleDriveObject($user);
//            $client = new \Google_Client();
//            $client->setAccessToken($user->oauth_token);

            $parentId = env('LOCATION_DEBIT_CARD_FOLDER_ID'); //Location Debit Card Reports folder

//            $this->L->log('Google Client', $client);

//            try {
//                $drive = new \Google_Service_Drive($client);
//            } catch (Exception $e) {
//                print "An error occurred: " . $e->getMessage();
//            }
            $this->L->log('User:', $user);
            $this->L->log('Google Drive locations', $this->drive);

            $files = $this->getAllLocationFoldersFromDrive($this->drive, $parentId);

            if ($files) {
                $this->L->log('Google Drive Files: ', $files);
            }

            $locations = [];
            foreach ($files as $file) {
                if ($file->mimeType == 'application/vnd.google-apps.folder') {

                    $locationObj = new \stdClass();
                    $locationObj->locationFolderId = $file->id;
                    $locationObj->locationFolderName = $file->name;

                    $this->L->log('--------------  File Detail ----------------');
                    $this->L->log('File Id: ' . $file->id);
                    $this->L->log('File Name: ' . $file->name);
                    $this->info('Getting Files from ' . $file->name);

                    $locations[] = $locationObj;
                }
            }

            foreach ($locations as $location) {
                $this->path = $location->locationFolderName;

                $this->L->log('Extracting Files From: ' . $location->locationFolderName);

                $files = $this->getFilesFromLocationFolder($this->drive, $location->locationFolderId, $location);
                foreach ($files as $file) {

                    if ($file->mimeType == 'application/vnd.google-apps.folder') {
                        $this->L->log('--------------  File Detail ----------------');
                        $this->L->log('File Id: ' . $file->id);
                        $this->L->log('File Name: ' . $file->name);
                        $this->L->log('File Parent ID: ' . $file->parents[0]);
                        $this->path .= '/' . $file->name;

                        $loc = explode('-', $file->name);
                        if ($loc[0] == 'Daily') {
                            location::where('id', $loc[1])->update(['daily_folder_id' => $file->id]);
                        } elseif ($loc[0] == 'Weekly') {
                            location::where('id', $loc[1])->update(['weekly_folder_id' => $file->id]);
                        } elseif ($loc[0] == 'Monthly') {
                            location::where('id', $loc[1])->update(['monthly_folder_id' => $file->id]);
                        } elseif ($loc[0] == '13Weeks') {
                            location::where('id', $loc[1])->update(['thirteen_weeks_folder_id' => $file->id]);
                        }

                    }
                }
            }
        }catch (\Exception $e){
            print_r($e->getMessage());
            $this->L->log($e->getMessage());
        }

    }

    function getAllLocationFoldersFromDrive(\Google_Service_Drive $drive, $parentId){
        $files = $this->getFiles($drive, $parentId);//get file from drive

        return $files;
    }


    function getFilesFromLocationFolder(\Google_Service_Drive $drive, $parentId, $location){

        $files = $this->getFiles($drive, $parentId);//get file from drive


        return $files;
    }


    function getFiles(\Google_Service_Drive $drive, $parentId){
        $parameters = [
            'q' => "trashed = false and '$parentId' in parents",
            'pageSize' => 1000,0,
            'fields' => 'nextPageToken, files(id, name, fileExtension, fullFileExtension, kind, mimeType, createdTime, modifiedTime, iconLink, webViewLink, webContentLink, parents)',
        ];

        $result = $drive->files->listFiles($parameters);
        $this->L->log('Google Drive Files: ', $result);
        if($result){
            $files = $result->files;
        }
        return $files;
    }

    function getGoogleDriveObject($user){
        $client = new \Google_Client();
        $client->setAccessToken($user->oauth_token);
        $this->L->log('Google Client', $client->getAccessToken());
        return new \Google_Service_Drive($client);
    }


}
