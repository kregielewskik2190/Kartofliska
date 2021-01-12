<?php

$visibility = -1;
require_once('core/bootstrap.php');

if ($_POST && isset($_POST['login'])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        $error = 'Uzupełnij dane!';
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($app->checkAuth($username, $password)) {
            $_SESSION['email'] = $username;
            $_SESSION['password'] = $password;
            header("location:index.php");
        } else {
            $error = 'Złe dane';
        }
    }
} elseif ($_POST && isset($_POST['register'])) {
    $verifyResponse = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptchaSecret . '&response=' . $_POST['g-recaptcha-response']
    );
    $responseData = json_decode($verifyResponse);

    if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["password2"])) {
        $error = 'Uzupełnij dane!';
    } else {
        if (!isset($responseData->success)) {
            $error = 'Zaznacz capcze';
        } elseif ($_POST["password"] != $_POST["password2"]) {
            $error = 'Hasła muszą być takie same';
        } elseif (strlen($_POST["password"]) < 8) {
            $error = 'Hasło zbyt krótkie';
        } elseif (!preg_match("#[0-9]+#", $_POST["password"])) {
            $error = 'Hasło musi zawierać conajmniej jedną cyfrę';
        } elseif (!preg_match("#[a-zA-Z]+#", $_POST["password"])) {
            $error = 'Hasło musi zawierać conajmniej jedną literę';
        } elseif ($app->isEmailTaken($_POST['username'])) {
            $error = 'Email istnieje';
        } else {
            $username = $_POST['username'];
            $password = $_POST["password"];

            if ($app->createUser($username, $password)) {
                $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
                $hash = $app->generateActivateUrl($username);
                $url = "{$root}/verifyMail.php?email={$username}&hash={$hash}";

                $headers = "From: noreply@kartofliska.x25.pl\r\n";
                $headers .= "Reply-To: noreply@kartofliska.x25.pl\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html;charset=utf8 \r\n";
                $headers .= "X-Priority: 3\r\n";
                $headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;

                $msg = 'Witaj w serwisie Kartofliska! <br/>';
                $msg .= '<a href="'.$url.'">Aby aktywować konto wejdź w link aktywacyjny.</a>';

                mail($username,'Potwierdź rejestrację', $msg ,$headers);
                $success = 'Rejestracja pomyślna! Potwierdź email';
            } else {
                $error = 'Nie udało się połączyć z bazą danych.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script
            src="https://kit.fontawesome.com/64d58efce2.js"
            crossorigin="anonymous"
    ></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="style.css"/>
    <title>Kartofliska</title>
</head>
<body>
<div class="container<?= isset($_REQUEST['register'])  ? ' sign-up-mode' : '' ?>">
    <div class="forms-container">
        <div class="signin-signup">
            <form action="" class="sign-in-form" method="post">
                <h2 class="title">Zaloguj</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="email" placeholder="E-mail" name="username"/>
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Hasło" name="password"/>
                </div>
                <input type="hidden" name="login" value="1"/>
                <input type="submit" value="Login" class="btn solid"/>
                <?php
                if (isset($error) && isset($_POST['login'])) {
                    echo "<div class='error'>{$error}</div>";
                } elseif (isset($message)) {
                    echo "<div class='success'>{$message}</div>";
                }
                ?>
            </form>
            <form action="" class="sign-up-form" method="post">
                <h2 class="title">Załóż konto</h2>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="E-mail" name="username"/>
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Hasło" name="password"/>
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Powtórz hasło" name="password2"/>
                </div>
                <div class="g-recaptcha" data-sitekey="<?=$recaptchaSite?>"></div>
                <input type="hidden" name="register" value="1"/>
                <input type="submit" class="btn" value="Załóż konto"/>
                <?php
                if (isset($error) && isset($_POST['register'])) {
                    echo "<div class='error'>{$error}</div>";
                } elseif (isset($success)) {
                    echo "<div class='success'>{$success}</div>";
                }
                ?>
            </form>
        </div>
    </div>
    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>Nie masz konta?</h3>
                <p>
                    Zarejestruj się i odkrywaj futbol w twojej okolicy dzięki aplikacji Kartofliska!
                </p>
                <button class="btn transparent" id="sign-up-btn">
                    ZAREJESTRUJ
                </button>
            </div>
            <img src="img/log.svg" class="image" alt=""/>
        </div>
        <div class="panel right-panel">
            <div class="content">
                <h3>Masz już konto?</h3>
                <p>
                    Zaloguj się i pobierz aplikację Kartofliska już dzisiaj!
                </p>
                <button class="btn transparent" id="sign-in-btn">
                    ZALOGUJ
                </button>
            </div>
            <img src="img/register.svg" class="image" alt=""/>
        </div>
    </div>
</div>

<script src="app.js"></script>
</body>
</html>
