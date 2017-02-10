<?php

namespace App\Library\FEG\System;

use Event;
use PDO;
use DB;
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
        $store_id_position = strripos($reader_id, '_');
        if ($store_id_position !== FALSE) {
            $reader_id = "<span style='color:#ccc;'>" . 
                    substr_replace($reader_id, "_</span>", $store_id_position, 1);
        }
        return $reader_id;
    }
}
