<?php

class DateHelpers{

        public static  function formatDate($date){
            if (preg_match('/[1-9]/',$date) && !is_null($date)){
                $oDate = new DateTime($date);
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
    

}