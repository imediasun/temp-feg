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
$id = $_GET['id'];
require_once('authorization.php');
$rest = $api->module($module,array('option'=>'true'))->show($id);
$rows = json_decode($rest['result']);


if(isset($_POST['submit']))
{
	
	$data = array();
	foreach($rows as $key=>$val) 
	{

		$data[$key] = $_POST[$key];
	}	
	$rest = $api->module('employee')->put($id ,$data);
}
	
?>
<div class="container">
<h1> Edit Form </h1>
<hr />
<form method="post">
<table class="table table-striped">
	<body>
	<?php foreach($rows as $key=>$val) {?>
		<tr>		
			<td align="left"><?php echo $key;?></td>
			<td align="left">
				<input type="text" name="<?php echo $key;?>" value="<?php echo $val?>" class="form-control"> 
			</td>
		</tr>
	<?php } ?>			
	</body>
</table>
<button type="submit" name="submit" class="btn btn-primary btn-sm">  Update Data</button>
<a href="index.php" class="btn btn-warning btn-sm"> Back To List </a>
</form>

</div>

</body>
</html>