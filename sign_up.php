<?php 
 	// Connects to your Database 
 	mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); 
	mysql_select_db("db1") or die(mysql_error()); 

 	//This code runs if the form has been submitted
	if (isset($_POST['submit'])) { 

	 	// checks if the group_name is in use
		if (!get_magic_quotes_gpc()) {
			$_POST['group_name'] = addslashes($_POST['group_name']);
		}

 		$usercheck = $_POST['group_name'];
 		$check = mysql_query("SELECT group_name FROM group_info WHERE group_name = '$usercheck'") or die(mysql_error());
		$check2 = mysql_num_rows($check);

 		//if the name exists it gives an error
		if ($check2 != 0) {
 			die('Sorry, the group name '.$_POST['group_name'].' is already in use. <a href="sign_up.php">Try again</a>');
 		}

 		//if there is a record with a matching email it gives an error
 		$query = mysql_query("SELECT * from group_info WHERE email='$_POST[email]'");
 		$query2 = mysql_num_rows($query);
 		if ($query2 != 0) {
 			die('Sorry, the email '.$_POST['email'].' is already in use. <a href="sign_up.php">Try again</a>');
 		}

 		// this makes sure both passwords entered match
 		if ($_POST['pass'] != $_POST['pass2']) {
 			die('Your passwords did not match. <a href="sign_up.php">Try again</a>');
 		}

		// here we encrypt the password and add slashes if needed
 		$_POST['pass'] = md5($_POST['pass']);

 		if (!get_magic_quotes_gpc()) {
 			$_POST['pass'] = addslashes($_POST['pass']);
 			$_POST['group_name'] = addslashes($_POST['group_name']);
 			$_POST['email'] = addslashes($_POST['email']);
 		}

 		// now we insert it into the database
		$insert = "INSERT INTO group_info (group_name, password, email) VALUES ('".$_POST['group_name']."', '".$_POST['pass']."', '".$_POST['email']."')";
 		$add_member = mysql_query($insert);
 ?>

<h1>Registered</h1>
<p>Thank you, you have registered - you may now <a href="index.php">login</a>.</p>

<?php 
 } 

 else 
 {	
 ?>

<html>
<head>
	<title>Divvy | Sign Up</title>
	<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="wrapper centered">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

	<h2>Register</h2>
	<ul>
		<li><input type="text" name="group_name" placeholder="Group Name" required /></li>
	 	<li><input type="password" name="pass" placeholder="Password" required /></li>
	 	<li><input type="password" name="pass2" placeholder="Re-Enter Password" required /></li>
		<li><input type="email" name="email" placeholder="Email" required /></li>
		<li><button type="submit" name="submit"> Register</button></li> 
	</ul>
	</form>
</div>

</body>
</html>
<?php

}
?>