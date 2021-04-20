<?php

session_start();
require_once('db.php');
require_once('app.php');

$db = Database::getDB();
$app = new App($db);

$loggedIn = false;
$user = null;

if (isset($_SESSION['email'], $_SESSION['password'])) {
    if (!$app->checkAuth($_SESSION['email'], $_SESSION['password'])) {
        $app->destroySession();
    } else {
        $loggedIn = true;
        $user = $app->getUser($_SESSION['email']);
    }
}

if (!isset($visibility)) {
    $visibility = 0;
}

if (!isset($minRole)) {
    $minRole = 0;
}

if ($loggedIn && $visibility === -1) {
    header('Location: /index.php');
    exit();
}

if (!$loggedIn && $visibility === 1) {
    header('Location: /auth.php');
    exit();
}

if($minRole > 0 && ($user === null || $user['role'] < $minRole)){
    header('Location: /auth.php');
    exit();
}


if(isset($_SESSION['message'])){
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$recaptchaSecret = '6LeIxAcTAAAAAGG-6Lcx6PwZAAAAAG7wHsWfsQg1qlhmiC1o2j9d1DiM';
$recaptchaSite = '6Lcx6PwZAAAAAJda06rA4lPECWu-JytAbkS1ilKQ';