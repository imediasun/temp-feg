<?php

class DateHelpers
{

    public static function formatDate($date)
    {
        if (preg_match('/[1-9]/', $date) && !is_null($date)) {
            $oDate = new \DateTime($date);
            return $newDateString = $oDate->format('m/d/Y');
        } else {
            return "No Data";
        }

    }

    public static function formatDateTime($date)
    {
        if (preg_match('/[1-9]/', $date) && !is_null($date)) {
            return date("m/d/Y H:i:s", strtotime(str_replace("/", "-", $date)));
        } else {
            return "No Data";
        }

    }

    public static function formatDateCustom($date, $format = "m/d/Y h:i:s A")
    {
        $formattedValue = '';
        $customFormats = [
            "usdate" => 'm/d/y',
            "usdatetime" => 'm/d/Y h:i:s A',
            "usdatetime_wos" => 'm/d/Y h:i A',
            "usdtime" => 'h:i:s A',
            "usdtime_wos" => 'h:i A',
        ];
        if (isset($customFormats[$format])) {
            $format = $customFormats[$format];
        }

        if (preg_match('/[1-9]/', $date) && !is_null($date)) {
            $formattedValue = date($format, strtotime(str_replace("/", "-", $date)));
        }
        return $formattedValue;
    }

    protected static function formatValue($val,$nodata)
    {
        if (($val === 0 || $val == null || $val == "0" || empty($val) || $val == ""||  strtolower($val) === "null") && $nodata == 0) {
            return "No Data";
        } else {
            return $val;
        }
    }

    public static function formatZeroValue($val,$nodata=0)
    {
        return self::formatValue($val,$nodata);
    }

    public static function formatStringValue($val,$nodata=0)
    {
        return self::formatValue($val,$nodata);
    }

    // formatting 2 values used in module->view
    public static function formatMultiValues($val1, $val2)
    {
        if ($val1 === 0 || $val1 == null || $val1 == "0" || empty($val1) || $val1 == "" || strtolower($val1) === "null") {
            return "No Data";
        } elseif ($val2 === 0 || $val2 == null || $val2 == "0" || empty($val2) || $val2 == "" || strtolower($val2) === "null") {
            return "No Data";
        } else {
            return $val1 . " " . $val2;
        }
    }
}
