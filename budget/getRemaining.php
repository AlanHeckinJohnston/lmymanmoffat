<?php
	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$week=$_POST['week'];

	$categories = getCategories();
	$persistant = getPersistant();
	$i=0;
	$income = getTotalAmount($week,"Income");
	foreach ($categories as $key=>$category)
	{
		if ($key == "Income")
			continue;
		$keypes = $persistant[$key];
		
		
		$keyspent = getTotalAmount($week,$key);
		
		$allocated = $income*($category['data']/100);
		$remaining = tomoney(($allocated - $keyspent)+$keypes);
		
		$data[$i]=$remaining;
		$i++;
	}
	
	echo json_encode($data);
?>