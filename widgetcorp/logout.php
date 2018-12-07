<?php
	//include the functions
	require_once("includes/functions.php");

	//Completely destroy the session cookie
	//There are four steps to destroy the session cookie completely
	//1. start the sesion
	session_start();

	//2. Unset all the sesion variables that was originally set
	//There are different wasy to do this, but it would be easier if you use this
	$_SESSION = array(); //This means setting the session variable to an empty array

	//3. Destroy the session cookie
	//This is done by using the session_name() function which will let php return the name of the session cookie variable itself
	if(isset($_COOKIE[session_name()])){
		//set the session cookie to an expired time using
		// the time() function minus a certain amount of time 
		setcookie(session_name(), "", time() - 1234, "/"); //The "/" is used to go to the root to make sure that the cookie is gotten
	}

	//4. Destroy the session
	session_destroy();

	redirect_to("login.php?log=0");
?>