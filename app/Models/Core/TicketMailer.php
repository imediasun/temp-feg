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
                // send email to all individual users
                break;

        };
        /*
        echo '<pre>';
        print_r($type);
        print_r($data);
        print_r($object);
        echo '</pre>';
        exit;
        */
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

    protected function assignToSendMail($assignTo, $ticketId, $message)
    {
        $assigneesTo = $assigneesTo = \DB::select("select users.email FROM users WHERE users.id IN (" . $assignTo . ")");
        foreach ($assigneesTo as $assignee) {
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