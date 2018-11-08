<?php
namespace App\Models\Core;
use Log;
use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Ticketfollowers;

class TicketMailer
{
    function callBack($object, $type, $data){
        
        $actions = ["AddComment", "FirstEmail"];
        if (in_array($type, $actions)) {
            
            $ticketData = $data['ticket'];
            $ticketId = $data['ticketId'];
            $message = $data['message'];
            $skipUsers = isset($data['skipUsers']) ? $data['skipUsers'] : [];
            $locationId = $ticketData['location_id'];
            $isFirstNotification = $type == 'FirstEmail';
            
            $followers = $this->getTicketFollowers($ticketId, $locationId);
            if (!empty($skipUsers)) {
                $followers = array_diff($followers, $skipUsers);
            }
            $emails['to'] = $this->getFollowersEmails($followers, $locationId);
            $emails['bcc'] = [];
            if ($isFirstNotification) {
                $firstFollowers = $this->getTicketInitialFollowers($locationId);
                if (!empty($skipUsers)) {
                    $firstFollowers = array_diff($firstFollowers, $skipUsers);
                }                
                $emails['bcc'] = $this->getFollowersEmails($firstFollowers, $locationId);
            }           
            $this->sendTicketNotification($ticketId, $message, $emails, $ticketData);
        }
        
    }

    protected function sendTicketNotification($ticketId, $message, $users, $data)
    {
        // $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
        $title = @$data['Subject'];
        $location = @$data['location_id'];
        /*$priority = \FEGFormat::getTicketPriority(@$data['Priority']);*/
        $locationName = $location . '-' .\SiteHelpers::getLocationInfoById($location, "location_name");
        $createdOn = \DateHelpers::formatDate($data['Created']);
        $users['bcc'][] = "element5@fegllc.com"; 
        $to         = implode(',', $users['to']);
        $bcc         = implode(',', $users['bcc']);
        $reply_to   ='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        /* FEG-2003 Comment out Priority field from Service Requests */
       /* $subject    = "$locationName, $title, [".(strtolower($priority)=="urgent" ? strtoupper($priority):$priority)."][Service Request #{$ticketId}] $createdOn" ;*/
        $subject    = "$locationName, $title, [Service Request #{$ticketId}] $createdOn" ;
//        $headers    = 'MIME-Version: 1.0' . "\r\n";
//        $headers   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//        $headers   .= 'From: ' . CNF_APPNAME . ' <' . $reply_to . '>' . "\r\n";
//        Log::info("**Send Ticket Email => ",[$subject, $message, $headers]);
        $fromName = \Session::get('fid');
        if (empty($fromName)) {
            $fromName = CNF_APPNAME;
        }
        $emailConfigurations = [
            'from' => $reply_to, 
            'replyTo' => $reply_to, 
            'fromName' => $fromName,
            'replyToName' => CNF_APPNAME, 
            'to' => $to, 
            'bcc' => $bcc, 
            'subject' => $subject, 
            'message' => $message,
//            'preferGoogleOAuthMail' => true, // DO NOT UNCOMMENT 
            'isTest' => env('SEND_TICKET_EMAIL_TO_TEST_RECIPIENT', false),
            'configNamePrefix' => 'Ticket-Notification-'.$ticketId,
            'preferGoogleOAuthMail' => true
        ];
        
        if (!empty($data['_base_file_path'])) {
            $emailConfigurations['attach'] = explode(',', $data['_base_file_path']);
        }
        
        FEGSystemHelper::sendSystemEmail($emailConfigurations);
        
//        foreach ($users as $email) {
//            if (!empty($email)) {
//                $to = $email;
//                Log::info("**Send Ticket Email => ",[$to, $subject, $message, $headers]);
//                //enabled on gabe request
//                if (!env('PREVENT_FEG_SYSTEM_EMAIL', false)) {
//                    mail($to, $subject, $message, $headers);
//                }
//              
//            }
//        }
    }
 
    protected function getTicketInitialFollowers($locationId = null) {
        $emails = Ticketfollowers::getDefaultFollowers($locationId, true, true);
        return array_diff(array_unique($emails), ['', null]);
    }
    protected function getTicketFollowers($ticketId, $locationId = null, $type = '') {
        $emails = Ticketfollowers::getAllFollowers($ticketId, $locationId, $type == 'FirstEmail');
        return array_diff(array_unique($emails), ['', null]);
    }
    protected function getFollowersEmails($followerIDs = array(), $locationId = null) {
        $emails = [];
        if (!empty($followerIDs)) {
            $emails = FEGSystemHelper::getUserEmails(implode(',',$followerIDs), null, true);
        }        
        return array_diff(array_unique($emails), ['', null]);
    }

    /**
     * [DEPRECATED] Remove this function in future
     * @param type $departmentId
     * @param type $ticketId
     * @param type $message
     * @param type $data
     */
    protected function departmentSendMail($departmentId, $ticketId, $message, $data)
    {
        $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $departmentId . "");
        $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);
        $subject = "<Location Name>, <Title>, [Service Request #{$ticketId}] <Date Created>" ;
        $reply_to='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        //$headers = 'MIME-Version: 1.0' . "\r\n";
        //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'From: ' . CNF_APPNAME . ' <' . $reply_to . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM users  WHERE users.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
                //Log::info("**Send Emmail => ",[$to, $subject, $message, $headers]);
                //enabled on gabe request
                if (!env('PREVENT_FEG_SYSTEM_EMAIL', false)) {
                    //mail($to, $subject, $message, $headers);
                    if(!empty($to)){
                        FEGSystemHelper::sendSystemEmail(array(
                            'to' => $to,
                            'subject' => $subject,
                            'message' => $message,
                            'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                            'from' => $reply_to,
                            //'bcc' => $bcc,
                            'configName' => 'DEPARTMENT TICKET EMAIL'
                        ));
                    }
                }
                
            }
        }
    }
           
}
