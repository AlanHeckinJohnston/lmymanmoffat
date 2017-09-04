<?php
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	session_start();
	include dirname(ROOT) . '/lmp/file_structure.php';
	include dirname(ROOT) . '/lmp/connections.php';
	$con = new connections('USER');
	if (!isset($_SESSION['username']))//if no session data is recorded, this means the user is not logged in.
	{
		if (isset($_COOKIE['timeID']))//if the timeId cookie is there, the user can be automatically logged in.
		{
			$timeID = $_COOKIE['timeID']; //check the database for a matching timeID.
			$query = mysqli_query($con -> USER,"SELECT * FROM users WHERE timeID='$timeID'");
			if (mysqli_num_rows($query) == 1)
			{
				$username = mysqli_fetch_object($query)->username;
				$_SESSION['username'] = $username;
				$_SESSION['trans_file']= dirname(ROOT) . "/lmp/user_transactions/$username/transactions.txt";
				$_SESSION['pref_file'] = dirname(ROOT) . "/lmp/user_transactions/$username/options.txt";
				$_SESSION['const_file'] = dirname(ROOT) . "/lmp/user_transactions/$username/constants.txt";
				$_SESSION['directory'] = dirname(ROOT) . "/lmp/user_transactions/$username";
			}
			else
			{
				header('Location: log.php');
			}
			
		}
		else
		{
			header('Location: log.php');//user is not logged in and does not have a time id. Redirect them to login page.
		}
	}
	else
	{
		$microtime = (string)microtime();
		setcookie('timeID',$microtime,time()+259200,'/');
		$us = $_SESSION['username'];
		mysqli_query($con->USER, "UPDATE users SET timeID='$microtime' WHERE username='$us'");
	}
	if (!file_exists($_SESSION['directory']))
	{
		mkdir($_SESSION['directory']);
	}
	if (!file_exists($_SESSION['trans_file']))
	{
		$file = fopen($_SESSION['trans_file'],'w');
		fwrite($file,"PERSISTANT\nPERSISTANT\n");
		fclose($file);
	}
	if (!file_exists($_SESSION['pref_file']))
	{
		$file = fopen($_SESSION['pref_file'],'w');
		fwrite($file, "Income:0:INCOME");
		fclose($file);
	}
	if (!file_exists($_SESSION['const_file']))
	{
		$file = fopen($_SESSION['const_file'],'w');
		fwrite($file, "S:".time()."\n"."I:WEEK");
		fclose($file);
	}
	$week = thisWeek(); //Budget week. Week refers to the interval the user is on, regardless of wheater or not it is actually a week.

	
?>
<!doctype html>
<html>
	<head>
		<script src="jquerydevkit.js"></script>
		<script>week = <?php echo $week; ?>; allcats = <?php echo json_encode(array_keys(getCategories($week))); ?>;</script>
		<script src="client.js?v=4"></script>
		<link rel="stylesheet" href="client.css?v=4">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<title>Budgeting</title>
	</head>
	<body>
		<section id="wrapper">
		<section id="main">
			<div id="Remaining_Money">
			<?php 
				$c = getCategories($week);
				$count = 0;
				foreach ($c as $key=>$data)
				{
					if ($key=="Income")
						continue;
					echo "<div id =\"rm_$count\" class=\"remaining\">
							<div class=\"remaining_bar\">$key</div>
							<div class=\"remaining_object\"></div>
							</div>";
					$count++;
				}
			?>
			</div>
			
			<div id="transactionWrapper">
				<div id="openTransaction" class="button">
					<div class="text">New Transaction</div>
				</div>
			</div>
			<div id="newT">
				<p>Budget Interval:
					<?php 
						echo "<select id='change'>";
						for ($i=1;$i<=$week;$i++)
						{
							echo "<option>$i</option>";
						}
						echo "</select>";
					?>
				</p>
				<div class="open">
					<div class="option">Category:</div>			
					<div class="option">
						<select id="category">
							<?php

								//echo "<code>" . json_encode($c) . "</code>";
								$count = 1;
								foreach ($c as $key=>$data)
								{
									if ($data['type']=='CONTINIOUS')
										echo "<option id=\"$count\">$key</option>";
									$count++;
								}
							?>					
						</select>
					</div>
				</div>
				<div class="open">
					<div class="option">Description:</div>
					<div class="option"><input id="description"></div>
				</div>
				<div class="open">
					<div class="option">Amount:</div>
					<div class="option"><input id="amount" type="number" step=".01"></div>
				</div>
				<div class="open" id="submitWrapper"><div class="button" id="submit"><div class="text">Submit</div></div>
			</div>
		</section>
		</section>
	</body>
</html>