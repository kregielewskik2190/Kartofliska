<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

if(isset($_POST['action']) && $_POST['action'] === 'create'){
    $app->createClub($_POST['Nazwa'], $_POST['Skrót'],$_POST['Herb'],$_POST['Liga'],$_POST['Miejscowość']);
}

if(isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $club['ID']) {
    $app->removeClub($_GET['remove']);
    header('Location: clubs.php');
}

$clubs = $app->getAllClubs();
?>
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
    foreach ($clubs as $club_) {
        echo '<tr>
<td>'.$club_['ID'].'</td>
<td>'.$club_['Nazwa'].'</td>
<td>'.$club_['Skrót'].'</td>
<td>'.$club_['Herb'].'</td>
<td>'.$club_['Liga'].'</td>
<td>'.$club_['Miejscowość'].'</td>

<td><a href="?remove='.$club_['ID'].'">Usuń</a> </td>
</tr>';
    }
    ?>
</table>
<br /><a href="menu.php">Powrót</a>
<hr>
<form action="" method="POST">
    <input type="text" name="Nazwa" placeholder="Nazwa"/>
    <input type="text" name="Skrót" placeholder="Skrót"/>
    <input type="text" name="Herb" placeholder="Herb"/>
    <input type="text" name="Liga" placeholder="Liga"/>
    <input type="text" name="Miejscowość" placeholder="Miejscowość"/>
    <input type="hidden" name="action" value="create">
    <input type="hidden" name="edit" value=""/>
    <input type="submit" value="Utwórz Klub"/>
</form>
