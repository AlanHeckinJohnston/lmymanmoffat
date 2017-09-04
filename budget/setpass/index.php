<?php
	if (!isset($_GET['r']))
		header('Location: lymanmoffat.com');
	
	$r = $_GET['r'];
	session_start();
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/connections.php';
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$con = new connections('USER');
	
	$query = mysqli_query($con->USER,"SELECT * FROM activation WHERE activation_key='$r'");
	if (mysqli_num_rows($query)!=0)
	{
		$id = mysqli_fetch_object($query) -> id;
		$_SESSION['id']=$id;
		$echo = "<p>All right! You got the email. Ok, set your password! Make it something secure and easy to remember, but not one you have used on other websites.</p><div id='floater'><input id='pass' type='password'><button id='stage1submit'>Go</button></div>";
	}
	else
	{
		$echo = "<p>Sorry, we don't see your email in our list. If you believe something is wrong, email support@lymanmoffat.com and we will be glad to assist you.</p>";
	}
?>
<!doctype html>
<html>
	<head>
		<script src="../jquerydevkit.js"></script>
		<script src="setpass.js"></script>
		<link rel="stylesheet" href="../start.css">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<section id="wrapper">
			<div id="main">
				<h1>Set Password</h1>
				<div id="info">
					<?php echo $echo;?>
				</div>
			</div>
		</section>
	</body>
</html>