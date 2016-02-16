<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Sbticket;
use App\Models\Ticketcomment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class SbticketController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'sbticket';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Sbticket();
		
		$this->info = $this->model->makeInfo($this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'sbticket',
			'pageUrl'			=>  url('sbticket'),
			'return' 			=> 	self::returnUrl()	
		);
		
			
				
	} 
	
	public function getIndex()
	{
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
				
		$this->data['access']		= $this->access;	
		return view('sbticket.index',$this->data);
	}	

	public function getSetting()
	{
		$individuals = \DB::select("Select id,first_name,last_name FROM users");
		$roles = \DB::select("Select group_id,name FROM tb_groups");

		$this->data['roles']		= $roles;
		$this->data['individuals']		= $individuals;
		$this->data['access']		= $this->access;
		return view('sbticket.setting',$this->data);
	}
	public function postData( Request $request)
	{
		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
		$order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : "AND sb_tickets.Status != 'close'");

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
		$results = $this->model->getRows( $params );
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('sbticket/data');
		$rows = $results['rows'];
		$comments = new Ticketcomment();

		$user_id = \Session::get('uid');
		$group_id = \Session::get('gid');
		foreach($rows as $index => $row)
		{
			$flag = 1;
			//$row->comments = $comments->where('TicketID', '=', $row->TicketID)->orderBy('TicketID', 'desc')->take(1)->get();
			$department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = ".$row->department_id ."");
			$department_memebers = explode(',',$department_memebers[0]->assign_employee_ids);

			$assign_employee_ids = explode(',' ,$row->assign_to);

			$members_access = array_unique(array_merge($assign_employee_ids,$department_memebers));
			foreach($members_access as $i => $id)
			{
				$get_user_id_from_employess = \DB::select("Select user_id FROM employees WHERE id = ".$id ."");
				$members_access[$i] = $get_user_id_from_employess[0]->user_id;
			}

			if($group_id != 10)
			{
				if(!in_array($user_id,array_unique($members_access)))
				{
					$flag = 0;
				}
			}

			if($flag == 1)
			{
				$assign_employee_names = array();
				foreach ($assign_employee_ids as $key => $value) {
					$assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM employees WHERE id = " . $value . "");
				}
				$row->assign_employee_names = $assign_employee_names;
			}
			else
			{
				unset($rows[$index]);
			}
		}

		$this->data['param']		= $params;
		$this->data['rowData']		= $rows;
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
		// Render into template
		return view('sbticket.table',$this->data);

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
			$this->data['row'] 		= $this->model->getColumnTable('sb_tickets'); 
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('sbticket.form',$this->data);
	}	

	public function getShow( $id = null)
	{
		if($this->access['is_detail'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->find($id);
		$assign_employee_ids = explode(',' ,$row->assign_to);
		$assign_employee_names = array();
		foreach($assign_employee_ids as $key => $value)
		{
			$assign_employee_names[$key] = \DB::select("Select first_name,last_name FROM employees WHERE id = ".$value ."");
		}
		$row->assign_employee_names = $assign_employee_names;
		if($row)
		{
			$comments = new Ticketcomment();
			$this->data['comments'] = $comments->where('TicketID', '=', $id)->get();
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('sb_tickets'); 
		}
		
		$this->data['id'] = $id;
		$this->data['uid'] = \Session::get('uid');
		$this->data['fid'] = \Session::get('fid');
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		return view('sbticket.view',$this->data);	
	}	


	function postCopy( Request $request)
	{
		
	    foreach(\DB::select("SHOW COLUMNS FROM sb_tickets ") as $column)
        {
			if( $column->Field != 'TicketID')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));
		
				
		$sql = "INSERT INTO sb_tickets (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM sb_tickets WHERE TicketID IN (".$toCopy.")";
		\DB::insert($sql);
		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success')
		));	
	}		

	function postSave( Request $request, $id =0)
	{
		$rules = $this->validateForm();
		unset($rules['debit_card']);
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('sb_tickets');
			if($id==0)
			{
				$data['Created'] = date("Y-m-d",time());;
			}
			$id = $this->model->insertRow($data , $request->input('TicketID'));
			
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
	function validateTicketCommentsForm()
	{
		$rules = array();
		$rules['Comments'] = 'required';
		$rules['department_id'] = 'required|numeric';
		$rules['Priority'] = 'required';
		$rules['Status'] = 'required';
		return $rules;
	}

	public function postComment(Request $request)
	{
		$rules = $this->validateTicketCommentsForm();
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			//validate post for sb_tickets module
			$ticketsData = $this->validatePost('sb_tickets');
			if($ticketsData['Status'] == 'close')
			{
				$ticketsData['closed'] = date("Y-m-d",time());
			}
			$ticketsData['updated'] = date("Y-m-d",time());
			$commentsData['USERNAME'] = \Session::get('fid');
			$comment_model = new Ticketcomment();
			$TicketID = $request->input('TicketID');
			$total_comments = \DB::select("Select * FROM sb_ticketcomments WHERE TicketID = ". $TicketID ."");
			if(count($total_comments) == 0){
				$ticketsData['Status'] = 'inqueue';
			}

			//re-populate info array to ticket comments module
			$this->info = $comment_model->makeInfo('ticketcomment');
			$commentsData = $this->validatePost('sb_ticketcomments');

			//@todo need separate table for comment attachments
			unset($ticketsData['file_path']);
			$comment_model->insertRow($commentsData, NULL);
			$this->model->insertRow($ticketsData , $request->input('TicketID'));

			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('core.note_success')
			));

		}
		else
		{

			return response()->json(array(
				'message'	=> $message,
				'status'	=> 'error'
			));
		}
	}

}