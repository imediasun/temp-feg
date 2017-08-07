<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.csv"');
// create a file pointer connected to the output stream
echo "\xEF\xBB\xBF"; // UTF-8 BOM
$fp = fopen('php://output', 'w');
$title=array();
$content=array();
foreach ($fields as $line)
{
    $title[]=$line;
}

fputcsv($fp,$title);

foreach ($rows as $row) {
    //$row->$Description=html_entity_decode(htmlentities($row->$Description, ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-15');
    //$row->$sku=html_entity_decode(htmlentities($row->$sku, ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-15');
    $data = array($row->Vendor,$row->Description,$row->sku,$row->Unit_Price,$row->Items_Per_Case,$row->Case_Price,$row->Ticket_Value,$row->Order_Type,$row->Product_Type,$row->INACTIVE);
    fputcsv($fp, $data);
}
fclose($fp);

global $exportSessionID;
\Session::forget($exportSessionID);
\Session::forget($exportID);
?>
