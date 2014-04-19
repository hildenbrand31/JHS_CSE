<?php
/**
 * CSE Activity 2.2.2 IntroducingPHP
 * 
 * 222indexB.php models use of PHP in conjunction with MySQL, JavaScript, and CSS
 * @copyright Unpublished work 2013 Project Lead The Way
 * @version 2013.12.20
 */
 
/* 
This block allows our program to access the MySQL database.
Elaborated on in 2.2.3.
 */
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)
	or die("Unable to select database: " . mysql_error());
	
echo "<script type='text/javascript' src='222popouts.js'></script>";
echo <<<_END
	<style = "text/css">
		#popout {
			z-index: 5;
			position: absolute;
			width: 550px;
			height: 300px;
			top: 50px;
			border: 1px dashed #000099;
			background-color: #ddddff;
		}	
	</style>
_END;
	
// $_COOKIE is a data structure that holds all cookies for this site.
// This conditional verifies that the cookie 'username' contains data.
// That would symbolize that the user is logged in as an artist.
if (isset($_COOKIE['username']))
{
	$username = $_COOKIE['username']; // Retrieves the value of the cookie
	
	// Dynamically respond to the data in the cookie to recognize the user
	// An echo statement is used to display something in PHP. Plain text
	// shows up as such, and html code enclosed in quotes functions as normal html.
	echo "Welcome back, " . $username . 
		", click <a href='222artist_portal.php'>here to go to the Artist's page</a>.<br />";
	echo "Click <a href='logout.php'>here to Log Out</a>...<br />";
	echo "<br /><br />Enter information in the fields at the bottom of the page to search the image database.<br /><br />";
}
else
{
	echo "Click <a href='222artist_portal.php'>here to log in as an artist</a>.<br />";
	echo "Or click <a href='222account_creation.php'>here to create an artist account</a>.<br />";
}
	
// When the POST protocol is invoked, checks case 1 for the data entered in the form.	
if (isset($_POST['firstname']) &&
	isset($_POST['lastname']) &&
	$_POST['firstname'] != '' &&
	$_POST['lastname'] != '')
{
	// Gathers the information provided in the form with the POST protocol
	// creates and submits MySQL queries using it, explained in 2.2.3
	$query = "SELECT * FROM artists WHERE firstname='" . 
		$_POST['firstname'] . "' AND lastname='" . $_POST['lastname'] . "'";
	$result_artist = mysql_query($query);
	$row = mysql_fetch_row($result_artist);
	$artistID = $row[0];
	$query = "SELECT * FROM images WHERE username='" . $artistID . "'";
	$result_image = mysql_query($query);
	// Call a function defined later in this file, with two arguments
	display_table($artistID, $result_image);
}

// When the POST protocol is invoked, checks case 2 for the data entered in the form.
// The statements executed by this conditional mimic the ones in the previous conditional block	
else if (isset($_POST['firstname']) &&
	isset($_POST['lastname']) &&
	$_POST['firstname'] != '' &&
	$_POST['lastname'] == '')
{
	$query = "SELECT * FROM artists WHERE firstname='" . $_POST['firstname'] . "'";
	$result_artist = mysql_query($query);
	$row = mysql_fetch_row($result_artist);
	$artistID = $row[0];
	$query = "SELECT * FROM images WHERE username='" . $artistID . "'";
	$result_image = mysql_query($query);
	
	display_table($artistID, $result_image);
}

// When the POST protocol is invoked, checks case 3 for the data entered in the form.
// The statements executed by this conditional mimic the ones in the previous conditional block	
else if (isset($_POST['firstname']) &&
	isset($_POST['lastname']) &&
	$_POST['firstname'] == '' &&
	$_POST['lastname'] != '')
{
	$query = "SELECT * FROM artists WHERE lastname='" . $_POST['lastname'] . "'";
	$result_artist = mysql_query($query);
	$row = mysql_fetch_row($result_artist);
	$artistID = $row[0];
	$query = "SELECT * FROM images WHERE username='" . $artistID . "'";
	$result_image = mysql_query($query);
	
	display_table($artistID, $result_image);
}

// HTML to display the form on this page.
echo '<br />Search the art database using the fields below.';
// Sets POST as method of data submission
echo '<form action="222indexB.php" method="post"><pre>'; 
echo 'First Name <input type="text" name="firstname" />';
echo '<br />Last Name <input type="text" name="lastname" />';
// Creates the SEARCH button which calls the POST method with the data entered
echo '<br /><input type="submit" value="SEARCH" />'; 
echo '</pre></form>';

/** 
 * Generates HTML to render table of images returned by user query
 * 
 * An example of code reuse. This code is needed for each of our form submission cases.
 * @param string $key the name of the artist's folder
 * @param array $image_info_table a 2D array containing the data about each relevant image.
 * @return null
 */
function display_table($key, $image_info_table)
{
	echo "<TABLE><CAPTION>Your Results:</CAPTION>";
	$closed_tr = 0; // flag, used to determine if we are at the end of a row when the loop terminates
	$num_images = mysql_num_rows($image_info_table);
	
	// Iterate through all of the returned images, placing them in a table for easy viewing
	for ($count = 0; $count < $num_images; $count++)
	{
		// The following few lines store information from specific cells in the data about an image
		$image_row = mysql_fetch_row($image_info_table); // Advances a row each time it is called
		$image_name = $image_row[1];
		$thumb_name = $image_row[2];
		$image_path = pathinfo($image_name);
		$id_name = $image_path['filename'];
		$div_id = $id_name . "popin";

		// Accesses the information about the artist associated with the image stored previously
		$firstname = $row[2];
		$lastname = $row[3];
		// Remember the mod operator, this one gives us the remainder when $count is divided by 6
		if ($count % 6 == 0)
		{
			echo "<TR>";
			$closed_tr = 0;
		}

		echo "<TD><img id='$id_name' src='$key/$thumb_name' onmouseover=" . '"' . "showDetailedView('$div_id', '$id_name', '$firstname')" . '" />';
		echo "<div id = '$div_id'></div></TD>";
		if ($count % 6 == 5)
		{
			echo "</TR>";
			$closed_tr = 1;
		}
	}
	if ($closed_tr == 0) echo "</TR>"; // Appends a close tag for the TR element if the loop did not terminate at a row end.
	echo "</TABLE>";
}
?>