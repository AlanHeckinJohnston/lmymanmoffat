<?php
	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/connections.php';
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	
	if (!empty($_POST))//if login form has sent another piece of info.
	{
		if (isset($_POST['username']))
		{
			$con = new connections('USER');
			$username = correctString($_POST['username']);
			if ($username=="")
			{
				echo "empty";
				die();
			}
			if (mysqli_num_rows(mysqli_query($con->USER, "SELECT * FROM users WHERE username='$username'"))==0)
				echo "ye";
			else
				echo "taken";
			die();
			
		}
		if (isset($_POST['email']))
		{
			$con = new connections('USER');
			$email = correctString($_POST['email']);
			if ($email=="")
			{
				echo "empty";
				die();
			}
			if (mysqli_num_rows(mysqli_query($con->USER, "SELECT * FROM users WHERE email='$email'"))==0)
				echo "ye";
			else
				echo "taken";
			die();
			
		}

	}

?>