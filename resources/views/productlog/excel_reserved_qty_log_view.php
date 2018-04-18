<?php

$content = '<table border="1">';
$content .= '<tr>';
foreach ($fields as $f) {
    $content .= '<th style="background:#f9f9f9;">' . $f . '</th>';
}
$content .= '</tr>';

foreach ($data as $d) {
    $orderID = $d->order_id;
    $poNumber = "";
    if(!empty($orderID) && $orderID!=0)
    {
        $orderDetails = \App\Models\Order::where('id',$orderID)->first();
        $poNumber = $orderDetails->po_number;
    }
    $content .= '<tr>';
        $content .= '<td> '. $row->vendor_description  .'</td>';
        $content .= '<td>'.((!empty($poNumber) || $poNumber=0)?$poNumber:"No Data").'</td>';
        $content .= '<td>'.($d->adjustment_amount > 0 ? $d->adjustment_amount:($d->adjustment_amount * -1)).'</td>';
        $content .= '<td>'.(!empty($d->adjustment_type=='negative') ? 'Reduced':'Added').'</td>';
        $content .= '<td>'.(!empty($d->reserved_qty_reason) ? $d->reserved_qty_reason:"No Data").'</td>';
        $content .= '<td>'.$d->adjusted_by.'</td>';
        $content .= '<td>'.$d->created_at.'</td>';
    $content .= '</tr>';
}
$content .= '</table>';
$path = "../storage/app/" . time() . ".html";
file_put_contents($path, $content);

// Read the contents of the file into PHPExcel Reader class
$reader = new PHPExcel_Reader_HTML;
$content = $reader->load($path);

// Pass to writer and output as needed
$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
$objPHPExcel = $objWriter->getPHPExcel();

//Finding Serial column
$serialColumn = '';
$d = $objPHPExcel->getActiveSheet()->getRowIterator(2)->current();
$cellIterator = $d->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(false);

if (!isset($excelExcludeFormatting)) {
    $excelExcludeFormatting = [];
}
$excelExcludeFormatting = array_merge([
    'Unit Price',
    'Case Price',
    'Total Spent',
    'Retail Price',
    'Total Cost',
    'Price'
], $excelExcludeFormatting);

foreach ($cellIterator as $cell) {
    if (in_array($cell->getValue(), $excelExcludeFormatting)) {
        $serialColumn = $cell->getColumn();
        //$objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn)->setAutoSize(true);
        $serialCol = $objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn);
        $colString = ($serialCol->getColumnIndex() . '1:' . $serialCol->getColumnIndex() . (count($ds) + 2));

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
header('Content-disposition: attachment; filename="' . ($title . '-' . date("mdYHis")) . '.xlsx"');

global $exportSessionID;
\Session::forget($exportSessionID);
// Write file to the browser
$objWriter->save('php://output');
?>
