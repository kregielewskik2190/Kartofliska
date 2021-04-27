<?php
require_once('core/bootstrap.php');
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/style.css">

    <!--//odpowiada za sryft-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script
&family=Montserrat:wght@400;700
&family=Open+Sans&display=swap" rel="stylesheet">

    <title>Kartofliska</title>
</head>

<body>
<div class="intro">
    <header class-
    "header">
    <div class="container">
        <div class="header__inner">
            <div class="header__logo">
                Kartofliska
            </div>
            <nav class="nav">
                <a class="nav__link" href="guest.php">Przejdź do portalu</a>
                <?php if(!$loggedIn) { ?>
                <a class="nav__link" href="auth.php">Zaloguj się</a>
                <a class="nav__link" href="auth.php?register">Załóż konto użytkownika</a>
                <?php } else { ?>
                    <a class="nav__link" href="#"><?=$user['email'];?></a>
                    <a class="nav__link" href="entry.php">Pobierz aplikację</a>
                    <?php
                    if($user['role'] > 0) {
                        echo '<a class="nav__link" href="/admin/menu.php">Panel administratora</a>';
                    }
                    ?>
                    <a class="nav__link" href="logout.php">Wyloguj się</a>
                <?php } ?>
            </nav>
        </div>
    </div>
    </header>
</div>
</body>
</html>
<!--

 <div class="intro">
	<div class="container">
		<h1>Kartofliska</h1>
	</div>
 </div>

<nav>

	<a href="#">About</a>
	<a href="#">Service</a>
	<a href="#">About</a>
	<a href="#">About</a>
</nav>

<h1>Kartofliska</h1>
-->