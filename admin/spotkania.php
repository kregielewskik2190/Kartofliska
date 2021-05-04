<?php


$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

if (isset($_POST['action']) && $_POST['action'] === 'create') {

	$err = false;

	$liga = $_POST['liga'];
	$druzyna_a = $_POST['druzyna_a'];
	$druzyna_b = $_POST['druzyna_b'];
	$data = $_POST['data'];
	$kolejka = $_POST['kolejka'];

	if (empty($liga)) {
		$err = true;
		echo "Pole liga nie może być puste!\r\n";
	}

	if (empty($druzyna_a) || empty($druzyna_b)) {
		$err = true;
		echo "Nazwy drużyn nie mogą być puste!\r\n";
	}

	if (empty($data)) {
		$err = true;
		echo "Pole daty jest puste!\r\n";
	}

	if ($druzyna_a == $druzyna_b) {
		$err = true;
		echo "Nazwy drużyn są identyczne\r\n";
	}

	if (empty($kolejka)) {
		$err = true;
		echo "Brak kolejki\r\n";
	}

	if (!$err)
		$app->createSpotkania(
			$liga,
			$druzyna_a,
			$druzyna_b,
			$data,
			$kolejka
		);

	} else if (isset($_POST['action']) && $_POST['action'] === 'edit') {
	$editSpotkania = $app->getSpotkania($_POST['ID']);
	if ($editSpotkania) {
		$app->updateSpotkanie(
			$_POST['ID'],
			$_POST['druzyna_a'],
			$_POST['druzyna_b'],
			$_POST['data'],
			$_POST['bramkiA'],
			$_POST['bramkiB'],
			$_POST['liga'],
			$_POST['kolejka']
		);
		header('Location: spotkania.php');

	}
}


if (isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $spotkania['id']) {
	$app->removeSpotkania($_GET['remove']);
	header('Location: spotkania.php');
}
if (isset($_GET['edit'])) {
	$editSpotkania = $app->getSpotkania($_GET['edit']);
	if (!$editSpotkania) {
		header('Location: spotkania.php');
		exit();
	}
}
$spotkania = $app->getAllSpotkania();
?>
<form action="spotkania.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="ID" value="<?= isset($editSpotkania) ? $editSpotkania['id'] : '' ?>" />
	<input type="number" name="liga" value="<?= isset($editSpotkania) ? $editSpotkania['liga'] : '' ?>" placeholder="liga" min="1" max="5" required/>
	<label for="druzyna_a">drużyna a:</label>
	<select id="druzyna_a" name="druzyna_a" required>
		<?php $teams = $app->getAllNamesDruzyna();
		foreach ($teams as $key => $team) {
			$nazwa = $team['Nazwa'];

			$option = '<option value="'.$nazwa.'"'.(isset($editSpotkania) && $editSpotkania["druzyna_a"] == $nazwa ?'selected' : '').'>'.$nazwa.'</option>';
			echo $option;
		} ?>
	</select>
	<label for="druzyna_b">drużyna b:</label>
	<select id="druzyna_b" name="druzyna_b" required>
		<?php $teams = $app->getAllNamesDruzyna();
		foreach ($teams as $key => $team) {
			$nazwa = $team['Nazwa'];
			$option = '<option value="'.$nazwa.'"'.(isset($editSpotkania) && $editSpotkania["druzyna_b"] == $nazwa ?'selected' : '').'>'.$nazwa.'</option>';
			echo $option;
		} ?>
	</select>
	<input type="date" name="data" value="<?= isset($editSpotkania) ? $editSpotkania['data'] : '' ?>" placeholder="data" required/>
	<input type="number" name="bramkiA" value="<?= isset($editSpotkania) ? $editSpotkania['bramkiA'] : '' ?>" placeholder="Liczba bramek drużyny A" <?= isset($editSpotkania) ? '' : 'disabled="disabled"' ?> />
	<input type="number" name="bramkiB" value="<?= isset($editSpotkania) ? $editSpotkania['bramkiB'] : '' ?>" placeholder="Liczba bramek drużyny A" <?= isset($editSpotkania) ? '' : 'disabled="disabled"' ?> />
	<input type="number" name="kolejka" value="<?= isset($editSpotkania) ? $editSpotkania['kolejka'] : '' ?>" placeholder="Kolejka" min="1" max="30"/>
	<input type="hidden" name="action" value="<?= isset($editSpotkania) ? "edit" : 'create' ?>">

	<input type="submit" value="<?= isset($editSpotkania) ? 'Edytuj' : 'Utwórz' ?> spotkanie" />

</form>
<br /><a href="menu.php">Powrót</a>
<hr>
<table>
	<tr>
		<td>id</td>
		<td>Liga</td>
		<td>druzyna_a</td>
		<td>druzyna_b</td>
		<td>data</td>
		<td>bramki A</td>
		<td>bramki B</td>
		<td>Kolejka</td>
	</tr>
	<?php
	foreach ($spotkania as $spotkania_) {
		echo '<tr>
<td>' . $spotkania_['id'] . '</td>
<td>' . $spotkania_['liga'] . '</td>
<td>' . $spotkania_['druzyna_a'] . '</td>
<td>' . $spotkania_['druzyna_b'] . '</td>
<td>' . $spotkania_['data'] . '</td>
<td>' . $spotkania_['bramkiA'] . '</td>
<td>' . $spotkania_['bramkiB'] . '</td>
<td>' . $spotkania_['kolejka'] . '</td>

<td><a href="?remove=' . $spotkania_['id'] . '">Usuń</a> <a href="?edit=' . $spotkania_['id'] . '">Edytuj</a></td>
</tr>';
	}
	?>
</table>

