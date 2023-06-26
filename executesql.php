<?php 
    require_once 'connection.php';
	// echo '<script>alert("'.$_GET['sql'].'")</script>';
    try {
        mysqli_query($connection, $_GET['sql']);
    } catch (Exception $e) {
        echo '';
    }

    $message = '';
	$error_code = mysqli_errno($connection);
    switch ($error_code) {
            case "1062":
                $message = "запись с таким же уникальным полем уже существует";
                break;
            case "4025":
                $message = "неверный формат значения полей";
                break;
            case "1064":
                $message = "неверный формат значения полей";
                break;
            case "1644":
                $message = "Дата введена неверно!";
                break;
            default:
                $message = "Данные изменены";
                break;
        }
?>

<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const url = 'images/1.jpg';

            const body = document.getElementsByTagName('body')[0];
            let preloaderImg = document.createElement('img');
            preloaderImg.src = url;

            preloaderImg.addEventListener('load', (event) => {
                body.style.backgroundImage = `url(${url})`;
                preloaderImg = null;

                setTimeout(() => {
                    alert('<?= $message ?>');
                    location.href = '<?= $_GET["link"] ?>';
                }, 250);
            });
        });
    </script>
</html>