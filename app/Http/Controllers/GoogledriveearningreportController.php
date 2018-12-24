<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Core\Users;
use App\Models\excludedreaders;
use App\Models\Googledriveearningreport;
use App\Models\GoogleDriveAuthToken;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Session;
use Validator, Input, Redirect ;
use Illuminate\Support\Facades\Response;

class GoogledriveearningreportController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'googledriveearningreport';
	static $per_page	= '10';
	
	public function __construct()
	{
		parent::__construct();
		$this->model = new Googledriveearningreport();

		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'googledriveearningreport',
			'pageUrl'			=>  url('googledriveearningreport'),
			'return' 			=> 	self::returnUrl()
		);
		


	}

	public function getIndex()
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

		$this->data['access']		= $this->access;
		return view('googledriveearningreport.index',$this->data);
	}

	public function postData( Request $request)
	{

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'googledriveearningreport')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
        $config_id = Input::get('config_id');
        } elseif (\Session::has('config_id')) {
        $config_id = \Session::get('config_id');
        } else {
        $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        \Session::put('config_id', $config_id);
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if(!empty($config))
        {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);        
        }
		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
		$order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        $temp = "loc_id:equal:2035|modified_time:bigger_equal:2018-12-21|modified_time:smaller_equal:2018-12-21|file_name:like:fdsafasdf|";
        $searchQuery = $this->model->getSearchFiltersAsArray();
        $skipFilters = ['date_start','date_end'];
        $mergeFilters = $this->model->getSearchFilterWithDate($searchQuery);
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters);
        $filter = (!is_null($request->input('search')) ? $this->buildSearch($trimmedSearchQuery) : '');
        $page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : $this->info['setting']['perpage'] ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query
//		$results  = $this->model->getRows( $params );
		$results = googledriveearningreport::getRows($params);
        // Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], 
            (isset($params['limit']) && $params['limit'] > 0  ? $params['limit'] : 
				($results['total'] > 0 ? $results['total'] : '1')));        
		$pagination->setPath('googledriveearningreport/data');
		$this->data['param']		= $params;
        $this->data['topMessage']	= @$results['topMessage'];
		$this->data['message']          = @$results['message'];
		$this->data['bottomMessage']	= @$results['bottomMessage'];
        
		$this->data['rowData']		= $results['rows'];
		// Build Pagination
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();
		// Row grid Number
		$this->data['i']			= ($page * $params['limit'])- $params['limit'];
		// Grid Configuration
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		$this->data['setting'] 		= $this->info['setting'];

		// Master detail link if any
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
        $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        $this->data['excludedUserLocations']		= $this->getUsersExcludedLocations();
