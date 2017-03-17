<?php namespace App\Models\Feg\System;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sximo;

class Pass extends Sximo  {
	protected $table = 'feg_special_permissions';
	protected $tableMaster = 'feg_special_permissions_master';

    public function master() {
        return $this->belongsTo('App\Models\Feg\System\PassMaster', 'permission_id');
    }
    
    public static function getPasses($moduleId) {
        
        $models = self::where('module_id', $moduleId)->orWhere('module_id', 0)->get();
        foreach($models as $model) {
            $values = (array) $model->attributes;
            $parentValues = (array) $model->master->attributes;
            unset($parentValues['id']);
            $pass = (object) array_merge($parentValues, $values);
            $data[] = $pass;
        }
        return $data;
    }
    
    public static function getGrid() {
        $obj = new self;        
        
        $columns = self::getColumnTable($obj->table);
        $parentColumns = self::getColumnTable($obj->tableMaster);        
        $grid = self::buildGrid(array_merge($parentColumns, $columns));
        
        return $grid;
    }
    public static function buildGrid($columns) {
        
        $removeColumns = ['id', 'created_at', 'updated_at', 
                'permission_id', 'module_id', 'is_global',
                'data_type', 'data_options', 'default_value',
            ];
        $labels = [
            'group_ids' => 'user_groups',
            'exclude_user_ids' => 'exluded',
            'user_ids' => 'individual_users',
            'data_type' => 'input_type',
            'data_options' => 'input_options',
            'custom_emails' => 'include_custom_emails',
        ];
        $columnOrder = ['config_title' => '', 'config_name' => '', 
                'config_description' => '', 'config_value' => '', 
                'group_ids' => '', 'exclude_user_ids' => '', 'user_ids' => ''
            ];
        $hideByDefault = ['config_name', 'config_value', 'custom_emails', 'priority'];
        
        $defaultInput = [
            'config_title' => ['text', '', ['required' => true,]],
            'config_name' => ['text', '',],
            'config_description' => ['__textarea', ''],
            'data_type' => ['dynamic', ''],
            'data_options' => ['data_type', ''],
            'default_value' => ['data_options', ''],
            'config_value' => ['data_options', ''],
            'group_ids' => ['select', '', [
                'type' => 'external', 
                'table'=> 'tb_groups', 
                'key' => 'group_id', 
                'value' => 'name', 
                'search' => '', 
                'multiple' => true,
                ]
            ],
            'exclude_user_ids' => ['select', '', [
                'type' => 'external', 
                'table'=> 'users', 
                'key' => 'id', 
                'value' => 'first_name|last_name', 
                'search' => '', 
                'multiple' => true,
                'required' => false,
                ]
            ],
            'user_ids' => ['select', '', [
                'type' => 'external', 
                'table'=> 'users', 
                'key' => 'id', 
                'value' => 'first_name|last_name', 
                'search' => '', 
                'multiple' => true,
                'required' => false,
                ]
            ],
            'custom_emails' => ['text', ''],
            'is_active' => ['__checkbox', '1']
        ];
        
        foreach($removeColumns as $columnName) {
            unset($columns[$columnName]);
        }
        $orderedColumns = array_merge($columnOrder, $columns);
        
        $grid = [];
        foreach($orderedColumns as $columnName => $item) {
            if (isset($columns[$columnName])) {
                
                $label = isset($labels[$columnName]) ? $labels[$columnName] : $columnName;
                $formattedLabel = \FEGFormat::field2title(str_replace('config_', '', $label));
                
                $input = empty($defaultInput[$columnName]) ? [] : $defaultInput[$columnName];              
                $type = empty($input[0]) ? '' : $input[0];
                
                $grid[$columnName] = [
                    'field' => $columnName,
                    'colClass' => 'permissionHeader '.$columnName,
                    'label' => $formattedLabel,
                    'rawLabel' => $label,
                    'hidden' => in_array($columnName, $hideByDefault),
                    'view' => 1,
                    'type' => $type,
                    'options' => isset($input[2]) ? $input[2] : [],
                ];
            }     
        }
        return $grid;
    }
    
    
}
