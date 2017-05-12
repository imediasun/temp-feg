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
         if(empty($value) || $value === "0.00" || $value === "0.00" || $value === 0.00 || $value === 0.000)
         {
             return "No Data";
         }
         return '$ '.$value;
     }
     public static function formatPrice($value){
         if(empty($value) || $value === "0.00" || $value === "0.00" || $value === 0.00 || $value === 0.000)
         {
             return "No Data";
         }
         return '$ '. number_format((float)$value,3);
     }
    
}