// Render into template
		return view('googledriveearningreport.table',$this->data);

	}


	function getUpdate(Request $request, $id = null)
	{

		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}

		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}

		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] 		=  $row;
		} else {
			$this->data['row'] 		= $this->model->getColumnTable('google_drive_earning_reports');
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('googledriveearningreport.form',$this->data);
	}

	public function getShow( $id = null)
	{

		if($this->access['is_detail'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('google_drive_earning_reports');
		}
		
        $this->data['tableGrid'] = $this->info['config']['grid'];
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		return view('googledriveearningreport.view',$this->data);
	}


	function postCopy( Request $request)
	{

	    foreach(\DB::select("SHOW COLUMNS FROM google_drive_earning_reports ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));


		$sql = "INSERT INTO google_drive_earning_reports (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM google_drive_earning_reports WHERE id IN (".$toCopy.")";
		\DB::insert($sql);
		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success')
		));
	}

	function postSave( Request $request, $id =0)
	{

		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$data = $this->validatePost('google_drive_earning_reports');

			$id = $this->model->insertRow($data , $request->input('id'));
			
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('core.note_success')
				));

		} else {

			$message = $this->validateListError(  $validator->getMessageBag()->toArray() );
			return response()->json(array(
				'message'	=> $message,
				'status'	=> 'error'
			));
		}

	}


	public function postDelete( Request $request)
	{

		if($this->access['is_remove'] ==0) {
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_restric')
			));
			die;

		}
		// delete multipe rows
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('core.note_success_delete')
			));
		} else {
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_error')
			));

		}

	}
	public function getDownloadDriveFile($filesIds, $length){
        $path = public_path('upload/googledrive/' . Session::get('uid'));
        \File::makeDirectory($path, $mode = 0777, true, true);
        if($length>1) {
                $filesIds = !is_array($filesIds) ? explode(',', $filesIds) : $filesIds;
                $data = Googledriveearningreport::select('*')->whereIn('id', $filesIds)->get();
                $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token', '!=', '')->first();
                $client = new \Google_Client();
                $client->setAccessToken($user->oauth_token);
                $drive = new \Google_Service_Drive($client);
                $cf_zip = new \ZipHelpers;
                foreach ($data as $files) {
                    $response = $drive->files->get($files['google_file_id'], array(
                        'alt' => 'media'));
                    $content = $response->getBody()->getContents();
                    $cf_zip->add_data($files['file_name'], $content);
                }
                $zip_file = $path . "/drivefiles.zip";
                $_zip = $cf_zip->archive($zip_file);
                $cf_zip->clear_data();
                $headers = array(
                    'Content-type: application/octet-stream',
                );
                return Response::download($zip_file, rand(9999, 99999999) . "_driverfiles.zip", $headers)->deleteFileAfterSend(true);
            }
            elseif ($length==1){
                $data = Googledriveearningreport::select('*')->where('id', $filesIds)->first();
                $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token', '!=', '')->first();
                $client = new \Google_Client();
                $client->setAccessToken($user->oauth_token);
                $drive = new \Google_Service_Drive($client);
                $response = $drive->files->get($data['google_file_id'], array(
                    'alt' => 'media'));
                $content = $response->getBody()->getContents();
                if($data['mime_type']=='application/pdf'){
                    $content_type = 'application/pdf';
                }
                elseif ($data['mime_type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
                    $content_type = 'application/docx';
                }
                $response =  Response::make($content, 200, [
                    'Content-Type' => $content_type,
                    'Content-Disposition' => 'attachment; filename="'.$data['file_name'].'"',

                ]);
                return $response;

            }
	   }

    function postChangeFilename(Request $request){

        try {
            $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->first();
            $client = new \Google_Client();
            $client->setAccessToken($user->oauth_token);
            $service = new \Google_Service_Drive($client);
            $file = new \Google_Service_Drive_DriveFile();
            $fileId = $request->id;
            $fileName = $request->file;
            $file->setName($fileName);
            $updatedFile = $service->files->update($fileId, $file);
            $update_details = array(
                'file_name' => $fileName,
                 'modified_time'=> date("Y-m-d H:i:s"));
            $res = Googledriveearningreport::where('google_file_id', $fileId)
                ->update($update_details);
            if ($res){
                return response()->json([
                    'name' => $updatedFile['name'],
                    'status' => '200'
                ]);
            }
            else{
                return response()->json(['status'=>'0']);
            }
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }

    public function getCreateLocationDirectoryOnGoogleDrive(){
        $parent=  '1lgiyuKBI1BczHh2RMGPIFxyUGKAjy_td';
        $user = GoogleDriveAuthToken::whereNotNull('refresh_token')->where('oauth_refreshed_at')->orWhere('refresh_token','!=','')->first();
        $client = new \Google_Client();
        $client->setAccessToken($user->oauth_token);
        $service = new \Google_Service_Drive($client);
        $location ='1000001';
        $foldersArray = ['13Weeks'.'-'.$location , 'Monthly'.'-'.$location ,'Weekly'.'-'.$location ,
            'Daily'.'-'.$location];
        $parent = $this->createFoldersWithSpecifiedDuration($service,$parent,$location);
        foreach ($foldersArray as $location){
            $this->createFoldersWithSpecifiedDuration($service,$parent,$location);
        }
    }
    function createFoldersWithSpecifiedDuration($service,$parent,$location){
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                'name' => $location,
                'parents' => array($parent),
                'mimeType' => 'application/vnd.google-apps.folder')
        );
        $file = $service->files->create($fileMetadata, array(
            'fields' => 'id'));
           return $file->id;
    }
}