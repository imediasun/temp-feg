<?php
$data = array("name" => "Hagrid", "age" => "36");
$data_string = json_encode($data);

$ch = curl_init('http://localhost/sximo/public/fegapi?module=freightquoters');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);
file_put_contents('error.html',$result);
