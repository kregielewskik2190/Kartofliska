<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');
//Def user
if(isset($_POST['action']) && $_POST['action'] === 'create'){
    $app->createUser($_POST['name'], $_POST['password']);
}

if(isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $user['id']) {
    $app->removeUser($_GET['remove']);
    header('Location: index.php');
}
//Def Stadion
if(isset($_POST['action']) && $_POST['action'] === 'create'){
    $app->createStadion($_POST['miejscowosc'], $_POST['informacje'], $_POST['druzyna_id'], $_POST['szerokosc'], $_POST['wysokosc']);
}

if(isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $stadion['id']) {
    $app->removeStadion($_GET['remove']);
    header('Location: index.php');
}
//Def club
if(isset($_POST['action']) && $_POST['action'] === 'create'){
    $app->createDruzyna($_POST['Nazwa'], $_POST['Skrot'],$_FILES['Herb'],$_POST['Liga'],$_POST['Miejscowosc']);
}

if(isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $druzyna['ID']) {
    $app->removeDruzyna($_GET['remove']);
    header('Location: index.php');
}
//User 
$users = $app->getAllUsers();
$stadions = $app->getAllStadions();
$druzyna = $app->getAllDruzyna();
?>
<table>
    <tr>
        <td>Email</td>
        <td>Rola</td>
        <td>Akcje</td>
    </tr>
    <?php
    foreach ($users as $user_) {
        echo '<tr>
<td>'.$user_['email'].'</td>
<td>'.$user_['role'].'</td>
<td><a href="?remove='.$user_['id'].'">Usuń</a></td>
</tr>';
    }
    ?>
</table>
<hr>
<form action="" method="POST">
    <input type="text" name="name"/>
    <input type="password" name="password"/>
    <input type="hidden" name="action" value="create">
    <input type="submit" value="Utwórz użytkownika"/>
</form>

<table>
    <tr>
        <td>ID</td>
        <td>Miejscowość</td>
        <td>Informacje</td>
        <td>druzyna_id</td>
    </tr>
    <?php
    foreach ($stadions as $stadion_) {
        echo '<tr>
<td>'.$stadion_['id'].'</td>
<td>'.$stadion_['miejscowosc'].'</td>
<td>'.$stadion_['informacje'].'</td>
<td>'.$stadion_['druzyna_id'].'</td>
<td>'.$stadion_['szerokosc'].'</td>
<td>'.$stadion_['wysokosc'].'</td>
<td><a href="?remove='.$stadion_['id'].'">Usuń</a></td>
</tr>';
    }
    ?>
</table>
<hr>
<form action="" method="POST">
    <input type="text" name="miejscowosc"/>
    <input type="text" name="informacje"/>
    <input type="text" name="druzyna_id"/>
    <input type="text" name="szerokosc"/>
    <input type="text" name="wysokosc"/>
    <input type="hidden" name="action" value="create">
    <input type="submit" value="Utwórz stadion"/>
</form>

<table>
    <tr>
        <td>ID</td>
        <td>Nazwa</td>
        <td>Skrót</td>
        <td>Herb</td>
        <td>Liga</td>
        <td>Miejscowość</td>
    </tr>
    <?php
    foreach ($druzyna as $druzyna_) {
        echo '<tr>
<td>'.$druzyna_['ID'].'</td>
<td>'.$druzyna_['Nazwa'].'</td>
<td>'.$druzyna_['Skrot'].'</td>
<td>'.$druzyna_['Herb'].'</td>
<td>'.$druzyna_['Liga'].'</td>
<td>'.$druzyna_['Miejscowosc'].'</td>

<td><a href="?remove='.$druzyna_['ID'].'">Usuń</a></td>
</tr>';
    }
    ?>
</table>
<hr>
<form action="" method="POST">
    <input type="text" name="Nazwa"/>
    <input type="text" name="Skrot"/>
    <input type="file" name="Herb"/>
    <input type="text" name="Liga"/>
    <input type="text" name="Miejscowosc"/>
    <input type="hidden" name="action" value="create">
    <input type="submit" value="Utwórz Druzynę"/>
</form>
