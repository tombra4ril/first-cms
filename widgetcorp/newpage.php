<?php
	//Header information
	require_once("includes/session.php");
	
	//Other relevant files
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");
	
	include("includes/header.php");

	

	//if the user did not login, there getting to this page is hacking
	//redirect to the login page
	confirm_auth();
?>
		<div>
			<nav>
				<?php
					//List for each subjects and pages
					$subject_set = get_subjects_set($link);
					echo "<ul>";
					//Unordered list for each subject
						while($subject_item = mysqli_fetch_array($subject_set)){
							echo "<li class='subjects'>" . 
								  "<a href='content.php?subject=".
								  urlencode($subject_item["id"]).
								  "'>" .
								  $subject_item["menu_name"] . 
								  "</a></li>"; //List each subjects
							
							//Unordered list for each page
							$page_set = get_page_set($link, $subject_item);

							while($page_item = mysqli_fetch_array($page_set)){
							echo "<li class='pages'>" .
								  "<a href=\"content.php?page=" .
								  urlencode($page_item['id']) . 
								  "\">" . 
								  $page_item["menu_name"] . 
								  "</a></li>"; //List each page
							}
							echo "<br>";
						}
					echo "</ul>";
				?>
			</nav>
			
			<section>
				<?php
				//This code is used to get the name of the subject to add a page
					$name = get_subject_name($link, $_GET["subject"]);
				?>
				<h2>Add Page: <?php echo "<span class='capitalize'>{$name}</span>"; ?></h2>
				<p>
					<form action="processpage.php?id=<?php echo $_GET['subject']; ?>" method="post">
						
						<p><label class="right">Page Name:</label>
						<input type="text" name="page_name" size="15" value=""></p>
						
						<p><label class="right">Position:</label>
						<?php
							//Use this code block to get the next position to use
							//Construct a query to retrieve the highest position
							$query = "SELECT position FROM pages";
							$query .= " WHERE subjects_id = {$_GET["subject"]}";

							$fectch_num_position = mysqli_query($link, $query);
							//Create a variable to hold the highest position
							$highest_position = mysqli_num_rows($fectch_num_position);

							// // if($fectch_num_position){
							// 	while($item = mysqli_fetch_array($fectch_num_position)){
							// 		$highest_position = $item;
							// 	}
							// // }else{
							// // 	$highest_position = 0;
							// // }
						?>
						<input type="number" maxlength="2" name="position" value="<?php echo $highest_position + 1; ?>"></p>
						
						<p><label class="right">Visible:</label>
						<input type="radio" name="visible" value="0">
						<label>No</label>

						<input type="radio" name="visible" value="1" checked>
						<label>Yes</label></p>

						<p><label class="right content-area">Content:</label>
						<textarea name="content"></textarea>
						</p>

						<p><button type="submit" value="page_submit" name="submit">
							Add Page
						</button>
						<button type="reset">
							Clear
						</button></p>
					</form>

					<p><a href="content.php">Cancel</a></p>
				</p>
			</section>
		</div>
<?php
	include("includes/footer.php");
	include("includes/clsconnection.php");
?>