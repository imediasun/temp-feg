<?php namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function getIndex( Request $request )
	{
		/* connect to gmail */
		$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
		$username = 'dev5@shayansolutions.com';
		$password = 'N48575Kx';

		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		echo "connection established";
		/* grab emails */
		$emails = imap_search($inbox,'SUBJECT "FEG Ticket #"');

		/* if emails are returned, cycle through each... */
		if($emails) {
			/* begin output var */
			$output = '';

			/* put the newest emails on top */
			rsort($emails);

			/* for every email... */
			foreach($emails as $email_number) {

				/* get information specific to this email */
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,1);

				/* output the email header information */
				$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
				$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
				$output.= '<span class="from">'.$overview[0]->from.'</span>';
				$output.= '<span class="date">on '.$overview[0]->date.'</span>';
				$output.= '</div>';

				/* output the email body */
				$output.= '<div class="body">'.$message.'</div>';

				imap_delete($inbox,$email_number);
			}

			echo $output;
		}
		/* close the connection */
		imap_close($inbox);
		die('in dashboard');
		$this->data['online_users'] = \DB::table('users')->orderBy('last_activity','desc')->limit(10)->get();
		return view('dashboard.index',$this->data);
	}	


}