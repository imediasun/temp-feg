<?php

namespace App\Console\Commands;

use App\Library\FEG\System\FEGSystemHelper;
use App\Models\Envconfiguration;
use Illuminate\Console\Command;

class CheckEnvConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:checkenv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checkenvconfig command will check ENV configurations.';
    public $L;

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
        global $__logger;
        $L = $this->L = $__logger = FEGSystemHelper::setLogger($this->L, "feg-env-configuration.log", "FEGENVConfigurations/Configurations", "Configurations");
        $L->log('Start testing env');

        if (!empty($_ENV)) {
            $envConfigurations = Envconfiguration::all(['option', 'value'])->toArray();
            $existingConfigurations = $this->compareENV($_ENV, $envConfigurations);
            $newConfigurations = $this->getEnvUpdatedConfigurations($_ENV, $envConfigurations);


            // sort alphabetically by name
            usort($existingConfigurations, function ($a, $b)
            {
                return strlen($a['status']) < strlen($b['status']);
            });
            $data = ['existigConfigurations'=>$existingConfigurations,'newConfigurationsEnv'=>$newConfigurations];

            $message =  view('emails.notifications.dev-team.env-configuration-email',$data)->render();
            $humanDate = FEGSystemHelper::getHumanDate(date('d/m/Y'));
            //[FEG Verification][ENV File] [Dev/Demo/Live] Configurations
            $appENV = Envconfiguration::where('option','=','APP_ENV')->first();
            $appEnv = ($appENV) ? $appENV->value:"APP_ENV not defined in database";
            $subject = '[FEG Verification] [ENV File] ['.$appEnv.'] Configurations - '.$humanDate;
            FEGSystemHelper::sendNotificationToDevTeam($subject,$message);

        } else {

        }
    }

    /**
     * @param $envConfigs
     * @param $dbenvConfigs
     * @return array
     */
    public function compareENV($envConfigs, $dbenvConfigs)
    {
        global $__logger;
        $__logger->log('Comparing ENV file with database.');
        $excludedExtraVariables = [];
        foreach ($envConfigs as $envConfig=>$value){
            if(Envconfiguration::isExtraVariable($envConfig)){
                $excludedExtraVariables[$envConfig] = (string) $value;
            }
        }
        $envConfigs = $excludedExtraVariables;
        $compareResult = [];
        foreach ($dbenvConfigs as $dbEnvConfig) {
            $data = [];
            if (isset($envConfigs[$dbEnvConfig['option']])) {
                $data = [
                    'option' => $dbEnvConfig['option'],
                    'value' => $dbEnvConfig['value']
                ];
                $data['status'] = ($envConfigs[$dbEnvConfig['option']] == $dbEnvConfig['value']) ? 'OK' : 'FAIL';
                $__logger->log('ENV vs DB:', ['option' => $dbEnvConfig['option'], 'status' => $data['status']]);
            } else {
                $data = [
                    'option' => $dbEnvConfig['option'],
                    'value' => $dbEnvConfig['value']
                ];
                $data['status'] = 'Missing in ENV';
                $__logger->log('ENV vs DB:', ['option' => $dbEnvConfig['option'], 'status' => $data['status']]);
            }
            $compareResult[] = $data;
        }
        return $compareResult;
    }

    /**
     * @param $envConfigs
     * @param $dbenvConfigs
     * @return array
     */
    public function getEnvUpdatedConfigurations($envConfigs, $dbenvConfigs)
    {
        global $__logger;
        $__logger->log('getting ENV updated configurations.');
        $excludedExtraVariables = [];
        foreach ($envConfigs as $envConfig=>$value){
            if(Envconfiguration::isExtraVariable($envConfig)){
                $excludedExtraVariables[$envConfig] = (string) $value;
            }
        }
        $dbenvConfigKeys = [];
        $envConfigKeys = array_keys($envConfigs);
        foreach ($dbenvConfigs as $dbenvConfig) {
            $dbenvConfigKeys[] = $dbenvConfig['option'];
        }

        $diff = array_diff($envConfigKeys, $dbenvConfigKeys);
        $__logger->log('New Configurations found in ENV:', $diff);
        $data = [];
        foreach ($diff as $item) {
            $data[] = ['option' => $item, 'value' => $envConfigs[$item], 'status' => 'New'];
        }
        return $data;
    }
}
