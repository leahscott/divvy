<?php
	//connect to the database
	mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); 
	mysql_select_db("db1") or die(mysql_error()); 

	$username = $_COOKIE['ID_my_site'];

	if(isset($_POST['submit'])) {

		//create variables from form data
		if(!get_magic_quotes_gpc()) {
			$email = $_POST['email'];
			$currentPassword = md5($_POST['current-password']);
			$newPassword = md5($_POST['new-password']);
		}
		
		//prepare query to check if a record exists
		$query = mysql_query("SELECT * FROM group_info WHERE email='$email'") or die (mysql_error());
		$check = mysql_fetch_assoc($query);
		$check2 = mysql_num_rows($query);

		//if the entry doesn't exist, die
		if($check2 == 0) {
			echo "'$email' is not registered";
			echo "<br><a href='password_reset.php'>Retry</a>";
			die;
		}
		
		//check if old password is correct
		//if so it updates the record
		if($check['password'] != $currentPassword)
			die("The current password you have entered does not match our records");
		else {
			mysql_query("UPDATE group_info SET password='$newPassword' WHERE id='$check[id]'") or die (mysql_error());
		}
	
?>

<h1>Success!</h1>
<p>Your new password has been saved</p>
<p>Please <a href="logout.php">Login</a> using your new password.</p> 

<?php
	} else {
?>

<html>
	<head>
		<title>Divvy | Password Reset</title>
		<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
		<link type="text/css" rel="stylesheet" href="css/reset.css" />
		<link type="text/css" rel="stylesheet" href="css/login.css" />
	</head>
	<body>
		<div class="wrapper centered">
			<h2>Password Reset</h2>
			<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
				<input type="email" name="email" placeholder="Email" required />
				<input type="password" name="current-password" placeholder="Current Password" required />
				<input type="password" name="new-password" placeholder="New Password" required />
				<button type="submit" name="submit">Submit</button>
			</form>
		</div>
	</body>
</html>

<?php } ?>