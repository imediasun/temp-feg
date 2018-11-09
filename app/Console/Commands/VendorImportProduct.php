<?php
namespace App\Console\Commands;

require_once('setting.php');

use App\Models\reviewvendorimportlist;
use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;
use File;

class VendorImportProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendorproduct:import';
    protected $L = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        if (env('DONT_READ_VENDOR_EMAILS', false) === true) {
            return;
        }


        global $__logger;
        $L = $this->L = $__logger = FEGSystemHelper::setLogger($this->L, "fetch-vendor-emails.log", "FEGVendorCron/VendorImportProduct", "VendorImport");
        $L->log('Start Fetching Emails');



        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "vendor.products@fegllc.com";
        $password = "%Am=%5JK";

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
        $emails = imap_search($inbox, 'UNSEEN');
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
               $attachments = $this->saveAttachments($inbox,$email_number);
                if ($attachments['is_available']) {
                    $L->log('Attachment Found: [total:'.count($attachments['attachments']).", attachments".json_encode($attachments['attachments']));
                    $L->log("Message Details: ", $meta);
                    $fromDetails = $this->getSenderDetails($meta);
                    $emailReceivedAt = $this->getDate($meta);
                    $fromEmail = @$fromDetails['email'];
                    $data = [
                        'email_received_at' => $emailReceivedAt,
                        'from_email'=>$fromEmail,
                        'attachments' =>$attachments['attachments'],
                    ];
                    $reviewVendorImportList = new reviewvendorimportlist();
                    if($reviewVendorImportList->isVendorExist($fromEmail)) {
                        $reviewVendorImportList->importExlAttachment($data);
                    }else{

                    }

                }else{
                    $L->log('No Attachment Found.');
                }
                if(!env('DONT_DELETE_VENDOR_EMAIL', true))
                {
                    //commented for testing on dev
                    $L->log('Delete email');
                    imap_delete($inbox, $email_number);
                }


                $L->log('---------------------------------------------');
            }
        }
        else {
            echo " no emails found....";
            $L->log(' No emails found');
        }
        /* close the connection */
        imap_close($inbox);
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
    public function getVendorIdFromEmail($email) {

    }
    public function getDate($meta) {
        return date("Y-m-d H:i:s", strtotime($meta->date));
    }

    /**
     * @param $inbox
     * @param $email_number
     * @return array
     */
    public function saveAttachments($inbox, $email_number){
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        $attachments = array();

        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts))
        {
            for($i = 0; $i < count($structure->parts); $i++)
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if($structure->parts[$i]->ifdparameters)
                {
                    foreach($structure->parts[$i]->dparameters as $object)
                    {
                        if(strtolower($object->attribute) == 'filename')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if($structure->parts[$i]->ifparameters)
                {
                    foreach($structure->parts[$i]->parameters as $object)
                    {
                        if(strtolower($object->attribute) == 'name')
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if($attachments[$i]['is_attachment'])
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);

                    /* 3 = BASE64 encoding */
                    if($structure->parts[$i]->encoding == 3)
                    {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif($structure->parts[$i]->encoding == 4)
                    {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        /* iterate through each attachment and save it */
        $attachmentSaved = ['is_available'=>false,'attachments'=>[]];
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];

                if(empty($filename)) $filename = time() . ".dat";
                $folder = public_path("/uploads/vendors-attachments");
                if(!File::exists($folder."/".date("Y-m-d"))) {

                    File::makeDirectory($folder."/".date("Y-m-d"), 0777, true, true);
                }
                $folder = $folder."/".date("Y-m-d");
                $fp = fopen($folder."/". $email_number . "-" . $filename, "w+");
                fwrite($fp, $attachment['attachment']);
                $attachmentSaved['is_available'] = true;
                $attachmentSaved['attachments'][] =  $folder."/". $email_number . "-" . $filename;
                fclose($fp);
            }
        }
        return $attachmentSaved;
    }

}
