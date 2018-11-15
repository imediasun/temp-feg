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
        $products = $product->where(['vendor_id' => $vendorId, 'exclude_export' => 0])->groupBy('variation_id')->orderBy('id','asc')->get();
        if(!$products){
            return false;
        }
        $fields = [
                'ID',
                'Item Name',
                'SKU',
                'UPC/Barcode',
                'Item Per Case',
                'Case Price',
                'Unit Price',
                'Ticket Value',
                'Is Reserved',
                'Reserved Qty',
                ];
//        return $products;


        $title = 'Products List';
        $AddNote = "Don't update Product ID.";


//        $content = $title;
        $content = '<table border="1">';
        $content .= '<tr>';
        $start = 1;
        foreach($fields as $f )
        {
            $content .= '<th style="background:#f9f9f9;">'. $f . '</th>';
        }
        $content .= '</tr>';

        foreach ($products as $product)
        {
//            dd($product);
            $start++;
            $content .= '<tr>';
            $content .= '<td> '. ($product->id) . '</td>';
            $content .= '<td> '. ($product->vendor_description) . '</td>';
            $content .= '<td> '. ($product->sku) . '</td>';
            $content .= '<td> '. ($product->upc_barcode) . '</td>';
            $content .= '<td> '. ($product->num_items) . '</td>';
            $content .= '<td> '. ($product->case_price) . '</td>';
            $content .= '<td> '. ($product->unit_price) . '</td>';
            $content .= '<td> '. ($product->ticket_value) . '</td>';
            $content .= '<td> '. ($product->is_reserved) . '</td>';
            $content .= '<td> '. ($product->reserved_qty) . '</td>';
            $content .= '</tr>';
        }
        if (!empty($AddNote)){
            $start++;
            $start++;
            $start++;
            $start++;
            $content .='<tr><td></td></tr><tr><td></td></tr>';
            $content .='<tr><td colspan="30"><strong style="color: red;">Note: '.$AddNote.'</strong></td></tr>';
        }
        $content .= '</table>';

        $path = storage_path("/app/".time().".html");
        file_put_contents($path, $content);


        // Read the contents of the file into PHPExcel Reader class
        $reader = new PHPExcel_Reader_HTML;
        // set error level
        $internalErrors = libxml_use_internal_errors(true);

        $content = $reader->load($path);
//        dd($path);

        // Pass to writer and output as needed
        $objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
        $objPHPExcel = $objWriter->getPHPExcel();

        //Finding Serial column
        $serialColumn = '';
        $row = $objPHPExcel->getActiveSheet()->getRowIterator(2)->current();
        $objPHPExcel->getActiveSheet()->getStyle('A'.$start.':P'.$start)->applyFromArray(
            array(
                'font'  => array(
                    'color' => array('rgb' => '061ab7'),
                )
            )
        );
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        if(!isset($excelExcludeFormatting)) {
            $excelExcludeFormatting = [];
        }
        $excelExcludeFormatting = array_merge([
            'Unit Price',
            'Case Price',
            'Total Spent',
            'Retail Price',
            'Total Cost',
            'Price'
        ],$excelExcludeFormatting);

        foreach ($cellIterator as $cell) {
            if(in_array($cell->getValue(),$excelExcludeFormatting))
            {
                $serialColumn = $cell->getColumn();
                //$objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn)->setAutoSize(true);
                $serialCol = $objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn);
                $colString = ($serialCol->getColumnIndex().'1:'.$serialCol->getColumnIndex() . (count($products)+2));

                $objPHPExcel->getActiveSheet()->getStyle($colString)
                    ->getNumberFormat()
                    ->setFormatCode('0.00###');
            }
        }


        // Delete temporary file
        unlink($path);

        // We'll be outputting an excel file
        header('Content-type: text/csv');

        // It will be called file.xls
        header('Content-disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.csv"');

        $fileName = 'vendor-'.$vendorId.'-product-list'. '-' . date("mdYHis").'.csv';

        $directoryName = storage_path("/app/vendor-products");

        //Check if the directory already exists.
        if(!is_dir($directoryName)){
            //Directory does not exist, so lets create it.
            mkdir($directoryName, 0755, true);
        }

        $content = $objWriter->save(storage_path("/app/vendor-products/".$fileName ));

        //Sending mail with Excel file attachment
        $subject = "Products List - [Vendor Product List #$vendorId] ". date('m/d/Y');
        $file_to_save = storage_path().'/app/vendor-products/' . $fileName;

        $cc = 'vendor.products@fegllc.com';
        $bcc = 'vendor.products@fegllc.com';
        $to = $vendorEmail;

        $sendEmailFromMerchandise = false;
        $from = 'vendor.products@fegllc.com';

        $message = '<p>Hello <strong>'.$vendor->vendor_name.'</strong>,</p>';

        $message .= '<p>Attached you will find the most up-to-date pricing and product information we have for your products. Please download and review this file, making any necessary product updates. Please do not make any changes to the file\'s name. Any new products may be added to this file. If you no longer offer a product contained in this file, please delete the row.</p>';

        $message .= '<p>Do not make any changes to the ID field (Column A), except as noted below:</p>';
        $message .= '<ul>';
        $message .= '<ol>1. Newly added products do not need an ID# added to the file.</ol>';
        $message .= '<ol>2. If a product needs to be removed, you may remove the entire row, including the ID.</ol>';
        $message .= '<ol>3. Make no changes to the ID number.</ol></ul>';

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

        $configName = 'Send Product Export To Vendor';
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
            if (!$sent) {
                return 3;
            } else {
                // Delete temporary file
                unlink(storage_path("/app/vendor-products/" . $fileName));
                return 1;
            }
        }

    }

}
