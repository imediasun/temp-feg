<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$title.' '.date("d/m/Y").'.csv');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
$title=array();
$content=array();
foreach ($fields as $line)
{
    $title[]=$line;
}

fputcsv($fp,$title);

    foreach ($rows as $row) {
        $data = array($row->Vendor,$row->Description,$row->sku,$row->Unit_Price,$row->Items_Per_Case,$row->Case_Price,$row->Ticket_Value,$row->Order_Type,$row->Product_Type,$row->INACTIVE); fputcsv($fp, $data);
}
fclose($fp);
?>
