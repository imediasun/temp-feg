<?php
namespace App\Console\Commands;

require_once('setting.php');

use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Servicerequests;
use App\Models\Ticketcomment;
use App\Models\Core\TicketMailer;

class ReadComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comments:read';
    
    protected $L = null;

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
        global $__logger;
        $L = $this->L = $__logger = FEGSystemHelper::setLogger($this->L, "fetch-ticket-emails.log", "FEGTicketCron/ReadComments", "TICKET");
        $L->log('Start Fetching Emails');
        
        $now = date('Y-m-d H:i:s');
        $nowStamp = strtotime($now);
        $lastRun = FEGSystemHelper::getOption('ReadingTicketCommentsFromIMAP', '');
        if (!empty($lastRun)) {
            $lastRunTimestamp = strtotime($lastRun);
            if ($nowStamp - $lastRunTimestamp < (3600)) { //wait for 1 hour 
                $L->log("Task to fetch emails already running since $lastRun. Quit.");
                return;
            }
        }
        FEGSystemHelper::updateOption('ReadingTicketCommentsFromIMAP', $now);
        
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "tickets@tickets.fegllc.com";
        $password = "8d<Sy%68";

        $L->log("Connecting...");
        /* try to connect */
        try {            
            $inbox = imap_open($hostname, $username, $password);            
            
        } catch (Exception $ex) {
            $L->log("Error connecting to IMAP:" . $ex->getMessage());
            return;
        }
        
        if (empty($inbox)) {
            $L->log("IMAP Error:" . imap_last_error());
            return;
        }
        $L->log("connection established");
        echo "connection established";
        /* grab emails */
        $emails = imap_search($inbox, 'TEXT "ticket-reply-" UNDELETED');
        /* if emails are returned, cycle through each... */
        if ($emails) {
            /* begin output var */
            $output = '';

            /* put the newest emails on top */
            rsort($emails);

            /* for every email... */
            foreach ($emails as $email_number) {

                /* get information specific to this email */
                $meta = $this->getMessageDetails($inbox, $email_number);
                $fromDetails = $this->getSenderDetails($meta);
                $fromEmail = @$fromDetails['email'];
                
                $userId = $this->getUserIdFromEmail($fromEmail);
                $userName = @$fromDetails['personal'];
                
                $ticketId = $this->getTicketID($meta);
                
                $posted = $this->getDate($meta);
                
                $message = $this->cleanUpMessage($this->getMessage($inbox, $email_number));
                
                //Insert In sb_ticketcomments table
                $comment_model = new Ticketcomment();
                $commentsData = array(
                    'TicketID' => $ticketId,
                    'Comments' => $message,
                    'Posted' => $posted,
                    'UserID' => $userId,
                    'USERNAME' => $userName,
                );
                
                $L->log('Adding comment to database', $commentsData);
                $id = $comment_model->insertRow($commentsData, NULL);
                Servicerequests::where("TicketID", $ticketId)->update(['updated' => $posted]);
                
                $L->log('Delete email');
                imap_delete($inbox, $email_number);
                //$L->log('Sending comment notificaiton');                
                //$this->sendNotification($commentsData, $userId);
                $L->log('---------------------------------------------');
            }
        } 
        else {
            echo "no emails found....";
            $L->log('No emails found');
        }
        /* close the connection */
        imap_close($inbox);
        FEGSystemHelper::updateOption('ReadingTicketCommentsFromIMAP', '');
        $L->log('End Fetching Emails');
    }
    
    public function getMessageDetails($inbox, $email_number) {
        $header = imap_rfc822_parse_headers(imap_fetchheader($inbox, $email_number));
        return $header;
    }
    public function getSenderDetails($meta) {
        $from = [ 'email' => '', 'personal' => '' ];
        $sender = $meta->from;
        if (isset($sender[0])) {
            $sender = $sender[0];
        }
        $from['email'] = $sender->mailbox.'@'.$sender->host;
        $from['personal'] = isset($sender->personal) ? $sender->personal : '';
        
        return $from;        
    }
    public function getUserIdFromEmail($email) {
        $userId = \App\Models\Core\Users::where('email', $email)->pluck('id');
        if (empty($userId)) {
            $userId = 0;
        }
        return $userId;
    }
    
    public function getTicketID($meta) {
        //ticket-reply-<ticketId>@tickets.fegllc.com
        $to = $meta->to;
        $ticketID = "0";
        foreach($to as $toItem) {
            $host = $toItem->host;
            $mailbox = $toItem->mailbox;
            if ($host == 'tickets.fegllc.com') {                
                $ticketID = str_replace('ticket-reply-', '', $mailbox);
                break;
            }
        }
        return $ticketID;
    }
    public function getDate($meta) {
        return date("Y-m-d H:i:s", strtotime($meta->date));
    }
    public function getMessage($inbox, $email_number, $structure = ["1.1", "1"]) {
        foreach($structure as $structureNumber) {
            $message = imap_fetchbody($inbox, $email_number, $structureNumber);
            if (!empty($message)) {
                break;
            }
        }
        if (empty($message)) {
            $message = '';
        }
        return $message;        
    }
    public function cleanUpMessage($message) {
        $cmessage = trim(preg_replace('/From\:[\s\S]*$/','',$message));
        $cmessage = trim(preg_replace('/[\r\n]{4}On [\s\S]*$/','',$cmessage));
        $cmessage = trim(preg_replace('/[\-]{9} Original Message [\-]{9}[\s\S]*$/','',$cmessage));
        
        $cmessage = preg_replace('/^[\r\n\t\s]+?/', '', $cmessage);
        $cmessage = preg_replace('/[\r\n\t\s]+?$/', '', $cmessage);
        if (empty($cmessage)) {
            $cmessage = '';
        }
        return $cmessage;
    }
    
    public function sendNotification($data, $skipUserId) {
        $ticketID = $data['$ticketID'];
        $ticketData = Servicerequests::where("TicketID", $ticketID)->first()->toArray();
        $serviceRequests = new Servicerequests();
        $serviceRequests->attachObserver('AddComment',new TicketMailer);
        $serviceRequests->notifyObserver('AddComment',[
            'message'       => $data['Comments'],
            'ticketId'      => $data['TicketID'],
            'ticket'        => $ticketData,
            'skipUsers'     => [$skipUserId],
            'department_id' => '',                
        ]);        
    }
}
