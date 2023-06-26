<?php 
	$connection = mysqli_connect('localhost', 'root', '', 'biblioteka'); //первый параметр оставляешь, вторым и 3-им логин и пароль соответственно (по умолчанию root и пустая строка) 4 аргумент - название бд

	if( $connection == false ) {
		echo 'Нет соединения с бд';
		echo mysqli_connect_error();
		exit();
	}
?>