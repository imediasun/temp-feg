<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FEG'S OWN CONFIGURATIONS
    |--------------------------------------------------------------------------
    |
    */

    'specialPermissions' => [
        'moduleDetector' => [
            '*special-permissions/order' => 'order',
            '*special-permissions/order/solo' => 'order',
        ],
        'order' => [
            'removeColumns' => ['id', 'created_at', 'updated_at', 'permission_id', 'module_id', 'is_global', 'default_value'],
            'masterFields' => ['config_title', 'config_name', 'data_type','data_options', 'config_description'],
        ],
    ],

];
