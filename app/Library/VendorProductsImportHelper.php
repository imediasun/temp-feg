<?php
namespace App\Library;
use App\Models\Product;
use PHPExcel_Reader_HTML;
use PHPExcel_IOFactory;
use App\Library\FEG\System\FEGSystemHelper;


class VendorProductsImportHelper
{

    public static function exportExcel($vendorId, $vendorEmail)
    {
//        return $vendorId;
        ini_set('memory_limit', '1G');
        set_time_limit(0);

        $product = new Product();
        $products = $product->where(['vendor_id' => $vendorId, 'exclude_export' => 0])->get();
        if(!$products){
            return false;
        }
        $fields = [
                'ID',
                'Vendor Description',
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


        $content = $title;
        $content .= '<table border="1">';
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


        $path = "../storage/app/".time().".html";
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
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
        header('Content-disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.xlsx"');

        $fileName = 'vendor-'.$vendorId.'-product-list'. '-' . date("mdYHis").'.xlsx';

        $content = $objWriter->save("../storage/app/vendor-products/".$fileName );

        //Sending mail with Excel file attachment
        $subject = "Products List";
        $file_to_save = storage_path().'/app/vendor-products/' . $fileName;

        $cc = 'vendor.products@fegllc.com';
        $bcc = 'vendor.products@fegllc.com';
        $to = $vendorEmail;
        $message = 'test vendor import mail';
        $sendEmailFromMerchandise = false;
        $from = 'vendor.products@fegllc.com';


        /* current user */
        $google_acc = \DB::table('users')->where('id', \Session::get('uid'))->first();

        $configName = '';
        $sent = FEGSystemHelper::sendSystemEmail(array(
            'to' => $to,
            'cc' => $cc,
            'bcc' => $bcc,
            'subject' => $subject,
            'message' => $message,
            'preferGoogleOAuthMail' => false,
            'sendEmailFromVendorAccount' => true,
            'isTest' => env('APP_ENV', 'development') !== 'production' ? true : false,
//            'configName' => $configName,
            'from' => $from,
            'replyTo' => $from,
            'attach' => $file_to_save,
            'filename' => $fileName,
            'encoding' => 'base64',
            'type' => 'application/vnd.ms-excel',
        ), $sendEmailFromMerchandise);
        if (!$sent) {
            return 3;
        } else {
            return 1;
        }

    }

}
