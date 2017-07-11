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

        // don't connect to imap to read ticket comments
        if (env('DONT_READ_IMAP_TICKET_COMMENTS', false) === true) {
            return;
        }

        
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
                //return;
            }
        }
        FEGSystemHelper::updateOption('ReadingTicketCommentsFromIMAP', $now);
        
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "tickets@tickets.fegllc.com";
        $password = "MdkHly2Ub5";

        $L->log("Connecting...");
        /* try to connect */
        try {            
            $inbox = imap_open($hostname, $username, $password,NULL, 1,
                array('DISABLE_AUTHENTICATOR' => 'PLAIN'));
            
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
                $L->log("Message Details: ", $meta);
                $UID = isset($meta->message_id) ? $meta->message_id: '';
                //$messageExists = Ticketcomment::doesCommentExist($UID);
                //if ($messageExists) {
                //     $L->log("Message exists with ID: $UID.");
                //}
                //else{

                $fromDetails = $this->getSenderDetails($meta);
                $fromEmail = @$fromDetails['email'];

                $userId = $this->getUserIdFromEmail($fromEmail);
                $userName = @$fromDetails['personal'];

                $ticketId = $this->getTicketID($meta);
                $L->log("Ticket ID: ", $ticketId);
                $L->log("Checking if ticket exists....");
                $ticketExists = !empty($ticketId) && Servicerequests::doesTicketExist($ticketId);
                $L->log("Checked if ticket exists result = ".$ticketExists);
                if ($ticketExists) {
                    $L->log("in if block means ticket exists");
                    $posted = $this->getDate($meta);
                    $L->log("Posted date = ".$posted);
                    $L->log("inbox = ".$inbox);
                    $L->log("email_number = ".$email_number);
                    $L->log("getMessage() = ".$this->getMessage($inbox, $email_number));
                    $message = $this->cleanUpMessage($this->getMessage($inbox, $email_number));
                    $L->log("Message = ".$posted);
                    //Insert In sb_ticketcomments table
                    $L->log("Creating new comment");
                    $comment_model = new Ticketcomment();
                    $L->log("Created new comment instance");
                    $commentsData = array(
                        'TicketID' => $ticketId,
                        'Comments' => $message,
                        'Posted' => $posted,
                        'UserID' => $userId,
                        'USERNAME' => $userName,
                        'imap_read' => 1,
                        'imap_meta' => json_encode($meta),
                        'imap_message_id' => $UID,
                    );
                    $L->log("comments data = ".json_encode($commentsData));
                    $L->log('Adding comment to database', $commentsData);
                    $id = $comment_model->insertRow($commentsData, NULL);
                    $L->log("Updaet ticket updated date to $posted");
                    Servicerequests::where("TicketID", $ticketId)->update(['updated' => $posted]);

                }
                else {
                    $L->log("TICKET [ID: $ticketId] DOES NOT EXIST. Skipping.....");
                }


                /*$L->log('Delete email');
                imap_delete($inbox, $email_number);*/
                //$L->log('Sending comment notificaiton');
                //$this->sendNotification($commentsData, $userId);

                //}
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
        $ticketID = "0";
        $to = isset($meta->to) ? $meta->to : [];
        $cc = isset($meta->cc) ? $meta->cc : [];
        $bcc = isset($meta->bcc) ? $meta->bcc : [];
        $recipients = array_merge($to, $cc, $bcc);
        foreach($recipients as $toItem) {
            $host = $toItem->host;
            $mailbox = $toItem->mailbox;
            if ($host == 'tickets.fegllc.com') {                
                $ticketID = str_replace('ticket-reply-', '', $mailbox);
                break;
            }
        }
        if ($ticketID == "0") {
            $subject = isset($meta->subject) ? $meta->subject : '';
            $matched = preg_match('/\[Service Request \#(\d+?)\]/', $subject, $subjectMatch);
            if ($matched==1 && !empty($subjectMatch[1]))    {
                $ticketID = $subjectMatch[1];
            }
        }
        return $ticketID;
    }
    public function getDate($meta) {
        return date("Y-m-d H:i:s", strtotime($meta->date));
    }
    public function getMessage($inbox, $email_number, $structure = ["1.1", "1"]) {
//        foreach($structure as $structureNumber) {
//            $message = imap_fetchbody($inbox, $email_number, $structureNumber);
//            if (!empty($message)) {
//                break;
//            }
//        }
        $this->L->log('in get message function');
        $this->L->log("inbox = $inbox email_number = $email_number structure = ".json_encode($structure));
        $message = $this->getMessageFromStructure($inbox, $email_number, $structure);
        if (empty($message)) {
            $message = '';
        }
        return $message;        
    }
    public function cleanUpMessage($message) {
        $message = trim(preg_replace('/From\:[\s\S]*$/', '', $message));
        $message = trim(preg_replace('/[\r\n]{4}On [\s\S]*$/', '', $message));
        $message = trim(preg_replace('/[\-]{9} Original Message [\-]{9}[\s\S]*$/', '', $message));
        
        $message = preg_replace('/^[\r\n\t\s]+?/', '', $message);
        $message = preg_replace('/[\r\n\t\s]+?$/', '', $message);
        $message = preg_replace('/\r\n{2,}|\r{2,}|\n{2,}/', "\n", $message);
        if (empty($message)) {
            $message = '';
        }
        return $message;
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
    
    public function getMessageFromStructure($connection, $messageNumber, $partNumbers) {
        $this->L->log('in getMessageFromStructure function');
        $this->L->log("connection = $connection messageNumber = $messageNumber partNumbers = ".json_encode($partNumbers));
        $structure = imap_fetchstructure($connection, $messageNumber);
        $this->L->log('structure = '. json_encode( (array)$structure ));
        $flattenedParts = $this->emailFlattenParts($structure->parts);
        $this->L->log('flattenedParts = '.json_encode($flattenedParts));
        $message = "";
        foreach($flattenedParts as $partNumber => $part) {

            switch($part->type) {

                case 0:
                    $this->L->log('case 0 matched ');
                    // the HTML or plain text part of the email
                    $message = $this->emailGetPart($connection, $messageNumber, $partNumber, $part->encoding);
                    // now do something with the message, e.g. render it
                break;

                case 1:
                    // multi-part headers, can ignore

                break;
                case 2:
                    // attached message headers, can ignore
                break;

                case 3: // application
                case 4: // audio
                case 5: // image
                case 6: // video
                case 7: // other
                    //$filename = $this->emailgetEmailAttachmentFilenameFromPart($part);
                    //if($filename) {
                        // it's an attachment
                        //$attachment = $this->emailGetPart($connection, $messageNumber, $partNumber, $part->encoding);
                        // now do something with the attachment, e.g. save it somewhere
                    //}
                    //else {
                        // don't know what it is
                    //}
                break;

            }
            
            if (!empty($message) && in_array($partNumber, $partNumbers)) {
                $this->L->log('in if breaking loop ');
                break;
            }
        }
        $this->L->log('returning message = '.$message);
        return $message;
    }
    
    private function emailFlattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {

        foreach($messageParts as $part) {
            $flattenedParts[$prefix.$index] = $part;
            if(isset($part->parts)) {
                if($part->type == 2) {
                    $flattenedParts = $this->emailFlattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
                }
                elseif($fullPrefix) {
                    $flattenedParts = $this->emailFlattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
                }
                else {
                    $flattenedParts = $this->emailFlattenParts($part->parts, $flattenedParts, $prefix);
                }
                unset($flattenedParts[$prefix.$index]->parts);
            }
            $index++;
        }

        return $flattenedParts;
			
    }
    
    private function emailGetPart($connection, $messageNumber, $partNumber, $encoding) {

        $data = imap_fetchbody($connection, $messageNumber, $partNumber);
        switch($encoding) {
            case 0: return $data; // 7BIT
            case 1: return $data; // 8BIT
            case 2: return $data; // BINARY
            case 3: return base64_decode($data); // BASE64
            case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5: return $data; // OTHER
        }
    }  
    
    private function emailgetEmailAttachmentFilenameFromPart($part) {

        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }    
    
}
