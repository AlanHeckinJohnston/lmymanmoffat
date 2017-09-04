<?php
	session_start();
	if (!isset($_SESSION['user']))
		header('Location: index.php');
	require dirname($_SERVER['DOCUMENT_ROOT']) . '/lmp/file_structure.php';
	$week=thisWeek();
	$c = getCategories($week);
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="client.css">
		<script src="jquerydevkit.js"></script>
		<script src="viewtransactions.js"></script>
		<script>week = <?php echo $week;?>;  allcats = <?php echo json_encode(array_keys(getCategories($week))); ?></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
	</head>
	<body>
		<div id="oldT">
			<h1>Previous Transactions</h1>
			Budget Interval: <select id="bw"><?php for ($i=1; $i<=$week; $i++){echo "<option>$i</option>";}?></select>
			<select id="oldTcategory">
				<?php
					$count = 1;
					foreach ($c as $key=>$data)
					{
						if ($key===0)
							continue;
						echo "<option id=\"$count\">$key</option>";
						$count++;
					}
				?>
			</select>
			<div id="group"></div>
		</div>
	</body>
</html>