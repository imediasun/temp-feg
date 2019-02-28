<?php
namespace App\Console\Commands;

require_once('setting.php');

use App\Models\Reviewvendorimportlist;
use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;
use File;
use Carbon\Carbon;

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
    protected $description = 'To import vendor product list from.';

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
        $L->log('----------------Start Fetching Emails----------------'.Carbon::now());



        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $username = "vendor.products@fegllc.com";
        $password = "%Am=%5JK";

        $L->log("Connecting...");
        $inbox = "";
        /* try to connect */
        try {
            $inbox = imap_open($hostname, $username, $password,NULL, 1,
                array('DISABLE_AUTHENTICATOR' => 'PLAIN'));

            if (empty($inbox)) {
                $L->log("IMAP Error:" . imap_last_error());
                throw new Exception("IMAP Error:" . imap_last_error());
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
                        $vendorCount = $reviewVendorImportList->isVendorExist($fromEmail);

                        if($vendorCount > 0) {
                            $vendorInformation = $reviewVendorImportList->getVendorByEmail($fromEmail);
                            $isDuplicateFound = false;
                            foreach ($attachments['attachments'] as $attachment) {
                                $fileData = \SiteHelpers::getVendorFileImportData($attachment);
                                $duplicateItems = $this->checkDuplicateItems($fileData);
                                if($duplicateItems['status'] == true){
                                    $subject = '[System Error] Unable to import products';
                                    $isDuplicateFound = true;
                                    $this->sendVendorEmailNotification($subject,$duplicateItems['message'],$fromEmail,$vendorInformation);
                                    $L->log('[System Error] Duplicate Items found. Unable to import products.');
                                    echo " [System Error] Unable to import products Notification has been sent at".$fromEmail." ";
                                    return true;
                                }
                            }

                            //if email id exist against single vendor
                            if($vendorCount == 1){
                                $reviewVendorImportList->importExlAttachment($data, $vendorCount);

                            }else{//If multiple vendors exist with same email id.

                                /* get information specific to this email */
                                $overview = imap_fetch_overview($inbox, $email_number, 0);

                                //Parse subject to find vendor id
                                $subject = $overview[0]->subject;
                                $vendorId = str_replace("]","",substr($subject, strpos($subject, "#") + 1));
                                $vendor = $reviewVendorImportList->getVendorById($vendorId);
                                if(!$vendor){
                                    $L->log(' Vendor Id does not exist.');
                                    $subject = '[System Error] Email subject does not contain vendor ID.';
                                  echo  $message = 'We found that your email is associated with multiple vendors. To correctly associate list with vendor, you must provide vendor ID in email subject like [Vendor Product List #xxxx] (replace xxxx with vendor ID). If you do not know what your Vendor ID is, please contact the Merchandise team at merch.office@fegllc.com.';
                                    $this->sendVendorEmailNotification($subject,$message,$fromEmail,$vendor);
                                }else{
                                    $L->log(' Vendor Id: '.$vendorId);
                                    $data['vendor_id'] =  $vendorId;
                                    $reviewVendorImportList->importExlAttachment($data, $vendorCount);
                                }
                            }

                        }

                    }else{
                        $L->log('No Attachment Found.');
                    }
                    if(!env('DONT_DELETE_VENDOR_EMAIL', true))
                    {
                        //commented for testing on dev
                        $L->log('Delete email');
                         imap_delete($inbox, $email_number); // uncomment if needed
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

            $L->log('-------------End Fetching Emails-----------------------------');

        } catch (Exception $ex) {
            $L->log("Error connecting to IMAP:" . $ex->getMessage());
            $subject = '[Error] ['.env('APP_ENV').'] Vendor Product Import Cron Job '. FEGSystemHelper::getHumanDate(date('Y-m-d'));
            $message = '<h1>Vendor Product Import Cron Job has an issue</h1>';
            $message .= '<br />'.$ex->getMessage();
            FEGSystemHelper::sendNotificationToDevTeam($subject, $message);
            /* close the connection */
            imap_close($inbox);
            return;
        }


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

    /**
     * @param $items
     * @return array
     */
    public function checkDuplicateItems($items){

        $itemNotify = ['status'=>false,'message'=>'<b>We cannot import your file into system because of following errors:</b> <ul>'];
        $ignoreIndex = 1;
        $rowIndex = 1;
        $duplicateCheck = [];

        foreach ($items as $listItem){
            $rowIndexEqualTo = 1;
            $ignoreIndex = $rowIndex;
            foreach ($items as $item) {
                if (!empty($item['item_name']) && !in_array($rowIndex,$duplicateCheck)) {
                    if ($rowIndexEqualTo <> $ignoreIndex) {
                        if (
                            $item['item_name'] == $listItem['item_name']
                            && $item['sku'] == $listItem['sku']
                            && $item['upc_barcode'] == $listItem['upc_barcode']
                            && $item['item_per_case'] == $listItem['item_per_case']
                            && $item['case_price'] == $listItem['case_price']
                            && $item['unit_price'] == $listItem['unit_price']
                            && $item['item_name'] == $listItem['item_name']
//                            && $item['ticket_value'] == $listItem['ticket_value']
//                            && $item['is_reserved'] == $listItem['is_reserved']
                            && $item['reserved_qty'] == $listItem['reserved_qty']
                        ) {
                            $duplicateCheck[] = $rowIndexEqualTo;
                            $itemNotify['status'] = true;
                            $itemNotify['message'] .= '<li>Duplicate item found : <b>' . $listItem['item_name'] . '</b> duplicate on Row ' . ($rowIndex+1) . '  and ' . ($rowIndexEqualTo+1) . ' </li>';
                        }
                    }
                    $rowIndexEqualTo++;
                }
            }
            $rowIndex++;
        }
        $itemNotify['message'] .= '</ul>';

        return $itemNotify;

    }

    /**
     * @param $subject
     * @param $message
     * @param $to
     * @param null $vendor
     */
    public function sendVendorEmailNotification($subject,$message,$to,$vendor = null)
    {

        $from = 'vendor.products@fegllc.com';
        $sendEmailFromMerchandise = false;
        $sendEmailFromVendorAccount = true;

        // Old email configuration name "Send Product Export To Vendor"
        if ($vendor != null) {
            $this->L->log('Vendor Email:'.$vendor->email); //-----------Debug---------------
            //Merchandise Vendor Email Configuration name "Send Product Export To Merchandise Vendor"
            //Check if vendor ismerch = yes
            if ($vendor->ismerch == 1) {
                $this->L->log('Vendor is merch'); //-----------Debug---------------
                $configName = 'Send Product Export To Merchandise Vendor';
                $recipients = FEGSystemHelper::getSystemEmailRecipients($configName);
                if (!empty($to)) {
                    $this->L->log('TO was not empty when vendor is merch'); //-----------Debug---------------
                    $recipients['to'] .= $recipients['to'] != '' ? ',' . $to : $to;
                }

                if ($recipients['to'] != '') {
                    $this->L->log('recipients TO was not empty when vendor is merch'); //-----------Debug---------------
                    $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                        'subject' => $subject,
                        'message' => $message,
                        'preferGoogleOAuthMail' => false,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'configName' => $configName,
                        'from' => $from,
                        'replyTo' => $from,
                    )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount);
                }
            }

            // Game Related Vendor Email Configuration name "Send Product Export To Game Vendor"
            //Check if vendor isgame = yes
            if ($vendor->isgame == 1) {
                $this->L->log('Vendor is game'); //-----------Debug---------------
                $configName = 'Send Product Export To Game Vendor';
                $recipients = FEGSystemHelper::getSystemEmailRecipients($configName);
                if (!empty($to)) {
                    $this->L->log('TO was not empty when vendor is game'); //-----------Debug---------------
                    $recipients['to'] .= $recipients['to'] != '' ? ',' . $to : $to;
                }

                if ($recipients['to'] != '') {
                    $this->L->log('recipients TO was not empty when vendor is game'); //-----------Debug---------------
                    $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                        'subject' => $subject,
                        'message' => $message,
                        'preferGoogleOAuthMail' => false,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'configName' => $configName,
                        'from' => $from,
                        'replyTo' => $from,
                    )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount);
                }
            }
        } else {
            $this->L->log('vendor is null'); //-----------Debug---------------
            $configName = 'Send Product Export To Merchandise Vendor';
            $recipients = FEGSystemHelper::getSystemEmailRecipients($configName);
            if (!empty($to)) {
                $this->L->log('TO was empty when vendor is null'); //-----------Debug---------------
                $recipients['to'] .= ',' . $to;
            }

            if ($recipients['to'] != '') {
                $this->L->log('recipients TO was empty when vendor is null'); //-----------Debug---------------
                $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                    'subject' => $subject,
                    'message' => $message,
                    'preferGoogleOAuthMail' => false,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'configName' => $configName,
                    'from' => $from,
                    'replyTo' => $from,
                )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount);
            }
        }
    }
}
