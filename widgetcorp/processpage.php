<?php
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");
?>

<?php
	$sub_id = $_GET["id"];
	$page_name = $_POST["page_name"];
	$position = $_POST["position"];
	$visible = $_POST["visible"];
	$content = $_POST["content"];

	if(isset($_POST["submit"]) && ($_POST["submit"] == "page_submit")){
		insert_page($link, $sub_id, $page_name, $position, $visible, $content);
		header("Location: content.php");
		exit;
	}

	
?>

<?php include("includes/clsconnection.php"); ?>