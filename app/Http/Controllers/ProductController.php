<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use Validator, Input, Redirect,Image;

class ProductController extends Controller
{

    protected $layout = "layouts.main";
    protected $data = array();
    protected $sortMapping = [];
    protected $sortUnMapping = [];
    public $module = 'product';
    static $per_page = '10';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Product();

        $this->info = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = array(
            'pageTitle' => $this->info['title'],
            'pageNote' => $this->info['note'],
            'pageModule' => 'product',
            'pageUrl' => url('product'),
            'return' => self::returnUrl()
        );
        $this->sortMapping = ['vendor_id' => 'vendor.vendor_name', 'prod_type_id' => 'O.order_type', 'prod_sub_type_id' => 'T.type_description'];
        $this->sortUnMapping = ['vendor.vendor_name' => 'vendor_id', 'O.order_type' => 'prod_type_id', 'T.type_description' => 'prod_sub_type_id'];


    }

    public
    function getExport($t = 'excel')
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-' . $exportId;
            \Session::put($exportSessionID, microtime(true));
        }

        $info = $this->model->makeInfo($this->module);
        //$master  	= $this->buildMasterDetail();
        if (method_exists($this, 'getSearchFilterQuery')) {
            $filter = $this->getSearchFilterQuery();
        } else {
            $filter = (!is_null(Input::get('search')) ? $this->buildSearch() : '');
        }

        //$filter 	.=  $master['masterFilter'];
