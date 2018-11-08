<?php

$orders = [
	'Product Name'=>0,
	'Vendor Name'=>1,
	'Ticket Value'=>2,
	'Sku'=>3,
	'Case Pack'=>4,
	'Unit Price'=>5,
	'Case Price'=>6,
	'Quantity Ordered in this time period'=>7,
	'Total Spent'=>8,
	'Order Type'=>9,
	'Product Type'=>10,
	'Total Unit Inventory Count'=>11,
	'Total Inventory Value at Location'=>12
];

$content = $topMessage;
$content .= '<table border="1">';
$content .= '<tr>';
foreach($fields as $f )
{
	if($f['download'] =='1') $content .= '<th style="background:#f9f9f9;">'. $f['label'] . '</th>';
}
$content .= '</tr>';

$rows = collect($rows);
$counters = array();
$excludeCats = [];
foreach ($categories as $key=>$category)
{
	$counts = 1;
	$loopData = $rows->where('Product_Type',$category->order_type);
	if(count($loopData))
	{
		foreach ($loopData as $row)
		{
			$content .= '<tr>';
			foreach($fields as $f )
			{
				if(!isset($f['nodata']))
				{
					$nodata = 0;
				}
				$nodata = 1;
				if($f['download'] =='1'):
					if(isset($f['attribute']['formater']))
					{
						$f['attribute']['formater']['value'] = $f['attribute']['formater']['value'].':2:false:';
					}
					unset($f['attribute']['hyperlink']);
					$conn = (isset($f['conn']) ? $f['conn'] : array() );
					if($f['field'] == 'ticket_value')
					{
						$nodata = 0;
					}
					$a = '';
					if(($f['field']=='Case_Price' || $f['field']=='Unit_Price') && $row->$f['field'] == "____")
					{
						$a = '____';
					}
					else
					{
						if ($f['field'] == 'Cases_Ordered') {
							if (in_array($row->is_broken_case, ['YES', 'yes', 'Yes', 1])) {
								$f['attribute']['formater']['active'] = 0;
							}
						}

						$a = htmlentities(strip_tags(AjaxHelpers::gridFormater($row->$f['field'],$row,$f['attribute'],$conn,$nodata)));
						$b = str_replace( ',', '', $a );
						$c = str_replace('$','',$b);
						if( is_numeric( $c ) ) {
							$a = $c;
						}
					}
					$content .= '<td> '. strip_tags(($a)) . '</td>';
				endif;
			}
			$content .= '</tr>';
			$counters[$key] = $counts;
			$counts++;
		}
	}
	else{
		$excludeCats[] = $key;
	}

}
$counters = array_values($counters);
$content .= '</table>';
$path = "../storage/app/".time().".html";
file_put_contents($path, $content);

// Read the contents of the file into PHPExcel Reader class
$reader = new PHPExcel_Reader_HTML;
$content = $reader->load($path);

// Pass to writer and output as needed
$objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
$objPHPExcel = $objWriter->getPHPExcel();
$objSheet = $objPHPExcel->getActiveSheet();
// Set Orientation, size and scaling
/*$objPHPExcel->setActiveSheetIndex(0);
$objSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objSheet->getPageSetup()->setFitToPage(true);
$objSheet->getPageSetup()->setFitToWidth(1);
$objSheet->getPageSetup()->setFitToHeight(0);*/

//Finding Serial column

$serialColumn = '';
$row = $objSheet->getRowIterator(2)->current();
$cellIterator = $row->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(false);

