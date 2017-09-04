<?php
	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	var_dump(getCategories(9));
?>