<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php 
require_once('authorization.php');

$id = $_GET['id'];

$rest = $api->module($module)->delete($id);
header('Location: index.php');

?>

