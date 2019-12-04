<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/cart.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB;


?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <?php
            include("tpl/head-tag-template.php");

        ?>
    </head>
    <body>

    <?php
        include("tpl/header_template.php");
    ?>

    <!--Simpele tekst voor de 404 page not found met een knop om terug te gaan naar de homepage-->
    <div id="pagina-container" style="text-align: center">

        <h1>Pagina niet gevonden</h1>
        <p>Sorry er wordt hieraan gewerkt. Klik op een categorie of ga terug naar de homepagina.</p><br><br>

        <!-- Deze knop stuurt de gebruiker naar de homepagina -->
        <button class="Big-button" onclick="location.href='index.php'">Verder met shoppen</button>

    </div>

    </body>
</html>
<!-- IE needs 512+ bytes: https://blogs.msdn.microsoft.com/ieinternals/2010/08/18/friendly-http-error-pages/ -->
