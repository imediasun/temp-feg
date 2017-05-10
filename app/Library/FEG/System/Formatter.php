<?php

namespace App\Library\FEG\System;

use Event;
use PDO;
use DB;
use Route;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\DBHelpers;
use App\Library\DateHelpers;
use App\Library\FEG\System\FEGSystemHelper;

class Formatter
{    
    /**
     * Removed Store internal ID from reader ID
     * For example, reader id "__YP__301" will be trimmed to "301"
     * @param string $reader_id Reader ID as string
     * @return string
     */
    public static function readerIDTrimmer($reader_id) {
        $store_id_position = strripos($reader_id, '_');
        if ($store_id_position !== FALSE) {
            $reader_id = substr($reader_id, $store_id_position + 1);
        }
        return $reader_id;        
    }
    
    /**
     * Set a HTML based gray color to Store internal ID in a reader id
     * For example, reader id "__YP__301" will be trimmed to "<span style='#ccc'>__YP__</span>301"
     * @param string $reader_id Reader ID as string
     * @return string
     */
    public static function readerIDHTMLStyler($reader_id) {
        $action =Route::getCurrentRoute()->getActionName(); 
        $isExportAction = stripos($action, "@getExport") !== false || stripos($action, "@postExport") !== false;
        if ($isExportAction) {
            return $reader_id;
        }
        $store_id_position = strripos($reader_id, '_');
        if ($store_id_position !== FALSE) {
            $reader_id = "<span style='color:#ccc;'>" . 
                    substr_replace($reader_id, "_</span>", $store_id_position, 1);
        }
        return $reader_id;
    }
    
    public static function userToLink($id, $displayValue = "", $inputOptions = []) {
        extract(array_merge([
                'path' => "/core/users/show/", 
                'displayFields' => ["first_name", "last_name"], 
                'target' => '_blank',
                'class' => 'gridLink',
                'delim' => " "
            ], $inputOptions));
        
        $action = Route::getCurrentRoute()->getActionName(); 
        $isExportAction = stripos($action, "@getExport") !== false || stripos($action, "@postExport") !== false;
        
        $newDisplayValue = self::userToName($id, $displayValue, $displayFields, $delim);
        if ($newDisplayValue != $displayValue) {
            $displayValue = $newDisplayValue;
            if ($isExportAction) {
                $url = $displayValue;
            }
            else {
                $url = implode("", [
                    "<a href='",
                    url(),
                    $path,
                    $id.
                    "' ",
                    empty($target) ? "": "target='$target' ",
                    empty($class) ? "": "class='$class' ",
                    empty($style) ? "": "style='$style' ",

                    ">",
                    $displayValue,
                    "</a>"
                ]);
            }
        }
        return $url;
    }
    public static function usersToNames($ids, $separator = ", ", $prefix = "", $suffix = "", $displayFields = [], $delim = " ") {
        $values = [];
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        if (empty($displayFields)) {
            $displayFields = ["first_name", "last_name"];
        }
        
        foreach($ids as $id) {
            $user = \App\Models\Core\Users::where('id', $id)->first();
            if (!empty($user)) {
                $displayValues = [];
                foreach($displayFields as $field) {
                    $displayValues[] = $user->$field;
                }
                $displayValue = implode($delim, $displayValues);
            }
            $values[] = $prefix.$displayValue.$suffix;
        }

        $finalValue = implode($separator, $values);
        return $finalValue;
    }

    public static function userToName($id, $displayValue = "", $displayFields = ["first_name", "last_name"], $delim = " ") {
        $user = \App\Models\Core\Users::where('id', $id)->first();
        if (!empty($user)) {
            $displayValues = [];
            foreach($displayFields as $field) {
                $displayValues[] = $user->$field;
            }
            $displayValue = implode($delim, $displayValues);

        }
        return $displayValue;
    }
    public static function userToEmail($id, $displayValue = "") {
        $email = \App\Models\Core\Users::where('id', $id)->pluck('email');
        if (!empty($email)) {
            $displayValue = $email;
        }
        return $displayValue;
    }
    
    public static function getTicketStatus($value = '', $default = '') {
        return FEGSystemHelper::getLabelFromOptions($value, self::getTicketStatuses(), $default);
    }
    public static function getTicketPriority($value = '', $default = '') {
        return FEGSystemHelper::getLabelFromOptions($value, self::getTicketPriorities(), $default);
    }
    public static function getTicketIssueType($value = '', $default = '') {
        return FEGSystemHelper::getLabelFromOptions($value, self::getTicketIssueTypes(), $default);        
    }    
  
    public static function getTicketStatuses() {
        return \SiteHelpers::getModuleFormFieldDropdownOptions('servicerequests', 'Status');
    }
    public static function getTicketPriorities() {
        return \SiteHelpers::getModuleFormFieldDropdownOptions('servicerequests', 'Priority');
    }
    public static function getTicketIssueTypes() {        
        return \SiteHelpers::getModuleFormFieldDropdownOptions('servicerequests', 'issue_type');
    } 
    
    public static function empty2blank($value = null) {        
        return empty($value) ? '' : $value;
    } 
    public static function delegateTo($value = null) {        
        return is_null($value) ? '' : $value;
    }

    /**
     * Converts all snake case strings (word_word_word) to title case (Word Word Word)
     * @param type $value
     * @return type
     */
    public static function field2title($value = null) {        
        return str_replace('_', ' ', ucwords($value, '_'));
    } 
}
