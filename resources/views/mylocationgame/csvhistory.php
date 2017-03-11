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

//if($type=="move") {
//    foreach ($rows as $row) {
//        $data = array($row->game_id, $row->game_title, $row->from_loc , $row->from_location, $row->from_name, $row->from_date, $row->to_loc, $row->to_location, $row->to_name, $row->to_date);
//        fputcsv($fp, $data);
//    }
//}
//
//elseif($type=="pending")
//{
//    foreach ($rows as $row) {
//        $data = array($row->Manufacturer, $row->Game_Title , $row->version, $row->serial, $row->id, $row->location_id , $row->city, $row->state, $row->Wholesale,$row->Retail,$row->notes);
//        fputcsv($fp, $data);
//    }
//}
//elseif($type=="forsale")
//{
//    foreach ($rows as $row) {
//        $data = array($row->Manufacturer, $row->Game_Title , $row->version, $row->serial, $row->date_service, $row->location_id , $row->city, $row->state, $row->Wholesale,$row->Retail);
//        fputcsv($fp, $data);
//    }
//}
fclose($fp);

?>
