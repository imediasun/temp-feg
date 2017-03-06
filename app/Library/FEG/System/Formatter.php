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

}
