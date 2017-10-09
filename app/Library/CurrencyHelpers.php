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
     public static function formatPrice($value,$decimalPlaces=3,$isDollarSign=true, $thousands_sep = ',',$dec_point = '.'  ){
         $formattedValue= ($isDollarSign === true)?'$ ':"";
         $split = explode('.', $value);
         $decimalSection = '';
         if(isset($split[1])){
             $decimalSection = $split[1].'00000000000000000';
             $decimalSection = $dec_point.substr($decimalSection, 0, $decimalPlaces);
         }
         $decimalPlaces = 0;
         $formattedValue .= number_format((double)$split[0],$decimalPlaces,$dec_point , $thousands_sep);
         return $formattedValue.$decimalSection;
     }

}
