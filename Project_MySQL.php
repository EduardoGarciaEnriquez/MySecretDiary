<?php
	//log in sistem (DONE)
	//sign up form and log in form (DONE)
	//email, password, check box connected to cookies to stay logged in, submit button for each.(almost)
	//successfully log in redirects to another page that says "you're log in" and gives the option to log out (almost)
	
	//start session for cookies
	session_start();

	//variables
	$error="";
	$emailSU= $_POST['emailSignUp'];
	$passwordSU= $_POST['passwordSignUp'];


	//conect to DB
	include ("connectionDB.php");

	//if user manuali tryies to redirect to the main page with out logout it redirects to logged in page
	if (array_key_exists("logout",$_GET)) {
		unset($_SESSION);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"]="";
	}

	elseif (array_key_exists("id",$_SESSION) || array_key_exists("id",$_COOKIE)) {
		header("location: ProjectLogedIn_MySQL.php");
	}
	

	//if user sign up:
	if (array_key_exists("SubmitB", $_POST)) {

		//if email field is emtpy add to error msg
		if (!$emailSU) {
			$error .= "Email address field is empty.<br>";
		}

		//if password field is empty add to error msg
		if (!$passwordSU) {
			$error .= "Password field is empty.<br>Try again.";
		}

		//if error value isn't empty add and show final error msg
		if ($error != "") {
			$error = "<p>Empty field or wrog values in your form:</p>".$error;
		}

		//if there's no errors, then sign up or log in user
		else
		{	
			//if sign up hidden = 1 means that user is signin up
			if($_POST['SignUpHidden'] =='1')
			{

				//check if the entered email address is already registered
				$query = "SELECT id FROM `secretDiary` WHERE email = '".mysqli_real_escape_string($link, $_POST['emailSignUp'])."' LIMIT 1";

				//run query
				$result = mysqli_query($link, $query);

				//if the email address is registered error msg = "email address already taken. try again"
				if (mysqli_num_rows($result) > 0) {
					$error= "<p>This email address is already taken by other user. Try with another address or log in.</p>";
				}

				//if the email address is not registered, sign up new user.
				else{

					//insert email address and pasword for new user into DB secret diary
					$query= "INSERT INTO `secretDiary` (`email`,`password`) VALUES ('$emailSU', '$passwordSU')";

					//if fail to run query to insert new user add to error msg
					if (!mysqli_query($link,$query)) {
						$error="<p>Unable to sign up new user. Try again later.</p>";
					}

					//if query run succesfully show succes msg.
					else{
						//id of the most recent incerted value in the DB
						//mysqli_insert_id($link);

						//update hashed password to the DB
						$query = "UPDATE `secretDiary` SET `password` = '".md5(md5(mysqli_insert_id($link)).$passwordSU)."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";

						#error in cookies, fix later...
						//run query
						mysqli_query($link,$query);
			
						$_SESSION["id"] = mysqli_insert_id($link);
							
						if ($_POST['StayLoggedIn'] == "1") {
							//cookie (log in) expires in 1 hour
							setcookie("id",mysqli_insert_id($link), time() + (60*60*24)); 	
						}

						//log in, echo it or redirect to logged in page
						//echo "Sign up successfully. Welcome!";
						header("location: ProjectLogedIn_MySQL.php");
					}
				}
			}

			//if not, user is loggin in
			else
			{	
				//select all info from the row where the email column has the same value than the email entered by the user
				$query = "SELECT * FROM `secretDiary` WHERE `email` = '" .mysqli_real_escape_string($link,$_POST['emailSignUp'])."'  ";
				//check and runn query
				$result = mysqli_query($link, $query);

				//save all the values of the query into an array (id, email, password, dairy)
				$row = mysqli_fetch_array($result);

				//if there is an id in the array, hash the user password and compare it with the hashed DB password
				if (array_key_exists("id", $row)) {
					//hash the pasword enteres by the user
					$hashedPass= md5(md5($row["id"]).$_POST["passwordSignUp"]);

					//check if the hashed pasword is the same hashed password in the DB
					if ($hashedPass== $row["password"]) {
						//if yes, session id equals to the id the in the array form the DB
						$_SESSION["id"] = $row ["id"];

						if ($_POST['StayLoggedIn'] == "1") {
							//cookie (log in) expires in 1 hour
							setcookie("id",$row["id"], time() + (60*60*24)); 	
						}

						//log in, echo it or redirect to logged in page
						//echo "Sign up successfully. Welcome!";
						header("location: ProjectLogedIn_MySQL.php");
					}

					$error = "<p>Wrong password or email address</p>";

				}

				else{
					$error = "<p>Email address is not registered. Please try signing up first.</p>";
				}

			}
		}


	}

?>

<?php
	//include file that contains the style of the html
	include ("header.php");
?>

		<div class="container" id="homePage">
			<h1>Secret dairy</h1>
			<p><strong>Store your thoughts permanently and securely</strong></p>
	    	<div id="error"><strong>
	    		<?php
			    	if ($error!="")
			    	{
			    		echo '<div class="alert alert-warning alert-dismissible fade show  col-sm-12" role="alert">'.$error.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  	</button>
		</div>';
					}
				?></strong>
		</div>

			<form id="SignUpForm" method="post">
				<p><strong>Interested? Sign up now.</strong></p>
				<div class="form-group col-sm-12">
					<input type="email" name="emailSignUp" placeholder="email@example.com" class="form-control">
				</div>
				
				<div class="form-group col-sm-12">
					<input type="password" name="passwordSignUp" placeholder="Password" class="form-control">
				</div>

				<div class="form-group">
					<input type="checkbox" name="StayLoggedIn" value="1">
					<input type="hidden" name="SignUpHidden" value="1">
					<label class="form-check-label" for="exampleCheck1">Stay Logged in!</label>
				</div>
					<button type="submit" name="SubmitB" class="btn btn-primary">Sign up!</button>
					<p><a class="toggleForm">Log in</a></p>
			</form>

			<form id="LogInForm" method="post">
				<p><strong>Log in using your email and password.</strong></p>
				<div class="form-group col-sm-12">
					<input type="email" name="emailSignUp" placeholder="email@example.com" class="form-control">
				</div>
				
				<div class="form-group col-sm-12">
					<input type="password" name="passwordSignUp" placeholder="Password" class="form-control">
				</div>
				
				<div class="form-group">
					<input type="checkbox" name="StayLoggedIn" value="1">
					<input type="hidden" name="SignUpHidden" value="0">
					<label class="form-check-label" for="exampleCheck1">Stay Logged in!</label>
				</div>
					<button type="submit" name="SubmitB" class="btn btn-primary">Log in!</button>
					<p><a class="toggleForm">Sign up</a></p>
			</form>

		</div>

<?php
	//include file that contains all the jquery for the bootsrtap of the page
	include "footer.php";
?>