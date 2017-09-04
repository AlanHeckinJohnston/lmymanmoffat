<?php
	define('ROOT',dirname($_SERVER['DOCUMENT_ROOT']));
	require_once ROOT . '/lmp/connections.php';
	
	$con = new connections('USER');
	$password = md5('monstertruck88');
	if (mysqli_query($con -> USER, "INSERT INTO users (username, email, password) VALUES ('josh', 'Vexarial@gmail.com','$password')")!=false)
		echo "User successfuly added, manually. This file will now delete itself.";
	unlink(__FILE__);
?>