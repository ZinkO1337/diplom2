<?php 
	require_once 'connection.php';
    //table schema поменять на свою бд
    $tables = mysqli_query($connection, "SELECT TABLE_COMMENT, TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_COMMENT <> ''");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Работа с таблицам</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo" style="cursor: pointer" onclick="location.href = 'index.php?status=admin'"></div>
				<p id="logostr">Работа с таблицами</p>
			</div>
			<div class="buttons" style = "padding-top: 100px">
				<?php while($table = mysqli_fetch_array($tables)){ ?>
					<button onclick = "location.href = 'workwithdata.php?table=<?= $table[1] ?>'" style = "width: 400px"><?= $table[0] ?></button><br>
				<?php } ?>
				<button onclick="location.href = 'main.php?status=admin'">назад</button>
			</div>
		</div>
	</body>
</html>
