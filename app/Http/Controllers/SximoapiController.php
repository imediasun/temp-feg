<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Restapi;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
class SximoapiController extends Controller {

	public function __construct() 
	{
		parent::__construct();
					
	}
	public function index()
	{		
		$class 	= ucwords(Input::get('module'));
        if(!empty($class)) {
            if($class == "Users")
            {
                $class1="App\\Models\\core\\".$class;
            }
            else
            {
                $class1 = "App\\Models\\" . $class;
            }
            $config = $class1::makeInfo($class);
            $tables = $config['config']['grid'];
            $page = (!is_null(Input::get('page')) or Input::get('page') != 0) ? Input::get('page') : 1;
            $param = array('page' => $page, 'sort' => '', 'order' => 'asc', 'limit' => '');
            $limit=Input::get('limit');
            $sort=Input::get('order');
            $order=Input::get('sort');
            if (!is_null($limit) or $limit != 0) $param['limit'] = $limit;
            if (!is_null($order)) $param['order'] =$order ;
            if (!is_null($sort)) $param['sort'] = $sort;
            $results = $class1::getRows($param);
            $json = array();
            foreach ($results['rows'] as $row) {
                $rows = array();
                foreach ($tables as $table) {
                    $conn = (isset($table['conn']) ? $table['conn'] : array());
                    $rows[$table['field']] = $row->$table['field'];
                }
                $json[] = $rows;
            }
            $jsonData = array(
                'total' => $results['total'],
                'rows' => $json,
                'control' => $param,
                'key' => $config['key']
            );
            $option=Input::get('option');
            if (!is_null($option) && $option == 'true') {
                $label = array();
                foreach ($tables as $table) {
                    $label[] = $table['label'];
                }
                $field = array();
                foreach ($tables as $table) {
                    $field[] = $table['field'];
                }
                $jsonData['option'] = array(
                    'label' => $label,
                    'field' => $field
                );
            }
            return \Response::json($jsonData, 200);
        }
        else{
            return \Response::json(array('Status'=>'Error','Message'=>\Lang::get('restapi.EmptyModule')));
        }
	}
	public function show( $id )
	{	$class 	= ucwords(Input::get('module'));
        if($class == "Users")
        {
            $class1="App\\Models\\core\\".$class;
        }
        else
        {
            $class1 = "App\\Models\\" . $class;
        }
	  	$config	 		= 	$class1::makeInfo( $class );
	  	$tables 		=	$config['config']['grid'];			
		$jsonData 			= 	$class1::getRow( $id );
        if(!empty($jsonData)) {
            return \Response::json($jsonData, 200);
        }
        else
        {
            return \Response::json(array('Status'=>\Lang::get('restapi.StatusError'),"Message"=>\Lang::get('restapi.NothingFound')));
        }
	}
	public function store(  )
	{
        $class 	= ucwords(Input::get('module'));
        $class1 = "App\\Models\\".$class;

		$this->info		= 	$class1::makeInfo( $class );

		$data 			= $this->validatePost($this->info['table']);
		unset($data['entry_by']);

		$id = $class1::insertRow($data ,NULL);
        if($id)
        {
            return \Response::json(array('Status'=> \Lang::get('restapi.StatusSuccess'),'Message' => \Lang::get('restapi.StroredSuccess')),200);
        }
        else
        {
            return \Response::json(array('Status'=> \Lang::get('restapi.StatusError'),'Message'=> \Lang::get('restapi.StoreError')));
        }

	}
	public function update( $id )
	{
        $class 			= ucwords(Input::get('module'));
        $class1 = "App\\Models\\".$class;
		$this->info		= 	$class1::makeInfo( $class );
		$data 			= $this->validatePost($this->info['table']);
		unset($data['entry_by']);
		$id 			= $class1::insertRow($data , $id );
        return \Response::json(array('Status'=>\Lang::get('restapi.StatusSuccess'),'Message'=> \Lang::get('restapi.UpdatedSuccess')),200);
	}
	public function destroy( $id )
	{
        $class 	= ucwords(Input::get('module'));
        $class1 = "App\\Models\\".$class;
		$results   =	$class1::find($id);
		if(is_null($results))
		{
				return \Response::json(array("Status"=>\Lang::get('restapi.StatusError'),"Message"=>\Lang::get('restapi.NothingFound')),500);
		}
		$success	=	$results->delete();
		if(!$success)
		{
			return \Response::json(array("Status"=>\Lang::get('restapi.StatusError'),"Message"=>\Lang::get('restapi.DeleteError')),500);
		}
		 
		return \Response::json(array("Status"=>\Lang::get('restapi.StatusSuccess'),"Message"=>\Lang::get('restapi.DeleteSuccess')),200);
    }
}