<?php

include_once("app/cart.php");
?>

<!-- Dit is de template voor de header, als je een nieuwe <head> tag maakt in een php file, moet je dit includen -->
<meta charset="utf-8">

<!-- De beschrijving en eigenschappen van deze pagina -->
<link rel="icon" href="img/logo/icon.png" type="image/x-icon">
<title><?php echo VENDOR_NAME ?></title>
<meta name="description" content="<?php echo VENDOR_DESCRIPTION ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="<?php echo VENDOR_THEME_COLOR_PRIMARY ?>">
<link rel="manifest" href="site.webmanifest">
<link rel="apple-touch-icon" href="icon.png">

<!-- Normalize hebben we nodig voor het schoonmaken van pagina zodat het er in alle browsers hetzelfde uit ziet -->
<link rel="stylesheet" type="text/css" href="css/normalize.css">
<link rel="stylesheet" type="text/css" href="css/main.css.php">
<link rel="stylesheet" type="text/css" href="css/header_footer.css.php">
<link rel="stylesheet" type="text/css" href="css/forms.css.php">

<!-- Alle JavaScript dependencies-->
<script src="js/vendor/modernizr-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-3.4.1.min.js"><\/script>')</script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>

<!-- Bootstrap -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<?php
    // Start de sessie als hij nog niet gestart is
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];

    Cart::update();
?>
