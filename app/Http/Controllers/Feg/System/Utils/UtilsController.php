<?php namespace App\Http\Controllers\Feg\System\Utils;

use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Session, Auth, DB; 
use FEGHelp;
use FEGFormat;

class UtilsController extends Controller
{

	protected $layout = "layouts.app";
	protected $data = array();	
	
	public function __construct() 
	{
		parent::__construct();
        
		$this->data = array(
			'return' 			=> 	self::returnUrl()
		);
	}

	public function index($params = null)
	{   
        //$this->data['request'] = Request::all();
        //$this->data['params'] = $params;
		return view('feg.system.utils.index',$this->data);
	}

}