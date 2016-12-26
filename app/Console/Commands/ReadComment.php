<?php
namespace App\Console\Commands;

require_once('setting.php');
use Illuminate\Console\Command;
use App\Models\Ticketcomment;

class ReadComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read Reply email tickets response and add in ticket comment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "tickets@tickets.fegllc.com";
        $password = "8d<Sy%68";

        /* try to connect */
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
        echo "connection established";
        /* grab emails */
        $emails = imap_search($inbox, 'TEXT "ticket-reply-"');
        /* if emails are returned, cycle through each... */
        if ($emails) {
            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach ($emails as $email_number) {

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                //var_dump($overview[0]);
                $from = $overview[0]->from;
                $from = substr($from, strpos($from, "<") + 1, -1);
                $to = $overview[0]->to;
                $to = substr($to, strpos($to, "<") + 1, -1);
                // date format according to sql
                $date = str_replace('at', '', $overview[0]->date);
                $posted = date_create($date);

                //Parse subject to find comment id
                $subject = $overview[0]->subject;
                $ticketId = explode('-', $to);
                $ticketId = substr($ticketId[2], 0, strpos($ticketId[2], "@"));
                //insert comment
                $postUser = \DB::select("Select * FROM users WHERE email = '" . $from . "'");
                $userId = $postUser[0]->id;
                $message = imap_fetchbody($inbox, $email_number, 1);

                //Insert In sb_comment table
                $comment_model = new Ticketcomment();
                $commentsData = array(
                    'TicketID' => $ticketId,
                    'Comments' => $message,
                    'Posted' => $posted,
                    'UserID' => $userId
                );
                $comment_model->insertRow($commentsData, NULL);
            }

            imap_delete($inbox, $email_number);
        } else {
            echo "no emails found....";
        }
        /* close the connection */
        imap_close($inbox);
    }
}
