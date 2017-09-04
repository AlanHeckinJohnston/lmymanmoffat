<?php
	session_start();
	include dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';;
	$week = $_POST['week'];
	$categories = getCategories();
	$transactions = [[]];
	foreach ($categories as $key=>$category)
	{
		$transactions[] = getUnits($week,$key,true);
	}
	
	echo json_encode($transactions);

?>