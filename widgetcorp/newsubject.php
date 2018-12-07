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
								  "</a></li><br>"; //List each subjects
							
							//Unordered list for each page
							$page_set = get_page_set($link, $subject_item);

							while($page_item = mysqli_fetch_array($page_set)){
							echo "<li class='pages'>" .
								  "<a href=\"content.php?page=" .
								  urlencode($page_item['id']) . 
								  "\">" . 
								  $page_item["menu_name"] . 
								  "</a></li><br>"; //List each page
							}
						}
					echo "</ul>";
				?>
			</nav>
			
			<section>
				<h2>Add Subject</h2>
				<p>
					<form name="new_subject" action="processform.php" method="post">
						
						<p><label class="right">Subject Name:</label>
						<input type="text" name="subject_name" size="15"value=""></p>
						
						<p><label class="right">Position:</label>
						<input type="number" maxlength="2"name="position"
						value="<?php
							echo subject_count_number($link) + 1;
						?>"></p>
						
						<p><label class="right">Visible:</label>
						<input type="radio" name="visible" value="0">
						<label>No</label>

						<input type="radio" name="visible" value="1" checked>
						<label>Yes</label></p>

						<p><button type="submit" name="subject_submit">
							Add Subject
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