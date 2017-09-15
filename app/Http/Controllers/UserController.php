<?php namespace App\Http\Controllers;

use App\Models\UserLocations;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\Core\Groups;
use App\Models\Addtocart;
use App\User;
use Socialize;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Library\FEG\System\FEGSystemHelper;
use Validator, Input, Redirect;
use App\Models\Location;
use DB;

class UserController extends Controller
{


    protected $layout = "layouts.main";

    public function __construct()
    {
        parent::__construct();
        $this->addToCartModel = new Addtocart();

    }
    /*
    public function readCsv()
    {
        echo '<br>';
        echo '---------------Script Started --------------';
        $file = fopen("user_with_location_client.csv","r");

        while(! feof($file))
        {
            $records = fgetcsv($file);
            $locations = explode(',',$records[1]);



            foreach ($locations as $location)
            {
                $location = trim($location);
                if(!empty($location))
                {
                    $loc = Location::where('location_name' , $location)->first();
                    if(is_object($loc))
                    {
                        $user = User::findOrFail($records[0]);
                        if(!is_object($user)){
                            echo "User Not Found";
                        }
                        $locUser = DB::table('user_locations')->where('user_id',$records[0])->where('location_id',$loc->id)->first();
                        if(!is_object($locUser))
                        {
                            echo "Insert {$loc->id} for {$user->id} <br>";

                            $locUser = 'Relation Not Found';
                        }
                    }
                    else
                    {

                        $loc = 'Location Not Found {'.$location.'}';
                        $locUser = 'Relation Not Found';
                    }
                    echo "<pre>";
                    echo ' user id : ' . $records[0] .'---';
                    echo is_object($loc) ? 'Location Found {'.$location.'} with id ' . $loc->id : $loc;
                    echo "<br>";
                    echo "<hr>";
                    echo "<br>";
                    echo is_object($locUser) ? 'Relation Found with id ' . $locUser->id : $locUser;
                    echo "<hr>";
                    echo "</pre>";
                }



            }
        }

        fclose($file);
        echo '<br>';
        echo "----------------Script Ended----------------";
    }*/

