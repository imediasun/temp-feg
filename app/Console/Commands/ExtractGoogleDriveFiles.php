<?php

namespace App\Console\Commands;

use App\Library\MyLog;
use App\Models\googledriveearningreport;
use Illuminate\Console\Command;
use App\Models\GoogleDriveAuthToken;
use GuzzleHttp\Client;

use App\Models\location;


class ExtractGoogleDriveFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:googledrivefiles {period}';// period value must be Daily, Weekly, Monthly or 13Weeks

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the google drive files from nate.smith@fegllc.com under the Location Debit Card Reports folder.';

    protected $L = null;
    protected $path = '';
    protected $period = '';
    protected $refreshDriveObjectAt = '';
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
        $this->period = $this->argument('period');
        $this->info('Period: '.$this->period);

        $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->first();

        $drive = $this->getGoogleDriveObject($user);

        $currentTime = time();
        $this->refreshDriveObjectAt = $currentTime+3500;
        $this->info('Current Time : '.$currentTime);
        $this->info('Drive object will refresh at : '.$this->refreshDriveObjectAt);
        $this->L->log('Drive object will refresh at : '.$this->refreshDriveObjectAt);

        $locations = null;
        if($this->period == 'Daily' || $this->period == 'daily'){
            $locations = location::select('id','location_name_short','daily_folder_id As parent_id')->where('daily_folder_id', '!=', '')->get();
            $this->L->log('Daily Folders ID: ' . $locations);
        }
        elseif($this->period == 'Weekly' || $this->period == 'weekly'){
            $locations = location::select('id','location_name_short','weekly_folder_id As parent_id')->where('weekly_folder_id', '!=', '')->get();
            $this->L->log('Weekly Folders ID: ' . $locations);
        }
        elseif($this->period == 'Monthly' || $this->period == 'monthly'){
            $locations = location::select('id','location_name_short','monthly_folder_id As parent_id')->where('monthly_folder_id', '!=', '')->get();
            $this->L->log('Monthly Folders ID: ' . $locations);
        }
        elseif($this->period == '13Weeks' || $this->period == '13weeks'){
            $locations = location::select('id','location_name_short','thirteen_weeks_folder_id As parent_id')->where('thirteen_weeks_folder_id', '!=', '')->get();
            $this->L->log('13Weeks Folders ID: ' . $locations);
        }

        foreach ($locations as $location){
            $this->path = $this->period.'-'.$location->id;

            $this->getFilesFromLocationFolder($drive,$location->parent_id, $location);
        }


    }


    function getGoogleDriveObject($user){
        $client = new \Google_Client();
        $client->setAccessToken($user->oauth_token);


        $this->L->log('Google Client', $client->getAccessToken());

        return new \Google_Service_Drive($client);

    }


    function getFilesFromLocationFolder(\Google_Service_Drive $drive, $parentId, $location){

        $files = $this->getFiles($drive, $parentId);//get file from drive

        foreach ($files as $file){

            if($file->mimeType == 'application/vnd.google-apps.folder'){
                $this->path .= '/'.$file->name;
                if(time() > $this->refreshDriveObjectAt){
                    $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->first();//Refresh google auth token
                    $drive = $this->getGoogleDriveObject($user);//reset google drive object
                }
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
                $storeFileObject->createOrUpdateFile($file, $location, $this->path);
                $this->path = $this->period.'-'.$location->id;
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
