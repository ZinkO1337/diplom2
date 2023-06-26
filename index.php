
<?php
	if(!(isset($_GET['status']))){
		$_GET['status'] = 'guest';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Учебная практика_2</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo"></div>
				<p id="logostr">Учебная практика_2.0</p>
			</div>
			<a id="uprightbt" href="authorization.php?status=<?= $_GET['status'] ?>">Войти</a>
			<div class="buttons" style="width: 750px; height: 300px">
			<p><h1> Добро пожаловать в электронную систему "Библиотека"</h1></p>
				<p style="font-style:italic; font-weight: 600";>Здесь вы сможете регистрировать новых сотрудников, читателей и читательские билеты</p>
				<button style="margin-top: 100px; margin-bottom: 250px" onclick="<?php if($_GET['status'] != 'guest'){ ?> location.href = 'main.php?status=<?= $_GET['status'] ?>'; <?php } else { ?> alert('вы еще не авторизованы') <?php } ?>">работа с приложением (доступ только после авторизации)</button><br>
			</div>
		</div>
	</body>
</html>
