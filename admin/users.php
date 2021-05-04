<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');
//Def user
if (isset($_POST['action']) && $_POST['action'] === 'create') {
	$app->createUser($_POST['name'], $_POST['password']);
}

if (isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $user['id']) {
	$app->removeUser($_GET['remove']);
	header('Location: users.php');
}
$users = $app->getAllUsers();
?>

<form action="" method="POST">
	<input type="text" name="name" />
	<input type="password" name="password" />
	<input type="hidden" name="action" value="create">
	<input type="submit" value="Utwórz użytkownika" />
</form>
<br /><a href="menu.php">Powrót</a>
<hr>
<table>
	<tr>
		<td>Email</td>
		<td>Rola</td>
		<td>Akcje</td>
	</tr>
	<?php
	foreach ($users as $user_) {
		echo '<tr>
<td>' . $user_['email'] . '</td>
<td>' . $user_['role'] . '</td>
<td><a href="?remove=' . $user_['id'] . '">Usuń</a></td>
</tr>';
	}
	?>
</table>
