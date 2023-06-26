<?php 
	require_once 'connection.php';

	if(isset($_GET['newlogin']) and isset($_GET['newpass'])){

try{
	mysqli_query($connection, "INSERT INTO users (login, pass) VALUES ('".mysqli_real_escape_string($connection, $_GET['newlogin'])."', '".mysqli_real_escape_string($connection, $_GET['newpass'])."')");
}catch (Exception $e) 
{ echo 'ошибка!';}

		switch (mysqli_errno($connection)) {
			case "0":
				echo '<script> alert("аккаунт успешно создан"); location.href = "authorization.php?status='.$_GET['status'].'" </script>';
				break;
			case "1062":
				echo '<script> alert("пользователь с таким логином уже существует"); </script>';
				break;
			case "4025":
				echo '<script> alert("неверный формат значения полей"); </script>';
				break;
			default:
				echo '<script> alert("неизвестная ошибка :("); location.href = "authorization.php?status='.$_GET['status'].'" </script>';
				break;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Регистрация</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo"></div>
				<p id="logostr">Учебная практика_02</p>
			</div>
			<div class="buttons">
				<input placeholder="введите логин" type="text"><br>
				<input placeholder="введите пароль" type="text"><br>
				<button onclick="location.href = 'registration.php?status=<?= $_GET['status'] ?>&newlogin='+document.getElementsByTagName('input')[0].value+'&newpass='+document.getElementsByTagName('input')[1].value">зарегистрироваться</button><br>
				<button onclick="location.href = 'authorization.php?status=<?= $_GET['status'] ?>'">назад</button>
			</div>
		</div>
	</body>
</html>