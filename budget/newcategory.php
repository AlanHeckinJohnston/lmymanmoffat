<?php
	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$cat = $_POST['category'];
	$type = $_POST['type'];
	$value = $_POST['value'];
	$mode = $_POST['mode'];
	//grab post data
	$string = $cat . ':' . $mode . $value . ':' . strtoupper($cat) . ':' . strtoupper($type);
	//What will be appended to the file.
	$week = thisWeek();
	$c = getCategories($week);
	
	foreach ($c as $cas)
	{
		if ($cas['com']==strtoupper($cat))
		{
			echo 'taken';
			die();
		}
	}

	$before = [];
	$after = [];
	$file = fopen($_SESSION['pref_file'],'r');
	$on_spot = false;
	$current=[];
	$done = false; // stores wheather we have stored the current week into the array.
	while (!feof($file))
	{
		$s=trim(fgets($file));
		
		
		if ($s == "BUDGET_START")
		{
			$before[]=$s;
			$s = trim(fgets($file));
			$before[]=$s;
			$s = explode(',',$s);
			if (in_array($week,$s));
			{
				$on_spot = true;
			}
			continue;
		}
		if ($s == "BUDGET_END")
		{
			
			if ($on_spot)
			{
				$done=true;
			}
			$on_spot = false;
		}
		
		if ($on_spot)
		{
			$current[]=$s;
		}
		elseif (!$done)
		{
			$before[]=$s;
		}
		else
		{
			$after[]=$s;
		}
		
	
	}
	fclose($file);
	$current[]=$string;
	
	setPersistantAll();
	$before = array_merge($before,$current);
	$before = array_merge($before,$after);
	//merge the arrays into the full file, and write it.
	$file = fopen($_SESSION['pref_file'],'w');
	foreach ($before as $line)
	{
		if (trim($line) == "")
			continue;
		fwrite($file,$line . "\n");
	}
		
	fclose($file);
	echo "yes";
?>