<?php


		
	$content = $title;
	$content .= '<table border="1">';
	$content .= '<tr>';
	foreach($fields as $f )
	{
		if($f['download'] =='1') $content .= '<th style="background:#f9f9f9;">'. $f['label'] . '</th>';
	}
	$content .= '</tr>';

	foreach ($rows as $row)
	{
		$content .= '<tr>';
		foreach($fields as $f )
		{
			if($f['download'] =='1'):
				if(isset($f['attribute']['formater']))
				{
                    $f['attribute']['formater']['value'] = $f['attribute']['formater']['value'].':3:false:';
				}
				unset($f['attribute']['hyperlink']);
				$conn = (isset($f['conn']) ? $f['conn'] : array() );
                $a = htmlentities(strip_tags(AjaxHelpers::gridFormater($row->$f['field'],$row,$f['attribute'],$conn)));
                $b = str_replace( ',', '', $a );
                $c = str_replace('$','',$b);
                if( is_numeric( $c ) ) {
                    $a = $c;
                }
                $content .= '<td> '. strip_tags(($a)) . '</td>';
			endif;
		}
		$content .= '</tr>';
	}
	$content .= '</table>';
	$path = "../storage/app/".time().".html";
	file_put_contents($path, $content);

	// Read the contents of the file into PHPExcel Reader class
	$reader = new PHPExcel_Reader_HTML;
	$content = $reader->load($path);

	// Pass to writer and output as needed
	$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
	$objPHPExcel = $objWriter->getPHPExcel();
// Set Orientation, size and scaling
/*$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);*/

	//Finding Serial column
	$serialColumn = '';
	$row = $objPHPExcel->getActiveSheet()->getRowIterator(2)->current();
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false);
	foreach ($cellIterator as $cell) {
		if($cell->getValue() == 'Serial'){
			$serialColumn = $cell->getColumn();
			break;
		}
	}

	/*$objPHPExcel->getActiveSheet(0)->getStyle('P1:P97')
	->getNumberFormat()
	->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);*/

	//$objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn)->setWidth(50);

	$objPHPExcel->getDefaultStyle()
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objSheet = $objPHPExcel->getActiveSheet();
$objSheet->protectCells('A1:B1', 'PHP');
$objSheet->getStyle('A2:B2')->getProtection()
    ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
$objSheet->getProtection()->setSheet(true);
	// Delete temporary file
	unlink($path);

	// We'll be outputting an excel file
	header('Content-type: application/vnd.ms-excel');

	// It will be called file.xls
	header('Content-disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.xlsx"');

    global $exportSessionID;
    \Session::forget($exportSessionID);
    \Session::forget($exportID);
	// Write file to the browser
	$objWriter->save('php://output');
	/*
	exit;

	@header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	@header('Content-Length: '.strlen($content));
	@header('Content-disposition: inline; filename="'.($title . '-' . date("mdYHis")).'.xls"');

	echo $content;
	exit;
	*/

/*		

$content = $title;
$content .= '<table border="1">';
$content .= '<tr>';
foreach($fields as $f )
{
	if($f['download'] =='1') $content .= '<th style="background:#f9f9f9;">'. $f['label'] . '</th>';
}
$content .= '</tr>';

foreach ($rows as $row)
{
	$content .= '<tr>';
	foreach($fields as $f )
	{
		if($f['download'] =='1')	$content .= '<td>'. $row[$f['field']] . '</td>';
	}
	$content .= '</tr>';
}
$content .= '</table>';

@header('Content-Type: application/ms-excel');
@header('Content-Length: '.strlen($content));
@header('Content-disposition: inline; filename="'.($title . '-' . date("mdYHis")).'.xls"');

echo $content;
*/	
?>
