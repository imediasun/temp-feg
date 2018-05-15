<?php

namespace App\Library\FEG\Utils;

use DB;
use Mail;
use FEGHelp;
use FEGFormat;
use App\Models\Sximo;
use App\Models\Sximo\Module;

use Illuminate\Support\Facades\Schema;

class Tools
{

    public static function injectFieldToModule($module, $injectOptions = [], $options = [])
    {
        $messages = [];
        $backups = [];
        $L = @$options['logger'];
        $C = @$options['commandObj'];
        $LP = @$options['logPath'] . '/config';
        $instance = "c-" . date('Y-m-d-his');

        /**  @var $moduleData Sximo */
        $moduleData = Module::where('module_name', $module)->first();
        if (empty($moduleData)) {
            $messages = ['error' => 'Unable to find module by name: ' . $module];
            return $messages;
        }
        $moduleId = $moduleData->module_id;
        FEGHelp::logPlus("\n      => Module `$module` found having module_id: $moduleId ", $L, null, $C);


        FEGHelp::logPlus("        ... retrieving configuration", $L, null, $C);
        $configRaw = $moduleData->module_config;
        FEGHelp::logPlus('Config RAW:', $L, $configRaw);
        $backupfile = $instance . '.encoded.txt';
        FEGHelp::logit($configRaw, $backupfile, $LP, true);
        FEGHelp::logPlus("            -> backup encoded configuration in {$LP}/{$backupfile} file.", $L, null, $C);
        $backups['Old Configuration (encoded) has been stored in file'] = "{$LP}/{$backupfile}";

        $config = null;
        if (empty($configRaw)) {
            $messages = ['error' => 'Unable to find encoded config data in database for module: ' . $module];
            return $messages;
        }

        $config = \SiteHelpers::CF_decode_json($configRaw);
        if (empty($config)) {
            $messages = ['error' => 'Unable to decode config data for module: ' . $module];
            return $messages;
        }

        FEGHelp::logPlus('Config JSON:', $L, $config);
        $backupfile = $instance . '.json';
        FEGHelp::logit($config, $backupfile, $LP, true);
        FEGHelp::logPlus("            -> backup json configuration in {$LP}/{$backupfile} file.", $L, null, $C);
        $backups['Old Configuration (json) has been stored in file'] = "{$LP}/{$backupfile}";

        $table = $injectOptions['table'];
        if (empty($table)) {
            $table = $moduleData->module_db;
        }
        FEGHelp::logPlus("\n     => Detected table: $table", $L, null, $C);

        FEGHelp::logPlus("       ... Preparing data  ...", $L, null, $C);

        $field = $injectOptions['field-name'];
        $defaultLabel = FEGHelp::desanitizeTitleId($field);

        $fieldDefs = [];
        $formDefs = [
            "field" => $field,
            "alias" => $table,
        ];
        $gridDefs = [
            "field" => $field,
            "alias" => $table,
        ];
        foreach ($injectOptions as $key => $value) {
            if ($value === 'null') {
                $value = null;
            }
            if ($value === 'true' || $value === true) {
                $value = 1;
            }
            if ($value === 'false' || $value === false) {
                $value = 0;
            }

            if (starts_with($key, 'field-')) {
                $fieldDefs[substr($key, 6)] = $value;
            }
            if (starts_with($key, 'grid-')) {
                $gridDefs[substr($key, 5)] = $value;
            }
            if (starts_with($key, 'form-')) {
                $formDefs[substr($key, 5)] = $value;
            }
        }


        if (!$fieldDefs['exists'] && empty($injectOptions['simulate'])) {
            if (!Schema::hasTable($table)) {
                $messages = ['error' => "Table $table does not exist!"];
                return $messages;
            }

            if (Schema::hasColumn($table, $field)) {
                $messages = ['error' => "Field $field exists in $table!"];
                return $messages;
            }

            //var_dump($fieldDefs);
            //return $messages;
            try {
                Schema::table($table, function ($tableObj) use ($fieldDefs, $L, $C, $LP) {

                    extract($fieldDefs);
                    /** @var  $name */
                    /** @var  $exists */
                    /** @var  $type */
                    /** @var  $nullable */
                    /** @var  $after */
                    /** @var  $before */
                    /** @var  $default */
                    /** @var  $unsigned */
                    /** @var  $length */
                    /** @var  $decimals */
                    $params = [$name];
                    switch ($type) {
                        case 'char':
                        case 'string':
                            if (!empty($length)) {
                                $params[] = $length;
                            }
                            break;
                        case 'decimal':
                        case 'double':
                            if (!empty($length)) {
                                $params[] = $length;
                            }
                            if (!is_null($decimals)) {
                                $params[] = $decimals;
                            }
                            break;
                        case 'longText':
                        case 'longText':
                        case 'mediumText':
                        case 'text':
                        case 'json':
                            $default = null;
                            break;

                    }
                    if (method_exists($tableObj, $type)) {
                        $query = call_user_func_array([$tableObj, $type], $params);
                        if (!empty($before)) {
                            $query->before($before);
                        }
                        if (!empty($after)) {
                            $query->after($after);
                        }
                        if ($nullable) {
                            $query->nullable();
                        }
                        if ($unsigned && in_array($type, ['integer'])) {
                            $query->unsigned();
                        }
                        if (!is_null($default)) {
                            $query->default($default);
                        }
                    }
                });
            } catch (\Exception $ex) {
                $messages = ['error' => '!!!!! Unable to create database field. Error: ' . $ex->getMessage()];
                return $messages;
            }

            FEGHelp::logPlus("      => Added field to database table", $L, null, $C);

        } else {
            if ($fieldDefs['exists']) {
                FEGHelp::logPlus('      => Field is supposed to exist in database table. So can skip.', $L, null, $C);
            } elseif (!empty($injectOptions['simulate'])) {
                FEGHelp::logPlus('      => ** Field not added to database as running in simulation mode', $L, null, $C);
            }

        }

        $gridDefaultData = [
            "language" => [
                "id" => "",
            ],
            "frozen" => 1,
            "limited" => "",
            "conn" => [
                "valid" => "0",
                "db" => "",
                "key" => "",
                "display" => "",
            ],
            "attribute" => [
                "hyperlink" => [
                    "active" => 0,
                    "link" => "",
                    "target" => "modal",
                    "html" => "",
                ],
                "image" => [
                    "active" => 0,
                    "path" => "",
                    "size_x" => "",
                    "size_y" => "",
                    "html" => "",
                ],
                "formater" => [
                    "active" => 0,
                    "value" => "",
                ],
            ],
        ];

        $gridData = array_merge($gridDefaultData, $gridDefs);
        if (empty($gridData['label'])) {
            $gridData['label'] = $defaultLabel;
        }
        if (is_null($gridData['sortlist'])) {
            $gridData['sortlist'] = count($config['grid']) + 1;
        }

        FEGHelp::logPlus("\n      => Preparing definition for `Table Grid` in module configuration", $L, null, $C);
        FEGHelp::logPlus('Grid Definition for new field', $L, $gridData);
        $backupfile = $instance . '.new.table.json';
        FEGHelp::logit($gridData, $backupfile, $LP, true);
        //FEGHelp::logPlus("            -> save Grid Config json in {$LP}/{$backupfile} file.", $L , null, $C);
        $backups['New Grid Configuration (json) has been stored in file'] = "{$LP}/{$backupfile}";

        $formDefaultData = [
            "language" => [
                "id" => "",
            ],
            "form_group" => "0",
            "simplesearchorder" => "",
            "simplesearchfieldwidth" => "",
            "simplesearchoperator" => "equal",
            "advancedsearchoperator" => "equal",
            "simplesearchselectfieldwithoutblankdefault" => "0",
            "limited" => "",
            "option" => [
                "opt_type" => "",
                "lookup_query" => "",
                "lookup_table" => "",
                "lookup_key" => "",
                "lookup_value" => "",
                "is_dependency" => "",
                "select_multiple" => "0",
                "image_multiple" => "0",
                "lookup_search" => "",
                "lookup_dependency_key" => "",
                "path_to_upload" => "",
                "resize_width" => "",
                "resize_height" => "",
                "upload_type" => "",
                "tooltip" => "",
                "attribute" => "",
                "extend_class" => "",
            ],
        ];
        $formData = array_merge($formDefaultData, $formDefs);
        if (empty($formData['label'])) {
            $formData['label'] = $defaultLabel;
        }
        if (is_null($formData['sortlist'])) {
            $formData['sortlist'] = count($config['forms']) + 1;
        }

        FEGHelp::logPlus('      => Preparing definition for `Form Grid` in module configuration', $L, null, $C);
        FEGHelp::logPlus('Form Definition for new field', $L, $formData);
        $backupfile = $instance . '.new.form.json';
        FEGHelp::logit($formData, $backupfile, $LP, true);
        //FEGHelp::logPlus("            -> save Form Config json in {$LP}/{$backupfile} file.", $L , null, $C);
        $backups['New Form Configuration (json) has been stored in file'] = "{$LP}/{$backupfile}";


        FEGHelp::logPlus('      => Merge Grid and Form Definitions to existing config', $L, null, $C);
        if (empty($config['grid'])) {
            $config['grid'] = [];
        }
        $config['grid'][] = $gridData;

        if (empty($config['forms'])) {
            $config['forms'] = [];
        }
        $config['forms'][] = $formData;

        FEGHelp::logPlus("\n      => Backup new json configuration to file", $L, null, $C);
        $backupfile = $instance . '.new.json';
        FEGHelp::logPlus('Final Config JSON', $L, $config);
        FEGHelp::logit($config, $backupfile, $LP, true);
        $backups['New Configuration (json) has been stored in file'] = "{$LP}/{$backupfile}";


        FEGHelp::logPlus("\n      => Encode new json configuration and save to file", $L, null, $C);
        $configRawFinal = \SiteHelpers::CF_encode_json($config);
        $backupfile = $instance . '.new.encoded.txt';
        FEGHelp::logPlus('Final Raw config:', $L, $configRawFinal);
        FEGHelp::logit($configRawFinal, $backupfile, $LP, true);
        $backups['New Configuration (encoded) has been stored in file'] = "{$LP}/{$backupfile}";

        //$moduleData->module_config = $configRawFinal;
        //$saved = $moduleData->save();
        if (empty($injectOptions['simulate'])) {

            try {
                $saved = $moduleData->insertRow(['module_config' => $configRawFinal], $moduleId);
            }
            catch (\Exception $ex) {
                $messages = ['error' => "Unable to save config to database table. Error: ". $ex->getMessage()];
                return $messages;
            }
            if (empty($saved)) {
                $messages = ['error' => 'Unable to save config to database table!'];
                return $messages;
            }
            FEGHelp::logPlus("\n      => Saved Configuration to database table with id:$saved", $L, null, $C);


        } else {
            FEGHelp::logPlus("\n      => ** Database saving skipped as running in simulation mode", $L, null, $C);
        }


        FEGHelp::logPlus("\n====================================================================================", $L, null, $C);
        FEGHelp::logPlus('                  LOG AND DATA BACKUP DETAILS', $L, null, $C);
        FEGHelp::logPlus("Log file is stored in path: storage/logs/" . @$options['logPath'], $L, null, $C);
        foreach ($backups as $key => $path) {
            $path = 'storage/logs/' . $path;
            FEGHelp::logPlus("  $key: $path", $L, null, $C);
        }
        FEGHelp::logPlus("====================================================================================\n\n", $L, null, $C);

        $messages = ['info' => 'Command completed successfully'];

        return $messages;

    }
}
