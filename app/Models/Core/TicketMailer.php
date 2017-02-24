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
            
            $followers = $this->getTicketFollowers($ticketId, $locationId, $type);
            if (!empty($skipUsers)) {
                $followers = array_diff($followers, $skipUsers);
            }
            $emails = $this->getFollowersEmails($followers, $locationId);
            $this->sendTicketNotification($ticketId, $message, $emails, $ticketData);
        }
        
    }

    protected function sendTicketNotification($ticketId, $message, $users, $data)
    {
        // $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
        $title = @$data['Subject'];
        $location = @$data['location_id'];
        $locationName = $location . '-' .\SiteHelpers::getLocationInfoById($location, "location_name");
        $createdOn = \DateHelpers::formatDate($data['Created']);
        
        $to         = implode(',', $users);
        $reply_to   ='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        $subject    = "[Service Request #{$ticketId}] $locationName, $createdOn, $title" ;
//        $headers    = 'MIME-Version: 1.0' . "\r\n";
//        $headers   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//        $headers   .= 'From: ' . CNF_APPNAME . ' <' . $reply_to . '>' . "\r\n";
//        Log::info("**Send Ticket Email => ",[$subject, $message, $headers]);
        
        FEGSystemHelper::sendSystemEmail([
            'from' => $reply_to, 
            'fromName' => CNF_APPNAME, 
            'to' => $to, 
            'subject' => $subject, 
            'message' => $message, 
            'isTest' => env('SEND_TICKET_EMAIL_TO_TEST_RECIPIENT', false),
            'configNamePrefix' => 'Ticket-Notification-'.$ticketId,
        ]);
        
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
 
    protected function getTicketFollowers($ticketId, $locationId = null, $type = '') {
        $emails = Ticketfollowers::getAllFollowers($ticketId, $locationId, $type == 'FirstEmail');
        return array_diff(array_unique($emails), ['', null]);
    }
    protected function getFollowersEmails($followerIDs = array(), $locationId = null) {
        $emails = [];
        if (!empty($followerIDs)) {
            $emails = FEGSystemHelper::getUserEmails(implode(',',$followerIDs), $locationId);
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
        $subject = "[Service Request #{$ticketId}] <Location Name>, <Date Created>, <Title>" ;
        $reply_to='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . CNF_APPNAME . ' <' . $reply_to . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM users  WHERE users.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
                Log::info("**Send Emmail => ",[$to, $subject, $message, $headers]);
                //enabled on gabe request
                if (!env('PREVENT_FEG_SYSTEM_EMAIL', false)) {
                    mail($to, $subject, $message, $headers);
                }
                
            }
        }
    }
           
}