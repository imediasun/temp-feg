<?php

class DateHelpers{

        public static  function formatDate($date){
            if (preg_match('/[1-9]/',$date) && !is_null($date)){
                $oDate = new \DateTime($date);
                return $newDateString = $oDate->format('m/d/Y');
            }
            else{
                return null;
            }

    }
    public static  function formatDateTime($date){
            if (preg_match('/[1-9]/',$date) && !is_null($date)){
                return date("m/d/Y H:i:s",strtotime(str_replace("/","-",$date)));
            }
            else{
                return null;
            }

    }

    public static  function formatDateCustom($date, $format = "m/d/Y h:i:s A"){
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
        
        if (preg_match('/[1-9]/',$date) && !is_null($date)){
            $formattedValue = date($format,strtotime(str_replace("/","-",$date)));
        }
        return $formattedValue;
    }
    
}