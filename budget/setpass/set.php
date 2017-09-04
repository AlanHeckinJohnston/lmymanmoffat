<?php
	$pass = md5($_POST['password']);
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/connections.php';
	$con = new connections('USER');
	session_start();
	$id = $_SESSION['id'];
	session_unset();
	mysqli_query($con->USER, "UPDATE users SET password='$pass' WHERE id=$id");
	mysqli_query($con->USER, "DELETE FROM activation WHERE id=$id");
?>