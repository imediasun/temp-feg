<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ticketcomment;
use EmailReplyParser\Parser\EmailParser;

class CronController extends Controller
{

    public function getIndex(Request $request)
    {
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = CNF_REPLY_TO;
        $password = CNF_REPLY_TO_PASSWORD;

        /* try to connect */
        $inbox = imap_open($hostname, $username, $password,NULL, 1,
            array('DISABLE_AUTHENTICATOR' => 'PLAIN')) or die('Cannot connect to Gmail: ' . imap_last_error());
        echo "connection established";
        /* grab emails */
        $emails = imap_search($inbox, 'SUBJECT "FEG Ticket #"');

        /* if emails are returned, cycle through each... */
        if ($emails) {
            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach ($emails as $email_number) {
                echo 'here found email';

                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox, $email_number, 0);
                //var_dump($overview[0]);
                $from = $overview[0]->from;
                $from = substr($from, strpos($from, "<") + 1, -1);

                // date format according to sql
                $date = str_replace('at', '', $overview[0]->date);
                $posted = date_create($date);

                //Parse subject to find comment id
                $subject = $overview[0]->subject;
                $ticketId = substr($subject, strpos($subject, "#") + 1);

                //insert comment
                $postUser = \DB::select("Select * FROM users WHERE email = '" . $from . "'");
                $userId = $postUser[0]->id;

                $message = imap_fetchbody($inbox, $email_number, 1);
                $comment = \EmailReplyParser\EmailReplyParser::parseReply($message);

                //Insert In sb_comment table
                $comment_model = new Ticketcomment();
                $commentsData = array(
                    'TicketID' => $ticketId,
                    'Comments' => $comment,
                    'Posted' => $posted,
                    'UserID' => $userId
                );
                $comment_model->insertRow($commentsData, NULL);
            }

            //imap_delete($inbox,$email_number);
        }
        /* close the connection */
        imap_close($inbox);
    }
}
