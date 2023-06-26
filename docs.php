<?php 
    //тут вроде все норми
	require_once 'connection.php';
	$titles = mysqli_query($connection, "SELECT * FROM ".$_GET['table']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Работа с отчетами</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo" style="cursor: pointer" onclick="location.href = 'index.php?status=<?= $_GET['status'] ?>'"></div>
				<p id="logostr">Работа с отчетами</p>
			</div>
			<h1 style="position: absolute; top: 20%; left: 50%; font-size: 45px; transform: translate(-50%, -50%);">Выберете нужную запись</h1>
			<div class="buttons" style = "padding-top: 100px; align-items: flex-start">
				<?php while($title = mysqli_fetch_array($titles)){ ?>
					<button onclick = "location.href = 'createdoc.php?table=<?= $_GET['table'] ?>&row=<?= $title[0] ?>&status=<?= $_GET['status'] ?>'" style = "width: 400px">«<?= $title[1] ?>»</button><br>
				<?php } ?>
				<button onclick="location.href = 'workwithdocs.php?table=<?= $_GET['table'] ?>&status=<?= $_GET['status'] ?>'">назад</button>
			</div>
		</div>
	</body>
</html>
