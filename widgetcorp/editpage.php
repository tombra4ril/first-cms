<?php
//This file is the edit page
//A subject id is passed to this page from the content page
//This page is use for editing, updating and deleting subjects of the 
// widget-corp application (which is purely all php files)
	//Header information
	require_once("includes/session.php");
	

	//Other relevant files
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");
	

	//if the user did not login, there getting to this page is hacking
	//redirect to the login page
	confirm_auth();

	// if (isset($_POST["submit"])){
	// 	echo "submit button is set <br>";
	// }else{
	// 	echo "submit button is not set <br>";
	// }

	// if (empty($_POST["submit"])){
	// 	echo "submit button is empty<br>";
	// }else{
	// 	echo "submit button is not empty<br>";
	// }
	
	if(isset($_POST["submit"]) && !empty($_POST["submit"])){
		$errors = array(); //this variable is used to hold the error for any
		//of the input fields which produces an error
		if(!isset($_POST["page_name"])){//checks if the subject input field has an error
			$errors[] = "Error in the subject field";
		}
		if (!isset($_POST["position"])) {
			$errors[] = "Error in the position field";
		}
		if (!isset($_POST["visible"])){
			$errors[] = "Error in the visible radio buttons field";
		}
		if (!isset($_POST["content"])) {
			//Do nothing for now
			$errors[] = "Error in the content field";
		}

		if(empty($errors)){
			//update the database with the values from the form
			//start by first getting the values of from the glabal POST array
			//and cleaning or verifying that the values are sanitized
			$page = $_POST["page_name"];
			$position = $_POST["position"];
			$visible = $_POST["visible"];
			$content = $_POST["content"];

			//before querying set the value of the message variable just in case the 
			//querying failed.
			$message = "Failed to Update";

			//i am using this form of query statement so that if any part of the 
			//query statement breaks, it can be easily fixed by commenting out
			//the broken part of the statement.
			$query = "UPDATE pages SET";
			//$query .= " SET(menu_name, position, visible)";
			//$query .= " VALUES('{$subject}', {$position}, {$visible})";
			$query .=  " menu_name = '{$page}',";
			$query .= " position = {$position},";
			$query .= " visible = '{$visible}',";
			$query .= " content = '{$content}'";
			$query .= " WHERE id={$_GET['page']}";

			$result = update_page($link, $query);
			$message = "Updated Successfully";
		}else{
			//do nothing for now
			$message = "Failed to Update";
			$message .= ", check";
			//Get the last item in the array
			$message .= " : {$errors[count($errors) - 1]}";
		}
	}
	include("includes/header.php");
?>
		<div>
			<nav>
				<?php
	//List for each subjects and pages
	$subject_set = get_subjects_set($link);
	echo "<ul>";
	//Unordered list for each subject
		while($subject_item = mysqli_fetch_array($subject_set)){
			//Displays each subject from the database
			$output = "<li class='subjects' ><a";//Makes output to be equal to this
			// if($subject_item["id"] == $_GET["subject"]){
			// 	//Should have gotten the subject id using $_GET["subject"] and stored it in a variable for efficiency, but this 
			// 	//would produce an error everytime that variable does not exist(which will happen if the user clicks on a page and not a subject)
			// 	$output .= " id='selected-link'";//Adds an id attribute to style the link if this link was clicked
			// }  
			//Concatenates the remaining portion of the list items
			$output .= " href='editsubject.php?subject=" . 
				urlencode($subject_item["id"]) .
				"'>" .
				$subject_item["menu_name"] . 
				"</a></li><br>"; //List each subjects
			
			//echo out the output
			echo $output;

			//Unordered list for each page
			$page_set = get_page_set($link, $subject_item);

			while($page_item = mysqli_fetch_array($page_set)){
			$output = "<li class='pages'><a";

			if($page_item["id"] == $_GET["page"] ){
				//Should have gotten the page id using $_GET["page"] and stored it in a variable for efficiency, but this 
				//would produce an error everytime that variable does not exist(which will happen if the user clicks on a subject and not a page)
				$output .= " id='selected-link'";//Adds an id attribute to style the link if this link was clicked
			}
			$output .= " href='editpage.php?page=" .
				  	    urlencode($page_item["id"]) . 
				  		"'>" . 
				  		$page_item["menu_name"] . 
				  		"</a></li>"; //List each page
			echo $output; //Displays the selected page
			}

			//Give a small space between two subjects
			echo "<br>";
		}
	echo "</ul>";
?>
			</nav>
			
			<section>
				<?php
						//retrieve the page properties
						$page_row = get_page_by_id($link, $_GET["page"]);
					?>
				<h2>Edit Page: <?php echo $page_row['menu_name']; ?></h2>
				<?php
					if(empty($errors) && isset($_POST["submit"]) && !empty($_POST["submit"])){
						echo "<h6 class='query-status'>{$message}</h6>";
					}
				?>
				<p>
					<form name="edit_page" action="editpage.php?page=<?php echo $page_row['id']; ?>" method="post">
						
						<p><label class="right">Page Name:</label>
						<input type="text" name="page_name" size="15" value="<?php echo $page_row['menu_name']; ?>"></p>
						
						<p><label class="right">Position:</label>
						<input type="number" maxlength="2" name="position"
						value="<?php echo $page_row['position']; ?>"></p>
						
						<p><label class="right">Visible:</label>
						<input type="radio" name="visible" value="0" <?php
						if(!$page_row['visible']){
							echo "checked";
						}
						?>>
						<label>No</label>

						<input type="radio" name="visible" value="1" <?php
						if($page_row['visible']){
							echo "checked";
						}
						?>>
						<label>Yes</label></p>

						<!-- This is for the content of the page -->
						<p><label class="right content-area">Content:</label>
							<textarea name="content"><?php echo $page_row['content']; ?></textarea>
						</p>
						<!-- rememeber to always include the value attribute so that 
						that it the can be passed through the form to the $_POST variable -->
						<p><button class="submit-clear" type="submit" name="submit" value="submit">
							Update
						</button>
						<button class="submit-clear" type="reset">
							Clear
						</button></p>
					</form>

					<p class="cancel-delete"><a href="content.php">Cancel</a></p>
					<p class="cancel-delete"><a href="deletepage.php?page=<?php echo urlencode($page_row['id']);?>" onclick="return confirm('Are you sure?')">Delete</a></p>
				</p>
			</section>
		</div>
<?php
	include("includes/footer.php");
	include("includes/clsconnection.php");
?>