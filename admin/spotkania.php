<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

if (isset($_POST['action']) && $_POST['action'] === 'create') {
	$app->createSpotkania(
		$_POST['druzyna_a'],
		$_POST['druzyna_b'],
		$_POST['data']
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
			$_POST['bramkiB']
		);
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
<table>
	<tr>
		<td>id</td>
		<td>druzyna_a</td>
		<td>druzyna_b</td>
		<td>data</td>
		<td>bramki A</td>
		<td>bramki B</td>
	</tr>
	<?php
	foreach ($spotkania as $spotkania_) {
		echo '<tr>
<td>' . $spotkania_['id'] . '</td>
<td>' . $spotkania_['druzyna_a'] . '</td>
<td>' . $spotkania_['druzyna_b'] . '</td>
<td>' . $spotkania_['data'] . '</td>
<td>' . $spotkania_['bramkiA'] . '</td>
<td>' . $spotkania_['bramkiB'] . '</td>

<td><a href="?remove=' . $spotkania_['id'] . '">Usuń</a> <a href="?edit=' . $spotkania_['id'] . '">Edytuj</a></td>
</tr>';
	}
	?>
</table>
<br /><a href="menu.php">Powrót</a>
<hr>
<form action="spotkania.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="ID" value="<?= isset($editSpotkania) ? $editSpotkania['id'] : '' ?>" />
	<input type="text" name="druzyna_a" value="<?= isset($editSpotkania) ? $editSpotkania['druzyna_a'] : '' ?>" placeholder="druzyna_a" />
	<input type="text" name="druzyna_b" value="<?= isset($editSpotkania) ? $editSpotkania['druzyna_b'] : '' ?>" placeholder="druzyna_b" />
	<input type="date" name="data" value="<?= isset($editSpotkania) ? $editSpotkania['data'] : '' ?>" placeholder="data" />
	<input type="number" name="bramkiA" value="<?= isset($editSpotkania) ? $editSpotkania['bramkiA'] : '' ?>" placeholder="Liczba bramek drużyny A" <?= isset($editSpotkania) ? '' : 'disabled="disabled"' ?> />
	<input type="number" name="bramkiB" value="<?= isset($editSpotkania) ? $editSpotkania['bramkiB'] : '' ?>" placeholder="Liczba bramek drużyny " <?= isset($editSpotkania) ? '' : 'disabled="disabled"' ?> />
	<input type="hidden" name="action" value="<?= isset($editSpotkania) ? "edit" : 'create' ?>">

	<input type="submit" value="<?= isset($editSpotkania) ? 'Edytuj' : 'Utwórz' ?> spotkanie" />

</form>