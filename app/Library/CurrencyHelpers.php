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
     public static function formatPrice($value,$decimalPlaces=3,$isDollarSign=true, $thousands_sep = ',',$dec_point = '.' , $dolar_space = false ){

         if($dolar_space === false){
             $formattedValue= ($isDollarSign === true)?'$ ':"";
         }else{
             $formattedValue= ($isDollarSign === true)?'$':"";
         }

         $formattedValue .= number_format((float)$value,$decimalPlaces,$dec_point , $thousands_sep);
         return $formattedValue;
     }

}
