<?php namespace App\Http\Controllers;
use App\Models\Sbticket;
use App\Http\Controllers\controller;
use App\Models\Ticketsetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class TicketsettingController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'ticketsetting';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Ticketsetting();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'ticketsetting',
			'pageUrl'			=>  url('ticketsetting'),
			'return'	=> self::returnUrl()

		);
	}


	public function getSetting()
	{
		$ticket_setting = \DB::select("Select * FROM sbticket_setting");

		$individuals = \DB::select("Select id,first_name,last_name FROM users");
		$roles = \DB::select("Select group_id,name FROM tb_groups");

		$this->data['ticket_setting']		= $ticket_setting[0];
		$this->data['roles']		= $roles;
		$this->data['individuals']		= $individuals;
		$this->data['access']		= $this->access;
		return view('ticketsetting.setting',$this->data);
	}


}