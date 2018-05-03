<?php
libxml_use_internal_errors(true);

$rowBreaker = 7;
$content = "";
$pages = 0;
$offset = .70;

$content .= '<table border="1">';
foreach ($rows->chunk(6) as $chunk)
{
    $pages++;
    $content .= '<tr>';
    foreach ($fields as $f) {
        $content .= '<td>' . $f . '</td>';
    }
    $content .= '</tr>';
    $iterator = 0;
    foreach ($chunk  as $r) {
        $iterator++;
        $content .= '<tr>';
        $content .= '<td >'.$r->gameTitle->game_title.'</td>';
        $content .= '<td >'.$r->id.'</td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '<td ></td>';
        $content .= '</tr>';
    }


}
$content .= '</table>';
$path = "../storage/app/" . time() . ".html";
file_put_contents($path, $content);

// Read the contents of the file into PHPExcel Reader class
$reader = new PHPExcel_Reader_HTML;
$content = $reader->load($path);

$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');

$objPHPExcel = $objWriter->getPHPExcel();

$objPHPExcel->getActiveSheet()
    ->getPageSetup()
    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()
    ->getPageSetup()
    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$BAllStyle = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$BOutStyle = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$BottomStyle = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$BLeftRightStyle = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$boldFontStyleArray = array(
    'font' => array(
        'bold' => true,
    ),
);

$verticalCenterAlign = array(
    'alignment' => array(
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    )
);
$horizontalCenterAlign = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);
$fillColor = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'f2f2f2')
        )
);
$bottomThickBorder = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
    )
);
$leftThickBorder = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
    )
);
$rightThickBorder = array(
    'borders' => array(
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
    )
);

$totalRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

//Common Styling
$objPHPExcel->getActiveSheet()->getStyle("A1:K".$totalRows)->applyFromArray($BAllStyle);
$objPHPExcel->getActiveSheet()->getStyle("A1:K".$totalRows)->applyFromArray($verticalCenterAlign);
$objPHPExcel->getActiveSheet()->getStyle("B1:B".$totalRows)->applyFromArray($horizontalCenterAlign);



$dTok = 11.5+$offset+.12;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13+$offset);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10+$offset);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7+$offset);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth($dTok);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth($dTok);

$objSheet = $objPHPExcel->getActiveSheet();

$TotalColumn = $objSheet->getHighestColumn();

//$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$totalRows)->applyFromArray($boldFontStyleArray);

$objPHPExcel->getActiveSheet()->getStyle('A1:K'.$totalRows)
    ->getAlignment()->setWrapText(true);
$rowRepeater = 1;
for($repeater=1;$repeater<=$pages;$repeater++)
{
    $objPHPExcel->getActiveSheet()->getStyle('A'.$rowRepeater.':'.$TotalColumn.$rowRepeater)->applyFromArray($boldFontStyleArray);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$rowRepeater.':'.$TotalColumn.$rowRepeater)->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$rowRepeater.':'.$TotalColumn.$rowRepeater)->applyFromArray($fillColor);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$rowRepeater.':'.$TotalColumn.$rowRepeater)->applyFromArray($horizontalCenterAlign);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$rowRepeater.':'.$TotalColumn.$rowRepeater)->applyFromArray($bottomThickBorder);
    $rowRepeater = ($repeater*$rowBreaker)+1;
}
$objPHPExcel->getActiveSheet()->getStyle('A1:A8')->applyFromArray($leftThickBorder);

for($i=0;$i<=$totalRows;$i++)
{
    $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(70);
}

for($repeater = 1; $repeater<=$pages;$repeater++)
{
    $objPHPExcel->getActiveSheet()->setBreak( 'A'.($rowBreaker*$repeater), PHPExcel_Worksheet::BREAK_ROW );
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
