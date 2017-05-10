<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\Core\Groups;
use App\Models\Feg\System\Options;
use App\Models\Sximo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Models\Sximo\Module;

class DashboardController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data = array(
            'pageModule' => 'dashboard',
        );
    }

    public function getIndex(Request $request)
    {
        if(Auth::user() && Auth::user()->redirect_link)
        {

            /*$client = new Client();
            $res = $client->request('GET', url().'/core/users/check-access?module='.$pageOrModule);
            $result = $res->getBody();
            $result = json_decode($result,true);
            $request = new \GuzzleHttp\Psr7\Request('GET', url());
            $promise = $client->sendAsync($request)->then(function ($response) {
                echo 'I completed! ' . $response->getBody();
            });
            $promise->wait();
            $request = new \GuzzleHttp\Psr7\Request('GET', url().'/core/users/check-access?module='.$pageOrModule);
            $promise = $client->sendAsync($request)->then(function ($response) {
                echo 'I completed! ' . $response->getBody();
                dd($response->getBody());
            });
            $promise->wait();*/
            if(Auth::user()->redirect_link != "dashboard" && self::accessCheck(Auth::user()->redirect_link))
            {
                return redirect(Auth::user()->redirect_link);
            }
            else{
                return redirect('user/profile');
            }

        }
        $group = Auth::user()?Auth::user()->group_id:0;
        $redirect = Groups::find($group)?Groups::find($group)->redirect_link:'';
        if(!empty($redirect) && $redirect != "dashboard" && self::accessCheck($redirect))
        {
            return redirect($redirect);
        }
        //require_once('setting.php');
        /*if(CNF_REDIRECTLINK)
        {
            return redirect(CNF_REDIRECTLINK);
        }*/

        $redirect = Options::where('option_name','CNF_REDIRECLINK')->pluck('option_value');
        if(!empty($redirect) && $redirect != "dashboard" && self::accessCheck($redirect))
        {
            return redirect($redirect);
        }
        else{
            return redirect('user/profile');
        }

        /* connect to gmail */
        $this->data['online_users'] = \DB::table('users')->orderBy('last_activity', 'desc')->limit(10)->get();
        return view('dashboard.index', $this->data);
    }

    public static function accessCheck($redirect)
    {
        $pageOrModule = explode('/',Auth::user()->redirect_link);
        $pageOrModule = $pageOrModule[count($pageOrModule)-1];
        $moduleId = Module::where('module_name', $pageOrModule)->pluck('module_id');
        if (!empty($moduleId)) {
            $row = \DB::table('tb_groups_access')->where('module_id', '=', $moduleId)
                ->where('group_id', '=', \Session::get('gid'))
                ->get();

            if (count($row) >= 1) {
                $row = $row[0];
                if ($row->access_data != '') {
                    $data = json_decode($row->access_data, true);
                } else {
                    $data = array();
                }
                return $data;
            } else {
                return false;
            }
        }
        else {
            $row = \DB::table('tb_pages')->where('alias', '=', $pageOrModule)
                ->first();

            if (!empty($row)) {
                $data = ['is_view' => 0];
                if ($row->access != '') {
                    $accsss = json_decode($row->access, true);
                    $data['is_view'] = isset($accsss[\Session::get('gid')])? $accsss[\Session::get('gid')] : 0;
                }
                return $data;
            }
            else {
                return false;
            }
        }
    }

}
