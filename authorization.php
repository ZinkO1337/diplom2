<?php 
	require_once 'connection.php';
	if(isset($_GET['login']) and isset($_GET['pass'])){
		$acc = mysqli_query($connection, "SELECT * FROM users WHERE login = '".mysqli_real_escape_string($connection, $_GET['login'])."' AND pass = '".mysqli_real_escape_string($connection, $_GET['pass'])."'");
		
		if(mysqli_num_rows($acc)){
            $role = mysqli_fetch_assoc($acc)['role_status'];
			echo '<script> alert("вы успешно авторизованы"); location.href="index.php?status='.$role.'" </script>';
		}else{
			echo '<script> alert("аккаунт не найден"); </script>';
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Авторизации</title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo"></div>
				<p id="logostr">Авторизации</p>
			</div>
			<a id="uprightbt" href="registration.php?status=<?= $_GET['status'] ?>">Ещё не зарегистрированы?</a>
			<div class="buttons">
				<input placeholder="введите логин" type="text"><br>
				<input placeholder="введите пароль" type="text"><br>
				<button onclick="location.href = 'authorization.php?status=<?= $_GET['status'] ?>&login='+document.getElementsByTagName('input')[0].value+'&pass='+document.getElementsByTagName('input')[1].value">авторизоваться</button><br>
				<button onclick="<?php if($_GET['status'] != 'guest'){ ?> alert('вы успешно вышли из учетной записи'); location.href = 'index.php?status=guest' <?php }else{ ?> alert('вы еще не авторизованы'); <?php } ?>">выйти</button>
				<button onclick="location.href = 'index.php?status=<?= $_GET['status'] ?>'">назад</button>
			</div>
		</div>
	</body>
</html>
