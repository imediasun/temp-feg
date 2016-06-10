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
$id = $_GET['id'];
$rest = $api->module($module)->show($id);
$rows = json_decode($rest['result']);
?>
<div class="container">
<h1> View Detail </h1>
<hr />
<table class="table table-bordered table-striped">
	<body>
	<?php foreach($rows as $key=>$val) {?>
		<tr>		
			<td align="left"><?php echo $key;?></td>
			<td align="left"><?php echo $val;?></td>
		</tr>
	<?php } ?>	

	</body>
</table>
<a href="edit.php?id=<?php echo $_GET['id'];?>" class="btn btn-primary btn-sm"> Edit </a>
<a href="index.php" class="btn btn-warning btn-sm"> Back To List </a>
</div>

</body>
</html>