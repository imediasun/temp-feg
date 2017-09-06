<?php


		
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

	foreach ($categories as $key=>$category)
	{
		$counts = 1;
		foreach ($rows->where('Order_Type',$category->order_type) as $row)
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
					$a = htmlentities(strip_tags(AjaxHelpers::gridFormater($row->$f['field'],$row,$f['attribute'],$conn,1)));
					$b = str_replace( ',', '', $a );
					$c = str_replace('$','',$b);
					if( is_numeric( $c ) ) {
						$a = $c;
					}
					$content .= '<td> '. strip_tags(($a)) . '</td>';
				endif;
			}
			$content .= '</tr>';
			$counters[$key] = $counts;
			$counts++;
		}
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

	foreach ($cellIterator as $cell) {
		$column = $cell->getColumn();
		$objSheet->getStyle($column."2")->getAlignment()->setWrapText(true);
		$objSheet->getStyle($column."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objSheet->getStyle($column."2")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		if($cell->getValue() == 'Serial'){
			$serialColumn = $cell->getColumn();

			break;
		}
	}
	$TotalColumn = $objSheet->getHighestColumn();
	$UnitInventoryColumn = 'L';
	$UnitPriceColumn = 'F';
	$CasePriceColumn = 'G';
	$TotalSpentColumn = 'I';
	$OrderTypeColumn = 'J';
	$ProductTypeColumn = 'K';
	$ProductColumn = 'A';
	$SkuColumn = 'D';
	$CasePackColumn = 'E';
	$QuantityOrderedColumn = 'H';
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
		if(($i+1) != count($counters))
		{
			$endOn = $startFrom + $counters[$i+1];
		}
		else
		{
			$endOn = count($rows);
		}

	}
$objSheet->getStyle($TotalColumn.'3:'.$TotalColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getStyle($TotalSpentColumn.'3:'.$TotalSpentColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getStyle($UnitPriceColumn.'3:'.$UnitPriceColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getStyle($CasePriceColumn.'3:'.$CasePriceColumn.($lastRow+$totalCounters))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
$objSheet->getColumnDimension($TotalColumn)->setWidth(15);
$objSheet->getColumnDimension($TotalSpentColumn)->setWidth(12);
$objSheet->getColumnDimension($UnitPriceColumn)->setWidth(12);
$objSheet->getColumnDimension($UnitInventoryColumn)->setWidth(12);
$objSheet->getColumnDimension($CasePriceColumn)->setWidth(12);
$objSheet->getColumnDimension($OrderTypeColumn)->setWidth(20);
$objSheet->getColumnDimension($ProductTypeColumn)->setWidth(20);
$objSheet->getColumnDimension($ProductColumn)->setWidth(20);
$objSheet->mergeCells($ProductColumn."1:".$TotalColumn."1");
$objSheet->getStyle($ProductColumn."1")->getAlignment()->setWrapText(true);
$objSheet->getStyle($ProductColumn."1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle($ProductColumn."1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objSheet->getStyle($ProductColumn."1")->getFont()->setSize(18);
$objSheet->getStyle($SkuColumn."3:".$SkuColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle($CasePackColumn."3:".$CasePackColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objSheet->getStyle($QuantityOrderedColumn."3:".$QuantityOrderedColumn.($lastRow+$totalCounters))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//$objSheet->getColumnDimension($serialColumn)->setWidth(50);
$endOn = $endOn+2;
$objSheet->insertNewRowBefore($endOn, 1);
$totalsRowStart = $endOn;
$objSheet->setCellValue(
	"A".$totalsRowStart,
	"Totals"
);
dd($categories,$totalsCells);
	foreach($categories as $key=>$category)
	{
		$endOn++;
		$objSheet->insertNewRowBefore($endOn, 1);
		$objSheet->setCellValue(
			"A".$endOn,
			"$category->order_type"
		);
		$objSheet->setCellValue(
			"B".$endOn,
			"=$totalsCells[$key]"
		);
	}
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"Total Location Inventory"
);
$objSheet->setCellValue(
	"B".$endOn,
	"=SUM(B$totalsRowStart:B$endOn)"
);
$objSheet->getStyle("A$totalsRowStart:B$endOn")->applyFromArray(
	array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => '#ffff00')
		)
	)
);
$endOn = $endOn+2;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"$category->order_type"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"$category->order_type"
);
$endOn++;
$objSheet->insertNewRowBefore($endOn, 1);
$objSheet->setCellValue(
	"A".$endOn,
	"$category->order_type"
);
	$objPHPExcel->getDefaultStyle()
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

//$objSheet->protectCells('A1:B1', 'PHP');//password protected
//$objSheet->getStyle('A2:B2')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);//unlocked
//$objSheet->getProtection()->setSheet(true);//locked sheet
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