//    $params = array(
//        'params' => ''
//    );
        $sort = isset($_GET['sort']) ? $_GET['sort'] : $this->info['setting']['orderby'];
        $order = isset($_GET['order']) ? $_GET['order'] : $this->info['setting']['ordertype'];
        $params = array(
            'params' => '',
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
            'forExcel' => 1
        );


        $results = $this->model->getRows($params);

        $fields = $info['config']['grid'];
        $rows = $results['rows'];

        $extra = array(
            'field' => '',
            'alias' => 'departments',
            'language' =>
                array('id' => ''),
            'label' => '',
            'view' => '1',
            'detail' => '1',
            'sortable' => '1',
            'search' => '1',

            'download' => '1',
            'frozen' => '1',
            'limited' => '',
            'width' => '100',
            'align' => 'left',
            'sortlist' => '0',
            'conn' =>
                array(
                    'valid' => '0',
                    'db' => '',
                    'key' => '',
                    'display' => ''),
            'attribute' =>
                array(
                    'hyperlink' => '',
                    array(
                        'active' => '0',
                        'link' => '',
                        'target' => 'modal',
                        'html' => ''),
                    'image' =>
                        array(

                            'active' => '0',
                            'path' => '',
                            'size_x' => '',
                            'size_y' => '',
                            'html' => ''),
                    'formater' =>
                        array(
                            'active' => '0',
                            'value' => '',


                        )));

        $rows = $this->updateDateInAllRows($rows);
        $rows = array_map(function ($element) {
            if (!empty($element->expense_category) && $element->expense_category > 0) {
                $result = $this->getExpenseCategoryById($element->expense_category);
                $element->expense_category = count($result) > 0 ? $result[0]->expense_category : 0;
            }
            return $element;
        }, $rows);


        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'title' => $this->data['pageTitle'],
            'excelExcludeFormatting' => isset($results['excelExcludeFormatting']) ? $results['excelExcludeFormatting'] : []
        );

        if ($t == 'word') {

            return view('sximo.module.utility.word', $content);

        } else if ($t == 'pdf') {

            $pdf = PDF::loadView('sximo.module.utility.pdf', $content);
            return view($this->data['pageTitle'] . '.pdf');

        } else if ($t == 'csv') {

            return view('sximo.module.utility.csv', $content);

        } else if ($t == 'print') {

            return view('sximo.module.utility.print', $content);

        } else {

            return view('sximo.module.utility.excel', $content);
        }
    }

    public function getModify(){
        $query ="SELECT products.*  FROM `products` WHERE vendor_description REGEXP '[ ]{2,}'";
        $products = DB::select($query);

        foreach($products as $pro){
            $vendor_desc = trim(preg_replace('/\s+/',' ', $pro->vendor_description));
            DB::table('products')
                ->where('id', $pro->id)
                ->update(['vendor_description' => $vendor_desc]);
        }

    }

    public function getSearchFilterQuery($customQueryString = null) {
        // Filter Search for query
        // build sql query based on search filters


        // Get custom Ticket Type filter value
        $globalSearchFilter = $this->model->getSearchFilters(['search_all_fields' => '', 'inactive' => '']);
        $skipFilters = ['search_all_fields'];
        $mergeFilters = [];
        extract($globalSearchFilter); //search_all_fields

        // rebuild search query skipping 'ticket_custom_type' filter
        $trimmedSearchQuery = $this->model->rebuildSearchQuery($mergeFilters, $skipFilters, $customQueryString);
        $searchInput = $trimmedSearchQuery;
        if (!empty($search_all_fields)) {
            $searchFields = [
                    'O.order_type',
                    'T.type_description',
                    'vendor.vendor_name',
                    'products.vendor_description',
                    'products.case_price',
                    'products.retail_price',
                    'products.unit_price',
                    'T.type_description',
                    'products.expense_category',
                    'products.sku',
                    'products.size',
                    'products.item_description',
                    'products.ticket_value',
                    'products.details'
                ];
            $searchInput = ['query' => $search_all_fields, 'fields' => $searchFields];
        }

        // Filter Search for query
        // build sql query based on search filters
        $filter = is_null(Input::get('search')) ? '' : $this->buildSearch($searchInput);

        $activeInactive = '';
        if($inactive != ''){
            $activeInactive = " AND products.inactive = $inactive";
        }

        return $filter.$activeInactive;
    }
    
    public function getIndex()
    {
        if ($this->access['is_view'] == 0)
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['access'] = $this->access;
        return view('product.index', $this->data);
    }

    public function postData(Request $request)
    {

        $prod_list_type = isset($_GET['prod_list_type']) ? $_GET['prod_list_type'] : '';
        $active = isset($_GET['active']) ? $_GET['active'] : '';
        $sub_type = isset($_GET['sub_type']) ? $_GET['sub_type'] : '';


        $module_id = \DB::table('tb_module')->where('module_name', '=', 'product')->pluck('module_id');
        $this->data['module_id'] = $module_id;
        if (Input::has('config_id')) {
            $config_id = Input::get('config_id');
            \Session::put('config_id',$config_id);
        } elseif (\Session::has('config_id')) {
            $config_id = \Session::get('config_id');
        } else {
            $config_id = 0;
        }
        $this->data['config_id'] = $config_id;
        $config = $this->model->getModuleConfig($module_id, $config_id);
        if (!empty($config)) {
            $this->data['config'] = \SiteHelpers::CF_decode_json($config[0]->config);
            \Session::put('config_id', $config_id);
        }
        $sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
        // End Filter sort and order for query
        // Filter Search for query
        $filter = $this->getSearchFilterQuery();//(!is_null($request->input('search')) ? $this->buildSearch() : '');
        $sort = !empty($this->sortMapping) && isset($this->sortMapping[$sort]) ? $this->sortMapping[$sort] : $sort;

        $page = $request->input('page', 1);
        $params = array(
            'page' => $page,
            'limit' => (!is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
            'sort' => $sort,
            'order' => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),

        );
        // Get Query
        if ($prod_list_type) {
            $this->data['product_list_type'] = $prod_list_type;
            $this->data['active_prod'] = $active;
        } else {
            $this->data['product_list_type'] = 'select';
            $this->data['active_prod'] = 0;
        }
        $this->data['sub_type']=$sub_type;
        $results = $this->model->getRows($params, $prod_list_type, $active,$sub_type);
        $params['sort'] = !empty($this->sortUnMapping) && isset($this->sortUnMapping[$sort]) ? $this->sortUnMapping[$sort] : $sort;;

        $rows = $results['rows'];
        $ExpenseCategories = $this->model->allExpenseCategories();
        $this->data['ExpenseCategories'] = $ExpenseCategories;

        foreach ($rows as $index => $data) {
            if ($data->is_reserved == 1) {
                $data->is_reserved = "Yes";

            } else {
                $data->is_reserved = "No";
            }
            if ($data->inactive == 1) {
                $data->inactive = "Yes";

            } else {
                $data->inactive = "No";
            }
            if ($data->hot_item == 1) {
                $data->hot_item = "Yes";

            } else {
                $data->hot_item = "No";
            }
            /*
			$product_type = \DB::select("Select product_type FROM product_type WHERE id = '".$data->prod_type_id ."'");
			$rows[$index]->prod_type_id = (isset($product_type[0]->product_type) ? $product_type[0]->product_type : '');
			$product_sub_type = \DB::select("Select product_type FROM product_type WHERE id = ".$data->prod_sub_type_id ."");
			$rows[$index]->prod_sub_type_id = (isset($product_sub_type[0]->product_type) ? $product_sub_type[0]->product_type : '');

           $vendor = \DB::select("Select vendor_name FROM vendor WHERE id = ".htmlentities($data->vendor_id) ."");
			$rows[$index]->vendor_id = (isset($vendor[0]->vendor_name) ? $vendor[0]->vendor_name : '');
            */
        }
        // Build pagination setting
        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], (isset($params['limit']) && $params['limit'] > 0 ? $params['limit'] :
            ($results['total'] > 0 ? $results['total'] : '1')));

        $pagination->setPath('product/data');


        $this->data['param'] = $params;
        $this->data['rowData'] = $rows;
        // Build Pagination
        $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = ($page * $params['limit']) - $params['limit'];
        // Grid Configuration
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['colspan'] = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['setting'] = $this->info['setting'];
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        if ($this->data['config_id'] != 0 && !empty($config)) {
            $this->data['tableGrid'] = \SiteHelpers::showRequiredCols($this->data['tableGrid'], $this->data['config']);
        }
        // Render into template
        return view('product.table', $this->data);

    }


    function getUpdate(Request $request, $id = null)
    {

        if ($id == '') {
            if ($this->access['is_add'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        if ($id != '') {
            if ($this->access['is_edit'] == 0)
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('products');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('product.form', $this->data);
    }

    public function getShow($id = null)
    {

        if ($this->access['is_detail'] == 0)
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('products');
        }

        $this->data['id'] = $id;
        $this->data['access'] = $this->access;
        $this->data['setting'] = $this->info['setting'];
        $this->data['nodata']=\SiteHelpers::isNoData($this->info['config']['grid']);
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);
        return view('product.view', $this->data);
    }


    function postCopy(Request $request)
    {

        foreach (\DB::select("SHOW COLUMNS FROM products ") as $column) {

            if ($column->Field != 'id')
                $columns[] = $column->Field;

        }
        $toCopy = implode(",", $request->input('ids'));

        $sql = "INSERT INTO products (" . implode(",", $columns) . ") ";
        $columns[1] = "CONCAT('copy ".mt_rand()." ',vendor_description)";
        $sql .= " SELECT " . implode(",", $columns) . " FROM products WHERE id IN (" . $toCopy . ")";
        \DB::insert($sql);

        return response()->json(array(
            'status' => 'success',
            'message' => \Lang::get('core.note_success')
        ));
    }

    function postSave(Request $request, $id = 0)
    {

        //to remove the extra spaces im between the string
        $request->vendor_description = trim(preg_replace('/\s+/',' ', $request->vendor_description));

        if(is_array($request->prod_sub_type_id) && $id == 0)
        {
            if(count(array_unique($request->prod_sub_type_id))<count($request->prod_sub_type_id))
            {
                // Array has duplicates
                return response()->json(array(
                    'message' => "Please Select Unique Combinations of Product Type & Sub Type",
                    'status' => 'error'
                ));
            }
        }
        else
        {
            $type = is_array($request->prod_type_id)?$request->prod_type_id[0]:$request->prod_type_id;
            $subtype = is_array($request->prod_sub_type_id)?$request->prod_sub_type_id[1]:$request->prod_sub_type_id;

            // $productName = Product::find($id)->vendor_description;
            $productName = $request->vendor_description;

            $duplicate = Product::
            where('prod_type_id',$type)
            ->where('prod_sub_type_id',$subtype)
            ->where('sku',$request->sku)
            ->where('id','!=',$id)
            ->where('vendor_description',$productName)
                ->first();
            if(!empty($duplicate))
            {
                return response()->json(array(
                    'message' => "A product with same Product Type & Sub Type already exist",
                    'status' => 'error'
                ));
            }
        ;
        }

        if ($request->hasFile('img'))
        {
            $file = $request->file('img');
            $img = Image::make($file->getRealPath());
            $mime = $img->mime();
            if ($mime == 'image/jpeg') {
                $extension = '.jpg';
            } elseif ($mime == 'image/png') {
                $extension = '.png';
            } elseif ($mime == 'image/gif') {
                $extension = '.gif';
            } else {
                $extension = '';
            }
        }

        $rules = $this->validateForm();
        $rules['img'] = 'mimes:jpeg,gif,png';
        //$rules['sku'] = 'required';

            $rules['expense_category'] = 'required';


        $request->Product_Type = $request->prod_type_id;
        $request->Vendor = $request->vendor_id;

        $rules['vendor_description'] = 'required';
        $rules['Product_Type'] = 'required';
        $rules['sku'] = "required";
        $rules['case_price'] = 'required';
        $rules['unit_price'] = 'required';
        $rules['Vendor'] = 'required';

        $validator = Validator::make($request->all(), $rules);
        $retail_price = $request->get('retail_price');

        $product_categories = $request->get('prod_type_id');
        if ($validator->passes()) {
            if ($id == 0) {
                $data = $this->validatePost('products');
                $data['vendor_description'] = trim(preg_replace('/\s+/',' ', $data['vendor_description']));
            }
            else {
                //for inline editing all fields do not get saved
                $data = $this->validatePost('products',true);
                $data['vendor_description'] = trim(preg_replace('/\s+/',' ', $data['vendor_description']));
            }
            $postedtoNetSuite = $data['vendor_description'];

            if(strlen( $data['vendor_description'])>53){
                $postedtoNetSuite = substr($data['vendor_description'],0.53);
            }


            $data['netsuite_description'] = "$id...".$data['vendor_description'];
            if($id>0) {
                $products_combined = $this->model->checkProducts($id);
                $hot_items=0;
                if(!empty($request->input('hot_item')) && $request->input('hot_item')>0){
                    $hot_items = "'1'";
                }else if(!empty($request->input('hot_item')) && $request->input('hot_item')==0){
                    $hot_items = "'0'";
                }else{
                    $hot_items = "null";
                }
                \DB::update("update products set hot_item=$hot_items where id='$id'");

            }

            if (isset($data['inactive'])) {
                if ($data['inactive']) {
                    $data['inactive_by'] = Auth::user()->id;
                } else {
                    $data['inactive_by'] = NULL;
                }
            }

            if(is_array($product_categories) && $id > 0){

                $products_combined = $this->model->checkProducts($id);
                unset($data['is_default_expense_category']);
                $data_attached_products= $data;

                foreach($products_combined as $pc){
                    $data['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    if($pc->id == $id){
                        $data['prod_type_id'] = $data['prod_type_id'][0];
                        $data['prod_sub_type_id'] = $data['prod_sub_type_id'][1];
                        $data['expense_category'] = $data['expense_category'][1];
                        $data['retail_price'] = $data['retail_price'][1];
                        $data['ticket_value'] = $data['ticket_value'][1];
                        $this->model->insertRow($data, $id);
                    }else{

                        unset($data_attached_products['prod_type_id']);
                        unset($data_attached_products['prod_sub_type_id']);
                        unset($data_attached_products['expense_category']);
                        unset($data_attached_products['retail_price']);
                        unset($data_attached_products['ticket_value']);
                        unset($data_attached_products['inactive']);
                        unset($data_attached_products['inactive_by']);
                        unset($data_attached_products['in_development']);

                        $this->model->insertRow($data_attached_products,$pc->id);
                    }
                    $netsuite_description['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    $this->model->insertRow($netsuite_description, $pc->id);
                }
                $isDefaultExpenseCategory = $request->input("is_default_expense_category");
                if ($id > 0 && $isDefaultExpenseCategory > 0) {
                    $this->model->setDefaultExpenseCategory($id);
                }

            }elseif(is_array($product_categories))
            {

                $ids = [];
                $count = 1;
                $prodData = $data;
                foreach ($product_categories as $category)
                {
                    $prodData['retail_price'] = (isset($retail_price[$count]) && !empty($retail_price[$count]))?$retail_price[$count]:0;
                    $prodData['ticket_value'] = (isset($data['ticket_value'][$count]) && !empty($data['ticket_value'][$count]))?$data['ticket_value'][$count]:0;
                    $prodData['prod_type_id'] = $category;
                    $prodData['prod_sub_type_id'] = (isset($data['prod_sub_type_id'][$count]) && !empty($data['prod_sub_type_id'][$count]))?$data['prod_sub_type_id'][$count]:0;
                    $prodData['expense_category'] = (isset($data['expense_category'][$count]) && !empty($data['expense_category'][$count]))?$data['expense_category'][$count]:0;
                    $count++;
                    /*
                     * commented as per Gabe request on 9/13/2017
                    if($data['prod_type_id'] != 8){
                        $data['retail_price'] = 0.000;
                    }*/
                    $ids[] = $this->model->insertRow($prodData, $id);
                }

                foreach ($ids as $id)
                {
                    $postedtoNetSuite = $data['vendor_description'];

                    if(strlen( $data['vendor_description'])>53){
                        $postedtoNetSuite = substr($data['vendor_description'],0.53);
                    }

                    $updates = array();
                    $updates['netsuite_description'] = "$id...".$postedtoNetSuite;
                    if (isset($img)) {

                        $newfilename = $id . '' . $extension;
                        $img_path='./uploads/products/' . $newfilename;
                        $img->save($img_path);
                        $updates['img'] = $newfilename;

                    }
                    $this->model->insertRow($updates, $id);
                    $this->model->setFirstDefaultExpenseCategory($id);
                }

            }
            else
            {


                $products_combined = $this->model->checkProducts($id);
                $data_attached_products= $data;
                foreach($products_combined as $pc){
                    if($pc->id == $id){
                        $this->model->insertRow($data, $id);
                    }else{

                        unset($data_attached_products['prod_type_id']);
                        unset($data_attached_products['prod_sub_type_id']);
                        unset($data_attached_products['expense_category']);
                        unset($data_attached_products['retail_price']);
                        unset($data_attached_products['ticket_value']);

                        $this->model->insertRow($data_attached_products,$pc->id);
                    }
                    $postedtoNetSuite = $data['vendor_description'];

                    if(strlen( $data['vendor_description'])>53){
                        $postedtoNetSuite = substr($data['vendor_description'],0.53);
                    }
                    $netsuite_description['netsuite_description'] = $pc->id."...".$postedtoNetSuite;
                    $this->model->insertRow($netsuite_description, $pc->id);
                }
            }

            return response()->json(array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success')
            ));

        } else {

            $message = $this->validateListError($validator->getMessageBag()->toArray());
            return response()->json(array(
                'message' => $message,
                'status' => 'error'
            ));
        }

    }

    public function postDelete(Request $request)
    {

        if ($this->access['is_remove'] == 0) {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_restric')
            ));
        }
        $errorMessages = [];
        $ids = $request->input('ids');
        // delete multipe rows
        if (count($ids) >= 1) {
            $deletedIds = [];
            $errorId = [];
            $variationsErrorId = [];
            foreach ($ids as $id) {
                if (!in_array($id, $deletedIds)) {
                    $productVariation = Product::find($id);
                    $productVariations = $productVariation->getProductVariations();
                    $variations = new Collection();
                    foreach ($productVariations as $productVariation) {
                        if (in_array($productVariation->id, $ids)) {
                            //$variations[] = $productVariation;
                            $variations->add($productVariation);
                        }
                    }
                    //case when user has selected all variations of a product then delete all variations
                    if ($productVariations->count() == $variations->count()) {

                        $variations->each(function ($product) {
                            $product->delete();
                        });
                        foreach ($variations as $variation) {
                            $deletedIds[] = $variation->id;
                        }
                    } else if ($productVariations->count() - 1 == $variations->count()) {

                        //delete all variation including default varation
                        $variations->each(function ($product) {
                            $product->delete();
                        });
                        $remainingItem = $productVariations->diff($variations);
                        $remainingItem->each(function ($product) {
                            $product->is_default_expense_category = 1;
                            $product->save();
                        });
                        foreach ($variations as $variation) {
                            $deletedIds[] = $variation->id;
                        }
                    } else if ($productVariations->count() > $variations->count()) {

                        foreach ($variations as $productVariation) {
                            if ($productVariation->is_default_expense_category == 1) {
                                //Need to test that
                                foreach ($variations as $variation) {
                                    $errorId[] = $variation->id;
                                }
                                if (!in_array($productVariation->id, $variationsErrorId)) {
                                    $variationsErrorId[] = $productVariation->id;
                                    $errorMessages[] = [
                                        'status' => 'error',
                                        'message' => "Selected product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category before removing this variant."
                                    ];
                                }

                            }
                        }
                    }
                    if (!in_array($id, $errorId)) {
                        $this->model->destroy([$id]);
                    }
                }
            }

            if (count($errorMessages) > 0) {
                return response()->json($errorMessages);
            }

            /* $hasDefaultExpenseCategory = $this->model->hasDefaultExpenseCategory($id);
             if ($hasDefaultExpenseCategory == true) {
                 return response()->json(array(
                     'status' => 'error',
                     'message' => "Selected product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category before removing this variant."
                 ));
             }*/
            //$this->model->destroy($request->input('ids'));

            return response()->json([array(
                'status' => 'success',
                'message' => \Lang::get('core.note_success_delete')
            )]);
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => \Lang::get('core.note_error')
            ));

        }

    }

    function getUpload($id = NULL)
    {
        $data['img'] = \DB::table('products')->where('id', $id)->pluck('img');
        $data['return'] = "";
        return view('product.upload', $data);
    }

    function postListcsv(Request $request)
    {
        global $exportSessionID;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $exportId = Input::get('exportID');
        if (!empty($exportId)) {
            $exportSessionID = 'export-'.$exportId;
            \Session::put($exportSessionID, microtime(true));
        }
        
        $vendor_id = $request->vendor_id;
        $rows = $this->model->getVendorPorductlist($vendor_id);
        $fields = array('Vendor', 'Description', 'Sku', 'Unit Price', 'Item Per Case', 'Case Price', 'Ticket Value', 'Order Type', 'Product Type', 'INACTIVE', 'Reserved Qty');
        $this->data['pageTitle'] = 'ProductList_';
        $content = array(
            'exportID' => $exportSessionID,
            'fields' => $fields,
            'rows' => $rows,
            'type' => 'move',
            'title' => $this->data['pageTitle'],
        );
        return view('product.csvhistory', $content);
    }


    function postUpload(Request $request)
    {

        $files = array('img' => Input::file('img'));
        // setting up rules
        $rules = array('img' => 'required|mimes:jpeg,gif,png'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        $id = Input::get('id');

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'Please select an Image..')->withErrors($validator);;

        } else {
            $updates = array();
            $file = $request->file('img');
            $destinationPath = './uploads/products/';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file
            $newfilename = $id . mt_rand() . '.' . $extension;
            $uploadSuccess = $request->file('img')->move($destinationPath, $newfilename);
            if ($uploadSuccess) {
                $relatedProducts = $this->model->checkProducts($id);
                $ids = array_map(function($row){return $row->id;}, $relatedProducts);
                \DB::update('update products set img = "' . $newfilename . '" where id IN(' . implode(",", $ids) .')');
            }
            return Redirect::to('product/upload/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');

        }


    }

    function getTest()
    {
        $row = \DB::select("select id,img from products where id > 2820");
        $img = "";
        foreach ($row as $r) {
            if (!empty($r->img)) {
                $img = $r->id . ".jpg";
                \DB::update("update products set img='" . $img . "'where id=" . $r->id);
            }
        }
    }

    function postTrigger(Request $request)
    {
        $isActive = $request->get('isActive');
        $productId = $request->get('productId');
        if ($isActive == "true") {
            $update = \DB::update('update products set inactive = 1, inactive_by = ' . Auth::user()->id . ' where id=' . $productId);
        } else {
            $update = \DB::update('update products set inactive = 0, in_development = 0, inactive_by = NULL where id=' . $productId);
        }
        if ($update) {
            return response()->json(array(
                'status' => 'success'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred in Activation'
            ));
        }
    }

    public function postExclude(Request $request)
    {
        $excludeExport = $request->get('excludeExport');
        $productId = $request->get('productId');
        $relatedProducts = $this->model->checkProducts($productId);
        $ids = array_map(function($row){return $row->id;}, $relatedProducts);

        if ($excludeExport == "true") {
            $update = \DB::update('update products set exclude_export = 1 where id IN(' . implode(',', $ids) . ')');
        }
        else
        {
            $update = \DB::update('update products set exclude_export = 0 where id IN(' . implode(',', $ids) . ')');
        }
        if ($update) {
            return response()->json(array(
                'status' => 'success'
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'message' => 'Some Error occurred while excluding from export'
            ));
        }
    }

    function getExpenseCategory(Request $request)
{
    $order_type_id=$request->get('order_type');
    $product_type_id=$request->get('product_type');
    $expense_category="";
    if(!empty($product_type_id))
    {
        $expense_category=\DB::table('expense_category_mapping')->where('order_type',$order_type_id)->where('product_type',$product_type_id)->pluck('mapped_expense_category');
    }
    else
    {
        $expense_category=\DB::table('expense_category_mapping')->where('order_type',$order_type_id)->pluck('mapped_expense_category');
    }
    return json_encode(array('expense_category'=>$expense_category));
}

    function getExpenseCategoryGroups(){
        $expense_category = \DB::select("SELECT expense_category_mapping.id,expense_category_mapping.mapped_expense_category,order_type.`order_type`,CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) as order_type
FROM expense_category_mapping
JOIN order_type ON order_type.id = expense_category_mapping.order_type
WHERE product_type IS NULL
GROUP BY mapped_expense_category");
        $items = [];
        foreach ($expense_category as $category) {
            $orderType = $category->order_type;
            $categoryId = $category->mapped_expense_category;
            if ($categoryId == 0) {
                /* $orderType = "N/A";
                 $categoryId = "";
                */
            } else {

                $items[] = [$categoryId, $orderType];
            }
        }
        return $items;
    }
    function getExpenseCategoryAjax(Request $request){

        $expense_category = \DB::select("SELECT expense_category_mapping.id,expense_category_mapping.mapped_expense_category,order_type.`order_type`,CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) as order_type
FROM expense_category_mapping
JOIN order_type ON order_type.id = expense_category_mapping.order_type
WHERE product_type IS NULL
GROUP BY mapped_expense_category");

        $items = ['<option value=""> -- Select  -- </option>'];
        foreach ($expense_category as $category){
            $orderType = $category->order_type;
            $categoryId = $category->mapped_expense_category;
            if ($categoryId == 0) {
                /* $orderType = "N/A";
                 $categoryId = "";*/
            } else {
                $items[] = '<option value="' . $categoryId . '"> ' . $orderType . ' </option>';
            }

        }
        $options = implode("",$items);
        echo $options;
    }

    public function postSetdefaultcategory(Request $request)
    {
        $id = $request->input('productId');
        $isdefaultexp = (bool)$request->input('isdefault');
        $searchProduct = Product::find($id);
        $products = $this->model->checkProducts($id);

        if ($isdefaultexp == 0 && count($products) > 1 && $searchProduct->is_default_expense_category == 1) {
            return response()->json(array(
                'status' => 'error',
                'message' => "This product variant currently defines the default expense category for this product in the Products API. Please mark a different variant of this product as the default expense category."
            ));
        } elseif (count($products) == 1) {
            $searchProduct->is_default_expense_category = $isdefaultexp;
            $searchProduct->save();

            //  $this->model->toggleDefaultExpenseCategory($isdefaultexp,$id);
        } else {
            $this->model->setDefaultExpenseCategory($id);
        }
    }

    public function getExpenseCategoryById($expense_category)
    {
        $sql = "SELECT CONCAT(mapped_expense_category,' ',GROUP_CONCAT(order_type.`order_type` ORDER BY order_type.`order_type` ASC SEPARATOR ' | ')) AS expense_category";
        $sql .= " FROM expense_category_mapping 
                  JOIN order_type
                    ON order_type.id = expense_category_mapping.order_type
                WHERE product_type IS NULL AND mapped_expense_category !=0 AND mapped_expense_category = $expense_category 
                GROUP BY mapped_expense_category";
        $result = \DB::select($sql);
        return $result;
    }
}
