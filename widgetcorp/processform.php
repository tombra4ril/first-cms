<?php
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");
?>

<?php
	$menu_name = $_POST["subject_name"];
	$position = $_POST["position"];
	$visible = $_POST["visible"];

	if(isset($_POST[subject_submit])){
		insert_subject($link, $menu_name, $position, $visible);
	}
	
	header("Location: content.php");
	exit;
?>

<?php include("includes/clsconnection.php"); ?>