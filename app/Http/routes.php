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
Route::get('ticketsetting','TicketsettingController@getSetting');
Route::get('order/submitorder/{SID?}', 'OrderController@getSubmitorder');
Route::post('order/init-export/{ID?}', 'OrderController@postInitExport');
Route::post('order/probe-export/{ID?}', 'OrderController@postProbeExport');
Route::get('/', 'UserController@getLogin');
Route::controller('home', 'HomeController');
Route::controller('/user', 'UserController');
Route::get('/user/jsconnect', 'UserController@jsconnect');
Route::get('/user/user-details/{id?}','Core\UsersController@getIndex');
Route::controller('urlauth', 'URLAuthController');
include('pageroutes.php');
include('moduleroutes.php');

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
		'feg/system/tasks'	=> 'Feg\System\TasksController',
		'feg/system/systememailreportmanager'	=> 'Feg\System\SystemEmailReportManagerController',        
	]);
    
    Route::get('feg/system/utils/{slug}', function($slug) {
        $app = app();
        $parameters = [];
        $paths = explode('/', $slug);
        $classRootPath = 'App\\Http\\Controllers\\Feg\\System\\Utils\\';
        $method = "index";
        do {
            $path = str_replace('-', '', ucwords(ucwords(implode('\\', $paths), '-'), '\\'));
            $classPath = $classRootPath . $path . 'Controller' ;            
            try {
                $controller = $app->make( $classPath );
            } 
            catch (Exception $ex) {
                array_unshift($parameters, array_pop($paths)) ;                
            }
            
        } while (empty($controller) && count($paths) > 0);
        
        if (!empty($parameters[0])) {
            $method = array_shift($parameters);
        }
        if (empty($controller)) {        
            $classPath = $classRootPath .'UtilsController';
            $controller = $app->make( $classPath );
            $parameters = [['params' => $parameters, 'slug' => $slug]];
        }

        try {
            $called  = $controller->callAction($method, $parameters);
        } catch (Exception $ex) {
            array_unshift($parameters, $method);
            var_dump("Error: " . $ex->getMessage());
            $method = "index";
            $called  = $controller->callAction($method, $parameters);
        }
        
        return $called;

    })->where('slug','.+');    

   Route::post('feg/system/utils/{slug}', function($slug) {
        $app = app();
        $parameters = [];
        $paths = explode('/', $slug);
        $classRootPath = 'App\\Http\\Controllers\\Feg\\System\\Utils\\';
        $method = "index";
        do {
            $path = str_replace('-', '', ucwords(ucwords(implode('\\', $paths), '-'), '\\'));
            $classPath = $classRootPath . $path . 'Controller' ;
            try {
                $controller = $app->make( $classPath );
            }
            catch (Exception $ex) {
                array_unshift($parameters, array_pop($paths)) ;
            }

        } while (empty($controller) && count($paths) > 0);

        if (!empty($parameters[0])) {
            $method = array_shift($parameters);
        }
        if (empty($controller)) {
            $classPath = $classRootPath .'UtilsController';
            $controller = $app->make( $classPath );
            $parameters = [['params' => $parameters, 'slug' => $slug]];
        }

        try {
            $called  = $controller->callAction($method, $parameters);
        } catch (Exception $ex) {
            array_unshift($parameters, $method);
            $method = "index";
            $called  = $controller->callAction($method, $parameters);
        }

        return $called;

    })->where('slug','.+');

});

Route::group(['middleware' => 'auth' , 'middleware'=>'sximoauth'], function()
{

	Route::controllers([
		'feg/menu'		=> 'Sximo\MenuController',
		'feg/config' 		=> 'Sximo\ConfigController',
		'feg/module' 		=> 'Sximo\ModuleController',
		'feg/tables'		=> 'Sximo\TablesController',
	]);



});




