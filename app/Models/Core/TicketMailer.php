<?php
/**
 * Created by PhpStorm.
 * User: shayan
 * Date: 11/22/2016
 * Time: 6:06 PM
 */

namespace App\Models\Core;
use Log;


class TicketMailer
{
    function callBack($object,$type,$data){
        //@todo
        $settings = (array)\DB::table('sbticket_setting')->first();

        switch($type){
            case 'AddComment':
                //fetch ticket settings
                // send email to all group users
                // $this->departmentSendMail($data['department_id'],$data['ticketId'],$data['message']);
                $role=$settings['role2'].','.$settings['role5'];
                $indivisual=$settings['individual2'].','.$settings['individual5'];
                $users = $this->getUsers($role,$indivisual,$data['location_id']);
                $this->assignToSendMail($data['ticketId'],$data['message'],$users);
                break;
            case 'FirstEmail':
                //Call this when user create first ticket
                $role=$settings['role4'];
                $indivisual=$settings['individual4'];
                $users = $this->getUsers($role,$indivisual,$data['location_id']);
                $this->assignToSendMail($data['ticketId'],$data['message'],$users);
                break;
        };
        
    }
    protected function departmentSendMail($departmentId, $ticketId, $message)
    {
        $department_memebers = \DB::select("Select assign_employee_ids FROM departments WHERE id = " . $departmentId . "");
        $department_memebers = explode(',', $department_memebers[0]->assign_employee_ids);

        $subject = 'FEG Ticket #' . $ticketId;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . CNF_APPNAME . ' <' . CNF_REPLY_TO . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM users  WHERE users.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
                Log::info("**Send Emmail => ",[$to, $subject, $message, $headers]);
                //enabled on gabe request
                mail($to, $subject, $message, $headers);
            }
        }
    }

    protected function assignToSendMail( $ticketId, $message,$users)
    {
        // $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
        foreach ($users as $assignee) {
            if (isset($assignee->email)) {
                $to = $assignee->email;
                $subject = 'FEG Ticket #' . $ticketId;
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: ' . CNF_APPNAME . ' <' . CNF_REPLY_TO . '>' . "\r\n";
                Log::info("**Send Emmail => ",[$to, $subject, $message, $headers]);
                //enabled on gabe request
                mail($to, $subject, $message, $headers);
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