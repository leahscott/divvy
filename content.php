 <?php 

 	// Connects to your Database 
 	mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); 
 	mysql_select_db("db1") or die(mysql_error()); 
 
 	//checks cookies to make sure they are logged in 
 	if(isset($_COOKIE['ID_my_site'])) { 
 		$username = $_COOKIE['ID_my_site']; 
 		$pass = $_COOKIE['Key_my_site']; 

 	 	$check = mysql_query("SELECT * FROM group_info WHERE group_name = '$username'")or die(mysql_error()); 

 		while($info = mysql_fetch_array( $check )) { 
 			//if the cookie has the wrong password, they are taken to the login page 
			if ($pass != $info['password']) { 			
				header("Location: index.php"); 
 			} 
 			
 			//otherwise they are shown the admin area	 
 			else { 
 				
?> 

<html> 

<head>
	<title>Divvy</title>

	<link type="text/css" rel="stylesheet" href="css/reset.css" />
	<link type="text/css" rel="stylesheet" href="css/main.css" />
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="script.js"></script>
</head>

<body>

	<div id="main">

		<div id="sidebar">
			<h1 id="logo"><span>divvy</span></h1>
			<div class="sidebar-block" id="debt-wrapper">
				<h3 class="sidebar-header">
					<div><?php echo $username; ?></div>
					<a href="password_reset.php">Change Password</a>
					<a id="addUser">Add User</a>
					<a href="logout.php">Logout</a>
				</h3>
			</div>
			<div class="receipt-submit">
				<button type="button" name="divvy-it" id="divvy-it">Divvy It Up</button>
			</div>
		</div>

		<section id="content">
			<!--<nav id="nav">Divvy helps people who constantly need to split the cost of things within a small group of friends. It's a debt-tracking application that keeps a running total of who owes what. Roommate bought a pizza? Split the cost on Divvy and pay for next week's groceries to recoup your debt. No need for calculators or cash withdrawals.
			</nav>-->
			<div id="receipt-wrapper">
				<div id="receipt">
					<table>
						<tr class="receipt-header">
							<th class="head-buyer">Buyer</th>
							<th class="head-name">Item</th>
							<th class="head-price">Price</th>
							<th class="head-ower">For</th>
							<th class="head-action"></th>
						</tr>

						<form id="add_form">
							<tr class="receipt-form">
								<datalist id="datalist"></datalist>
								<td class="receipt-input-buyer">
									<input name="buyer" id="buyerData" list="datalist" autocomplete="off" placeholder="Who paid?">
								</td>
								<td class="receipt-input-name">
									<input type="text" name="item" value="" id="itemData" autocomplete="off" placeholder="What'd they buy?">
								</td>
								<td class="receipt-input-price">
									<div class="damn-firefox">
										<span class="dollar-sign">$</span>
										<input type="number" name="price" id="priceData" autocomplete="off" placeholder="0.00">
									</div>
								</td>
								<td class="receipt-input-ower">
									<input type="text" name="owers" value="" id="owersData" autocomplete="off" placeholder="Split with whom?">
								</td>
								<td class="receipt-input-add">
									<input type="submit" onclick="return false" name="add" value="&#43;" id="add">
								</td>
							</tr>
						</form>
					</table>
				</div>
			</div>
		</section>

	</div>

</body>
</html>

<?php
 			} 

 		} 

 	} 
 
 	//if the cookie does not exist, they are taken to the login screen 
 	else {			 
 		header("Location: index.php"); 
 	} 

 ?> 
