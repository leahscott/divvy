<?php

// set local variables
$json = $_POST['json'];
$username = $_COOKIE['ID_my_site'];


// database configuration settings
$config['db'] = array (
	'host'		=> 'localhost',
	'username'	=> 'root',
	'password'	=> 'caNdle%chAnge#',
	'dbname'	=> 'db1'
);

// connect to database
try {
	$db = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['username'], $config['db']['password']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
}

// prepare MYSQL injection
$query = $db->prepare("UPDATE group_info SET json='$json' WHERE group_name='$username'");
echo $db->errorCode();
echo $db->errorInfo();

// execute MYSQL injection 
$query->execute();

?>