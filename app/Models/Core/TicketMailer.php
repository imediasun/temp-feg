<?php
namespace App\Models\Core;
use Log;


class TicketMailer
{
    function callBack($object, $type, $data){
        //@todo
        $settings = (array)\DB::table('sbticket_setting')->first();
        $ticketData = $data['ticket'];
        $ticketId = $data['ticketId'];
        $message = $data['message'];
        $locationId = $ticketData['location_id'];

        switch($type){
            case 'AddComment':
                //fetch ticket settings
                // send email to all group users
                // $this->departmentSendMail($data['department_id'],$data['ticketId'],$data['message']);
                
                $role = $settings['role2'].','.$settings['role5'];
                $indivisual = $settings['individual2'].','.$settings['individual5'];
                $users = $this->getUsers($role, $indivisual, $locationId);
                $this->assignToSendMail($ticketId, $message, $users, $ticketData);
                break;
            case 'FirstEmail':
                //Call this when user create first ticket
                $role = $settings['role4'];
                $indivisual = $settings['individual4'];
                $users = $this->getUsers($role, $indivisual, $locationId);
                $this->assignToSendMail($ticketId, $message, $users, $ticketData);
                break;
        };
        
    }
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

    protected function assignToSendMail( $ticketId, $message, $users, $data)
    {
        // $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
        $title = @$data['Subject'];
        $location = @$data['location_id'];
        $locationName = \SiteHelpers::getLocationInfoById($location, "location_name");
        $createdOn = \DateHelpers::formatDate($data['Created']);
        
        $reply_to   ='ticket-reply-'.$ticketId.'@tickets.fegllc.com';
        $subject    = "[Service Request #{$ticketId}] $locationName, $createdOn, $title" ;
        $headers    = 'MIME-Version: 1.0' . "\r\n";
        $headers   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers   .= 'From: ' . CNF_APPNAME . ' <' . $reply_to . '>' . "\r\n";
        Log::info("**Send Ticket Email => ",[$subject, $message, $headers]);
        foreach ($users as $assignee) {
            if (isset($assignee->email)) {
                $to = $assignee->email;
                Log::info("**Send Ticket Email => ",[$to, $subject, $message, $headers]);
                //enabled on gabe request
                if (!env('PREVENT_FEG_SYSTEM_EMAIL', false)) {
                    mail($to, $subject, $message, $headers);
                }
              
            }
        }
    }


    protected function getUsers($role,$indivisual,$loc){
        $groupIds = array_unique(explode(",",$role));
        $users = (array) \DB::table('users')
            ->join('user_locations', 'user_locations.user_id', '=', 'users.id')
            ->where("location_id",$loc)->whereIn('group_id', $groupIds)->get();;
        $indvisuals = array_unique(explode(",",$indivisual));
        $indvisualUser = (array) \DB::table('users')
            ->join('user_locations', 'user_locations.user_id', '=', 'users.id')
            ->where("location_id",$loc)->whereIn('users.id', $indvisuals)->get();
        $users = array_merge($users,$indvisualUser);
        return $users;
    }

}