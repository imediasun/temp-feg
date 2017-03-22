<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\Core\Groups;
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
            return redirect(Auth::user()->redirect_link);
        }
        $group = Auth::user()?Auth::user()->group_id:0;
        $redirect = Groups::find($group)?Groups::find($group)->redirect_link:'';
        if (!empty($redirect)) {
            return redirect($redirect);
        }
        require_once('setting.php');
        if(CNF_REDIRECTLINK)
        {
            return redirect(CNF_REDIRECTLINK);
        }
        /* connect to gmail */
        $this->data['online_users'] = \DB::table('users')->orderBy('last_activity', 'desc')->limit(10)->get();
        return view('dashboard.index', $this->data);
    }

}
