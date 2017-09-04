<?php
	function makeFile($path)
	{
		$file = fopen($path,'w');
		fclose($file);
	}
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/connections.php';
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$username = correctString($_POST['u']);
	$transfile= dirname($_SERVER['DOCUMENT_ROOT']) . "/lmp/user_transactions/$username/transactions.txt";
	$preffile = dirname($_SERVER['DOCUMENT_ROOT']) . "/lmp/user_transactions/$username/options.txt";
	$constfile = dirname($_SERVER['DOCUMENT_ROOT']) . "/lmp/user_transactions/$username/constants.txt";
	$directory = dirname($_SERVER['DOCUMENT_ROOT']) . "/lmp/user_transactions/$username";
	mkdir($directory);
	makeFile($transfile);
	makeFile($preffile);
	makeFile($constfile);
	$email = correctString($_POST['e']);
	
	$interval = $_POST['i'];
	$date = strtoupper($_POST['d']);
	
	$con = new connections('USER');
	mysqli_query($con->USER,"INSERT INTO users (username,email) VALUES ('$username','$email')");
	$file = fopen($constfile,'w');
	fwrite($file,"$date:$interval");
	fclose($file);
	$md5username=md5($username);
	$id = mysqli_fetch_object(mysqli_query($con->USER,"SELECT id FROM users WHERE username='$username'"))->id;
	$link = "https://www.lymanmoffat.com/setpass?r=".md5($username);
	mysqli_query($con->USER,"INSERT INTO activation (id,activation_key) VALUES ($id,'$md5username')");
	@mail($email,'Thanks for signing up with lymanmoffat.com',"This email serves as a way to both set your password and your confirmation. Just copy and follow this link: $link");
	echo 'successful';
?>