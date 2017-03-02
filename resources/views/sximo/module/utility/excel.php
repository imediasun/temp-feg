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
				$conn = (isset($f['conn']) ? $f['conn'] : array() );
				$content .= '<td> '. htmlentities(AjaxHelpers::gridFormater($row->$f['field'],$row,$f['attribute'],$conn)) . '</td>';
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
	// Delete temporary file
	unlink($path);

	// We'll be outputting an excel file
	header('Content-type: application/vnd.ms-excel');

	// It will be called file.xls
	header('Content-disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.xlsx"');

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
