<?php
	session_start();
	include dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';;
	if (!isset($_POST['id']))
		die();
	$week = $_POST['week'];
	$oldCategory = $_POST['category'];
	$id = $_POST['id'];
	removeOld($week,$oldCategory,$id);
?>