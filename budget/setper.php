<?php

	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';;
	setPersistant(thisWeek()-1);
?>