<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Expensecategories;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class ExpensecategoriesController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'expensecategories';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Expensecategories();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'expensecategories',
			'pageUrl'			=>  url('expensecategories'),
			'return' 			=> 	self::returnUrl()
		);
		


	}

	public function getIndex()
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

		$this->data['access']		= $this->access;
		return view('expensecategories.index',$this->data);
	}

	public function postData( Request $request)
	{

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'expensecategories')->pluck('module_id');
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
		//$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
		$filter = $this->getSearchFilterQuery();
		if(!is_null($request->input('display_filter')) && $request->input('display_filter') == 'yes'){
			$filter = $filter." AND expense_category_mapping.order_type IS NOT NULL AND expense_category_mapping.product_type IS NULL ";
			$this->data['filter_toggle'] = 'true';
		}else{
			$this->data['filter_toggle'] = 'false';
		}

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

		//Filter results
		if($this->data['filter_toggle'] == 'true'){
			$results['rows'] = array_map(function($row){
				unset($row->product_type);
				return $row;
			},$results['rows']);

			$this->info['config']['grid'] = array_map(function($row){
				if($row['field'] != 'product_type'){
					return $row;
				}
			},$this->info['config']['grid']);
		}

		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], 
            (isset($params['limit']) && $params['limit'] > 0  ? $params['limit'] : 
				($results['total'] > 0 ? $results['total'] : '1')));        
		$pagination->setPath('expensecategories/data');
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
		return view('expensecategories.table',$this->data);

	}

	public function getSearchFilterQuery($customQueryString = null) {
		// Filter Search for query
		// build sql query based on search filters


		// Get custom Ticket Type filter value
		$globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '']);
		$skipFilters = ['search_all_fields'];
		$mergeFilters = [];
		extract($globalSearchFilter); //search_all_fields

		// rebuild search query skipping 'ticket_custom_type' filter
		$trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
		$searchInput = $trimmedSearchQuery;
		if (!empty($search_all_fields)) {
			$searchFields = [
				'order_type.order_type',
				'product_type.type_description',
				'expense_category_mapping.mapped_expense_category',
			];
			$searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
		}

		// Filter Search for query
		// build sql query based on search filters
		$filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);


		return $filter;
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
			$this->data['row'] 		= $this->model->getColumnTable('expense_category_mapping');
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('expensecategories.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('expense_category_mapping');
		}
		
        $this->data['tableGrid'] = $this->info['config']['grid'];
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		return view('expensecategories.view',$this->data);
	}

	function postCopy( Request $request)
	{

	    foreach(\DB::select("SHOW COLUMNS FROM expense_category_mapping ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));


		$sql = "INSERT INTO expense_category_mapping (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM expense_category_mapping WHERE id IN (".$toCopy.")";
		\DB::insert($sql);
		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success')
		));
	}

	function postSave( Request $request, $id =0)
	{
		$rules = $this->validateForm();
		$rules['mapped_expense_category'] = 'integer|required';
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes() && $id != 0) {

			$expense_category = $request->mapped_expense_category;
			$data = \DB::table('expense_category_mapping')->where('id', $id)->get();

			$order_type_id = $data[0]->order_type;
			$product_type_id = $data[0]->product_type;
			$old_expense_category = $data[0]->mapped_expense_category;

			if($product_type_id == ''){

				\DB::table('expense_category_mapping')
					->where('order_type', $order_type_id)
					->where('mapped_expense_category', $old_expense_category)
					->update(['mapped_expense_category' => $expense_category]);

				\DB::table('products')
					->where('prod_type_id', $order_type_id)
					//->where('prod_sub_type_id', '0')
					->where('expense_category', $old_expense_category)
					->update(['expense_category' => $expense_category]);

			}else{
				\DB::table('expense_category_mapping')
					->where('id', $id)
					->update(['mapped_expense_category' => $expense_category]);

				$product_type_id = empty($product_type_id) ? '0' : $product_type_id;

				\DB::table('products')
					->where('prod_type_id', $order_type_id)
					->where('prod_sub_type_id', $product_type_id)
					//->where('expense_category', $old_expense_category)
					->update(['expense_category' => $expense_category]);
			}

			return response()->json(array(
				'status'=>'success',
				'message'=> "Expense category has been updated successfully!"
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

	public function postSingleDelete(Request $request)
	{
		die('Feature is removed! Contact admin');

		if($this->access['is_remove'] ==0) {
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_restric')
			));
			die;
		}

		$this->model->destroy($request->id);

		\DB::table('products')
			->where('prod_type_id',$request->order_type)
			->where('prod_sub_type_id',empty($request->product_type)? '0':$request->product_type)
			->where('expense_category',$request->expense_category)
			->update(['expense_category' => '0']);

		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success_delete')
		));


	}

	public function getGenerateExpenseCategories()
	{
		\DB::delete("DELETE FROM expense_category_mapping WHERE mapped_expense_category = 0");
		echo "<H4>ALL UNUSED MAPPED CATEGORIES(0) DELETED AND RECREATED</H4>";
		$order_types = \DB::select("SELECT * FROM order_type");
		$product_types = \DB::select("SELECT * FROM product_type");

		$order_type_logs = '';
		$combined_type_logs = '';
		//Process one
		foreach ($order_types as $key => $order_type){
			$expense = '0';
			$check = \DB::select("SELECT mapped_expense_category FROM expense_category_mapping WHERE order_type = $order_type->id AND product_type IS NULL");
			if(empty($check)){
				\DB::insert("INSERT INTO expense_category_mapping (order_type, product_type, mapped_expense_category) VALUES ($order_type->id, NULL, $expense)");
				$order_type_logs .= "<b style='background-color:#61fd61'>Entry added</b> for order_type: <b>$order_type->id</b> and product_type: <b>0</b> with expense_category = $expense <br>";
			}else{
				$expense = $check[0]->mapped_expense_category;
				$order_type_logs .= "Entry for order_type: <b>$order_type->id</b> with expense_category = $expense is <b style='background-color:#fd9c9c'>already exist</b><br>";
			}

			//Process two
			foreach ($product_types as $key => $product_type){
				$checkCombined = \DB::select("SELECT mapped_expense_category FROM expense_category_mapping WHERE order_type = $order_type->id AND product_type = $product_type->id");
				if(empty($checkCombined)){
					\DB::insert("INSERT INTO expense_category_mapping (order_type, product_type, mapped_expense_category) VALUES ($order_type->id, $product_type->id, $expense)");
					$combined_type_logs .= "<b style='background-color:#61fd61'>Entry added</b> for order_type: <b>$order_type->id</b> and product_type: <b>$product_type->id</b> with expense_category = $expense <br>";
				}else{
					$combined_type_logs .= "Entry for order_type: <b>$order_type->id</b> and product_type: <b>$product_type->id</b> with expense_category = $expense is <b style='background-color:#fd9c9c'>already exist</b><br>";
				}
			}
		}

		echo $order_type_logs.'<br><br>';
		echo $combined_type_logs.'<br><br>';
	}
}
