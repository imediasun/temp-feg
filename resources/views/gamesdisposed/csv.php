<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . ($title . '-' . date("mdYHis")) . '.csv"');
// create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');
// loop over the rows, outputting them
fputcsv($fp, $fields);
foreach ($rows as $row) {
    $row = json_decode(json_encode($row), true);
    fputcsv($fp, $row);
}
fclose($fp);
exit;

?>
