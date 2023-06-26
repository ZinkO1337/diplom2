<?php 
	require_once 'connection.php';

	$columns = mysqli_query($connection, "SELECT COLUMN_NAME, COLUMN_KEY, COLUMN_COMMENT, COLLATION_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$_GET['table']."' AND COLUMN_COMMENT <> ''");

	$rows = mysqli_query($connection, "SELECT * FROM ".$_GET['table']);

	$foreignkeys = array();

	$keys = array();

	$updatestr = 'UPDATE '.$_GET['table'].' SET ';

	$insertstr = 'INSERT INTO '.$_GET['table']." (";
	$insertstr2 = ' VALUES (';

	if(!(isset($_GET['update']))){
		$_GET['update'] = 0;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $_GET['table']; ?></title>
		<link  rel="stylesheet" href="styles.css" type="text/css"/>
	</head>
	<body> 
		<div id="wrapper" style="align-items: flex-start;">
			<!-- logo -->
			<div id="logoBlock">
				<div id="logo" style="cursor: pointer" onclick="location.href = 'index.php?status=admin'"></div>
				<p id="logostr"><?= strtoupper($_GET['table'][0]).substr($_GET['table'], 1, strlen($_GET['table'])); ?></p>
			</div>
			<div id="insertmenu">
			</div>
			<a id="uprightbt" href="tables.php">назад</a>
			<div class="buttons" style="margin-top: 20%">
				<table>
					<tr>
					<?php $i=1; while($column = mysqli_fetch_array($columns)){ ?>
						<th><?= $column[2] ?></th>
						<?php $updatestr .= $column[0].' = '; ?>
						<?php $insertstr .= $column[0]; ?>
						<?php if($column[1] === 'MUL'){
							$foreignkeys[$i] = mysqli_fetch_array( mysqli_query($connection, "SELECT REFERENCED_TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA = 'biblioteka' AND TABLE_NAME = '".$_GET['table']."' AND COLUMN_NAME = '".$column[0]."'") )[0];
							array_push($keys, $i);
							echo '<script> select = document.createElement("select"); select.id = "select'.$i.'"; option = document.createElement("option"); option.text = "введите поле «'.$column[2].'»"; select.appendChild(option); select.classList.add("insertinput"); document.getElementById("insertmenu").appendChild(select); </script>';
						}else{ echo '<script> input = document.createElement("input"); input.placeholder = "введите поле «'.$column[2].'»"; input.classList.add("insertinput"); document.getElementById("insertmenu").appendChild(input); </script>'; } $i++; ?>
						<?php if(!(is_null($column[3])) or (strpos($column[4], 'year') !== false)){
								$updatestr .= "'";
								$insertstr2 .= "'";
						} ?>
						<?php $updatestr .= ', '; ?>
						<?php $insertstr2 .= ', ' ?>
						<?php $insertstr .= ', ' ?>
					<?php } ?>
					<?php $insertstr = substr($insertstr, 0, strlen($insertstr)-2); ?>
					<?php $insertstr .= ')'; ?>
					<th></th><th></th>
					</tr>

					<?php  foreach((array) $foreignkeys as $key => $foreignkey){ ?>
						<?php $foreignnames = mysqli_query($connection, "SELECT * FROM ".$foreignkey); ?>
						<?php while($foreignname = mysqli_fetch_array($foreignnames)){ ?>
							<?= '<script>option = document.createElement("option"); option.text = "'.$foreignname[1].'"; option.value = "'.$foreignname[0].'"; document.getElementById("select'.$key.'").appendChild(option); </script>' ?>
						<?php } ?>
					<?php } ?>
					<?= '<script> menu = document.getElementById("insertmenu"); button = document.createElement("button"); button.textContent = "добавить"; button.id = "insertbutton"; menu.appendChild(button); </script>' ?>
						

					<?php while($row = mysqli_fetch_array($rows)){ ?>
						<tr>
						<?php foreach((array) $row as $key => $domain){ ?>
							<?php if(is_int($key) and $key <> 0){ ?>
								<?php if(in_array($key, $keys)){ ?>
									<td><select class="row<?= $row[0] ?>" style="font-size: 20px; <?php if($row[0] == $_GET['update']){ ?> background-color: green; <?php } ?>">
											<?php $foreignname = mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM ".$foreignkeys[$key]." WHERE id = ".$row[$key])); ?>
											<option value = "<?= $foreignname[0] ?>"><?= $foreignname[1]; ?></option>
											<?php if($row[0] == $_GET['update']){ ?>
												<?php $foreignnames = mysqli_query($connection, "SELECT * FROM ".$foreignkeys[$key]." WHERE id <> ".$row[$key]); ?>
												<?php while($foreignname = mysqli_fetch_array($foreignnames)){ ?>
													<option value = "<?= $foreignname[0] ?>"><?= $foreignname[1] ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</td>
									<?php ?>
								<?php }else{ ?>
									<!-- на ошибку ide забей так надо -->
									<td><input class="row<?= $row[0] ?>" style="margin-bottom: 0px; padding: 2px; font-size: 20px; <?php if($row[0] <> $_GET['update']){ ?> " readonly <?php }else{ ?> background-color: green" <?php } ?>  oninput="this.size = this.value.length+1||10" value='<?= $domain ?>'></td>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<td><button onclick="<?php if($row[0] <> $_GET['update']){ ?> location.href='workwithdata.php?table=<?= $_GET['table'] ?>&update=<?= $row[0] ?>' <?php }else{ ?> createupdate('row<?= $row[0] ?>'); <?php } ?>" style="margin-bottom: 0px; padding: 2px; font-size: 20px; <?php if($row[0] == $_GET['update']){ ?> background-color: green; ">ок<?php }else{ ?> ">изменить<?php } ?></button></td>
						<td><button onclick="<?php if($row[0] == $_GET['update']){ ?> location.href='workwithdata.php?table=<?= $_GET['table'] ?>' <?php }else{ ?> deleterow(<?= $row[0]; ?>) <?php } ?>" style="margin-bottom: 0px; padding: 2px; font-size: 20px; <?php if($row[0] == $_GET['update']){ ?> background-color: green; ">отмена<?php }else{ ?> ">удалить<?php } ?></button></td>
						</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</body>
	<script>

		function createupdate(selclass){
			helpstr = "<?= $updatestr ?>";
			updatestr = '';
			Array.prototype.forEach.call(document.getElementsByClassName(selclass), (select) => { 
				charstr = ','
				if((helpstr.substring(0, helpstr.indexOf(','))).indexOf("'") !== -1){
					charstr = "',";
				}
				updatestr = updatestr + helpstr.substring(0, helpstr.indexOf(','))+select.value+charstr;
				helpstr = helpstr.substring(helpstr.indexOf(',')+1);
			});
			updatestr = updatestr.substring(0, updatestr.length-1)+' WHERE id = <?= $_GET['update'] ?>';
			location.href = "executesql.php?link=workwithdata.php?table=<?= $_GET['table'] ?>&sql="+updatestr;
		}

		function deleterow(id){
			location.href = "executesql.php?link=workwithdata.php?table=<?= $_GET['table'] ?>&sql=DELETE FROM <?= $_GET['table'] ?> WHERE id="+id;
		}

		document.addEventListener('DOMContentLoaded', () => {
			Array.prototype.forEach.call(document.getElementsByTagName('input'), (input) => { input.size = input.value.length+1; });
			document.getElementById('insertbutton').onclick = () => {
				helpstr = "<?= $insertstr2 ?>";
				insertstr = '';
				Array.prototype.forEach.call(document.getElementsByClassName('insertinput'), (values) => { 
					charstr = ',';
					if((helpstr.substring(0, helpstr.indexOf(','))).indexOf("'") !== -1){
						charstr = "',";
					}
					insertstr = insertstr + helpstr.substring(0, helpstr.indexOf(','))+values.value+charstr;
					helpstr = helpstr.substring(helpstr.indexOf(',')+1);
				});
				insertstr = insertstr.substring(0, insertstr.length-1)+')';
				location.href = "executesql.php?link=workwithdata.php?table=<?= $_GET['table'] ?>&sql="+'<?= $insertstr ?>'+insertstr;
			}
		});
	</script>
</html>
