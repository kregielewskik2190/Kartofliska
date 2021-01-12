<?php

$visibility = 1;
$minRole = 1;
require_once('../core/bootstrap.php');

if(isset($_GET['remove']) && $_GET['remove'] > 0 && $_GET['remove'] !== $user['id']) {
    $app->removeUser($_GET['remove']);
    header('Location: index.php');
}

$users = $app->getAllUsers();
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


