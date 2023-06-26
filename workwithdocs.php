<?php 
	require_once 'connection.php';
	$tables = mysqli_query($connection, "SELECT t.TABLE_COMMENT, t.TABLE_NAME FROM INFORMATION_SCHEMA.TABLES t WHERE t.TABLE_SCHEMA = 'biblioteka' AND t.TABLE_COMMENT <> '' AND t.TABLE_NAME NOT IN (SELECT c.TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS c WHERE c.TABLE_SCHEMA = 'biblioteka' AND c.TABLE_NAME = t.TABLE_NAME AND c.COLUMN_KEY = 'MUL')");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Работа с отчетом</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo" style="cursor: pointer" onclick="location.href = 'index.php?status=<?= $_GET['status'] ?>'"></div>
				<p id="logostr">Отчеты</p>
			</div>
			<div class="buttons" style = "padding-top: 100px; align-items: flex-start">
				<?php while($table = mysqli_fetch_array($tables)){ ?>
					<button onclick = "location.href = 'docs.php?table=<?= $table[1] ?>&status=<?= $_GET['status'] ?>'" style = "width: 400px">отчеты по таблице «<?= $table[0] ?>»</button><br>
				<?php } ?>
				<button onclick="location.href = 'main.php?status=<?= $_GET['status'] ?>'">назад</button>
			</div>
		</div>
	</body>
</html>