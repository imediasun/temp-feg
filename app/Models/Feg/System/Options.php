<?php namespace App\Models\Feg\System;

use Illuminate\Database\Eloquent\Model;
use App\Library\FEG\System\FEGSystemHelper;

class Options extends Model  {
	protected $table = 'feg_system_options';
    
    public static function getOption($optionName, $default = '', $all = false, $skipInactive = true, $details = false) {
        $value = $default;
        if ($details) {
            $value = new \stdClass();
            $value->option_name = $optionName;
            $value->option_value = $default;
            $value->is_active = 1;
            $value->notes = '';
            $value->created_at = null;
            $value->updated_at = null;
        }
        if ($all) {            
            $value = [$value];
        }        
        $q = self::where('option_name', $optionName);
        if ($skipInactive) {
            $q->where('is_active', 1);
        }
        $data = $q->get()->toArray();
        
        if (!empty($data)) {
            $firstData = $data[0];
            if ($details && $all) {                
                $value = $data;
            }
            elseif ($details) {
                $value = $firstData;
            }
            elseif ($all) {
                $value = [];
                foreach($data as $item) {
                    $value[] = $item['option_value'];
                }
            }
            else {
                $value = $firstData['option_value'];
            }
        }
        
        return $value;
    }
    public static function updateOption($optionName, $value = '', $options = array()) {
        $data = [
                'option_name' => $optionName,
                'option_value' => $value
            ];
        $data['notes'] = isset($options['notes']) ? $options['notes'] : '';
        if (isset($options['is_active'])) {
            $data['is_active'] = 1 * (bool)$options['is_active'];
        }
        
        if (isset($option['id'])) {
            $optionId = $option['id'];
            $id = self::where('id', $optionId)->pluck('id');
            if (empty($id)) {
                if (!empty($optionName)) {
                    self::insert($data);
                }
            }
            else {
                $data['updated_at'] = date("Y-m-d H:i:s");
                self::where('id', $optionId)->update($data);
            }
        }
        else {
            $id = self::where('option_name', $optionName)->pluck('id');
            if (empty($id)) {
                self::insert($data);
            }
            else {
                $data['updated_at'] = date("Y-m-d H:i:s");
                self::where('option_name', $optionName)->update($data);
            }            
        }

        return $value;
    }
    public static function addOption($optionName, $value = '', $options = array()) {
        $data = [
                'option_name' => $optionName,
                'option_value' => $value
            ];
        $data['notes'] = isset($options['notes']) ? $options['notes'] : '';
        if (isset($options['is_active'])) {
            $data['is_active'] = 1 * (bool)$options['is_active'];
        }
        self::insert($data);
        return $value;        
    }    
}
