<?php

	require_once("includes/session.php");
	require_once("includes/functions.php");
	include("includes/header.php");

	//if the user did not login, there getting to this page is hacking
	//redirect to the login page
	confirm_auth();
?>
		<div>
			<nav>

			</nav>
			
			<section>
				<h2>Staff Menu</h2>
				<p>Welcome to the staff area: <span class="capitalize"><?php echo $_SESSION["username"]; ?></span></p>
				<ul>
					<li><a href="content.php">Manage Website Content</a></li>
					<li><a href="newuser.php">Add Staff User</a></li>
					<li><a href="logout.php">Log out</a></li>
				</ul>
			</section>
		</div>
<?php
	include("includes/footer.php");
?>