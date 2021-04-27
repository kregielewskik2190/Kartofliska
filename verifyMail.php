<?php
$visibility = -1;
require_once('core/bootstrap.php');

if(!isset($_GET['email'], $_GET['hash']) || !$app->isEmailTaken($_GET['email']) || !$app->isValidEmailHash($_GET['email'], $_GET['hash'])){
    header('Location: /auth.php');
    exit();
}

$app->invalidateHash($_GET['hash']);
$app->activateUser($_GET['email']);

$_SESSION['message'] = 'Możesz się teraz zalogować!';

header('Location: /auth.php');
?>