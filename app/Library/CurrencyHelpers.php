<?php
/**
 * File: FormatCurrency.php
 * Date: 1/17/2017
 *
 * PHP version 5
 *
 * @category SmartView
 * @package  SmartView
 * @author   Marcel Tuinstra <marcel.tuinstra@antaris-solutions.net>
 * @license  [url] [description]
 * @link     http://www.antaris-solutions.net
 */

//namespace App\Library;


class CurrencyHelpers
{
    /**
     * General information about method
     * @param $value
     * @return currency with symbol
     */
     public static function formatCurrency($value){

         return '$ '.$value;
     }
     public static function formatPrice($value,$decimalPlaces=2,$isDollarSign=true, $thousands_sep = ',',$dec_point = '.' ,$dolar_space = false ){
         if($dolar_space === false){
             $formattedValue= ($isDollarSign === true)?'$ ':"";
         }else{
             $formattedValue= ($isDollarSign === true)?'$':"";
         }

         if($isDollarSign === false){
             $thousands_sep = '';
         }
         if (strpos($value, '.') === false) {
             $value = $value.'.0';
         }
         $split = explode('.', $value);
         $decimalSection = '';
         if(isset($split[1])){
             $decimalSection = $split[1].'00000';
             $fixed = substr($decimalSection, 0, 2);
             $decimalSection = $dec_point.$fixed.rtrim(substr($decimalSection,2, ($decimalPlaces-2)), '0');
         }
         $decimalPlaces = 0;
         $formattedValue .= number_format((double)$split[0],$decimalPlaces,$dec_point , $thousands_sep);
         return $formattedValue.$decimalSection;
     }

    public static function formatPriceAPI($value, $decimalPlaces = 5, $isDollarSign = true, $thousands_sep = ',', $dec_point = '.', $dolar_space = false)
    {
        if ($dolar_space === false) {
            $formattedValue = ($isDollarSign === true) ? '$ ' : "";
        } else {
            $formattedValue = ($isDollarSign === true) ? '$' : "";
        }

        if ($isDollarSign === false) {
            $thousands_sep = '';
        }
        if (strpos($value, '.') === false) {
            $value = $value . '.0';
        }
        $split = explode('.', $value);
        $decimalSection = '';
        if (isset($split[1])) {
            $decimalSection = $split[1] . '00000';
            $fixed = substr($decimalSection, 0, 5);
            $decimalSection = $dec_point . $fixed . rtrim(substr($decimalSection, 5, ($decimalPlaces - 5)), '0');
        }
        $decimalPlaces = 0;
        $formattedValue .= number_format((double)$split[0], $decimalPlaces, $dec_point, $thousands_sep);
        return $formattedValue . $decimalSection;
    }
    public static function truncateLongText($text,$maxLength){
        if(!empty($text)){

            if(strlen($text) > $maxLength){
                $text = substr($text,0,258)."...truncated - full contents in FEG admin.";
                return $text;
            }else{
                return $text;
            }

        }
        return '';
    }

}
