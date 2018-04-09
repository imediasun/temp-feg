<?php namespace App\Http\Controllers;

use App\Models\Sximo;
use App\User;
use Illuminate\Http\Request;
use PHPMailerOAuth;
use App\Models\Core\Pages;
use Validator, Input, Redirect, Auth;
use App\Models\Core\Groups;

class HomeController extends Controller
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
        if (CNF_FRONT == 'false' && $request->segment(1) == '') :
            return Redirect::to('dashboard');
        endif;

        $page = $request->segment(1);
        $pageUrl = $request->fullUrl();

        if ($page != '') :
            $content = \DB::table('tb_pages')
                ->where('alias', '=', $page)
                ->where('status', '=', 'enable')->get();

            if (count($content) >= 1) {

                $row = $content[0];

                $this->data['editLink'] = '';
                if (Pages::canIEdit($row->pageID)){
                    $editUrl = url('core/pages/update/'.$row->pageID.'?return='.$pageUrl);
                    $editLink = view('core.pages.edit-link', ['page' => $row,
                                    'url' => $editUrl]);
                    $this->data['editLink'] = $editLink;
                }

                $this->data['pageTitle'] = $row->title;
                $this->data['pageNote'] = $row->note;
                $this->data['pageMetakey'] = ($row->metakey != '' ? $row->metakey : CNF_METAKEY);
                $this->data['pageMetadesc'] = ($row->metadesc != '' ? $row->metadesc : CNF_METADESC);

                $this->data['breadcrumb'] = 'active';

                if ($row->access != '') {
                    $access = json_decode($row->access, true);
                } else {
                    $access = array();
                }

                // If guest not allowed
                if ($row->allow_guest != 1) {
                    $group_id = \Session::get('gid');
                    $isValid = (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0);
                    if ($isValid == 0) {
                        return Redirect::to('');
                        //->with('message', \SiteHelpers::alert('error',Lang::get('core.note_restric')));
                    }
                }
                if ($row->template == 'backend') {
                    $page = 'pages.' . $row->filename;
                } else {
                    $page = 'layouts.' . CNF_THEME . '.index';
                }
                //print_r($this->data);exit;

                $filename = base_path() . "/resources/views/pages/" . $row->filename . ".blade.php";
                if (file_exists($filename)) {
                    $this->data['pages'] = 'pages.' . $row->filename;
                    //	print_r($this->data);exit;

                    return view($page, $this->data);
                } else {
                    return Redirect::to('')
                        ->with('message', \SiteHelpers::alert('error', \Lang::get('core.note_noexists')));
                }

            } else {
                return Redirect::to('')
                    ->with('message', \SiteHelpers::alert('error', \Lang::get('core.note_noexists')));
            }


        else :
            $this->data['pageTitle'] = 'Home';
            $this->data['pageNote'] = 'Welcome To Our Site';
            $this->data['breadcrumb'] = 'inactive';
            $this->data['pageMetakey'] = CNF_METAKEY;
            $this->data['pageMetadesc'] = CNF_METADESC;

            $this->data['pages'] = 'pages.home';
            $page = 'layouts.' . CNF_THEME . '.index';
            return view($page, $this->data);
        endif;


    }

    public function gMailTest()
    {
        return view('pages.gmailtest');
    }
    public function saveToken(Request $request)
    {
        $user = User::find($request->user_id);
        $user->oauth_token = $request->access_token;
        $user->save();
        return 'Token Saved';
    }
    public function gMailCallback()
    {

        return view('pages.sendmail')->with('token',Input::get('code'))->with('token2',"ya29.GlsqBJsmtUF_G0uYnwosTrbPCOfImLbKHjyTdN3-ISdZ1V3lYJwcBTO46GYLjMGc8U-UIwDP7XkYrHu4bpCCyACzxkIzYGnV5ZTUgeUHWzETYUhgxFx7F9YwaiHm");
    }

    public function  getLang($lang = 'en')
    {
        \Session::put('lang', $lang);
        return Redirect::back();
    }

    public function  getSkin($skin = 'sximo')
    {
        \Session::put('themes', $skin);
        return Redirect::back();
    }

    public function  postContact(Request $request)
    {

        $this->beforeFilter('csrf', array('on' => 'post'));
        $rules = array(
            'name' => 'required',
            'subject' => 'required',
            'message' => 'required|min:20',
            'sender' => 'required|email'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {

            $data = array('name' => $request->input('name'), 'sender' => $request->input('sender'), 'subject' => $request->input('subject'), 'notes' => $request->input('message'));
            $message = view('emails.contact', $data);

            $to = CNF_EMAIL;
            $subject = $request->input('subject');
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $request->input('name') . ' <' . $request->input('sender') . '>' . "\r\n";
            //mail($to, $subject, $message, $headers);

            return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('success', 'Thank You , Your message has been sent !'));

        } else {
            return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('error', 'The following errors occurred'))
                ->withErrors($validator)->withInput();
        }
    }
    function TermsAndConditions(Request $request){
        $this->data['pageTitle'] = "Terms And Conditions";
        $this->data['editLink'] = '';
       return view('pages.terms',$this->data);
    }
    function PrivacyPolicty(Request $request){
        $this->data['pageTitle'] = "Privacy Policy";
        $this->data['editLink'] = '';
        return view('pages.privacypolicy',$this->data);
    }

}
