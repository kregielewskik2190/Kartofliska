<?php

$visibility = 1;
$minRole = 1;
// session_start();

$dir = __DIR__ . '/img/';


require_once('../core/bootstrap.php');


if (isset($_POST['action']) && $_POST['action'] === 'create') {
		$Nazwa = $_POST['Nazwa'];
		$Skrot =  $_POST['Skrot'];
		$Liga =  (int) $_POST['Liga'];
		$Miejscowosc = $_POST['Miejscowosc'];

		$Herb = $_FILES["Herb"]["name"];

		if (!empty($Herb)) {
			$file = $dir . basename($_FILES['Herb']['name']);
			move_uploaded_file($_FILES['Herb']['tmp_name'], $file);
		}

		$err = false;

		if (!isset($Nazwa) || empty($Nazwa)) {
			$err = true;
			echo "Podaj nazwę klubu ";
			echo "<br>";
		}

		if ((!isset($Skrot) || empty($Skrot))) {
			$err = true;
			echo "Podaj skrót nazwy klubu\r\n";
			echo "<br>";
		}

		if (isset($Skrot) && strlen($Skrot) > 3) {
			$err = true;
			echo "Skrót nazwy musi mieć max. 3 znak\r\n";
			echo "<br>";
		}

		if (!isset($Liga) || empty($Liga)) {
			$err = true;
			echo "Pole Liga jest puste \r\n ";
			echo "<br>";
		}

		if (isset($Liga) && !in_array($Liga, range(1,5)) ) {
			$err = true;
			echo "Wartość pola Liga jest podana nieprawidłowo\r\n";
			echo "<br>";
		}

				if (!isset($Miejscowosc) || empty($Miejscowosc)) {
			$err = true;
				echo "Podaj nazwę miejscowości \r\n ";
			echo "<br>";
		}
		if (!$err)
			$app->createDruzyna(
				$Nazwa,
				$Skrot,
				$Herb,
				$Liga,
				$Miejscowosc
			);
}
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
	$editDruzyna = $app->getDruzyna($_POST['ID']);
	if (!$editDruzyna) return;
		$ID = $_POST['ID'];
		$Nazwa = $_POST['Nazwa'];
		$Skrot =  $_POST['Skrot'];
		$Liga =  (int) $_POST['Liga'];
		$Miejscowosc = $_POST['Miejscowosc'];

		$Herb = $_FILES["Herb"]["name"];

		if (!empty($Herb)) {
			$file = $dir . basename($_FILES['Herb']['name']);
			move_uploaded_file($_FILES['Herb']['tmp_name'], $file);
		}

		$err = false;

		if (!isset($Nazwa) || empty($Nazwa)) {
			$err = true;
			echo "Podaj nazwę klubu ";
			echo "<br>";
		}

		if ((!isset($Skrot) || empty($Skrot))) {
			$err = true;
			echo "Podaj skrót nazwy klubu\r\n";
			echo "<br>";
		}

		if (isset($Skrot) && strlen($Skrot) > 3) {
			$err = true;
			echo "Skrót nazwy musi mieć max. 3 znak\r\n";
			echo "<br>";
		}

		if (!isset($Liga) || empty($Liga)) {
			$err = true;
			echo "Pole Liga jest puste \r\n ";
			echo "<br>";
		}

		if (isset($Liga) && !in_array($Liga, range(1,5)) ) {
			$err = true;
			echo "Wartość pola Liga jest podana nieprawidłowo\r\n";
			echo "<br>";
		}

		if (!isset($Miejscowosc) || empty($Miejscowosc)) {
			$err = true;
				echo "Podaj nazwę miejscowości \r\n ";
			echo "<br>";
		}

		if (!$err)
			$app->updateDruzyna($ID, $Nazwa, $Skrot, $Herb, $Liga, $Miejscowosc);
}

if (isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $druzyna['ID']) {
	$app->removeDruzyna($_GET['remove']);
	header('Location: druzyna.php');
}
if (isset($_GET['edit'])) {
	$editDruzyna = $app->getDruzyna($_GET['edit']);
	if (!$editDruzyna) {
		header('Location: druzyna.php');
		exit();
	}
}
$druzyna = $app->getAllDruzyna();
?>
<table>
	<tr>
		<td>ID</td>
		<td>Nazwa</td>
		<td>Skrot</td>
		<td>Herb</td>
		<td>Liga</td>
		<td>Miejscowosc</td>
	</tr>
	<?php
	foreach ($druzyna as $druzyna_) {
		echo '<tr>
<td>' . $druzyna_['ID'] . '</td>
<td>' . $druzyna_['Nazwa'] . '</td>
<td>' . $druzyna_['Skrot'] . '</td>
<td>' . ' <img width=64 src="/admin/img/'. $druzyna_['Herb'].'" '. '></td>
<td>' . $druzyna_['Liga'] . '</td>
<td>' . $druzyna_['Miejscowosc'] . '</td>

<td><a href="?remove=' . $druzyna_['ID'] . '">Usuń</a> <a href="?edit=' . $druzyna_['ID'] . '">Edytuj</a></td>
</tr>';
	}
	?>
</table>
<br /><a href="menu.php">Powrót</a>
<hr>
<form action="druzyna.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="ID" value="<?= isset($editDruzyna) ? $editDruzyna['ID'] : '' ?>" />
	<input type="text" name="Nazwa" value="<?= isset($editDruzyna) ? $editDruzyna['Nazwa'] : '' ?>" placeholder="Nazwa" />
	<input type="text" name="Skrot" value="<?= isset($editDruzyna) ? $editDruzyna['Skrot'] : '' ?>" placeholder="Skrot" />
	<input type="file" name="Herb" accept=".png, .jpeg, .jpg"/>
	<input type="text" name="Liga" value="<?= isset($editDruzyna) ? $editDruzyna['Liga'] : '' ?>" placeholder="Liga" />
	<input type="text" name="Miejscowosc" value="<?= isset($editDruzyna) ? $editDruzyna['Miejscowosc'] : '' ?>" placeholder="Miejscowosc" />
	<input type="hidden" name="action" value="<?= isset($editDruzyna) ? "edit" : 'create' ?>">

	<input type="submit" value="<?= isset($editDruzyna) ? 'Edytuj' : 'Utwórz' ?> druzynę" />

</form>