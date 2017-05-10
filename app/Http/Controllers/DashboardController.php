<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\Core\Groups;
use App\Models\Feg\System\Options;
use App\Models\Sximo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

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
            $pageOrModule = explode('/',Auth::user()->redirect_link);
            $pageOrModule = $pageOrModule[count($pageOrModule)-1];
            $client = new Client();
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
            $promise->wait();
            return redirect(Auth::user()->redirect_link == "dashboard"?'user/profile':Auth::user()->redirect_link);
        }
        $group = Auth::user()?Auth::user()->group_id:0;
        $redirect = Groups::find($group)?Groups::find($group)->redirect_link:'';

        if (!empty($redirect) && $redirect != "dashboard") {

            return redirect($redirect);
        }
        //require_once('setting.php');
        /*if(CNF_REDIRECTLINK)
        {
            return redirect(CNF_REDIRECTLINK);
        }*/

        $redirect = Options::where('option_name','CNF_REDIRECLINK')->pluck('option_value');

        return redirect($redirect == "dashboard"?'user/profile': $redirect);
        /* connect to gmail */
        $this->data['online_users'] = \DB::table('users')->orderBy('last_activity', 'desc')->limit(10)->get();
        return view('dashboard.index', $this->data);
    }

}
