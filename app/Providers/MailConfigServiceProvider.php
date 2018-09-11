<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Using the merchandise mail configuration from config file if present
         */
        if ($this->app->request->getRequestUri() !== "/order/save") {
            $config = array(
                'driver' => env('MAIL_MERCH_DRIVER'),
                'host' => env('MAIL_MERCH_HOST'),
                'port' => env('MAIL_PORT'),
                'from' => array('address' => env('MAIL_USERNAME'), 'name' => env('MAIL_NAME')),
                'encryption' => env('MAIL_ENCRYPTION'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'sendmail' => '/usr/sbin/sendmail -bs',
                'pretend' => false,
            );
            \Config::set('mail', $config);
        }
    }
}