    public function readCsv()
    {
        echo '<br>';
        echo '---------------Script Started --------------';
        $file = fopen("user_with_location_client.csv","r");

        while(! feof($file))
        {
            $records = fgetcsv($file);
            $locations = explode(',',$records[1]);



            foreach ($locations as $location)
            {
                $location = trim($location);
                if(!empty($location))
                {
                    $loc = Location::where('location_name' , $location)->first();
                    if(is_object($loc))
                    {
                        $user = User::findOrFail($records[0]);
                        if(!is_object($user)){
                            echo "User Not Found";
                        }
                        $locUser = DB::table('user_locations')->where('user_id',$records[0])->where('location_id',$loc->id)->first();
                        if(!is_object($locUser))
                        {
                            echo "Insert {$loc->id} for {$user->id} <br>";
                            /*
                            DB::table('user_locations')->insert(array(
                                    'user_id' => $user->id,
                                    'location_id' => $loc->id
                            ));*/
                            $locUser = 'Relation Not Found';
                        }
                    }
                    else
                    {

                        $loc = 'Location Not Found {'.$location.'}';
                        $locUser = 'Relation Not Found';
                    }
                    echo "<pre>";
                    echo ' user id : ' . $records[0] .'---';
                    echo is_object($loc) ? 'Location Found {'.$location.'} with id ' . $loc->id : $loc;
                    echo "<br>";
                    echo "<hr>";
                    echo "<br>";
                    echo is_object($locUser) ? 'Relation Found with id ' . $locUser->id : $locUser;
                    echo "<hr>";
                    echo "</pre>";
                }


               /* echo '--- location id : '. is_object($loc) ?print_r($loc->id):' No Data ' ;
                echo '--- user id : ' . $records[0] ;

                echo '--- Relation id : '. is_object($locUser) ?print_r($locUser):' No Relations ' . "<br>";*/
            }
        }

        fclose($file);
        echo '<br>';
        echo "----------------Script Ended----------------";
    }
    public function getGoogle()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $email = $user->email;
        $userCheck = User::where('email', '=', $user->email)->first();
        if ($userCheck) {
            \Auth::loginUsingId($userCheck->id);
            $row = $userCheck;
            $group = Groups::find($row->group_id);
            //CNF_REDIRECTLINK;
            if ($row->active == '0') {
                \Auth::logout();
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is not active'));

            } else if ($row->active == '2') {
                // BLocked users
                \Auth::logout();
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'));
            } else if ($row->banned == 1) {
                // BLocked users
                \Auth::logout();
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'));
            } else {
                \DB::table('users')->where('id', '=', $row->id)->update(array('last_login' => date("Y-m/d H:i:s"),'oauth_token'=>$user->token,'oauth_email'=>$user->email));
                if($user->refreshToken != '' && $user->refreshToken != null)
                {
                    \DB::table('users')->where('id', '=', $row->id)->update(array('refresh_token'=>$user->refreshToken));
                }
                \Session::put('uid', $row->id);
                \Session::put('gid', $row->group_id);
                \Session::put('eid', $row->email);
                \Session::put('ll', $row->last_login);
                \Session::put('fid', $row->first_name . ' ' . $row->last_name);
                \Session::put('user_name', $row->username);
                \Session::put('ufname', $row->first_name);
                \Session::put('ulname', $row->last_name);
                \Session::put('company_id', $row->company_id);
                $user_locations = \SiteHelpers::getLocationDetails($row->id);
                if (empty($user_locations)) {
                    $user_locations = [];
                }
                $user_location_ids = \SiteHelpers::getIdsFromLocationDetails($user_locations);
                $has_all_locations = $row->has_all_locations;
                \Session::put('user_has_all_locations', $has_all_locations);                    
                \Session::put('user_locations', $user_locations);
                \Session::put('selected_location', isset($user_locations[0]->id) ? $user_locations[0]->id: null);
                \Session::put('selected_location_name', isset($user_locations[0]->location_name_short) ? $user_locations[0]->location_name_short : null);
                \Session::put('user_location_ids', $user_location_ids);
                \Session::put('get_locations_by_region', $row->get_locations_by_region);
                \Session::put('email_2', $row->email_2);
                \Session::put('primary_phone', $row->primary_phone);
                \Session::put('secondary_phone', $row->secondary_phone);
                \Session::put('street', $row->street);
                \Session::put('city', $row->city);
                \Session::put('state', $row->state);
                \Session::put('zip', $row->zip);
                \Session::put('reg_id', $row->reg_id);
                \Session::put('restricted_mgr_email', $row->restricted_mgr_email);
                \Session::put('restricted_user_email', $row->restricted_user_email);
                $total_cart = $this->addToCartModel->totallyRecordInCart();
                \Session::put('total_cart', $total_cart[0]->total);
                \Session::put('lang', 'en');

                if (!empty($row->redirect_link)) {
                    return Redirect::to($row->redirect_link == 'dashboard'?'user/profile':$row->redirect_link);
                } elseif (!empty($group->redirect_link)) {
                    return Redirect::to($group->redirect_link == 'dashboard'?'user/profile':$group->redirect_link);
                } else {
                    return Redirect::to(CNF_REDIRECTLINK== 'dashboard'?'user/profile':CNF_REDIRECTLINK);
                }
                if (CNF_FRONT == 'false') {
                    return Redirect::to('dashboard');
                } else {
                    return Redirect::to('');
                }


            }

        } else {
            return Redirect::to('/')
                ->with('message', \SiteHelpers::alert('error', 'Sorry, Your email ' . $email . ' not found'));
        }

    }

    public function getRegister()
    {
        return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Sorry, Registration is disabled. Please contact administrator'));
        /*
        if (CNF_REGIST == 'false') :
            if (\Auth::check()):
                return Redirect::to('')->with('message', \SiteHelpers::alert('success', 'Youre already login'));
            else:
                return Redirect::to('/');
            endif;

        else :

            return view('user.register');
        endif;*/


    }

    public function postCreate(Request $request)
    {
        return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Sorry, Registration is disabled. Please contact administrator'));
        /*
        $rules = array(
            'firstname' => 'required|alpha_num|min:2',
            'lastname' => 'required|alpha_num|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,12|confirmed',
            'password_confirmation' => 'required|between:6,12'
        );
        if (CNF_RECAPTCHA == 'true') $rules['recaptcha_response_field'] = 'required|recaptcha';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $code = rand(10000, 10000000);

            $authen = new User;
            $authen->first_name = $request->input('firstname');
            $authen->last_name = $request->input('lastname');
            $authen->email = trim($request->input('email'));
            $authen->remember_token = $code;
            $authen->group_id = 1;
            $authen->approved = 1;
            $authen->password = \Hash::make($request->input('password'));

            if (CNF_ACTIVATION != 'auto') {
                $authen->active = '1';
            } else {
                $authen->active = '0';
            }
            $authen->save();

            $data = array(
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'code' => $code

            );
            if (CNF_ACTIVATION == 'confirmation') {

                $to = $request->input('email');
                $subject = "[ " . CNF_APPNAME . " ] REGISTRATION ";
                $message = view('user.emails.registration', $data);
                //$headers = 'MIME-Version: 1.0' . "\r\n";
                //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                //$headers .= 'From: ' . CNF_APPNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
                //mail($to, $subject, $message, $headers);
                if(!empty($to)){
                    FEGSystemHelper::sendSystemEmail(array(
                        'to' => $to,
                        'subject' => $subject,
                        'message' => $message,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'from' => CNF_EMAIL,
                        //'cc' => $cc,
                        //'bcc' => $bcc,
                        'configName' => 'USER CREATE EMAIL'
                    ));
                }

                $message = "Thanks for registering! . Please check your inbox and follow activation link";

            } elseif (CNF_ACTIVATION == 'manual') {
                $message = "Thanks for registering! . We will validate you account before your account active";
            } else {
                $message = "Thanks for registering! . Your account is active now ";

            }


            return Redirect::to('/')->with('message', \SiteHelpers::alert('success', $message));
        } else {
            return Redirect::to('user/register')->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }*/
    }

    public function getActivation(Request $request)
    {
        $num = $request->input('code');
        if ($num == '')
            return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Invalid Code Activation!'));

        $user = User::where('activation', '=', $num)->get();
        if (count($user) >= 1) {
            \DB::table('users')->where('activation', $num)->update(array('active' => 1, 'activation' => ''));
            return Redirect::to('/')->with('message', \SiteHelpers::alert('success', 'Your account is active now!'));

        } else {
            return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Invalid Code Activation!'));
        }


    }

    public function getLogin()
    {

        if (\Auth::check()) {
            return Redirect::to('dashboard')->with('message', \SiteHelpers::alert('success', 'You are already login'));

        } else {
            $this->data['socialize'] = config('services');
            return View('user.login', $this->data);

        }
    }

    public function postSignin(Request $request)
    {


        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
        if (CNF_RECAPTCHA == 'true') $rules['captcha'] = 'required|captcha';
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $remember = (!is_null($request->get('remember')) ? 'true' : 'false');

            if (\Auth::attempt(array('email' => $request->input('email'), 'password' => $request->input('password')), $remember)) {
                if (\Auth::check()) {
                    $row = User::find(\Auth::user()->id);
                    $group = Groups::find($row->group_id);
                    //CNF_REDIRECTLINK;

                    //print_r($group);exit;
                    if ($row->active == '0') {
                        // inactive
                        \Auth::logout();
                        return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is not active'))->with('active_tab',1);

                    } else if ($row->active == '2') {
                        // BLocked users
                        \Auth::logout();
                        return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'))->with('active_tab',1);
                    } else if ($row->banned == 1) {
                        // BLocked users
                        \Auth::logout();
                        return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'))->with('active_tab',1);
                    } else {
                        \DB::table('users')->where('id', '=', $row->id)->update(array('last_login' => date("Y-m/d H:i:s")));
                        \Session::put('uid', $row->id);
                        \Session::put('gid', $row->group_id);
                        \Session::put('eid', $row->email);
                        \Session::put('ll', $row->last_login);
                        \Session::put('fid', $row->first_name . ' ' . $row->last_name);
                        \Session::put('user_name', $row->username);
                        \Session::put('ufname', $row->first_name);
                        \Session::put('ulname', $row->last_name);
                        \Session::put('company_id', $row->company_id);

                        $has_all_locations = $row->has_all_locations;
                        \Session::put('user_has_all_locations', $has_all_locations);

                        /*
                        $user_locations = \SiteHelpers::getLocationDetails($row->id);
                        $user_location_ids = \SiteHelpers::getIdsFromLocationDetails($user_locations);
                        if (!empty($user_locations)) {
                            \Session::put('user_locations', $user_locations);
                            \Session::put('selected_location', $user_locations[0]->id);
                            \Session::put('selected_location_name', $user_locations[0]->location_name_short);
                            \Session::put('user_location_ids', $user_location_ids);
                        } else {
                            \Session::put('selected_location', 0);
                        }*/
                        \SiteHelpers::refreshUserLocations($row->id);
                        \Session::put('get_locations_by_region', $row->get_locations_by_region);
                        \Session::put('email_2', $row->email_2);
                        \Session::put('primary_phone', $row->primary_phone);
                        \Session::put('secondary_phone', $row->secondary_phone);
                        \Session::put('street', $row->street);
                        \Session::put('city', $row->city);
                        \Session::put('state', $row->state);
                        \Session::put('zip', $row->zip);
                        \Session::put('reg_id', $row->reg_id);
                        \Session::put('restricted_mgr_email', $row->restricted_mgr_email);
                        \Session::put('restricted_user_email', $row->restricted_user_email);
                        $total_cart = $this->addToCartModel->totallyRecordInCart();
                        \Session::put('total_cart', $total_cart[0]->total);
                        if (!is_null($request->input('language'))) {
                            \Session::put('lang', $request->input('language'));
                        } else {
                            \Session::put('lang', 'en');
                        }
                        if (!empty($row->redirect_link)) {
                            return Redirect::to($row->redirect_link == 'dashboard'?'user/profile':$row->redirect_link);
                        } elseif (!empty($group->redirect_link)) {
                            return Redirect::to($group->redirect_link == 'dashboard'?'user/profile':$group->redirect_link);
                        } else {
                            return Redirect::to(CNF_REDIRECTLINK== 'dashboard'?'user/profile':CNF_REDIRECTLINK);
                        }

                        if (CNF_FRONT == 'false') :
                            return Redirect::to('dashboard');
                        else :
                            return Redirect::to('');
                        endif;

                    }

                }

            } else {
                return Redirect::to('/')
                    ->with('message', \SiteHelpers::alert('error', 'Your username/password combination was incorrect'))
                    ->with('active_tab',1)
                    ->withInput();
            }
        } else {

            return Redirect::to('/')
                ->with('message', \SiteHelpers::alert('error', 'The following  errors occurred'))
                ->with('active_tab',1)
                ->withErrors($validator)->withInput();
        }
    }

    public function getPlay()
    {
        $row = User::find(4);
        \Auth::loginUsingId(4);
        \DB::table('users')->where('id', '=', $row->id)->update(array('last_login' => date("Y-m-d H:i:s")));
        \Session::put('uid', $row->id);
        \Session::put('gid', $row->group_id);
        \Session::put('eid', $row->email);
        \Session::put('ll', $row->last_login);
        \Session::put('fid', $row->first_name . ' ' . $row->last_name);
        \Session::put('user_name', $row->username);
        \Session::put('ufname', $row->first_name);
        \Session::put('ulname', $row->last_name);
        \Session::put('company_id', $row->company_id);
        $user_locations = \SiteHelpers::getLocationDetails($row->id);
        $user_location_ids = \SiteHelpers::getIdsFromLocationDetails($user_locations);
        $has_all_locations = $row->has_all_locations;
        \Session::put('user_has_all_locations', $has_all_locations);          
        if (!empty($user_locations)) {
            \Session::put('user_locations', $user_locations);
            \Session::put('selected_location', $user_locations[0]->id);
            \Session::put('selected_location_name', $user_locations[0]->location_name_short);
            \Session::put('user_location_ids', $user_location_ids);
        }
        \Session::put('get_locations_by_region', $row->get_locations_by_region);
        \Session::put('email_2', $row->email_2);
        \Session::put('primary_phone', $row->primary_phone);
        \Session::put('secondary_phone', $row->secondary_phone);
        \Session::put('street', $row->street);
        \Session::put('city', $row->city);
        \Session::put('state', $row->state);
        \Session::put('zip', $row->zip);
        \Session::put('reg_id', $row->reg_id);
        \Session::put('restricted_mgr_email', $row->restricted_mgr_email);
        \Session::put('restricted_user_email', $row->restricted_user_email);
        return Redirect::to($row->redirect_link == 'dashboard'?'user/profile':$row->redirect_link);
    }

    public function getData()
    {
        echo \Auth::user()->id;
        exit;
    }

    public function getProfile()
    {

        if (!\Auth::check()) return redirect('/');


        $info = User::find(\Auth::user()->id);
        $this->data = array(
            'pageTitle' => 'My Profile',
            'pageNote' => 'View Detail My Info',
            'info' => $info,
        );
        return view('user.profile', $this->data);
    }

    public function getJsconnect(Request $request) {

        $inputs = $request->all();

        $callback = @$inputs['callback'];
        $signature = @$inputs['signature'];
        $client_id = @$inputs['client_id'];
        $timestamp = @$inputs['timestamp'];

        $jsonpData = [
            'name' => '',
            'photourl' => '',
        ];

        if (\Auth::check())  {

            $jsonpData = [
                'client_id' => $client_id,
                'signature' => $signature,
                'timestamp' => $timestamp,
                'uniqueid' => \Session::get('uid'),
                'name' => \Session::get('fid'),
                'email' => \Session::get('eid'),
                'photourl' => ''//\FEGHelp::getUserAvatarUrl(\Session::get('uid'))
            ];
        }

        $jsonp = implode('', [$callback, '(', json_encode($jsonpData), ");"]);

        return $jsonp;
    }

    public function postSaveprofile(Request $request)
    {
        if (!\Auth::check()) return Redirect::to('/');
        $rules = array(
            'first_name' => 'required|alpha_num|min:2',
            'last_name' => 'required|alpha_num|min:2',
        );

        if ($request->input('email') != \Session::get('eid')) {
            $rules['email'] = 'required|email|unique:users';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {


            if (!is_null(Input::file('avatar'))) {
                $file = $request->file('avatar');
                $destinationPath = './uploads/users/';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename = \Session::get('uid') . '.' . $extension;
                $uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $data['avatar'] = $newfilename;
                }

            }

            $user = User::find(\Session::get('uid'));

            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            if (isset($data['avatar'])) $user->avatar = $newfilename;
            $user->save();

            return Redirect::to('user/profile')->with('messagetext', 'Profile has been saved!')->with('msgstatus', 'success');
        } else {
            return Redirect::to('user/profile')->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }

    }

    public function postSavepassword(Request $request)
    {
        $rules = array(
            'password' => 'required|between:6,12',
            'password_confirmation' => 'required|between:6,12'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user = User::find(\Session::get('uid'));
            $user->password = \Hash::make($request->input('password'));
            $user->save();

            return Redirect::to('user/profile')->with('messagetext', 'Password has been saved!')->with('msgstatus', 'success');
        } else {
            return Redirect::to('user/profile')->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }

    }

    public function getReminder()
    {

        return view('user.remind');
    }
    public function getForgetPassword()
    {
        return view('user.forget');
    }
    public function postRequest(Request $request)
    {
        $rules = array(
            'credit_email' => 'required|email'
        );

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {

            $user = User::where('email', '=', $request->input('credit_email'));
            if ($user->count() >= 1) {


                $user = $user->get();
                $user = $user[0];
                $data = array('token' => $request->input('_token'));
                $to = ['to'=>$request->input('credit_email')];

                $subject = "[ " . CNF_APPNAME . " ] REQUEST PASSWORD RESET ";
                $message = view('user.emails.auth.reminder', $data);
                FEGSystemHelper::sendSystemEmail(array_merge($to, array(
                    'subject' => $subject,
                    'message' => $message,
                    'isTest' => false,
                    'from' => CNF_EMAIL,
                    'configName' => 'FORGET PASSWORD EMAIL'
                )));


                $affectedRows = User::where('email', '=', $user->email)
                    ->update(array('reminder' => $request->input('_token')));

                return Redirect::to('/')->with('message', \SiteHelpers::alert('success', 'Please check your email'));

            } else {
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Cant find email address'));
            }

        } else {
            return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }
    }

    public function getReset(Request $request)
    {
        $token = isset($_GET['token']) ? $request->get('token') : "";
        $id = isset($_GET['id']) ? $request->get('id') : "";
        if (\Auth::check()) return Redirect::to('dashboard');
        if ($token != "") {
            $user = User::where('reminder', '=', $token);
            if ($user->count() >= 1) {
                $data = array('verCode' => $token);
                return view('user.remind', $data);
            } else {
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Cant find your reset code'));
            }

        } elseif ($id != "") {
            $user = User::where('id', '=', $id);
            if ($user->count() >= 1) {
                $data = array('verCode' => $id);
                return view('user.remind', $data);
            } else {
                return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Cant find your email'));
            }
        }
    }

    public function postDoreset(Request $request, $token = '')
    {
        $rules = array(
            'password' => 'required|alpha_num|between:6,12|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,12'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            // strlen($token)
            if (strlen($token) < 10) {
                $user = User::where('id', '=', $token);
            } else {
                $user = User::where('reminder', '=', $token);
            }

            if ($user->count() >= 1) {
                $data = $user->get();
                $user = User::find($data[0]->id);
                $user->reminder = '';
                $user->password = \Hash::make($request->input('password'));
                $user->save();
            }

            return Redirect::to('/')->with('message', \SiteHelpers::alert('success', 'Password has been saved!'));
        } else {
            return Redirect::to('user/reset/?token=' . $token)->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }

    }

    public function getLogout()
    {
        \Auth::logout();
        \Session::flush();
        return Redirect::to('/')->with('message', \SiteHelpers::alert('info', 'Your are now logged out!'));
    }

    function getSocialize($social)
    {
        return Socialize::with($social)->scopes(['openid', 'profile', 'email','https://mail.google.com'])->with(['access_type' => 'offline'])->redirect();
    }

    function getAutosocial($social)
    {
        $user = Socialize::with($social)->user();
        $user = User::where('email', $user->email)->first();
        return self::autoSignin($user);
    }


    function autoSignin($user)
    {

        if (is_null($user)) {
            return Redirect::to('/')
                ->with('message', \SiteHelpers::alert('error', 'You have not registered yet '))
                ->withInput();
        } else {

            Auth::login($user);
            if (Auth::check()) {
                $row = User::find(\Auth::user()->id);

                if ($row->active == '0') {
                    // inactive
                    Auth::logout();
                    return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is not active'));

                } else if ($row->active == '2') {
                    // BLocked users
                    Auth::logout();
                    return Redirect::to('/')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'));
                } else {
                    Session::put('uid', $row->id);
                    Session::put('gid', $row->group_id);
                    Session::put('eid', $row->group_email);
                    Session::put('fid', $row->first_name . ' ' . $row->last_name);
                    Session::put('user_name', $row->username);
                    Session::put('ufname', $row->first_name);
                    Session::put('ulname', $row->last_name);
                    Session::put('company_id', $row->company_id);
                    $user_locations = \SiteHelpers::getLocationDetails($row->id);
                    if (!empty($user_locations)) {
                        Session::put('user_locations', $user_locations);
                        Session::put('selected_location', $user_locations[0]->id);
                        Session::put('selected_location_name', $user_locations[0]->location_name_short);
                    }
                    Session::put('get_locations_by_region', $row->get_locations_by_region);
                    Session::put('email_2', $row->email_2);
                    Session::put('primary_phone', $row->primary_phone);
                    Session::put('secondary_phone', $row->secondary_phone);
                    Session::put('street', $row->street);
                    Session::put('city', $row->city);
                    Session::put('state', $row->state);
                    Session::put('zip', $row->zip);
                    Session::put('reg_id', $row->reg_id);
                    Session::put('restricted_mgr_email', $row->restricted_mgr_email);
                    Session::put('restricted_user_email', $row->restricted_user_email);
                    if (CNF_FRONT == 'false') :
                        return Redirect::to($row->redirect_link == 'dashboard'?'user/profile':$row->redirect_link);
                    else :
                        return Redirect::to('');
                    endif;


                }


            }
        }

    }

}
