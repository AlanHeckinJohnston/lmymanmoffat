<?php
	session_start();
	include dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$amount = toMoney($_POST['amount']);//the amount that the new transaction should be
	$description = $_POST['description']; //the description of the new transaction
	$id = $_POST['id'];//the id in respect to its week and category (basically array key)
	$oldCategory = $_POST['category']; //the category it currently resides in
	$newCategory = $_POST['tocategory']; //the category it should be moved to
	$week = $_POST['week']; //the week the transaction is in.
	
	$newLine = "$amount|$description";
	if ($oldCategory == $newCategory)
	{
		replaceOld($week,$oldCategory,$newLine,$id);
	}
	else
	{
		removeOld($week,$oldCategory,$id);
		insertNew($week,$newCategory,$newLine);
	}
?>