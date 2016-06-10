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
if(isset($_POST['submit']))
{
	$data = array(
		'LastName' => $_POST['LastName'],
		'FirstName' => $_POST['FirstName'],
		'ReportsTo' => $_POST['ReportsTo'],
		'BirthDate' => $_POST['BirthDate'],
		'HireDate' => $_POST['HireDate'],
		'Phone' => $_POST['Phone'],
		'Email' => $_POST['Email'],
	);
	$rest = $api->module('employee')->store($data);
	header('Location: index.php');
}

?>
<div class="container">
<h1> Edit Form </h1>
<hr />
<form method="post">
<table class="table table-striped">
	<body>
		<tr>		
			<td align="left">LastName</td>
			<td align="left">
			<input type="text" name="LastName" value="" class="form-control"> 
			</td>
		</tr>
		<tr>		
			<td align="left">FirstName</td>
			<td align="left">
				<input type="text" name="FirstName" value="" class="form-control"> 
			
			</td>
		</tr>
		<tr>		
			<td align="left">ReportsTo</td>
			<td align="left">
				<input type="text" name="ReportsTo" value="" class="form-control"> 
			</td>
		</tr>
		<tr>		
			<td align="left">BirthDate</td>
			<td align="left">
				<input type="text" name="BirthDate" value="" class="form-control"> 
			</td>
		</tr>
		<tr>		
			<td align="left">HireDate</td>
			<td align="left">
				<input type="text" name="HireDate" value="" class="form-control"> 
			</td>			
		</tr>						
		<tr>		
			<td align="left">Phone</td>
			<td align="left">
				<input type="text" name="Phone" value="" class="form-control"> 
			</td>			
		</tr>	
		<tr>		
			<td align="left">Email</td>
			<td align="left">
				<input type="text" name="Email" value="" class="form-control"> 
			</td>			
		</tr>	
		<tr>		
			<td align="left">Foto</td>
			<td align="left">
				<input type="text" name="Foto" value="" class="form-control"> 
			</td>			
		</tr>					
	</body>
</table>
<button type="submit" name="submit" class="btn btn-primary btn-sm">  Save Data</button>
<a href="index.php" class="btn btn-warning btn-sm"> Back To List </a>
</form>

</div>

</body>
</html>