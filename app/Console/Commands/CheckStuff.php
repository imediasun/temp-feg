<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\FEG\System\FEGSystemHelper;

class CheckStuff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:stuff';

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
        if (!env('ENABLE_CHECK_STUFF', false)) {
            return;
        }
        die('here');

        $recipients = ["to" => env('CHECK_STUFF_EMAILS', [])];

        // Check Duplicate PO's
        $orders = \DB::select("SELECT po_number,COUNT(id) AS no_of_time_repeat,date_ordered FROM orders WHERE date_ordered >= '2017-06-01' GROUP BY po_number HAVING no_of_time_repeat > 1");
        if($orders){
            $msgText = '<table border="1"><tr><th>PO</th> <th>Repeated</th> <th>Ordered Date</th></tr>';
            foreach ($orders as $order){
                $msgText .= '<tr><td>'.$order->po_number.'</td> <td>'.$order->no_of_time_repeat.'</td> <td>'.$order->date_ordered.'</td></tr>';
            }
            $msgText .= '</table>';

            FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                'subject' => 'Danger: Duplicate PO Found',
                'message' => $msgText,
                'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                'configName' => 'DUPLICATE PO FOUND ALERT'
            )));
            //dd($msgText);
        }

        //Check Test Data In Users
        $users = \DB::select("SELECT id,email FROM users WHERE email LIKE \"%shayan%\" OR email LIKE \"%@gmail%\" OR email LIKE \"%sadaf%\" OR first_name LIKE \"%arslan%\" OR last_name LIKE \"%ali%\" OR first_name LIKE \"%sadaf%\" OR last_name LIKE \"%sadaf%\" OR first_name LIKE \"%shayan%\" OR last_name LIKE \"%shayan%\";");
        if($users){
            $msgText = '<table border="1"><tr><th>User ID</th> <th>Email</th> </tr>';
            foreach ($users as $user){
                $msgText .= '<tr><td>'.$user->id.'</td> <td>'.$user->email.'</td></tr>';
            }
            $msgText .= '</table>';

            FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                'subject' => 'Critical: Test Data Found In User Module',
                'message' => $msgText,
                'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                'configName' => 'TEST DATA FOUND ALERT'
            )));
            //dd($msgText);
        }

        //Check Test Data In Vendors
        $vendors = \DB::select("SELECT id,email, email_2 FROM vendor WHERE email LIKE \"%shayan%\" OR email LIKE \"%arslan%\" OR email LIKE \"%gmail%\" OR email LIKE \"%sadaf%\" OR email_2 LIKE \"%arslan%\" OR email_2 LIKE \"%gmail%\" OR email_2 LIKE \"%shayan%\" OR email LIKE \"%sadaf%\"");
        if($vendors){
            $msgText = '<table border="1"><tr><th>Vendor ID</th> <th>Email</th> <th>Email 2</th></tr>';
            foreach ($vendors as $vendor){
                $msgText .= '<tr><td>'.$vendor->id.'</td> <td>'.$vendor->email.'</td> <td>'.$vendor->email_2.'</td></tr>';
            }
            $msgText .= '</table>';

            FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                'subject' => 'Critical: Test Data Found In Vendor Module',
                'message' => $msgText,
                'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                'configName' => 'TEST DATA FOUND ALERT'
            )));
            //dd($msgText);
        }

    }
}
