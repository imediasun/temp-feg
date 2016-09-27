<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::group(array('before' => 'authorization'), function()
{
    Route::resource('fegapi', 'FegapiController');
});
Route::filter('authorization', function()
{

    if(is_null(Input::get('module')))
        return Response::json(array('status'=>'error','message'=>\Lang::get('restapi.ModuleEmpty')),400);

   /* if(!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW']))
    {
        return Response::json([
            'error' => true,
            'message' => 'Not authenticated',
            'code' => 401], 401
        );
    } else {

        $user = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];

        $auth = DB::table('tb_restapi')
            ->join('tb_users', 'tb_users.id', '=', 'tb_restapi.apiuser')
            ->where('apikey',"$key")->where("email","$user")->get();


        if(count($auth) <=0 )
        {
            return Response::json([
                'error' => true,
                'message' => 'Invalid authenticated params !',
                'code' => 401], 401
            );
        }  else {

            $row = $auth[0];
            $modules = explode(',',str_replace(" ","",$row->modules));
            if(!in_array(Input::get('module'), $modules))
            {
                return Response::json([
                    'error' => true,
                    'message' => 'You Dont Have Authorization Access!',
                    'code' => 401], 401
                );
            }

        }
    }*/

});
Route::get('submitservicerequest/{GID?}/{LID?}', 'SubmitservicerequestController@getIndex');

Route::get('arslan/{id}/{name?}/{subject?}', 'DemoController@getIndex');
Route::post('arslan/{id}/{name?}/{subject?}', 'DemoController@postIndex');

Route::get('order/submitorder/{SID?}', 'OrderController@getSubmitorder');
Route::get('/', 'UserController@getLogin');
Route::controller('home', 'HomeController');
Route::controller('/user', 'UserController');
include('pageroutes.php');
include('moduleroutes.php');
Route::get('sbticket/setting', 'SbticketController@getSetting');
Route::get('/restric',function(){

	return view('errors.blocked');

});

Route::controller('demo', 'DemoController');
//Route::resource('sximoapi', 'SximoapiController');
Route::group(['middleware' => 'auth'], function()
{

	Route::get('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::post('core/elfinder', 'Core\ElfinderController@getIndex');
	Route::controller('/dashboard', 'DashboardController');
	Route::controller('/cron', 'CronController');
	Route::controllers([
		'core/users'		=> 'Core\UsersController',
		'notification'		=> 'NotificationController',
		'core/logs'			=> 'Core\LogsController',
		'core/pages' 		=> 'Core\PagesController',
		'core/groups' 		=> 'Core\GroupsController',
		'core/template' 	=> 'Core\TemplateController',
	]);

});

Route::group(['middleware' => 'auth' , 'middleware'=>'sximoauth'], function()
{

	Route::controllers([
		'feg/menu'		=> 'Sximo\MenuController',
		'feg/config' 		=> 'Sximo\ConfigController',
		'feg/module' 		=> 'Sximo\ModuleController',
		'feg/tables'		=> 'Sximo\TablesController'
	]);



});






