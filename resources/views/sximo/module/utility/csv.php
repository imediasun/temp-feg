<?php


// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.csv"');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
// loop over the rows, outputting them
$label=array();
foreach($fields as $f )
{
    if($f['download'] =='1'):
        $conn = (isset($f['conn']) ? $f['conn'] : array() );
        $label[]=$f['label'];
    endif;
}
fputcsv($fp,$label);
foreach ($rows as $row)
{
    $content=array();
	foreach($fields as $f )
	{
		if($f['download'] =='1'):
			$conn = (isset($f['conn']) ? $f['conn'] : array() );
            $row->$f['field']=html_entity_decode(htmlentities($row->$f['field'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-15');
            $content[] = htmlentities(AjaxHelpers::gridFormater($row->$f['field'],$row,$f['attribute'],$conn));
			//$content[] = SiteHelpers::gridDisplay($row->$f['field'],$f['field'],$conn);
		endif;
	}
	fputcsv($fp, $content);
	
}
fclose($fp);
exit;

?>
