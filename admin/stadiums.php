<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

//Def Stadion
if (isset($_POST['action']) && $_POST['action'] === 'create') {
	if (!isset($_POST['edit']) && isset($_POST['miejscowosc'])) {
		$app->createStadion(
			$_POST['miejscowosc'],
			$_POST['informacje'],
			$_POST['druzyna_id'],
			$_POST['szerokosc'],
			$_POST['wysokosc']
		);
	} else {
		$editStadion = $app->getStadion($_POST['edit']);
		if ($editStadion) {
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
}

if (isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $stadion['id']) {
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
<br /><a href="menu.php">Powrót</a>
<hr>
<form action="stadiums.php" method="POST">
	<input type="text" name="miejscowosc" value="<?= isset($editStadion) ? $editStadion['miejscowosc'] : '' ?>" placeholder="Miejscowość" />
	<input type="text" name="informacje" value="<?= isset($editStadion) ? $editStadion['informacje'] : '' ?>" placeholder="Informacje" />
	<input type="text" name="druzyna_id" value="<?= isset($editStadion) ? $editStadion['druzyna_id'] : '' ?>" placeholder="ID drużyny" />
	<input type="text" name="szerokosc" value="<?= isset($editStadion) ? $editStadion['szerokosc'] : '' ?>" placeholder="Szerokość geograficzna" />
	<input type="text" name="wysokosc" value="<?= isset($editStadion) ? $editStadion['wysokosc'] : '' ?>" placeholder="Wysokość geograficzna" />
	<input type="hidden" name="action" value="create">
	<?php
	if (isset($editStadion)) { ?>
		<input type="hidden" name="edit" value="<?= $editStadion['id']; ?>">
	<?php
	} ?>
	<input type="submit" value="<?= isset($editStadion) ? 'Edytuj' : 'Utwórz' ?> stadion" />
</form>