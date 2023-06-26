
<!DOCTYPE html>
<html>
	<head>
		<title>Меню</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo"></div>
				<p id="logostr">Меню</p>
			</div>
			<div class="buttons">
			<?php if ($_GET['status'] == 'admin') { ?>
				<button style="width: 350px; " onclick="location.href = 'tables.php'">работа с базой данных</button><br>
			<?php } ?> 
				<button style="width: 350px" onclick="location.href = 'workwithdocs.php?status=<?= $_GET['status'] ?>';">работа с отчетами</button>
				<button style="position: absolute; top: 650px; left: 50%; transform: translateX(-44%); width: 180px" onclick="location.href = 'index.php?status=<?= $_GET['status'] ?>'">назад</button>
			</div>
		</div>
	</body>
</html>
