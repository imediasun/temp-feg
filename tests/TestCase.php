<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    const TEST_SUPER_ADMIN_EMAIL = 'greg@element5digital.com';
    const FEG_TITLE = ' FEG LLC ';

    protected $baseUrl = 'http://localhost';
    public $superAdmin = null;

    public function setUp()
    {
        parent::setUp();
        if(!defined('CNF_APPNAME')){
            require __DIR__.'/../setting.php';
        }
        $this->superAdmin = \App\User::where('email', self::TEST_SUPER_ADMIN_EMAIL)->first();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
