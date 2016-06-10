<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Sximo Sample CRUD </title>
<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="assets/bootstrap/flaty.css">
</head>

<body>
<?php 
require_once('authorization.php');
?>

<div class="container">
        <h1> Create Auto CRUD FROM API </h1>
<hr />
<b>Quick Usage : </b>
<pre>
require_once('authorization.php');
$api->crud('employee'); 
</pre>
  <hr />  
    <?php $api->crud('employee'); ?>
</div>

</body>
</html>
