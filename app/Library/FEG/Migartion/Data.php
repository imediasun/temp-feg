<?php

namespace App\Library\FEG\Migration;

use PDO;
use DB;
use Carbon\Carbon;
use App\Library\MyLog;
use App\Library\FEG\System\FEGSystemHelper;
use App\Library\FEG\System\SyncHelpers;


class Data
{    
    private static $L;
    private static $limit = 1000;
    
    public static function products($params = array()) {
        $table = "products";
        $errorMessage = [];
        $messages = [];
        extract(array_merge(array(
            'cleanFirst' => 1,
            'skipSync' => 0,
            'date' => null,
            '_task' => array(),
            '_logger' => null,            
        ), $params));
        
        $L = FEGSystemHelper::setLogger($_logger);
        $params['sourceDB'] = 'livemysql';
        $params['targetDB'] = 'mysql';
        $params['table'] = $table;
        $params['cleanFirst'] = $cleanFirst;
        
        if ($skipSync != 1) {
            $L->log("Start Database Table copy");
            $ret = FEGSystemHelper::syncTable($params);   
            $L->log("End Database Table copy");   
            $messages[] = "Synced data.";
            
        }
        else {
            $L->log("Skipping syncing of data");   
            $messages[] = "Skipping syncing of data";
        }
        
        $L->log("Start Database Modification");
        
        $qArray = [
//"ALTER TABLE `products` ADD `created_at` DATETIME NULL DEFAULT NULL, ADD `updated_at` DATETIME NULL DEFAULT NULL , ADD `expense_category` int(11) NOT NULL DEFAULT '0'", 

"ALTER TABLE `products`  change `unit_price` `unit_price` decimal(9,5) NULL , change `case_price` `case_price` decimal(10,5) NULL , change `retail_price` `retail_price` decimal(10,5) NOT NULL",   
            
"update products set created_at = now() WHERE created_at IS NULL",
"update products set updated_at = now() WHERE updated_at IS NULL",
"UPDATE products SET item_description = SUBSTRING(CONCAT(id,'-',vendor_description),1,60) ",
"UPDATE `products` SET vendor_description = TRIM(vendor_description) ",
"UPDATE `products` SET sku = TRIM(sku)",
"UPDATE `products` SET sku = REPLACE(sku,' ','')",

"Update products set img = concat(id,'.jpg') where img !=''",

"UPDATE products SET expense_category = 0 ",
"UPDATE products,product_type,order_type SET products.expense_category = '60904' WHERE products.prod_sub_type_id = product_type.id AND product_type.type_description = 'Uniforms'  ",
"UPDATE products,order_type 			 SET products.expense_category = '60904' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND products.prod_sub_type_id = 0",

"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'medium'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Misc'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Lock / Key'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Lock / Key'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Lighting'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Lighting'",

"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Tools'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Tools'", 
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Tech' ",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Tech'",

"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Balls'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Balls' ",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Coin Door'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Coin Door'",
"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Air Hockey'",

"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Fuses'",

"UPDATE products,order_type,product_type SET products.expense_category = '61101' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Parts for Games' AND product_type.product_type = 'Ticket Dispenser'",

"UPDATE products,order_type,product_type SET products.expense_category = '61602' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'Tools'",
"UPDATE products,order_type,product_type SET products.expense_category = '61602' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'crane'",
"UPDATE products,order_type,product_type SET products.expense_category = '61602' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'candy'",

"UPDATE products,order_type 				SET products.expense_category = '61603' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Office Supplies'",

"UPDATE products,order_type 				SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Graphics'",
"UPDATE products,order_type 				SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Instant Win Prizes'",
"UPDATE products,order_type 				SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Parts for Games' ",

"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'High'  ",
"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'High'",
"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for  Games' AND product_type.product_type = 'crane' ",
"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'Key Master'  ",
"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'stinky feet prizes'",
"UPDATE products,order_type,product_type SET products.expense_category = '61701' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type = 'Cosmic Crane'",

"UPDATE products,order_type 				SET products.expense_category = '61703' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Redemption Prizes' ",
"UPDATE products,order_type,product_type SET products.expense_category = '61703' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Tickets-Tokens-Uniforms-Photo Paper-Debit Cards' AND product_type.product_type =  'candy'",
"UPDATE products,order_type,product_type SET products.expense_category = '61703' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type =  'Redemption Prizes' AND product_type.product_type =  'candy'",

"UPDATE products,order_type 				SET products.expense_category = '61605' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Party Supplies'",
"UPDATE products,order_type 				SET products.expense_category = '61605' WHERE products.prod_type_id = order_type.id AND order_type.order_type = 'Party Supplies' ",

"UPDATE products,product_type,order_type SET products.expense_category = '61705' WHERE products.prod_sub_type_id = product_type.id AND product_type.type_description = 'Tickets' ",
"UPDATE products,product_type,order_type SET products.expense_category = '61705' WHERE products.prod_sub_type_id = product_type.id AND product_type.type_description = 'Photo Paper'  ",
"UPDATE products,order_type,product_type SET products.expense_category = '61705' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'FEG_paper'",
"UPDATE products,order_type,product_type SET products.expense_category = '61705' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Parts for Games' AND product_type.product_type = 'FEG_paper'",

"UPDATE products,product_type,order_type SET products.expense_category = '61707' WHERE products.prod_sub_type_id = product_type.id AND product_type.type_description = 'Tokens'  ",

"UPDATE products,order_type,product_type SET products.expense_category = '61709' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Debit card parts' AND product_type.product_type = 'Embed debit card parts'",
"UPDATE products,order_type,product_type SET products.expense_category = '61709' WHERE products.prod_type_id = order_type.id AND products.prod_sub_type_id = product_type.id AND order_type.order_type = 'Debit card parts' AND product_type.product_type = 'SOCOA debit card parts'",

        ];
                
        foreach ($qArray as $q) {
            try {
                    
                if (stripos($q, "ALTER TABLE") >= 0) {
                    DB::statement($q);                    
                }
                elseif (stripos($q, "UPDATE") >= 0) {
                    DB::update($q);
                }
                elseif (stripos($q, "INSERT INTO") >= 0) {
                    DB::insert($q);
                }
                elseif (stripos($q, "DELETE") >= 0) {
                    DB::delete($q);
                }

            }             
            catch (Exception $ex) {
                $L->log("Error: " . $ex->getMessage());
                $L->log("In query: " . $q);
                $errorMessage[] = $ex->getMessage();
            }
        }
        
        $L->log("End Database Migration");
        
        $data = DB::select("Select * from products where expense_category = 0;");
        
        
        
        $errorCount = count($errorMessage);
        $qCount = count($qArray);
        $expCatZeroCount = count($data);
        
        if ($errorCount > 0) {
            if ($errorCount >= $qCount) {
                $messages[] = " Error: None of the queries executed!";
            }
            else {
                $messages[] = " Data updated but with some issues!";
                $messages[] = implode("\r\n<br/>" , $errorMessage);
                
            }            
        }
        else {
            $messages[] = " All Data updated successfully!";
        }
        
        if (isset($data) && count($data) > 0) {
            $messages[] = " However, having ".count($data)." records with 0 as expense category value. Please check and correct and then run again.";
        }
        
        $L->log("RESULT:", $messages);
        return $messages;
    }

}