<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\MyLog;
use FEGHelp;


class InjectFieldToModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:field:inject {module} {field}
            {--simulate} 
            {--table=} 
            {--field-exists} 
            {--field-type=longText} 
            {--field-nullable=true}
            {--field-after=null} 
            {--field-before=null} 
            {--field-default=null} 
            {--field-unsigned} 
            {--field-length=null}
            
            {--grid-label=null}
            {--grid-view=true}
            {--grid-detail=true}
            {--grid-sortable=true}
            {--grid-search=true}
            {--grid-download=true}
            {--grid-api=true}
            {--grid-inline}
            {--grid-nodata=0}
            {--grid-width=100}
            {--grid-align=left}
            {--grid-sortlist=null}

            {--form-label=null}
            {--form-required}
            {--form-view=true}
            {--form-type=textarea}
            {--form-add=true}
            {--form-size=0}
            {--form-edit=true},
            {--form-search}
            {--form-simplesearch}
            {--form-sortlist=null}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'InjectFieldToModule';

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
        set_time_limit(0);
        $uuid = uniqid();
        $logPath = 'command/modules/inject-field/';

        $arguments = $this->argument();

        if (empty($arguments['module'])) {
            $this->error('Please pass a module name.');
            return;
        }
        if (empty($arguments['field'])) {
            $this->error('Please pass a field name.');
            return;
        }

        $module = $arguments['module'];
        $field = $arguments['field'];
        $defaultOptions = [
            'table' => '',
            'field-name' => '',
            'field-exists' => '',
            'field-type' => 'longText',
            'field-nullable' => '1',
            'field-after' => '',
            'field-before' => '',
            'field-default' => '',
            'field-unsigned' => '',
            'field-length' => '',

            'grid-label' => '',
            'grid-view' => '1',
            'grid-detail' => '1',
            'grid-sortable' => '1',
            'grid-search' => '1',
            'grid-download' => '1',
            'grid-api' => '1',
            'grid-inline' => '0',
            'grid-nodata' => '0',
            'grid-width' => '100',
            'grid-align' => 'left',
            'grid-sortlist' => '',

            'form-label' => '',
            'form-required' => '0',
            'form-view' => '1',
            'form-type' => 'textarea',
            'form-add' => '1',
            'form-size' => '0',
            'form-edit' => '1',
            'form-search' => '0',
            'form-simplesearch' => '0',
            'form-sortlist' => '',


        ];

        $logPath .= $module.'-'.$uuid;
        $L = new MyLog("InjectFieldToModule.log", $logPath, "MODULE");
        FEGHelp::logPlus("Initiate Inject Field - $field to Module - $module", $L, null, $this);

        $L->log('Arguments:', $arguments);
        $options = $this->option();
        $options['field-name'] = $arguments['field'];
        $L->log('Options:', $options);

        if (!empty($options['simulate'])) {
            FEGHelp::logPlus("***** THIS IS A SIMULATION AND WILL NOT WRITE TO DATABASE *******", $L, null, $this);
            FEGHelp::logPlus("***      but will create data files for manual injection    *****", $L, null, $this);
            FEGHelp::logPlus("***               and logs for debugging                    *****", $L, null, $this);
            FEGHelp::logPlus("*****************************************************************", $L, null, $this);
        }


        $messages = \App\Library\FEG\Utils\Tools::injectFieldToModule($module, $options,
            ['logger' => $L, 'commandObj' => $this, 'logPath' => $logPath]);

        foreach($messages as $key => $message) {
            FEGHelp::logPlus($message, $L, null, $this, $key);
        }

        FEGHelp::logPlus("End Inject Field - $field to Module - $module", $L, null, $this);

    }

}
