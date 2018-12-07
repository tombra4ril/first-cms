<?php
//This file is the index page
//This is the first or the index page which is to be called in the 
// widget-corp application (which is purely all php files)
	

	//Other relevant files
	include_once("includes/constants.php");
	include("includes/connection.php");
	require_once("includes/functions.php");
	
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
							$pos_id = get_pos_by_id($link, $subject_item["id"]);
							$output .= " href='index.php?pos=" .
								"{$pos_id}" . 
								"&subject=" . 
								urlencode($subject_item["id"]) .
								"'>" .
								$subject_item["menu_name"] . 
								"</a></li><br>"; //List each subjects
							
							//echo out the output
							echo $output;

							//This line of code will only work if the any subject link works
							//What it does is that it, will display only the Subjects pages
							//It figures out which subject was clicked by checking
							//the subject value passed by the $_GET method in the url
							if(isset($_GET["subject"]) && isset($_GET["pos"])){
								if($_GET["subject"] == $subject_item["id"]){
										$sel_subj_id = $_GET["subject"];
										$sel_page_pos = $_GET["pos"];
										echo subject_click_nav($link, $sel_subj_id, $sel_page_pos);
								}
							}
							//Give a small space between two subjects
							echo "<br>";
						}
					echo "</ul>";
				?>
				<br>
			</nav>
			
			<section>
				<h2>
					<?php
						//Check if the subject or the page was clicked
						// and retrieve the page's details
						if(isset($_GET["subject"]) && isset($_GET["pos"])){
							$page_row = get_page_by_pos($link, $_GET["subject"], $_GET["pos"]);
							echo $page_row["menu_name"];
						}else{
							// do nothing for now
							echo "Welcome to Widget Corp!!!.";
						}
					?>
				</h2>
				<div>
					<?php
						//Check if a page was clicked
						// and retrieve the content
						if(isset($_GET["pos"])){
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