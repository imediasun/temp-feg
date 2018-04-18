<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Productlog;
use App\Models\ReservedQtyLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
use App\User;

class ProductlogController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'productlog';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Productlog();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'productlog',
			'pageUrl'			=>  url('productlog'),
			'return' 			=> 	self::returnUrl()
		);
		


	}

	public function getIndex()
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

		$this->data['access']		= $this->access;
		return view('productlog.index',$this->data);
	}

	public function postData( Request $request)
	{

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'productlog')->pluck('module_id');
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
		// End Filter sort and order for query
		// Filter Search for query
	//	$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

        $searchQuery = $request->input('search');
        $filter = $this->getSearchFilterQuery();


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
		//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], 
            (isset($params['limit']) && $params['limit'] > 0  ? $params['limit'] : 
				($results['total'] > 0 ? $results['total'] : '1')));        
		$pagination->setPath('productlog/data');
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
// Render into template
		return view('productlog.table',$this->data);

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
			$this->data['row'] 		= $this->model->getColumnTable('products');
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('productlog.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('products');
		}


        $productLogContentData = ['reducedByOrder'=>[],'addedFromProductList'=>[]];
		if(!empty($row->variation_id)) {
            $productLogContent = ReservedQtyLog::where("variation_id", "=", $row->variation_id);
            $Contents = $productLogContent->orderBy('id', 'DESC')->get()->filter(function ($item) {
            $userData = User::find($item->adjusted_by);
                return $item->adjusted_by = $userData->first_name." ".$userData->last_name;
            });
            $productLogContentData['Contents'] = $Contents;
            $totalRecords = $productLogContentData['Contents']->count();
            $productLogContentData['Contents'][$totalRecords-1]->reservedQty = $productLogContentData['Contents'][$totalRecords-1]->adjustment_amount;
            $initialAmount = $productLogContentData['Contents'][$totalRecords-1]->adjustment_amount;
            $productLogContentData['Contents'][$totalRecords-1]->reservedQuantity = $initialAmount;
            for($i = ($totalRecords-2); $i>=0; $i--){
                if($productLogContentData['Contents'][$i]->adjustment_type == 'negative'){
                    $productLogContentData['Contents'][$totalRecords-1]->reservedQty -= $productLogContentData['Contents'][$i]->adjustment_amount;
                }else{
                    $productLogContentData['Contents'][$totalRecords-1]->reservedQty += $productLogContentData['Contents'][$i]->adjustment_amount;
                }
                $productLogContentData['Contents'][$i]->reservedQuantity = $productLogContentData['Contents'][$totalRecords-1]->reservedQty;
            }
        }

        $this->data['productLogContent'] = $productLogContentData;
        $this->data['tableGrid'] = $this->info['config']['grid'];
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		return view('productlog.view',$this->data);
	}


	function postCopy( Request $request)
	{

	    foreach(\DB::select("SHOW COLUMNS FROM products ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));


		$sql = "INSERT INTO products (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM products WHERE id IN (".$toCopy.")";
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
			$data = $this->validatePost('products');

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

    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters



        $globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '', 'reserved_qty' => '']);
        $skipFilters = ['search_all_fields'];
        $mergeFilters = [];
        extract($globalSearchFilter); //search_all_fields

        // rebuild search query
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        if (!empty($search_all_fields)) {
            $searchFields = [
                'products.vendor_description',
                'products.sku',
                'orders.id',
                'orders.po_number',
            ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);

        return $filter;
    }

}