$objSheet->getRowDimension(1)->setRowHeight(50);
$objSheet->getRowDimension(2)->setRowHeight(80);
$ProductColumn = 'Z';
$VendorColumn = 'Z';
$SkuColumn = 'Z';
$CasePackColumn = 'Z';
$TicketValueColumn = 'Z';
$UnitPriceColumn = 'Z';
$CasePriceColumn = 'Z';
$QuantityOrderedColumn = 'Z';
$TotalSpentColumn = 'Z';
$OrderTypeColumn = 'Z';
$ProductTypeColumn = 'Z';
$ProductSubTypeColumn = 'Z';
$UnitInventoryColumn = 'Z';
$LocationColumn = 'Z';
foreach ($cellIterator as $cell) {
	$column = $cell->getColumn();
	$objSheet->getStyle($column."2")->getAlignment()->setWrapText(true);
	$objSheet->getStyle($column."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle($column."2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	if($cell->getValue() == 'Product Name')
	{
		$ProductColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Vendor Name')
	{
		$VendorColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Sku')
	{
		$SkuColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Case Pack')
	{
		$CasePackColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Ticket Value')
	{
		$TicketValueColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Unit Price')
	{
		$UnitPriceColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Case Price')
	{
		$CasePriceColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Quantity Ordered in this time period')
	{
		$QuantityOrderedColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Total Spent')
	{
		$TotalSpentColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Order Type')
	{
		$OrderTypeColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Product Type')
	{
		$ProductTypeColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Product Subtype')
	{
		$ProductSubTypeColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Total Unit Inventory Count')
	{
		$UnitInventoryColumn = $cell->getColumn();
	}
	else if($cell->getValue() == 'Location')
	{
		$LocationColumn = $cell->getColumn();
	}
}
$TotalColumn = $objSheet->getHighestColumn();
$lastRow = $objSheet->getHighestRow();
for ($row = 3; $row <= $lastRow; $row++) {
	$cell = $objSheet->getCell($TotalColumn.$row);
	$cell->setValue("=$UnitInventoryColumn$row*$UnitPriceColumn$row");
}

$startFrom = 3;
$endOn = $counters[0]+3;
$totalsCells = array();
$totalCounters = count($counters);
for($i = 0;$i < $totalCounters;$i++)
{
	$objSheet->insertNewRowBefore($endOn, 1);
	$objSheet->setCellValue(
		"$TotalColumn".$endOn,
		"=SUM($TotalColumn$startFrom:$TotalColumn".($endOn-1).")"
	);
	$totalsCells[] = "$TotalColumn".$endOn;
	$objSheet->getStyle("A$endOn:$TotalColumn".$endOn)->applyFromArray(
		array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '000000')
			),
			'font'  => array(
				//'bold'  => true,
				'color' => array('rgb' => 'FFFFFF'),
				//'size'  => 15,
				//'name'  => 'Verdana'
			)
		)
	);
	$startFrom = ($endOn+1);
	if (($i + 1) != $totalCounters)
	{
		$endOn = $startFrom + $counters[$i + 1];
	}
	else
	{
		$endOn = count($rows);
	}
}
$objSheet->getStyle($TotalColumn.'3:'.$TotalColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getStyle($TotalSpentColumn.'3:'.$TotalSpentColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getStyle($UnitPriceColumn.'3:'.$UnitPriceColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode('General');
$objSheet->getStyle($CasePriceColumn.'3:'.$CasePriceColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode('General');
$objSheet->getColumnDimension($TotalColumn)->setWidth(15);
$objSheet->getColumnDimension($TotalSpentColumn)->setWidth(12);
$objSheet->getColumnDimension($VendorColumn)->setWidth(15);
$objSheet->getColumnDimension($UnitPriceColumn)->setWidth(12);
$objSheet->getColumnDimension($UnitInventoryColumn)->setWidth(12);
$objSheet->getColumnDimension($CasePriceColumn)->setWidth(12);
$objSheet->getColumnDimension($OrderTypeColumn)->setWidth(20);
$objSheet->getColumnDimension($LocationColumn)->setWidth(20);
$objSheet->getColumnDimension($ProductTypeColumn)->setWidth(20);
$objSheet->getColumnDimension($ProductSubTypeColumn)->setWidth(20);
$objSheet->getColumnDimension($ProductColumn)->setWidth(25);
$objSheet->getColumnDimension('A')->setWidth(30);
$objSheet->mergeCells("A1:".$TotalColumn."1");
$objSheet->getStyle("A1")->getAlignment()->setWrapText(true);
$objSheet->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle("A1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objSheet->getStyle("A1")->getFont()->setSize(18);
$objSheet->getStyle($SkuColumn."3:".$SkuColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle($CasePackColumn."3:".$CasePackColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle($QuantityOrderedColumn."3:".$QuantityOrderedColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objSheet->getColumnDimension($serialColumn)->setWidth(50);
$lastDataEntry = $endOn = $lastRow+$totalCounters+2;
//$objSheet->getStyle("A3:A".($endOn-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
if(!empty($AddNote)) {


	$objSheet->insertNewRowBefore($endOn, 1);
	$totalsRowStart = $endOn;
	$objSheet->setCellValue(
		"A" . $totalsRowStart,
		"Note: ".$AddNote
	);
	$objSheet->mergeCells('A'.$totalsRowStart.':P'.$totalsRowStart);
	$objSheet->getStyle('A'.$totalsRowStart.':P'.$totalsRowStart)->applyFromArray(
		array(
			'font'  => array(
				'color' => array('rgb' => '061ab7'),
			)
		)
	);
	//$objSheet->getStyle("A".$totalsRowStart)->getAlignment()->setWrapText(true);
}
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$totalsRowStart = $endOn;
$objSheet->setCellValue(
	"A".$totalsRowStart,
	"TOTALS"
);
$objSheet->getStyle('A'.$totalsRowStart.':P'.$totalsRowStart)->applyFromArray(
	array(
		'font'  => array(
			'color' => array('rgb' => '000000'),
		)
	)
);
$loopCounter = 0;
foreach($categories as $key=>$category)
{
	if(!in_array($key,$excludeCats))
	{
		$endOn++;
		$objSheet->insertNewRowBefore($endOn, 1);
		$objSheet->setCellValue(
			"A".$endOn,
			"$category->order_type"
		);
		$objSheet->setCellValue(
			"B".$endOn,
			"=$totalsCells[$loopCounter]"
		);
		$objSheet->getStyle("B$endOn")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
		$loopCounter++;
	}
}
$objPHPExcel->getActiveSheet()->getStyle("B$endOn")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Total Location Inventory"
);
$totalsRowStart++;
$totalsRowEnd = $endOn-1;
$objSheet->setCellValue(
	"B".$endOn,
	"=SUM(B$totalsRowStart:B$totalsRowEnd)"
);

$hold = $endOn = $endOn+3;

$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Tokens In Stock:"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Tickets In Stock:"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Game Cards In Stock:"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Debit Cards In Stock:"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Photo Paper In Stock:"
);

$objSheet->getStyle("A$hold:B$endOn")->applyFromArray(
	array(
		'font'  => array(
			'bold'  => true,
		),
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array('argb' => '000000'),
			),
		),

	)
);


if(isset($pass["Users With Limited Access"]))
{

	$objSheet->getProtection()->setPassword('passwordhareherehere');
	$objSheet->getStyle($UnitInventoryColumn."3:".$UnitInventoryColumn.$endOn)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);//unlocked column L
	//$objSheet->getStyle("B$hold:B$endOn")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);//unlocked column L
	$objSheet->getProtection()->setSheet(true);//locked sheet
	//$worksheet->getProtection()->setInsertRows(true);
	$objSheet->getProtection()->setDeleteRows(false);
}
//dd("A$totalsRowStart:A$endOn");

$objSheet->getStyle("A$totalsRowStart:B$endOn")->applyFromArray(
	array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'ffff00')
		)
	)
);
$objPHPExcel->getActiveSheet()->getStyle("B$totalsRowStart:B$endOn")
	->getNumberFormat()
	->setFormatCode('0.00###');
$objPHPExcel->getDefaultStyle()
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objSheet->getPageSetup()->setFitToPage(true);
$objSheet->getPageSetup()->setFitToWidth(1);
$objSheet->getPageSetup()->setFitToHeight(0);
$objSheet->getPageMargins()->setRight(0.3);
$objSheet->getPageMargins()->setLeft(0.3);

if(isset($excelExcludeFormatting) && !empty($excelExcludeFormatting))
{
	foreach ($cellIterator as $cell) {
		if(in_array($cell->getValue(),$excelExcludeFormatting))
		{
			$serialColumn = $cell->getColumn();
			//$objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn)->setAutoSize(true);
			$serialCol = $objPHPExcel->getActiveSheet()->getColumnDimension($serialColumn);
			$colString = ($serialCol->getColumnIndex().'1:'.$serialCol->getColumnIndex() . $endOn);

			if($cell->getValue() == "Case Price" || $cell->getValue() == "Unit Price")
			{
				for ($row = 3; $row <= $lastDataEntry; $row++) {
					$customCell = $objSheet->getCell($serialColumn.$row);
					$value = $customCell->getValue();
					if($value === "____")
					{
						$objPHPExcel->getActiveSheet()->getStyle($serialColumn.$row)
							->getNumberFormat()
							->setFormatCode('');
						$customCell->setValue('USER');
						$objSheet->getStyle($serialColumn.$row)->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					}
					else
					{
						$objPHPExcel->getActiveSheet()->getStyle($serialColumn.$row)
							->getNumberFormat()
							->setFormatCode('0.00###');
					}
				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->getStyle($colString)
					->getNumberFormat()
					->setFormatCode('0.00###');
			}
		}
	}
}


//$objSheet->protectCells('A1:B1', 'PHP');//password protected
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
?>
