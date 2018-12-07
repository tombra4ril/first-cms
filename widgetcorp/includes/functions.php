<?php
	//function that defines how to retrieve data from the subjects
	//table.

	function get_subjects_set($link){
		$subject_query = "SELECT * 
						  FROM subjects
						  ORDER BY position ASC";
		$message = "Query of Subject's table failed.";
		$subject_set = mysqli_query($link, $subject_query);
		confirm_connection($subject_set, $message, $link);

		return $subject_set;
	}

	//function that retrieves data from the pages table from the database
	function get_page_set($link, $subject_item){
		$page_query = "SELECT * 
					   FROM pages 
					   WHERE subjects_id = {$subject_item['id']}
					   ORDER BY position ASC";
		$message = "Query of Page's table failed.";
		$page_set = mysqli_query($link, $page_query);
		confirm_connection($page_set, $message, $link);

		return $page_set;
	}

	function confirm_connection($data_set, $message, $link=NULL){
		if(!$data_set){
			die($message . mysqli_error());
		}
	}

	function get_subject_by_id($link, $sub){
		$query_string = "SELECT * FROM subjects 
						 WHERE id = {$sub}
						 LIMIT 1";
		$sub_set = mysqli_query($link, $query_string);
		confirm_connection($sub_set, "ID query was not successful.", $link);
		return mysqli_fetch_array($sub_set);
	}

	function get_page_by_id($link, $page){
		$query_string = "SELECT * FROM pages 
						 WHERE id = {$page}
						 LIMIT 1";
		$page_set = mysqli_query($link, $query_string);
		confirm_connection($page_set, "Pages ID query was not successful.", $link);
		return mysqli_fetch_array($page_set);
	}

	function get_page_by_pos($link, $sub, $pos){
		$query_string = "SELECT * FROM pages";
		$query_string .= " WHERE subjects_id = {$sub}";
		$query_string .= " AND position = {$pos}";
		$query_string .= " LIMIT 1";
		$page_set = mysqli_query($link, $query_string);
		confirm_connection($page_set, "Pages ID query was not successful.", $link);
		return mysqli_fetch_array($page_set);
	}

	function get_pos_by_id($link, $id){
		$query_string = "SELECT position FROM pages";
		$query_string .= " WHERE subjects_id = {$id}";
		$query_string .= " AND visible = 1";
		$query_string .= " ORDER BY position ASC";
		$query_string .= " LIMIT 1";
		$pos = mysqli_query($link, $query_string);
		confirm_connection($pos, "Pages ID query was not successful.", $link);
		$pos_id = 0;

		if($item = mysqli_fetch_array($pos)){
			$pos_id = $item["position"];
			return $pos_id;
		}
		return $pos_id;
	}

	//This is similar function the the get_page_by_id, with the exception that
	// it will return all the pages and not just one page
	function get_pages_by_id($link, $id, $vis){
		$query_string = "SELECT * FROM pages";
		$query_string .= " WHERE subjects_id = {$id}";

		//Test if visible
		if($vis){
			$query_string .= " AND visible = 1";
		}
		
		$query_string .= " ORDER BY position ASC";
		//$query_string .= " LIMIT 1";
		$page_set = mysqli_query($link, $query_string);
		confirm_connection($page_set, "Pages ID query was not successful.", $link);
		return $page_set;
	}

	function subject_click_nav($link, $sub_id, $pos){
		//Unordered list for each page
		//The function below is used to get all the pages of a 
		// particular id from the database
		$page_set = get_pages_by_id($link, $sub_id, true); 
		while($page_item = mysqli_fetch_array($page_set)){
			$output = "<li class='pages'><a";

			//Determines the first page item of the each subjects item
			if($pos == $page_item["position"]){
				$output .= " id='selected-link'";//Adds an id attribute to style the link if this link was clicked
			}

			$output .= " href='index.php?pos=" .
				  	    urlencode($page_item["position"]) .
				  		"&subject={$sub_id}'>" . 
				  		$page_item["menu_name"] . 
				  		"</a></li>"; //List each page
			echo $output; //Displays the selected page
		}
	}

	function insert_subject($link, $menu, $pos, $vis){
		$query = "INSERT INTO subjects(
				  menu_name, position, visible)
				  VALUES(
				  '{$menu}', {$pos}, {$vis})";

		//insert the data
		confirm_connection(mysqli_query($link, $query), "Insert statements not successful.", $link);	
	}

	function insert_page($link, $sub_id, $name, $pos, $vis, $content){
		$query = "INSERT INTO pages";
		$query .= " (subjects_id, menu_name, position, visible, content)";
		$query .= " VALUES({$sub_id},'{$name}', {$pos}, {$vis}, '{$content}')";

		//insert the data
		confirm_connection(mysqli_query($link, $query), "Insert statements not successful.", $link);
	}

	function page_count_number($link, $sub_id){
		$query_string = "SELECT * FROM pages";
		$query_string .= " WHERE subjects_id = {$sub_id}";
		$count = mysqli_query($link, $query_string);

		confirm_connection($count, "Could not count successully", $link);
		$count = mysqli_num_rows($count);
		return $count;
	}

	function subject_count_number($link){
		$query_string = "SELECT * FROM subjects";
		$count = mysqli_query($link, $query_string);

		confirm_connection($count, "Could not count successully", $link);
		$count = mysqli_num_rows($count);
		return $count;
	}

	function update_subject($link, $query){
		$result = mysqli_query($link, $query);

		//check if there was an error when performing the query
		confirm_connection($result, "Did not update succesfully", $link);
		return $result;
	}

	function update_page($link, $query){
		$result = mysqli_query($link, $query);

		//check if there was an error when performing the query
		confirm_connection($result, "Did not update succesfully", $link);
		return $result;
	}

	function delete_subject($link, $query){
		//Query the database with the passed arguments
		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to delete Subject", $link);
		return $result;
	}

	function delete_page($link, $query){
		//Query the database with the passed arguments
		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to delete Page", $link);
	}

	function redirect_to($url){
		header("Location: {$url}");
		exit;
	}

	function get_subject_name($link, $id){
		//This function is used to get the name of the subject with the specified id
		$query = "SELECT menu_name FROM subjects";
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);

		confirm_connection($result, "Failed to get the name of the subject");

		$item = mysqli_fetch_assoc($result);
		return $item["menu_name"];
	}

	function check_username_exist($link, $user){
		//Checks if a username exist in the database
		$query = "SELECT locked FROM users";
		$query .= " WHERE username = '{$user}'";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);
		return $result;
	}

	function increment_hacker_attempts($link, $ip){
		//Increments the value of the attempts column of the specified user
		$query = "UPDATE hacker_address";
		$query .= " SET attempts = attempts + 1";
		$query .= " WHERE ip = '{$ip}'";

		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to increment hacker attempts", $link);
	}

	function increment_attempts($link, $user){
		//Increments the value of the attempts column of the specified user
		$query = "UPDATE users";
		$query .= " SET attempts = attempts + 1";
		$query .= " WHERE username = '{$user}'";

		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to increment attempts for the hacker", $link);
	}

	function get_num_attempts($link, $user){
		//Returns the number of attempts of a particular user
		$query = "SELECT attempts FROM users";
		$query .= " WHERE username = '{$user}'";
		$query .= " LIMIT 1";

		//Query the database
		$result = mysqli_query($link, $query);

		//confirm if there was an error in the query
		confirm_connection($result, "Failed to query number of attempts");
		
		//Create variable to hold the number of attempts
		$attempts = 0;
		if($row = mysqli_fetch_assoc($result)){
			$attempts = $row["attempts"];
		}

		return $attempts;
	}

	function get_hacker_num_attempts($link, $ip){
		//Returns the number of attempts of a particular user
		$query = "SELECT attempts FROM hacker_address";
		$query .= " WHERE ip = '{$ip}'";
		$query .= " LIMIT 1";

		//Query the database
		$result = mysqli_query($link, $query);

		//confirm if there was an error in the query
		confirm_connection($result, "Failed to query number of attempts");
		
		//Create variable to hold the number of attempts
		$attempts = 0;
		if($row = mysqli_fetch_assoc($result)){
			$attempts = $row["attempts"];
		}

		return $attempts;
	}

	function lock_user_account($link, $user){
		//This function locks the account of the user
		$query = "UPDATE users";
		$query .= " SET locked = 1";
		$query .= " WHERE username = '{$user}'";

		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to delete Subject", $link);	
	}

	function ban_ip_address($link, $ip){
		//This function locks the account of the user
		$query = "UPDATE hacker_address";
		$query .= " SET locked = 1";
		$query .= " WHERE ip = '{$ip}'";

		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to delete Subject", $link);	
	}

	function check_hacker_banned($link, $ip){
		//This function checks if the ip address of a user exist
		$query = "SELECT locked FROM hacker_address";
		$query .= " WHERE ip = '{$ip}'";
		$query .= " LIMIT 1";

		//Query the database
		$result = mysqli_query($link, $query);

		//confirm if there was an error in the query
		confirm_connection($result, "Failed to check if an ip address exists", $link);

		//Return the result whether the ip address exits or not
		return $result;
	}

	function ip_address_exist($link, $ip){
		//This function checks if the ip address of a user exist
		$query = "SELECT id FROM hacker_address";
		$query .= " WHERE ip = '{$ip}'";
		$query .= " LIMIT 1";

		//Query the database
		$result = mysqli_query($link, $query);

		//confirm if there was an error in the query
		confirm_connection($result, "Failed to check if an ip address exists", $link);

		//Return the result whether the ip address exits or not
		if(mysqli_num_rows($result) >= 1){
			return true;
		}else{
			return false;
		}
	}

	function log_ip_address($link, $ip){
		//This function locks the account of the user
		$query = "INSERT INTO hacker_address";
		$query .= " (ip, locked, attempts)";
		$query .= " VALUES('{$ip}', 0, 0)";

		$result = mysqli_query($link, $query);

		//Check if there was an error when performing the query
		confirm_connection($result, "Failed to log ip address of hacker", $link);	
	}
?>