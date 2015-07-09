<?php 
 	mysql_connect("localhost", "root", "caNdle%chAnge#") or die(mysql_error()); 
 	mysql_select_db("db1") or die(mysql_error()); 

 	$username = $_COOKIE['ID_my_site'];
	$query = mysql_query("SELECT json FROM group_info WHERE group_name = '$username'");
	$array = mysql_fetch_array($query); 
	$entry = $array['json'];
	
	echo $entry;
?>