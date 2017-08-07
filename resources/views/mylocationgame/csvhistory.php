<?php


// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.($title . '-' . date("mdYHis")).'.csv"');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
$title=array();
$content=array();
foreach ($fields as $line)
{
    $title[]=$line;
}
$title = array_keys($fields);
fputcsv($fp,$title);
foreach ($rows as $row) {
    $data = [];
    foreach($fields as $field) {
        $value = isset($row->$field) ? $row->$field : '';
        $data[] = html_entity_decode($value);
    }
    fputcsv($fp, $data);
}

fclose($fp);

global $exportSessionID;
\Session::forget($exportSessionID);
\Session::forget($exportID);

?>
