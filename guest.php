<?php

$visibility = 1;
$minRole = 0;

require_once('core/bootstrap.php');

$spotkania = $app->getAllSpotkania();
$druzyny = $app->getAllDruzyna();
$stadiony = $app->getAllStadions();
function wrap($image) {
	if (strlen($image) > 0)
		return "<img src=\"admin/img/$image\" alt=\"\" width=\"64\">";
	else
		return "";
}

function wrapLocation($sz, $dl) {
	return '<a href="http://www.google.com/maps/place/'. $sz. ','.$dl. '">Google Maps</a>';
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" dir="ltr" lang="pl">
<!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Kartofliska</title>
	<meta name="description" content="lorem ipsum" />
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css">
	<link rel="icon" type="image/png" href="favicon.png">
	<link rel="stylesheet" href="assets/css/guest.css">
	<base href="/">
</head>

<body>
	<div class="container">
			<h2>Poniższe filtry działają niezależnie, tj. zmiana filtru ignoruje inne</h2>
		<div class="container__panel">
			<button id="toggleMeetings" class="container__nav-buttons">Najbliższe spotkania</button>
			<button id="toggleResults" class="container__nav-buttons">Wyniki</button>
			<button id="toggleTeams" class="container__nav-buttons">Drużyny</button>
			<button id="toggleStadiums" class="container__nav-buttons">Stadiony</button>
		</div>

		<div class="container__box container__box--hidden container__box--meetings" id="meetings">
			<div class="container__controls">
				<div class="container__control">
					<label for="spotkania_druzyna">Drużyna</label>
					<select name="spotkania_druzyna" id="spotkania_druzyna">
						<option value="Brak" selected>Brak</option>
					<?php
					foreach ($druzyny as $key => $value) {
							echo "<option value=\"".$value['Nazwa']."\">".$value['Nazwa']."</option>";
					}
					?></select>
				</div>

				<div class="container__control">
					<label for="Liga">Liga</label>
					<select name="liga" id="liga">
						<option value="Brak" selected>Brak</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>

				<div class="container__control">
					<label for="date-from">Data od:</label>
					<input type="date" name="data" id="date-from">
					<label for="date-to">Data do:</label>
					<input type="date" name="data" id="date-to">
					<button id="date">Filtruj po dacie</button>
				</div>


			</div>
			<div class="container__table" id="spotkania_table">
				<div class="container__row container__row--header">
					<div class="container__cell">Liga</div>
					<div class="container__cell">Gospodarz</div>
					<div class="container__cell">Goście</div>
					<div class="container__cell">Wynik gospodarza</div>
					<div class="container__cell">Wynik goście</div>
					<div class="container__cell">Kolejka</div>
					<div class="container__cell">Data</div>
				</div>
				<?php
					echo join("\n",array_map(function($spotkanie) {
						if (strtotime(date('Y-m-d')) > strtotime($spotkanie["data"])) return '';
						return "<div class=\"container__row\">
							<div class=\"container__cell\" data-team=\"liga\">" . $spotkanie['liga'] . "</div>
							<div class=\"container__cell\" data-team=\"a\">" . $spotkanie['druzyna_a'] . "</div>
							<div class=\"container__cell\" data-team=\"b\">" . $spotkanie['druzyna_b'] . "</div>
							<div class=\"container__cell\">" . $spotkanie['bramkiA'] . "</div>
							<div class=\"container__cell\">" . $spotkanie['bramkiB'] . "</div>
							<div class=\"container__cell\" data-team=\"time1\">" . $spotkanie['kolejka'] . "</div>
							<div class=\"container__cell\" data-team=\"time\">" . $spotkanie['data'] . "</div>
						</div>";
					}, $spotkania));
				?>
			</div>
		</div>
		<div class="container__box container__box--hidden container__box--results" id="results">
			<div class="container__controls">
				<div class="container__control">
					<label for="Liga">Liga</label>
					<select name="liga" id="liga">
						<option value="Brak" selected>Brak</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>

				<div class="container__control">
					<label for="wyniki_sezon">Sezon</label>
					<input type="number" name="wyniki_sezon" id="wyniki_sezon" min="2015" max="2021" step="1" value="2021" />
				</div>
				<div class="container__control">
					<label for="spotkania_druzyna">Drużyna</label>
					<select name="wyniki_druzyna" id="wyniki_druzyna">
						<option value="Brak" selected>Brak</option>
					<?php
					foreach ($druzyny as $key => $value) {
							echo "<option value=\"".$value['Nazwa']."\" data-liga=\"".$value["Liga"]."\">".$value['Nazwa']."</option>";
					}
					?></select>
				</div>
				<div class="container__control">
					<label for="wyniki_kolejka" >Kolejka</label>
					<input type="number" name="wyniki_kolejka" id="wyniki_kolejka" min="1" max="30" step="1" />
				</div>
				<div class="container__control">
					<button id="filterResults">Filtruj</button>
				</div>
			</div>
			<div class="container__table" id="wyniki_table">
				<div class="container__row container__row--header">
					<div class="container__cell">Liga</div>
					<div class="container__cell">Gospodarz</div>
					<div class="container__cell">Goście</div>
					<div class="container__cell">Wynik gospodarza</div>
					<div class="container__cell">Wynik goście</div>
					<div class="container__cell">Kolejka</div>
					<div class="container__cell">Sezon</div>
					<div class="container__cell">Data</div>
				</div>
				<?php
					echo join("\n",array_map(function($spotkanie) {
						if (strtotime(date('Y-m-d')) < strtotime($spotkanie["data"])) return '';
						return "<div class=\"container__row\">
							<div class=\"container__cell\" data-team=\"liga\">" . $spotkanie['liga'] . "</div>
							<div class=\"container__cell\" data-team=\"a\">" . $spotkanie['druzyna_a'] . "</div>
							<div class=\"container__cell\" data-team=\"b\">" . $spotkanie['druzyna_b'] . "</div>
							<div class=\"container__cell\" data-team=\"wynik-a\">" . $spotkanie['bramkiA'] . "</div>
							<div class=\"container__cell\" data-team=\"wynik-b\">" . $spotkanie['bramkiB'] . "</div>
							<div class=\"container__cell\" data-team=\"kolejka\">" . $spotkanie['kolejka'] . "</div>
							<div class=\"container__cell\" data-team=\"null\"></div>
							<div class=\"container__cell\" data-team=\"time\">" . $spotkanie['data'] . "</div>
						</div>";
					}, $spotkania));
				?>
			</div>
		</div>
		<div class="container__box container__box--hidden container__box--teams" id="teams">
			<div class="container__table">
				<div class="container__row container__row--header">
					<div class="container__cell">Nazwa</div>
					<div class="container__cell">Skrót</div>
					<div class="container__cell">Herb</div>
					<div class="container__cell">Liga</div>
					<div class="container__cell">Miejscowość</div>
				</div>
				<?php
					echo join("\n",array_map(function($druzyna) {
						return "<div class=\"container__row\">
							<div class=\"container__cell\">" . $druzyna['Nazwa'] . "</div>
							<div class=\"container__cell\">" . $druzyna['Skrot'] . "</div>
							<div class=\"container__cell\">" . wrap($druzyna['Herb']). "</div>
							<div class=\"container__cell\">" . $druzyna['Liga'] . "</div>
							<div class=\"container__cell\">" . $druzyna['Miejscowosc'] . "</div>
						</div>";
					}, $druzyny));
				?>
			</div>
		</div>
		<div class="container__box container__box--hidden container__box--stadiums" id="stadiums">
			<div class="container__controls">
				<div class="container__control">
					<label for="stadiony_miasto">Miasto</label>
					<select name="stadiony_miasto" id="stadiony_miasto">
						<option value="0,0" selected>Brak</option>
					<?php
					foreach ($stadiony as $key => $value) {
							echo "<option value=\"".$value['szerokosc'].','. $value['wysokosc'] ."\">".$value['miejscowosc']."</option>";
					}
					?></select>
				</div>
				<div class="container__control">
					<label for="stadiony_miasto">Odległość od stadionu</label>
					<input type="number" name="stadion_odleglosc" id="stadion_odleglosc" min="10" max="50" step="10" value="20">
				</div>
			</div>

			<div class="container__table" id="stadiony_table">
				<div class="container__row container__row--header">
					<div class="container__cell">Miejscowość</div>
					<div class="container__cell">Informacje</div>
					<div class="container__cell">Drużyna</div>
					<div class="container__cell">Pozycja</div>
				</div>
				<?php
					echo join("\n",array_map(function($stadion) use ($druzyny) {
						$druzynaID = array_search($stadion['druzyna_id'], array_column($druzyny, 'ID'));
						$druzyna = $druzyny[$druzynaID];
						return "<div class=\"container__row\">
							<div class=\"container__cell\" data-val=\"town\">" . $stadion['miejscowosc'] . "</div>
							<div class=\"container__cell\">" . $stadion['informacje'] . "</div>
							<div class=\"container__cell\">" . $druzyna["Nazwa"] . "</div>
							<div class=\"container__cell\" data-val=\"pos\" data-lat=\"".$stadion['szerokosc']."\" data-lon=\"". $stadion['wysokosc']. "\">" . wrapLocation($stadion['szerokosc'], $stadion['wysokosc']) . "</div>
						</div>";
					}, $stadiony));
				?>
			</div>
		</div>

	</div>
</body>


<script defer src="app_guest.js"></script>

</html>
