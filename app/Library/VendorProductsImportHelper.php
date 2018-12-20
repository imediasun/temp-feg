<?php
namespace App\Library;
use App\Models\Product;
use App\Models\vendor;
use PHPExcel_Reader_HTML;
use PHPExcel_IOFactory;
use App\Library\FEG\System\FEGSystemHelper;


class VendorProductsImportHelper
{
    /**
     * @param $vendorId
     * @param $vendorEmail
     * @return int
     * @throws \PHPExcel_Reader_Exception
     */
    public static function exportExcel($vendorId, $vendorEmail)
    {
//        return $vendorId;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $vendor = vendor::find($vendorId);//get vendor detail.


        $product = new Product();
        $products = $product->where(['vendor_id' => $vendorId, 'exclude_export' => 0])->groupBy('vendor_description')->groupBy('sku')->groupBy('case_price')->orderBy('id','asc')->get();
        if(!$products){
            return false;
        }

        $fileName = 'vendor-'.$vendorId.'-product-list'. '-' . date("mdYHis").'.csv';
        header('Content-type: text/csv');

        // It will be called file.xls
        header('Content-disposition: attachment; filename="'.$fileName.'"');

        $directoryName = storage_path("/app/vendor-products");

        //Check if the directory already exists.
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0755, true);
        }

        $file = fopen(storage_path("/app/vendor-products/".$fileName ), 'w');

        // save the column headers
        fputcsv($file, array('Product ID', 'Item Name', 'SKU', 'UPC/Barcode', 'Item Per Case', 'Case Price', 'Unit Price', 'Reserved Qty'));

        $start = 0;


        foreach ($products as $product)
        {
//            dd($product);
            $start++;
            $data[$start] = [
                                is_string($product->id) ? "=\"$product->id\"" : $product->id,
                                is_string($product->vendor_description) ? "=\"$product->vendor_description\"" : $product->vendor_description,
                                is_string($product->sku) ? "=\"$product->sku\"" : $product->sku,
                                is_string($product->upc_barcode) ? "=\"$product->upc_barcode\"" : $product->upc_barcode,
                                is_string($product->num_items) ? "=\"$product->num_items\"" : $product->num_items,
                                is_string($product->case_price) ? "=\"$product->case_price\"" : $product->case_price,
                                is_string($product->unit_price) ? "=\"$product->unit_price\"" : $product->unit_price,
                                is_string($product->reserved_qty) ? "=\"$product->reserved_qty\"" : $product->reserved_qty
                            ];
        }
        $data[] = [];
        $data[] = [];
        $data[] = ["Don't update Product ID."];


    // save each row of the data
        foreach ($data as $row)
        {
//            dd($row);
            fputcsv($file, $row);
        }

        // Close the file
        fclose($file);

        //Sending mail with Excel file attachment
        $subject = "Products List - [Vendor Product List #$vendorId] ". date('m/d/Y');
        $file_to_save = storage_path().'/app/vendor-products/' . $fileName;

        
        $to = $vendorEmail;

        $sendEmailFromMerchandise = false;
        $from = 'vendor.products@fegllc.com';

        $message = '<p>Hello '.$vendor->vendor_name.',</p>';
        $message .= '<p>Attached you will find the most up-to-date pricing and product information we have for your products. Please download and review this file, making any necessary product updates. Please do not make any changes to the file\'s name. Any new products you have may be added to this file. If you no longer offer a product contained in this file, please delete the row.</p>';
        $message .= '<p>Do not make any changes to the ID field (Column A), except as noted below:</p>';
        $message .= '<ul>';
        $message .= '<ol>Newly added products do not need an ID# added to the file.</ol>';
        $message .= '<ol>If a product needs to be removed, you may remove the entire row, including the ID.</ol>';
        $message .= '<ol>Make no changes to the ID number.</ol></ul>';
        $message .= '<p>SKUs and Barcodes/UPC codes need to have a =" added to the front of them and a " added to the end.</p>';
        $message .= '<p>EXAMPLE 1: ="barcode12345"</p>';
        $message .= '<p>When you have finished making updates, please save the file and attach it to your REPLY ALL to this email.</p>';
        $message .= '<p>Should you have any questions, please REPLY ALL to this email and we\'ll get back to you as soon as possible.</p>';
        $message .= '<p>Best regards,</p>';
        $message .= '<p>The Merchandise Team</p>';
        $message .= '<p>Family Entertainment Group</p>';
        $message .= '<p><a href="https://fegllc.com/">https://fegllc.com/</a></p>';
        $message .= '<p>Phone: (847) 842-6310</p>';
        $message .= '<p>Email: merch.office@fegllc.com</p>';

        /* current user */
        $google_acc = \DB::table('users')->where('id', \Session::get('uid'))->first();
        $isSent = 0;
        // Old email configuration name "Send Product Export To Vendor"
        //Merchandise Vendor Email Configuration name "Send Product Export To Merchandise Vendor"
        //Check if vendor ismerch = yes
        if ($vendor->ismerch == 1){
            $configName = 'Send Product Export To Merchandise Vendor';
            $recipients =  FEGSystemHelper::getSystemEmailRecipients($configName);
            if(!empty($to)){
                $recipients['to'].= ','.$to;
            }

            if($recipients['to']!='') {
                $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                    'subject' => $subject,
                    'message' => $message,
                    'preferGoogleOAuthMail' => false,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'configName' => $configName,
                    'from' => $from,
                    'replyTo' => $from,
                    'attach' => $file_to_save,
                    'filename' => $fileName,
                    'encoding' => 'base64',
                    'type' => 'text/csv',
                )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount = true);
            if (!$sent){
                $isSent = 3;
            }else {
                // Delete temporary file
                $isSent = 1;
            }

            }
        }


        // Game Related Vendor Email Configuration name "Send Product Export To Game Vendor"
        //Check if vendor isgame = yes
        if ($vendor->isgame == 1){
            $configName = 'Send Product Export To Game Vendor';
            $recipients =  FEGSystemHelper::getSystemEmailRecipients($configName);
            if(!empty($to)){
                $recipients['to'].= ','.$to;
            }

            if($recipients['to']!='') {
                $sent = FEGSystemHelper::sendSystemEmail(array_merge($recipients, array(
                    'subject' => $subject,
                    'message' => $message,
                    'preferGoogleOAuthMail' => false,
                    'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
                    'configName' => $configName,
                    'from' => $from,
                    'replyTo' => $from,
                    'attach' => $file_to_save,
                    'filename' => $fileName,
                    'encoding' => 'base64',
                    'type' => 'text/csv',
                )), $sendEmailFromMerchandise, $sendEmailFromVendorAccount = true);

                if (!$sent){
                    $isSent = 3;
                }else {
                    // Delete temporary file
                    $isSent = 1;
                }

            }
        }

        if($isSent == 1){
            unlink(storage_path("/app/vendor-products/" . $fileName));
        }
        return $isSent;

    }

}
