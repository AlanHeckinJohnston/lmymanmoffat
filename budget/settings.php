<?php
	session_start();
	
	if (!isset($_SESSION['username']))
		header('Location: log.php');
	include dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$week = thisWeek(); //Budget week
	$income = getTotalAmount($week,"Income");
	if (empty($income))
		$income = getTotalAmount($week-1, "Income");
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="client.css">
		<link rel="stylesheet" href="settings.css?v=3">
		<script src="jquerydevkit.js"></script>
		<script src="settings.js?v=6"></script>
		<script>var income=<?php echo $income;?>;</script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<section id="wrapper">
			<div id="option_wrapper">
			<?php
				$categories = getCategories($week);
				foreach ($categories as $key=>$category)
				{
					$p = $category['data'];
					if ($key=="Income")
						continue;
					echo "<div data-percentage='$p' class='option' id='$key'><div class='option_text'>$key</div></div>";
					
				}
			?>
			<div class='option' id='Savings'><div class='option_text'>Savings</div></div>
			<div class='option' id='new_category'><div class='option_text'>New...</div></div>
			</div>
			<div id='working_area'>
				<div id='animated_area'></div>
			</div>
		</section>
	</body>
</html>