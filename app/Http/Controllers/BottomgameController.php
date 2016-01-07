<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Bottomgame;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class BottomgameController extends TopgameController {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'bottomgame';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Bottomgame();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'bottomgame',
			'pageUrl'			=>  url('bottomgame'),
			'return' 			=> 	self::returnUrl()	
		);
		
			
				
	} 
}