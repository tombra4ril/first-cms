<?php
	//Header information
	require_once("includes/session.php");
	
	//Other relevant files
	require_once("includes/constants.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
?>
<?php
	//This code block is used to process the form in this file

	//Error message variable
	$message = "";
	if(isset($_POST["submit"])){
		//First check if there are no mistakes in the form input
		//Start by creating an error variable
		$errors = array();
		if(empty($_POST["username"])){
			$errors[] = "Enter username";
		}
		if(empty($_POST["password"])){
			$errors[] = "Enter password";
		}

		//Test if there is no error before processing
		if(empty($errors)){//Use only empty function to test because when creating an empty array(the value for isset for that array is true);
			//Create variable to hold hashed password
			$hashed = sha1($_POST['password']);
			$user = $_POST["username"];
			//This can also work but it is said to be less secure
			//$hashed = md5($_POST['password']);

			//Check if the same username or password already exists
			$query = "SELECT id, username, locked FROM users";
			$query .= " WHERE username = '{$user}'";
			$query .= " AND password = '{$hashed}'";
			$query .= " LIMIT 1";
			$result = mysqli_query($link, $query);

			if(mysqli_num_rows($result) == 1){
				//Create a session 
				//First get the result details to use for the session
				$user_details = mysqli_fetch_array($result);

				//checks if the user has been banned or not
				if($user_details["locked"]){
					die("You have been banned, contact Admin!!!" . "<h3>+23408105912717</h3>");
				}else{
					$_SESSION['user_id'] = $user_details["id"];
					$_SESSION['username'] = $user_details["username"];

					//If succeeded in logging in redirect to the staff area
					redirect_to("staff.php");
				}
			}else{//May not be a hacker
				//Checks if the username exits in the database
				$result_set = check_username_exist($link, $user);

				if($row = mysqli_fetch_array($result_set)){//Not an hacker
					//If the username is found in the database
					// increment the number of attempts
					//This will remain only for a period of 24 hours
					//If and only if the user does not attempt 3 times within 24 hours

					//Get the value used to checked if the user has already been banned
					$lock = $row["locked"];

					//checks if the user has been banned or not
					if($lock){
						die("You have been banned, contact Admin" . "<br>+2348105912717");
					}

					//increment the number of attempts if the user has not been banned
					increment_attempts($link, $user);

					//Check if the number of attempts is more than 3 times
					if(get_num_attempts($link, $user) >= 3){
						//Lock the user
						lock_user_account($link, $user);
						$message = "Your account has been banned!!!";
					}else{
						//Do not if the number of attempts is less than 3
						$message = "Password incorrect, Try again";
					}
				}else{//Hacker
					//If the username does not exist log in the ip address
					// of the hacker if the address does not exist and 
					// increment the number of attempts
					//If the number of attempst of the ip address is 
					// greater 3 ban the ip address

					//First get the ip address of the hacker
					$ip = $_SERVER["REMOTE_ADDR"];//This will return the ip address of the user
					if(ip_address_exist($link, $ip)){//Checks if the ip address exists
						//Get the value used to checked if the user has already been banned
						$result = check_hacker_banned($link, $ip);
						$row = mysqli_fetch_assoc($result);
						
						$lock = $row["locked"];

						//checks if the user has been banned or not
						if($lock){
							die("Your ip address ({$ip}) is banned!!!");
						}
						//Increment the number of attempts of the ip address
						increment_hacker_attempts($link, $ip);

						if(get_hacker_num_attempts($link, $ip) >= 3){//Check the number of attempts
							//Lock out the ip address
							ban_ip_address($link, $ip);
							die("Your ip address ({$ip})is banned!!!");
						}else{
							//Do nothing for now
							$message = "Username and Password incorrect";
						}
					}else{
						//Log in the ip address of the hacker
						log_ip_address($link, $ip);

						//Increment the number of attempts of that ip address
						increment_hacker_attempts($link, $ip);
						$message = "Wrong username or Password";
					}
				}
			}
		}else{//This means there is one or more errors
			//Do nothing for now
			//Get the last item in the array
			$message = "{$errors[count($errors) - 1]}";
		}
	}else{
		//do nothing because this form was not submitted
	}

	include("includes/header.php");
?>
		<div>
			<nav>
				<ul>
					<li class="subjects"><a href="index.php">Return to Site</a></li>
				</ul>
			</nav>
			
			<section>
				<h2>Log In:
					<?php 
						if(isset($_GET["log"])){
							echo "<h6 class='query-status'>Logged out Successfully</h6>";
						}
					?>
				</h2>
				<h5 class="query-status">
					<?php echo $message; ?>
				</h5>
				<form action="login.php" method="post">
					<p>
						<label class="right">Username:</label>
						<input type="text" size="15" name="username">
					</p>
					<p>
						<label class="right">Password:</label>
						<input type="password" size="15" name="password">
					</p>
					<p>
						<button class="submit-clear" type="submit" name="submit" value="submit">
							Login
						</button>
					</p>
				</form>
			</section>
		</div>
<?php
	include("includes/footer.php");
	include("includes/clsconnection.php");
?>