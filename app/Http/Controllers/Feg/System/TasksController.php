<?php namespace App\Http\Controllers\Feg\System;

use App\Http\Controllers\controller;
use App\Models\Feg\System\Tasks;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Session, Auth, DB; 

class TasksController extends Controller
{

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'tasks';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Tasks();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'tasks',
			'pageUrl'			=>  url('feg/system/tasks'),
            'pageDetails'       => array('url' => 'feg/system/tasks', 'module' => 'tasks'),
			'return' 			=> 	self::returnUrl()
		);
		

	}

	public function getIndex()
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
        
		$this->data['access']		= $this->access;
        $this->pageData();
		return view('feg.system.tasks.index',$this->data);
	}

    public function pageData(Request $request = null)
    {

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'tasks')->pluck('module_id');
        $data['module_id'] = $module_id;
        $config_id = 0;
        $this->data['config_id'] = $config_id;
        \Session::put('config_id', $config_id);
        $config = null;
		$sort = (!is_null($request) && !is_null($request->input('sort')) ? $request->input('sort') : 'id');
		$order = (!is_null($request) && !is_null($request->input('order')) ? $request->input('order') : 'desc');
		// End Filter sort and order for query
		// Filter Search for query
		$filter = (!is_null($request) && !is_null($request->input('search')) ? $this->buildSearch() : '');
        

		$page = !is_null($request) ? $request->input('page', 1) : 1;
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request) && !is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : $this->info['setting']['perpage'] ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query
		$results = $this->model->getRows( $params );
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], 
            (isset($params['limit']) && $params['limit'] > 0  ? $params['limit'] : 
				($results['total'] > 0 ? $results['total'] : '1')));        
		$pagination->setPath($this->data['pageModule'].'/data');
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
        
        return $this->data;
    }

	public function postData(Request $request)
	{

        $this->pageData($request);
        // Render into template
		return view('feg.system.tasks.table',$this->data);

	}

    public function getTestaction(Request $request = null) {
        $action = $request->input('actionName');
        $json = array();
        $json['status'] = 'error';
        $json['message'] = 'Task is not defined';
        $json['statusCode'] = 0;
        
        $isCallable = is_callable($action, false, $name);
        $json['exists'] = $isCallable;
        $json['name'] = $name;
        $json['action'] = $action;
        if ($isCallable) {
            $json['status'] = 'success';
            $json['message'] = 'Task exists';
            $json['statusCode'] = 1;            
        }

        return \Response::json($json);
    }
    
    public function getRunnow(Request $request = null) {
        $id = $request->input('id');
        $json = array();
        $json['status'] = 'error';
        $json['message'] = 'Error: unable to run task!';
        $json['statusCode'] = 0;
        
        if (!empty($id)) {
            $schedule = $this->model->addRunNowSchedule($id);

            if ($schedule) {
                $json['status'] = 'success';
                $json['message'] = 'Success: Task scheduled to run at ' . $schedule;
                $json['statusCode'] = 1;
            }
        }

        return \Response::json($json);
    }    
    public function postRunnow(Request $request = null) {
        $id = $request->input('taskId');
        $json = array();
        $json['status'] = 'error';
        $json['message'] = 'Error: unable to run task!';
        $json['statusCode'] = 0;
        
        if (!empty($id)) {
            $schedule = $this->model->addRunNowSchedule(array(
                "id" => $id,
                "scheduledat"  => $request->input('scheduledat'),
                "params"  => $request->input('params'),
                "logfolder"  => $request->input('logfolder'),
                "logfile" => $request->input('logfile'),
                "onsuccess" => $request->input('onsuccess'),
                "onfailure" => $request->input('onfailure'),        
                "istestmode" => $request->input('isTestMode'),        
                "rundependent" => $request->input('runDependent'),        
            ));

            if ($schedule) {
                $json['status'] = 'success';
                $json['message'] = 'Success: Task scheduled to run at ' . $schedule;
                $json['statusCode'] = 1;
            }
        }

        return \Response::json($json);
    }    
    public function getSchedules(Request $request) {
        $id = $request->input('id');
        $json = array();
        $json['status'] = 'error';
        $json['message'] = 'Error: unable to get schedules data!';
        $json['statusCode'] = 0;
        
        if (!empty($id)) {
            $data = $this->model->getSchedulesForReview($id);                
            $html = (string) view('feg.system.tasks.schedulestable', array("schedules" => $data));;                
            if ($html) {
                $json['html'] = $html;
                $json['status'] = 'success';
                $json['message'] = 'Schedules loaded';
                $json['statusCode'] = 1;
            }
        }

        return \Response::json($json);
    }    
    
    public function postTerminateschedule(Request $request) {
        $id = $request->input('id');
        if (!empty($id)) {
            \Session::put('terminate_elm5_schedule_'. $id, 1);
        }
        return \Response::json($id);
    }
    public function getSchedulestatus(Request $request) {
        $id = $request->input('id');
        
        $data = \Session::get('status_elm5_schedule_'. $id, array());
        
        return \Response::json($data);
    }
    
	function postSave( Request $request, $id = 0)
	{
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);
        
		if ($validator->passes()) {
            $data = $request->all();
            $oldId = $data['taskId'];
            $oldData = new \stdClass();
            if (!empty($oldId)) {
                $oldData = $this->model->getTaskData($oldId);                 
            }
            unset($data['taskId']);
            try {
                $id = $this->model->insertRow($data, $oldId);
            } 
            catch (Exception $ex) {
                
                return response()->json(array(
                    'status'=>'error',
                    'message'=> $ex->getMessage(),
                    'statusCode' => 0
                    ));
            }
            
            $isActive = $data['is_active'] == 1;            
            if (empty($oldId)) {
                if ($isActive) {
                    $schedule = $this->model->addSchedule($id);
                }
            }
            else {
                $id = $oldId;
                $taskSchedulesDeactivated = false;
                if ($oldData->is_active != $data['is_active'] || 
                        $oldData->schedule != $data['schedule']) {
                    if (empty($taskSchedulesDeactivated)) {
                        $this->model->deactivateTaskSchedule($id);
                        $taskSchedulesDeactivated = true;
                    }  
                    if ($isActive) {
                        $schedule = $this->model->addSchedule($id);                        
                    }
                }
            }
            
            $data['id'] = $id;
            
            $viewData = (object)$data;
            $viewData->lastSchedule = $this->model->getTaskLastRunAt($id);
            $viewData->nextSchedule = $this->model->getTaskNextScheduledAt($id);
            $viewData->isManualRunning = $this->model->getIsManualRunning($id) || 
                    $this->model->isTaskRunning($id);
            
            $html = (string) view('feg.system.tasks.tableitems', array("row" => $viewData));
            
			return response()->json(array(
                'statusCode' => 1,
				'status'=>'success',
				'message'=> \Lang::get('core.note_success'),
                'taskId' => $id,
                'html' => $html,
                'taskData' => $data
				));
            
		} 
        else {

			$message = $this->validateListError(  $validator->getMessageBag()->toArray() );
			return response()->json(array(
				'message'	=> $message,
				'status'	=> 'error',
                'statusCode' => 0
			));
		}

	}

	public function postDelete( Request $request)
	{
        $ids = $request->input('ids');
		// delete multipe rows
        if (count($ids) >= 1) {
			$this->model->destroy($ids);
            $this->model->deactivateTaskSchedule($ids);
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

}