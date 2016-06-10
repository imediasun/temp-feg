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
$rest = $api->module($module,array('limit'=>10,'option'=>'true'))->get( );
$rows = json_decode($rest['result']);
$key  = $rows->key
?>

<div class="container">
        <h1> Sample API </h1>
<hr />
<a href="create.php" class="btn btn-sm btn-primary"> Create New </a>
        <hr />
 <div class="table-responsive">
        <table class="table table-striped">
        <thead>
        <tr>
                <?php foreach($rows->option->label as $title) {?>
                        <th><?php echo $title ;?></th>
                <?php } ?>

                <th>Action</th>
        </tr>
        </thead>
        <tbody>
	<?php foreach($rows->rows as $row ) {?>
		<tr>
            <?php foreach($rows->option->field as $field) {?>
		      <td><?php echo $row->$field;?></td>
            <?php } ?>        
		<td>
		<a href="edit.php?id=<?php echo $row->$key ?>" class="btn btn-sm btn-primary"> Edit </a>
		<a href="show.php?id=<?php echo $row->$key ?>" class="btn btn-sm btn-success"> Show </a>
		<a href="delete.php?id=<?php echo $row->$key ?>" class="btn btn-sm btn-danger"> Delete </a>

		</td>
		

		</tr>

        	<?php }?>

        </tbody>

</table>
</div>
<?php $api->pagination($rows->total);?>
</div>

</body>
</html>
