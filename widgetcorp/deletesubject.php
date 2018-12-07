<?php
//This file is the deletesubject document
//It is called when the user clicks on the delete link found in the editsubject file
//This file is use for deleting subjects of the 
// widget-corp application (which is purely all php files)

	//This files should are needed before doing anything
	//Header information
	require_once("includes/session.php");
	
	
	//Other relevant files
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");

	
	//if the user did not login, there getting to this page is hacking
	//redirect to the login page
	confirm_auth();

	if($_GET["subject_id"] && $_GET["subject_id"] != 0){
		echo "inside the if statement";
		//i am using this form of query statement so that if any part of the 
		//query statement breaks, it can be easily fixed by commenting out
		//the broken part of the statement.
		$query = "DELETE FROM subjects ";
		$query .= " WHERE id = {$_GET['subject_id']}";

		$result = delete_subject($link, $query);
		redirect_to("content.php");
	}else{
		//do nothing for now
		echo "<br>inside the else statement<br>";
	}			

	include("includes/clsconnection.php");
?>