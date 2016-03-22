<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class VendorController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'vendor';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Vendor();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'vendor',
			'pageUrl'			=>  url('vendor'),
			'return' 			=> 	self::returnUrl()	
		);
		
			
				
	} 
	
	public function getIndex()
	{
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
				
		$this->data['access']		= $this->access;	
		return view('vendor.index',$this->data);
	}	

	public function postData( Request $request)
	{
        $module_id = \DB::table('tb_module')->where('module_name', '=', 'vendor')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if(!empty($config))
        {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
            \Session::put('config_id', $config_id);
        }
		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

		
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
        foreach ($results['rows'] as $result) {

            if ($result->partner_hide == 1) {
                $result->partner_hide = "Yes";

            } else {
                $result->partner_hide = "No";
            }
            if ($result->isgame == 1) {
                $result->isgame = "Yes";

            } else {
                $result->isgame = "No";
            }
            if ($result->ismerch == 1) {
                $result->ismerch = "Yes";

            } else {
                $result->ismerch = "No";
            }
        }
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('vendor/data');
		
		$this->data['param']		= $params;
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
		// Render into template
		return view('vendor.table',$this->data);

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
			$this->data['row'] 		= $this->model->getColumnTable('tb_vendor'); 
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('vendor.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
                if ($row->partner_hide == 1) {
                    $row->partner_hide = "Yes";

                } else {
                    $row->partner_hide = "No";
                }
                if ($row->isgame == 1) {
                    $row->isgame = "Yes";

                } else {
                    $row->isgame = "No";
                }
                if ($row->ismerch == 1) {
                    $row->ismerch = "Yes";

                } else {
                    $row->ismerch = "No";
                }

			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_vendor'); 
		}
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		return view('vendor.view',$this->data);	
	}	


	function postCopy( Request $request)
	{
		
	    foreach(\DB::select("SHOW COLUMNS FROM tb_vendor ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));
		
				
		$sql = "INSERT INTO tb_vendor (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM tb_vendor WHERE id IN (".$toCopy.")";
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
			$data = $this->validatePost('tb_vendor');
			
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

}