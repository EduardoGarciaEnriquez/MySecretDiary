<?php
	session_start();
	$diaryContent= '';
	include("connectionDB.php");
	//check if there's a cookie looking for the id in the cookie array
	if (array_key_exists("id", $_COOKIE)) {

		//session and cookie setter up, this recreates the session evry time we need it
		$_SESSION['id'] = $_COOKIE['id'];
	}

	//check if there's a session, if yes, echo log in and add a link to log out
	if (array_key_exists("id", $_SESSION)) {
		//echo logged in
		//echo '<p>Logged in!!!</p><p><a href="Project_MySQL.php?logout=1">Log out.</a></p>';

		//select what's in the diary row
		$query= "SELECT `diary` FROM `secretDiary` WHERE id = '".mysqli_real_escape_string($link, $_SESSION['id'])."' LIMIT 1";

		//save what's in the diary row into the variable diaryContent
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		$diaryContent = $row['diary'];
	}

	//if not, then return to main page
	else{
		header("location: Project_MySQL.php");
	}

	include 'header.php';
	

?>
<nav class="navbar navbar-dark bg-dark fixed-top">
  <a class="navbar-brand">My Secret Diary</a>
  <div class="form-inline">
    <a href="Project_MySQL.php?logout=1"><button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Log Out</button></a>
  </div>
</nav>

<div class="container-fluid">
	<textarea id="diaryText" class="form-control"><?php echo $diaryContent; ?></textarea>
</div>

<?php
	include 'footer.php';
?>
