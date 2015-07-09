<?php

//connect to database
mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); 
mysql_select_db("db1") or die(mysql_error()); 

$username = $_COOKIE['ID_my_site'];

//get email data
$query = mysql_query("SELECT email FROM group_info WHERE group_name='$username'"); 
$result = mysql_fetch_array($query);
$email = $result['email'];

?>

<html>
<head>
	<title>Divvy | Account Info</title>
	<link type="text/css" rel="stylesheet" href="css/reset.css" />
	<link type="text/css" rel="stylesheet" href="css/login.css" />
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<table>
		<tr><td>Group Name: </td><td><?php echo $username; ?></td></tr>
		<tr><td>Registered Email: </td><td><?php echo $email; ?></td></tr>
		<tr><td>Number of Users: </td><td>			</td></tr>
		<th colspan="2"><td><a href="password_reset.php">Change Password</a></td></th>
	</table>
</body>
</html>