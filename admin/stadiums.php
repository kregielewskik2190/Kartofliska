<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

$druzyny = $app->getAllDruzyna();

//Def Stadion
if (isset($_POST['action']) && $_POST['action'] === 'create') {

	$err = false;

	$miejscowosc = $_POST['miejscowosc'];
	$informacje = $_POST['informacje'];
	$druzyna_id = !empty($_POST['druzyna_id']) ? $_POST['druzyna_id'] : "-1";
	$szerokosc = $_POST['szerokosc'];
	$wysokosc = $_POST['wysokosc'];

	$druzynaTest = array_search($druzyna_id, array_column($druzyny, 'ID'));


	if (empty($miejscowosc)) {
		$err = true;
		echo "Pole miejscowosc nie może być puste!\r\n";
	}

	if (strlen($informacje) > 128) {
		$err = true;
		echo "Pole informacji jest za długie!\r\n";
	}

	if (!$druzynaTest) {
		$err = true;
		echo "Pole wyboru drużyny jest puste!\r\n";
	}

	if (empty($szerokosc) || empty($wysokosc)) {
		$err = true;
		echo "Brakuje współrzędnych\r\n";
	}


	if (!$err) {
		$app->createStadion(
				$_POST['miejscowosc'],
				$_POST['informacje'],
				$_POST['druzyna_id'],
				$_POST['szerokosc'],
				$_POST['wysokosc']
			);
		header('Location: stadiums.php');
	}
}

if (isset($_POST['action']) && $_POST['action'] === 'edit') {
		$editStadion = $app->getStadion($_POST['edit']);
		if ($editStadion && $_POST['druzyna_id']) {
			$app->updateStadion(
				$_POST['edit'],
				$_POST['miejscowosc'],
				$_POST['informacje'],
				$_POST['druzyna_id'],
				$_POST['szerokosc'],
				$_POST['wysokosc']
			);
		}
	unset($editStadion);
}

if (isset($_GET['remove']) && $_GET['remove'] > 0) {
	$app->removeStadion($_GET['remove']);
	header('Location: stadiums.php');
}

if (isset($_GET['edit'])) {
	$editStadion = $app->getStadion($_GET['edit']);
	if (!$editStadion) {
		header('Location: stadiums.php');
		exit();
	}
}

$stadions = $app->getAllStadions();
?>

<form action="stadiums.php" method="POST">
	<input type="text" name="miejscowosc" value="<?= isset($editStadion) ? $editStadion['miejscowosc'] : (isset($err) ? $_POST['miejscowosc'] : '') ?>" placeholder="Miejscowość" />
	<input type="text" name="informacje" value="<?= isset($editStadion) ? $editStadion['informacje'] : (isset($err) ? $_POST['informacje'] : '') ?>" placeholder="Informacje" />
	<select name="druzyna_id">
	<option value ="-1" disabled>-- brak --</option>
	<?php
		$v = !isset($editStadion) ? [] : $editStadion;
		echo join("\n",array_map( function($druzyna) use ($v) {
		$selected = $v ? $v['druzyna_id'] : '';
		$nazwa = $druzyna["Nazwa"];
		$id = $druzyna["ID"];
		$test = ($selected ? "selected" : '');
		$str= "<option value=\"$id\" $test>$nazwa</option>";
		return $str;
	}, $druzyny));
	?>
	</select>
	<input type="number" name="szerokosc" step="any" value="<?= isset($editStadion) ? $editStadion['szerokosc'] : (isset($err) ? $_POST['szerokosc'] : '') ?>" placeholder="Szerokość geograficzna" />
	<input type="number" name="wysokosc" step="any" value="<?= isset($editStadion) ? $editStadion['wysokosc'] : (isset($err) ? $_POST['wysokosc'] : '') ?>" placeholder="Wysokość geograficzna" />
	<input type="hidden" name="action" value=<?=
	isset($editStadion) ? "edit" :"create"
	?>>
	<?=
	isset($_GET["edit"]) ? '<input type="hidden" name="edit" value='.$_GET["edit"].' />' : ''
	?>
	<input type="submit" value="<?= isset($editStadion) ? 'Edytuj' : 'Utwórz' ?> stadion" />
</form>
<br /><a href="menu.php">Powrót</a>
<hr>
<table>
	<tr>
		<td>ID</td>
		<td>Miejscowość</td>
		<td>Informacje</td>
		<td>ID drużyny</td>
		<td>Szerokość</td>
		<td>Wysokość</td>
	</tr>
	<?php
	foreach ($stadions as $stadion_) {
		echo '<tr>
<td>' . $stadion_['id'] . '</td>
<td>' . $stadion_['miejscowosc'] . '</td>
<td>' . $stadion_['informacje'] . '</td>
<td>' . $stadion_['druzyna_id'] . '</td>
<td>' . $stadion_['szerokosc'] . '</td>
<td>' . $stadion_['wysokosc'] . '</td>
<td><a href="?remove=' . $stadion_['id'] . '">Usuń</a> <a href="?edit=' . $stadion_['id'] . '">Edytuj</a></td>
</tr>';
	}
	?>
</table>

