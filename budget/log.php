<?php
	define('ROOT',$_SERVER['DOCUMENT_ROOT']);
	session_start();

	if (isset($_POST['info']))
	{

		require dirname(ROOT) . '/lmp/file_structure.php';
		$username = correctString($_POST['username']);
		$password = correctString($_POST['password']);
		require dirname(ROOT) . '/lmp/connections.php';

		$succ = false;

		$succ = login($username,$password);
		if ($succ)
		{
			header('Location: index.php');
			$echo = "<script>window.location='index.php';</script>";
		}
		else
		{
			$echo = "<script>setTimeout(function(){window.location='log.php';},1500);</script><div class='greenbox'>Invalid Credentials.</div>";
		}
	}
	else
	{
		$echo = '<form class="greenbox" method="post" action="log.php">Username:<input name="username"><br>Password:<input type="password" name="password"><br><input type="submit" name="info" value="Log in"><p>Do NOT log in on a device other than your own.</p></form>';
	}
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width">
<style>
	*{
		margin:20px;font-size:22px;
	}
	.greenbox{
		padding:20px;
		position:relative;
		display:block;
		margin:10px auto;
		width:500px;
		text-align:center;
		padding:5px;
		background:green;
		padding-top:80px;
		border-radius:15px;
	}
	@media screen and (max-width:700px){
		*{
			margin:10px auto;
		}
		.greenbox{
			width:98%;
			font-size:30px;
		}
		input{
			max-width:90%;
		}
	}</style>
</head>
<body><?php echo $echo;?></body>
</html>