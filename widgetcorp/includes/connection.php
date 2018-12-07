<?php
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	if(!$link){
		die("Did not make a connection to server, or server offline" . mysqli_error());
	}
?>