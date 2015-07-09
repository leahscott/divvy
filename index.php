 <?php 

// Connects to your Database 
mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); mysql_select_db("db1") or die(mysql_error()); 

//Checks if there is a login cookie 
if(isset($_COOKIE['ID_my_site'])) {

	//if there is, it logs you in and directes you to the members page  	
	$group_name = $_COOKIE['ID_my_site']; 	
	$pass = $_COOKIE['Key_my_site']; 	 	
	$check = mysql_query("SELECT * FROM group_info WHERE group_name = '$group_name'")or die(mysql_error()); 	

	while($info = mysql_fetch_array( $check )) { 		
		if ($pass != $info['password']) {} 		
		else { 		
			header("Location: content.php"); 			
		} 
	} 
} 

//if the login form is submitted 
if (isset($_POST['submit'])) { 

 	$check = mysql_query("SELECT * FROM group_info WHERE group_name = '".$_POST['group_name']."'")or die(mysql_error());
 
 	//Gives error if user dosen't exist
 	$check2 = mysql_num_rows($check);
 	if ($check2 == 0) {
 		die('That user does not exist in our database. <a href="index.php">Try again</a>.</br>Or, <a href="sign_up.php">Click Here</a> to Register.');
 	}
 	while($info = mysql_fetch_array( $check )) {
 		$_POST['pass'] = stripslashes($_POST['pass']);
 		$info['password'] = stripslashes($info['password']);
 		$_POST['pass'] = md5($_POST['pass']);
 
 		//gives error if the password is wrong
 		if ($_POST['pass'] != $info['password']) {
 			die('Incorrect password, please <a href="index.php">try again</a>.');
 		}
		else { 
			// if login is ok then we add a cookie 	 
			$_POST['group_name'] = stripslashes($_POST['group_name']); 	 
			$hour = time() + 3600; 
			setcookie(ID_my_site, $_POST['group_name'], $hour); 
			setcookie(Key_my_site, $_POST['pass'], $hour);	 

			//then redirect them to the members area 
			header("Location: content.php"); 
		} 
	} 	
} else {	
// if they are not logged in 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Divvy</title>
	<link href='http://fonts.googleapis.com/css?family=Permanent+Marker' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
	<div class="sign-up"><a href="sign_up.php">Sign Up</a></div>

	<div class="wrapper centered">
		<h2>Divvy</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 
			<ul> 
				<li><input type="text" name="group_name" placeholder="Group Name" required /></li>
				<li><input type="password" name="pass" placeholder="Password" required /></li>
				<li><button type="submit" name="submit">Login</button></li>
			</ul>
		</form>
	</div>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>




<?php } ?> 