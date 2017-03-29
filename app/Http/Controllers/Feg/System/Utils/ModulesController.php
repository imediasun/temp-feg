<?php namespace App\Http\Controllers\Feg\System\Utils;

use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, Session, Auth, DB; 
use FEGHelp;
use FEGFormat;

class ModulesController extends Controller
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
		return view('feg.system.utils.modules.index',$this->data);
	}

	public function formatters($params = null)
	{
		return view('feg.system.utils.modules.formatters',$this->data);
	}
	public function hyperlinks($params = null)
	{
		return view('feg.system.utils.modules.hyperlinks',$this->data);
	}

}