<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Input, Redirect;
use Route;

class URLAuthController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        
    }
    public function postAccess(Request $request) {
        $isPage = $request->input('isPage') == 1;
        $url = $request->input('url');
        
        $ret = [ 'status' => 'error','message' => \Lang::get('core.note_restric')];
        $isAuth = $isPage ? $this->isPageAccessible($url) : $this->isModuleAccessible($url);
        if ($isAuth) {
            $ret['status'] = 'success';
            $ret['message'] = '';
        }
        
        return response()->json($ret);
        
    }
    protected function isPageAccessible($page) {
        $pageName = str_replace(url().'/', '', $page);
        $isAccessible = false;
        if ($pageName) {
            $content = \DB::table('tb_pages')->where('alias', '=', $pageName)
                    ->where('status', '=', 'enable')->first();
            if (!empty($content)) {
                if ($content->access != '') {
                    $access = json_decode($content->access, true);
                } else {
                    $access = array();
                }
                $group_id = \Session::get('gid');
                $isAccessible = $content->allow_guest == 1 || 
                        (isset($access[$group_id]) && $access[$group_id]);                
            }
        }       
        return $isAccessible;        
    }
    
    protected function isModuleAccessible($module) {
        $uri = str_replace(url(), '', $module);
        $route = Request::create($uri, 'GET');
        $action = Route::getRoutes()->match( $route )->getActionName();
        $isHomeController = stripos($action, 'HomeController@index') !== false;
        if ($isHomeController) {
            return $this->isPageAccessible($module);
        }
        else {
            $controllerName = preg_replace('/\@.+?$/', '', $action);
            $controller = new $controllerName();
            $moduleName = $controller->module;
            $access = $controller->access;            
            return $access['is_view'];
        }
        
    }    

}
