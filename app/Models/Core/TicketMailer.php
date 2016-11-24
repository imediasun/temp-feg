<?php
/**
 * Created by PhpStorm.
 * User: shayan
 * Date: 11/22/2016
 * Time: 6:06 PM
 */

namespace App\Models\Core;


class TicketMailer
{
    function callBack($object,$type,$data){
        //@todo

        switch($type){
            case 'AddComment':
                //fetch ticket settings
                // send email to all group users
                // $this->departmentSendMail($data['department_id'],$data['ticketId'],$data['message']);

                $settings = (array)\DB::table('sbticket_setting')->first();
                $groupIds = array_unique(explode(",",$settings['role2'].','.$settings['role5']));
                $users = (array) \DB::table('users')->whereIn('group_id', $groupIds)->get();;
                $indvisuals = array_unique(explode(",",$settings['individual2'].','.$settings['individual5']));
                $indvisualUser = (array) \DB::table('users')->whereIn('id', $indvisuals)->get();
                $users = array_merge($users,$indvisualUser);
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
        $headers .= 'From: ' . CNF_REPLY_TO . ' <' . CNF_REPLY_TO . '>' . "\r\n";

        foreach ($department_memebers as $i => $id) {
            $get_user_id_from_employess = \DB::select("Select users.email FROM users  WHERE users.id = " . $id . "");
            if (isset($get_user_id_from_employess[0]->email)) {
                $to = $get_user_id_from_employess[0]->email;
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
                $headers .= 'From: ' . CNF_REPLY_TO . ' <' . CNF_REPLY_TO . '>' . "\r\n";
                mail($to, $subject, $message, $headers);
            }
        }
    }

}