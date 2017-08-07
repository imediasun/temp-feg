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
        $this->data['data'] = [$params];
		return view('feg.system.utils.modules.index',$this->data);
	}
	public function loadlocations($params = null)
	{
        $locationControllerInstance = new \App\Http\Controllers\LocationController;
        $request = new \Illuminate\Http\Request;
        var_dump("

            Example showing how to run a controller function (that does not return a view) from a controller instance.

            \$locationControllerInstance = new \App\Http\Controllers\LocationController;
            \$locationControllerInstance->getSearchFilterQuery(\"id:like:2007|active:equal:1|\")

            Result returned: \"".$locationControllerInstance->getSearchFilterQuery("id:like:2007|active:equal:1|")."\"

                ");


        var_dump("

            Example showing how to run a function that returns a view from a controller instance (view showin below)

            \$locationControllerInstance = new \App\Http\Controllers\LocationController;
            \$request = new \Illuminate\Http\Request;
            \$locationControllerInstance->getIndex(\$request);

            ");
        return $locationControllerInstance->getIndex($request);
	}

	public function formatters($params = null)
	{
		return view('feg.system.utils.modules.formatters',$this->data);
	}
	public function hyperlinks($params = null)
	{
		return view('feg.system.utils.modules.hyperlinks',$this->data);
	}
	public function downgame($params = null)
	{
        $this->data['data'] = \App\Library\FEG\Utils\Fix\Tools::gameIdAnalyzer($params);
        $this->data['title'] = "Down Game Details";
		return view('feg.system.utils.modules.show',$this->data);
	}
}