<?php
	//Headers information
	require_once("includes/session.php");
	
	//Other relevant files
	require_once("includes/constants.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	include("includes/header.php");
	

	//if the user did not login, there getting to this page is hacking
	//redirect to the login page
	confirm_auth();
?>
<?php
	//This code block is used to process the form in this file

	//Error message variable
	$message = "";

	if(isset($_POST["submit"])){
		//First check if there are no mistakes in the form input
		//Start by creating an error variable
		$errors = array();

		if(!isset($_POST["username"]) || empty($_POST["username"])){
			$errors[] = "The username is empty";
		}
		if(!isset($_POST["password"]) || empty($_POST["password"])){
			$errors[] = "The password is empty";
		}
		if(strlen($_POST["password"]) <= 7){
			$errors[] = "The password length should be at least 8";
		}
		if(!isset($_POST["confirm"]) || ($_POST["confirm"] != $_POST["password"])){
			$errors[] = "Passwords do not match";
		}

		//Test if there is no error before processing
		if(empty($errors)){
			//Create variable to hold hashed password
			$hashed = sha1($_POST['password']);
			$user = $_POST["username"];
			//This can also work but it is said to be less secure
			//$hashed = md5($_POST['password']);

			//Check if the same username or password already exists
			$query = "SELECT username FROM users";
			$query .= " WHERE '{$user}' = username";
			$query .= " LIMIT 1";
			
			$result = mysqli_query($link, $query);

			//Check if the query returned any rows, which would mean
			// it was successful
			if(mysqli_num_rows($result) == 1){
				$message = "Username exists";	
			}else{
				//Create the query string
				//Use this form so that one can easily comment out any part
				// of the query that produces error
				$query = "INSERT INTO users";
				$query .= " (username, password, attempts, locked)";
				$query .= " VALUES";
				$query .= " ('{$user}', '{$hashed}', 0, 0)";

				$message = "Create user not successful";
				//send the query string
				$result = mysqli_query($link, $query);
							
				//Will display this message if the query worked/succeeded
				$message = "Create user successful";
			}
		}else{//This means there is one or more errors
			//Do nothing for now
			//Get the last item in the array
			$message .= "{$errors[count($errors) - 1]}";
		}
	}else{
		//do nothing because this is the form was not submitted

	}
?>
		<div>
			<nav>
				<ul>
					<li class="subjects"><a href="staff.php">Return to menu</a></li>
				</ul>
			</nav>
			
			<section>
				<h2>Create new User</h2>
				<h5 class="query-status">
					<?php echo $message; ?>
				</h5>
				<form action="newuser.php" method="post">
					<p>
						<label class="right">Username:</label>
						<input type="text" size="15" name="username">
					</p>
					<p>
						<label class="right">Password:</label>
						<input type="password" size="15" name="password">
					</p>
					<p>
						<label class="right">Confirm Password:</label>
						<input type="password" size="15" name="confirm">
					</p>
					<p>
						<button class="submit-clear" type="submit" name="submit" value="submit">
							Create User
						</button>
					</p>
				</form>
			</section>
		</div>
<?php
		include("includes/footer.php");
		require_once("includes/clsconnection.php");
?>