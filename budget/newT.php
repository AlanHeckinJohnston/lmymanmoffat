<?php
	session_start();
	define('ROOT',$_SERVER['DOCUMENT_ROOT']);
	if (!isset($_SESSION['username']))
	{
		die("noSes");
	}
		
	$week = $_POST['week'];
	$category = $_POST['category'];
	include dirname(ROOT) . '/lmp/file_structure.php';
	$info = toMoney($_POST['amount']) . "|" . $_POST['description'];
	if (!weekExists($week))
	{
		createWeek($week);
		setPersistant($week);
	}
	
	if (insertNew($week,$category,$info))
		echo "Yes";
	else
		echo "No";

?>