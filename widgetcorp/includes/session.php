<?php
	session_start();

	//function that redirects to the login page if there is no authentication
	function confirm_auth(){
		if(!isset($_SESSION["username"]) && !isset($_SESSION['id'])){
			redirect_to("login.php");
		}
	}
?>