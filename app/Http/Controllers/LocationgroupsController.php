<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Library\FEGDBRelationHelpers;
use App\Models\location;
use App\Models\Locationgroups;
use App\Models\product;
use App\Models\Ordertyperestrictions;
use App\Models\UserLocations;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Validator, Input, Redirect ;

class LocationgroupsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'locationgroups';
	static $per_page	= '10';

	private $location;

	public function __construct(location $location)
	{
		parent::__construct();
		$this->model = new Locationgroups();
		$this->location = $location;

		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'locationgroups',
			'pageUrl'			=>  url('locationgroups'),
			'return' 			=> 	self::returnUrl()
		);
		


	}

	public function getIndex()
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

		$this->data['access']		= $this->access;
		return view('locationgroups.index',$this->data);
	}

	public function postData( Request $request)
	{

        $module_id = \DB::table('tb_module')->where('module_name', '=', 'locationgroups')->pluck('module_id');
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
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		//$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination = new Paginator($results['rows'], $results['total'], 
            (isset($params['limit']) && $params['limit'] > 0  ? $params['limit'] : 
				($results['total'] > 0 ? $results['total'] : '1')));        
		$pagination->setPath('locationgroups/data');
		$this->data['param']		= $params;
        $this->data['topMessage']	= @$results['topMessage'];
		$this->data['message']          = @$results['message'];
		$this->data['bottomMessage']	= @$results['bottomMessage'];
        $results['rows'] = $this->model->setExcludedData($results['rows']);

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
		return view('locationgroups.table',$this->data);

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

		$row            =   $this->model->find($id);

		if($row)
		{
			$this->data['row'] 	                        =   $row;
            $savedLocations                             =   FEGDBRelationHelpers::getCustomRelationRecords($id, Locationgroups::class, location::class, 0, true)->lists('location_id')->toArray();
            $this->data['savedLocations'] 	            =   $savedLocations;
            $this->data['alreadyExcludedProductTypes']  =   FEGDBRelationHelpers::getCustomRelationRecords($id, Locationgroups::class, Ordertyperestrictions::class, 1, true)->lists('ordertyperestrictions_id')->toArray();
            $this->data['alreadyExcludedProducts']      =   FEGDBRelationHelpers::getCustomRelationRecords($id, Locationgroups::class, product::class, 1, true)->lists('product_id')->toArray();
		} else {
			$this->data['row'] 		= $this->model->getColumnTable('l_groups');
		}


        $this->data['productTypes'] = Ordertyperestrictions::lists('order_type', 'id');
        $this->data['products']     = product::where('inactive', 0)->orderBy('vendor_description', 'asc')->lists('vendor_description', 'id');



		//$locations1 = $this->location->select(DB::raw("CONCAT(id,' ', location_name) AS location_name, id"))->where('active', 1)->orderBy('id', 'asc')->lists('location_name', 'id');
        $locations = UserLocations::getUserAssignedLocations(\DB::raw("CONCAT(location.id,' ', location.location_name) AS location_name"))->lists('location_name', 'id');

        $this->data['locations'] 	    = $locations;
		$this->data['setting'] 		    = $this->info['setting'];
		$this->data['fields'] 		    =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;

		return view('locationgroups.form',$this->data);
	}

	public function getShow( $id = null)
	{

		if($this->access['is_detail'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$row = $this->model->getRow($id);

        $this->data['locations'] = [];
        $this->data['excluded_product_types'] = [];

		if($row) {
			$this->data['row']                      =   $row;
            $locationGroup                          =   $this->model->find($id);
            $this->data['locations']                =   $locationGroup->locations()->get();
            $this->data['excludedProductTypes']   =   $locationGroup->excludedProductTypes()->get();
            $this->data['excludedProducts']        =   $locationGroup->excludedProducts()->get();
		} else {
			$this->data['row'] = $this->model->getColumnTable('l_groups');
		}
		
        $this->data['tableGrid']    =   $this->info['config']['grid'];
		$this->data['id']           =   $id;
		$this->data['access']		=   $this->access;
		$this->data['setting'] 		=   $this->info['setting'];
        $this->data['nodata']       =   \SiteHelpers::isNoData($this->info['config']['grid']);
		$this->data['fields'] 		=   \AjaxHelpers::fieldLang($this->info['config']['forms']);

		return view('locationgroups.view',$this->data);
	}


	function postCopy( Request $request)
	{

	    foreach(\DB::select("SHOW COLUMNS FROM l_groups ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));


		$sql = "INSERT INTO l_groups (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM l_groups WHERE id IN (".$toCopy.")";
		\DB::insert($sql);
		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success')
		));
	}

	function postSave( Request $request, $id =0)
	{
	    $rules = [
            'name'              => 'required|string|max:100|unique:l_groups,name,'.$id,
        ];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$data = $this->validatePost('l_groups');

			unset($data['location_ids']);
			unset($data['excluded_product_ids']);
			unset($data['excluded_product_type_ids']);
			$id = $this->model->insertRow($data , $id);

			if($id){

			    $location_ids       = $request->get('location_ids');
			    $product_type_ids   = $request->get('excluded_product_type_ids');
			    $product_ids        = $request->get('excluded_product_ids');

                FEGDBRelationHelpers::destroyCustomRelation(location::class, Locationgroups::class, 0, 0, $id);
                FEGDBRelationHelpers::destroyCustomRelation(Ordertyperestrictions::class, Locationgroups::class, 1, 0, $id);
                FEGDBRelationHelpers::destroyCustomRelation(product::class, Locationgroups::class, 1, 0, $id);

                if(count($location_ids) != 0){
                    foreach ($location_ids as $location_id){
                        FEGDBRelationHelpers::insertCustomRelation($location_id, $id, location::class, Locationgroups::class, 0);
                    }
                }

                if(count($product_type_ids) != 0) {
                    foreach ($product_type_ids as $product_type_id) {
                        FEGDBRelationHelpers::insertCustomRelation($product_type_id, $id, Ordertyperestrictions::class, Locationgroups::class, 1);
                    }
                }

                if(count($product_ids) != 0) {
                    foreach ($product_ids as $product_id){
                        FEGDBRelationHelpers::insertCustomRelation($product_id, $id, product::class, Locationgroups::class, 1);
                    }
                }
            }

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
		if($this->access['is_remove'] == 0) {
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_restric')
			));
			die;

		}
		// delete multipe rows
		if(count($request->input('ids')) >=1)
		{

		    $locationGroupsIds = $request->input('ids');

		    foreach ($locationGroupsIds as $locationGroupsId){
                FEGDBRelationHelpers::destroyCustomRelation(Locationgroups::class, location::class, 0, 0, $locationGroupsId);
                FEGDBRelationHelpers::destroyCustomRelation(Locationgroups::class, product::class, 1, 0, $locationGroupsId);
                FEGDBRelationHelpers::destroyCustomRelation(Locationgroups::class, Ordertyperestrictions::class, 1, 0, $locationGroupsId);
            }

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
    public function getExcludedDataInline($groupId = 0){

        $savedLocations = FEGDBRelationHelpers::getCustomRelationRecords($groupId, Locationgroups::class, location::class, 0, true)->lists('location_id')->toArray();
        $alreadyExcludedProductTypes = FEGDBRelationHelpers::getCustomRelationRecords($groupId, Locationgroups::class, Ordertyperestrictions::class, 1, true)->lists('ordertyperestrictions_id')->toArray();
        $alreadyExcludedProducts = FEGDBRelationHelpers::getCustomRelationRecords($groupId, Locationgroups::class, product::class, 1, true)->lists('product_id')->toArray();

        $products = product::select('id','vendor_description')->where('inactive', 0)->orderBy('vendor_description')->get();
        $productType = Ordertyperestrictions::select('id','order_type as product_type')->orderBy('order_type','asc')->get();
       // $locations = location::select('id','location_name')->where('active',1)->orderBy('id','asc')->get();
        $locations = UserLocations::getUserAssignedLocations()->get();

        $productData = view('locationgroups.dropdown',['products'=>$products,'type'=>'products'])->render();
        $productTypeData = view('locationgroups.dropdown',['producttypes'=>$productType,'type'=>'producttypes'])->render();
        $locationData = view('locationgroups.dropdown',['locations'=>$locations,'type'=>'locations'])->render();

        $data = [
            'locations'=>$locationData,
            'productTypes'=>$productTypeData,
            'products'=>$productData,
        'selectedData' =>[
            'locations'=>$savedLocations,
            'productTypes'=>$alreadyExcludedProductTypes,
            'products'=>$alreadyExcludedProducts,
        ]
            ];
        return response()->json($data);
    }

}