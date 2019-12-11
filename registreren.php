<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie

?>

<!doctype html>
<html class="no-js" lang="">
<head>
    <?php
    //Hier include je de head-tag-template, alles wat in de header komt pas je aan in "tpl/head-tag-template.php"
    include("tpl/head-tag-template.php");

    ?>
</head>
<body>
<!-- Onze website werkt niet met Internet Explorer 9 en lager-->
<!--[if IE]>
<div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
<![endif]-->

<!-- Hierin  -->
<div id="pagina-container">

    <!-- Print de header (logo, navigatiebalken, etc.)-->
    <?php
    include("tpl/header_template.php");
    ?>


    <!-- Inhoud pagina -->
    <div class="content-container-home">
        <form method="post">

            <p>Email*</p>
            <input type="email">

            <p>Wachtwoord*</p>
            <input type="password">

            <p>Voornaam*</p>
            <input type="text">

            <p>Tussenvoegsel</p>
            <input type="text">

            <p>Achternaam*</p>
            <input type="text">

            <p>Straatnaam*</p>
            <input type="text">

            <p>Huisnummer*</p>
            <input type="number">

            <p>Toevoeging</p>
            <input type="text">

            <p>Postcode*</p>
            <input type="text">

            <p>Woonplaats*</p>
            <input type="text">


        </form>
    </div>
</div>

    <div class="footer-container">
        <?php
        include("tpl/footer_template.php");
        ?>

    </div>

</body>
</html>
