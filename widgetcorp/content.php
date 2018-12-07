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

							// if($page_item["id"] == $_GET["page"] ){
							// 	//Should have gotten the page id using $_GET["page"] and stored it in a variable for efficiency, but this 
							// 	//would produce an error everytime that variable does not exist(which will happen if the user clicks on a subject and not a page)
							// 	$output .= " id='selected-link'";//Adds an id attribute to style the link if this link was clicked
							// }
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
				<br>
				<p class="subjects"><a href="newsubject.php">+ Add a new Subject</a></p>
			</nav>
			
			<section>
				<h2>
					<?php
						//Check if the subject or the page was clicked
						// and retrieve the subject menu
						if(isset($_GET["subject"])){
							$subject_row = get_subject_by_id($link, $_GET["subject"]);
							echo $subject_row["menu_name"];
						}elseif(isset($_GET["page"])){
							$page_row = get_page_by_id($link, $_GET["page"]);
							echo $page_row["menu_name"];
						}else{
							// do nothing for now
							echo "Select a Subject or a Page to Edit...";
						}
					?>
				</h2>
				<div>
					<?php
						//Check if a page was clicked
						// and retrieve the content
						if(isset($_GET["page"])){
							echo $page_row["content"];
						}
					?>
				</div>
			</section>
		</div>
<?php
	include("includes/footer.php");
	include("includes/clsconnection.php");
?>