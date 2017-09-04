<?php
	session_start();
	
	$percentages = json_decode($_POST['per']); //get the 3D array passed via ajax
	
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';; //grab library
	$keys = array_keys(getCategories()); //get the keys (which are the readable names of categories)
	$i = 0; //set a counter to 0

	foreach ($percentages as $category) //process through the percentages variable
	{
		#0 index of category stores percentage, 1 stores name.
		$name=$category[1];
		if ($name != "")
		{
			$computerName = strtoupper($name);//how the computer views the category in transactions.txt (WEEK_WHATEVER)
			$string = $name . ":" . $category[0] . ":" . $computerName; //compile them into a category line
			$array[]=$string; //add this to the array, to be written into the file.
			$i++;
		}
	}
	
	$file = fopen($_SESSION['pref_file'],'w');
	fwrite($file, "Income:0:INCOME");
	$first=true;
	foreach($array as $line)
	{

		fwrite($file, "\n" . $line);
	}

	fclose($file);
	if (empty($percentages))
		echo "Empty";
	else
		echo "Yes";
?>