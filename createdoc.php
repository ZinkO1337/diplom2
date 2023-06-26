<?php
	require_once 'connection.php';

	$foreigns = mysqli_query($connection, "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = '".$_GET['table']."'");
	$domain = mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM ".$_GET['table']." WHERE id = ".$_GET['row']));
	$attributes = mysqli_query($connection, "SELECT COLUMN_COMMENT AS cc FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$_GET['table']."' AND COLUMN_COMMENT <> ''"); //при создании таблиц надо прописывать комментарии к таблицам и атрибутам, в таком виде оно и отображается на сайте
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<style>
			*{
				position: absolute;
				

			}
			html, body{
				position: relative; 
				width: 100%; 
				height: 100%; 
				margin: 0; 
				background-image: url('images/1.jpg');
			}	
			#exportContent{
				width: 100%;
				height: 75%;
			}
			#window{
				transform: translate(-50%, -50%); 
				left: 50%; 
				top: 80%;
				width: 630px;
				height: 891px;
				border: 1px solid black;
				background-color: white;
			}		
		</style>
	</head>
	<body>
		<div id="exportContent">
			<div id="window" style="position: relative; font: bold 15pt serif;">
			<div style="background-image: url('images/pech.png'); opacity: 65%; width: 35%; height: 23%; background-size: cover; top: 1%; right: 7%; top: 75%"></div>
				<?php $top = 8; ?>
				<div style="left: 5%; font-size: 25px; top: <?= $top ?>%">Общая информация: </div>
				<?php $top += 4; ?>
				<div align="center" style="top: 5%; left: 50%; transform: translate(-50%, -50%);"><?= mysqli_fetch_array(mysqli_query($connection, "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$_GET['table']."'"))[0]." «".mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM ".$_GET['table']." WHERE id = ".$_GET['row']))[1]."»" ?></div>
				<?php while($attribute = mysqli_fetch_assoc($attributes)){ ?>
					<div style="left: 5%; top: <?= $top ?>%"><?= $attribute['cc'] ?>: <?= $domain[($top-9)/3] ?></div>
					<?php $top += 3; ?>
				<?php } ?>
				
				<?php while($foreign = mysqli_fetch_array($foreigns)){
					$columns = mysqli_query($connection, "SELECT COLUMN_NAME, COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$foreign[0]."' AND COLUMN_KEY <> 'PRI'");
					$ref_tables = mysqli_query($connection, "SELECT REFERENCED_TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$foreign[0]."' AND REFERENCED_TABLE_NAME IS NOT NULL");
					$querypart1 = "SELECT DISTINCT ";
					$querypart2 = " FROM ".$foreign[0];
					$querypart3 = " ON ";
					while($column = mysqli_fetch_array($columns)){
						if($column[1] === 'MUL'){
							$ref_table = mysqli_fetch_array($ref_tables);
							if($_GET['table'] != $ref_table[0]){
								$querypart1 .= $ref_table[0].".".mysqli_fetch_array(mysqli_query($connection, "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$ref_table[0]."' AND COLUMN_KEY <> 'PRI'"))[0]." AS ".$ref_table[0]."name, ";
							}
							$querypart2 .= " INNER JOIN ".$ref_table[0];
							$querypart3 .= $foreign[0].".".$ref_table[1]." = ".$ref_table[0].".id AND ";
						}
					}
					$querypart1 = substr($querypart1, 0, strlen($querypart1)-2);
					$querypart3 = substr($querypart3, 0, strlen($querypart3)-5)." WHERE ".$_GET['table'].".id = ".$_GET['row'];
					?> 
					<div style="left: 5%; font-size: 25px; top: <?= $top ?>%"><?= mysqli_fetch_array(mysqli_query($connection, "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$foreign[0]."'"))[0]; ?>: </div> 
					<?php $top += 3; ?>
					<?php $thisquery = mysqli_query($connection, $querypart1.$querypart2.$querypart3); ?>
					<div style="left: 8%; top: <?= $top ?>%"><?= mysqli_fetch_array( mysqli_query($connection, "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".substr($querypart1, strpos($querypart1, "DISTINCT ")+9, strpos($querypart1, ".")-strpos($querypart1, "DISTINCT ")-9)."'" ))[0] ?>: </div>
					<?php $top += 3; ?>
					<?php if(mysqli_num_rows($thisquery) == 0){ ?>
						<div style="left: 10%; top: <?= $top ?>%">(отсутствует)</div>
						<?php $top += 3; ?>
					<?php } ?>
					<?php while($query = mysqli_fetch_array($thisquery)){ ?>
						<div style="left: 10%; top: <?= $top ?>%"><?= $query[0] ?></div>
						<?php $top += 3; ?>
					<?php }	?>
					<div style="left: 7%; top: <?= $top ?>%">общее кол-во: <?= mysqli_num_rows($thisquery) ?></div>
					<?php $top += 3; ?>
				<?php } ?>			

			</div>
		</div>
		<button onclick = "Export2doc('exportContent', 'отчет')" style="transform: translate(-50%, -50%); left: 50%; top: 110%">экспортировать отчет</button>
		<div style="top: 170%; width: 10%; height: 10%"></div>
		<button onclick="location.href = 'docs.php?table=<?= $_GET['table'] ?>'" style="transform: translate(-50%, -50%); left: 50%; top: 115%">назад</button>
	</body>
	<script>
		function Export2doc(element, fileName = '')
{
	var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML to Doc</title></head><body>";
	var postHtml = "</body></html>";
	var html = preHtml+document.getElementById(element).innerHTML+postHtml;

	var blob = new Blob(['\ufeff', html],{
		type: 'application/msword'
	});

	var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);

	fileName = fileName?fileName+'.doc': 'document.doc';

	var downloadLink = document.createElement("a");

	document.body.appendChild(downloadLink);

	if (navigator.msSaveOrOpenBlob)
	{
		navigator.msSaveOrOpenBlob(blob, fileName);
	}
	else
	{
		downloadLink.href = url;

		downloadLink.download = fileName;

		downloadLink.click();
	}

	document.body.removeChild(downloadLink);
}
	</script>
</html>
