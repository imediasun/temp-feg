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
            if(isset($f['attribute']['formater']))
            {
                $f['attribute']['formater']['value'] = $f['attribute']['formater']['value'].':3:false:';
            }
			unset($f['attribute']['hyperlink']);
			$conn = (isset($f['conn']) ? $f['conn'] : array() );
            $row->$f['field']=html_entity_decode(htmlentities($row->$f['field'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-15');
            $content[] = str_replace('$', '', html_entity_decode(AjaxHelpers::gridFormater($row->$f['field'], $row, $f['attribute'], $conn)));
            // GridFormater method is important for CSV. Do not remove this method.
            //$content[] = SiteHelpers::gridDisplay($row->$f['field'],$f['field'],$conn);
		endif;
	}
	fputcsv($fp, $content);

}

fclose($fp);

global $exportSessionID;
\Session::forget($exportSessionID);
\Session::forget($exportID);

?>
