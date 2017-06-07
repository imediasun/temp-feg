<?php

namespace App\Console\Commands;
require_once('setting.php');
use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Library\FEG\System\FEGSystemHelper;

class ResetEmailsToAllActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetemails:send';

    protected $L = null;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Password Reset Emails to All Active Users';
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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        // don't send password Reset Emails
        if (env('DONT_SEND_PASSWORD_RESET_EMAILS', true) === true) {
            return;
        }
        global $__logger;
        $L = $this->L = $__logger = FEGSystemHelper::setLogger($this->L, "send_password_reset_emails.log", "FEGCronTasks", "PASSWORDRESET");
        $L->log('Start Sending Emails');
        $now = date('Y-m-d H:i:s');
        $nowStamp = strtotime($now);
        $lastRun = FEGSystemHelper::getOption('SendingPasswordResetEmails', '');
        if (!empty($lastRun)) {
            $lastRunTimestamp = strtotime($lastRun);
            if ($nowStamp - $lastRunTimestamp < (300)) { //wait for 5 minutes
                $L->log("Task to send Password Reset emails already running since $lastRun. Quit.");
                return;
            }
        }
        FEGSystemHelper::updateOption('SendingPasswordResetEmails', $now);
        // get emails of active users
        $user_data=\DB::select('SELECT id,email FROM users WHERE active=1 AND forget_password_sent = 0');
        $subject = "[ " . CNF_APPNAME . " ] REQUEST PASSWORD RESET ";
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . CNF_APPNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
        /* for every user... */
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($user_data as $email) {
                if (isset($email->email) && !empty($email->email)) {
                    $data = array('id' => $email->id);
                    $to = $email->email;
                    $message = view('user.emails.auth.reminder-all', $data);
                    $L->log("Sending Email To: ", $email->email);
                    //@todo please enable email line in producton environment when itneded to send emails to all users
                    FEGSystemHelper::sendSystemEmail(['to' => $to,
                        'subject' => $subject,
                        'message' => $message,
                        'headers' => $headers,
                        'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                        'from' => CNF_EMAIL,
                        'configName' => 'Password Reset Email To All Users'
                    ]);
                    $L->log("Email Sent Successfully To: ", $email->email);
                }

            }
        }
        $L->log('------------------ END -------------------');
    }
}
