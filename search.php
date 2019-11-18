<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">

        <!-- De beschrijving en eigenschappen van deze pagina -->
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

        <!-- Alle JavaScript dependencies-->
        <script src="js/vendor/modernizr-3.7.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.4.1.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Bootstrap -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
            <div class="content-container">
                <form name="filter" method="get">
                    <fieldset>
                        <p>
                            <label>Aantal producten: </label>
                            <select name = "Hoeveelheid">
                                <option value = "5">5</option>
                                <option value = "10">10</option>
                                <option value = "20">20</option>
                                <option value = "50">50</option>
                            </select>
                            <label>Sorteren: </label>
                            <select name = "Sort">
                                <option value = "NaamASC">A-Z</option>
                                <option value = "NaamDESC">Z-A</option>
                                <option value = "PrijsASC">Prijs oplopend</option>
                                <option value = "PrijsDESC">Prijs aflopend</option>
                            </select>
                            <input type="hidden" name="search" value="<?php if (isset($_GET["search"])) {echo $_GET["search"]; }?>" />
                        </p>
                    </fieldset>
                    <input type="submit" name="submit" value="ok">
                </form>

                <?php

                //check of het filter is aangepast
                //pas filter toe
                //Hier moet ik nog naar kijken, zal ik volgende week doen
                /*
                    if($_GET['SortName'] == "NaamASC"){
                        $OrderBy = $StockItemName;
                        $AscDesc = "ASC";
                    } elseif ($_GET['SortName'] == "NaamDESC"){
                        $OrderBy = $StockItemName;
                        $AscDesc = "DESC";
                    } elseif ($_GET['Sort'] == "PrijsASC"){
                        $OrderBy = $UnitPrice;
                        $AscDesc = "ASC";
                    } elseif($_GET['Sort'] == "PrijsDESC"){
                        $OrderBy = $StockItemName;
                        $AscDesc = "DESC";
                    }
                }*/

                // Check of er een zoekterm is opgegeven in de URL
                if (isset($_GET['search'])) {
                    $zoekterm = $_GET['search'];

                    //Kijkt hoeveel de opgegeven hoeveelheid zichtbare producten is en maakt er een variabele van.
                    //Het variabele $aantal wordt meegenomen waar de zoek() functie wordt toegepast
                    // Als Hoeveelheid niet geset is of niet een nummer is wordt DEFAULT_PRODUCT_RETURN_AMOUNT gebruikt.
                    $aantal = DEFAULT_PRODUCT_RETURN_AMOUNT;
                    if (isset($_GET['Hoeveelheid']) && filter_var($_GET["Hoeveelheid"], FILTER_VALIDATE_INT) == true) {
                        $aantal = $_GET['Hoeveelheid'];
                    }

                    // Alle SQL magie en PDO connectie shit gebeurt in `Product::zoek()` dus in deze file hebben we geen queries meer nodig. We kunnen direct lezen van de statement zoals hieronder.
                    $stmt = (Product::zoek(Database::getConnection(), $zoekterm, $aantal));

                    // Per rij die we uit de database halen voeren we een stukje code uit
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // Dit zorgt er voor dat we alle database attributen kunnen gebruiken als variabelen
                        // (bijv. kolom "StockItemName" kunnen we gebruiken in PHP als "$StockItemName") (PHPStorm geeft rood streepje aan maar het werkt wel)
                        extract($row);


                        //Laat alle zoekresultaten zien
                        print($StockItemName . "<br>");
                        print('<img src="data:image/png;base64,' . $Photo . '"><br>');
                        print("Prijs: " . $UnitPrice . "<br><br><br>");

                    }

                } else {
                    print("Geen zoekterm opgegeven!!!!");
                }

                ?>

            </div>

        </div>
        <div class="footer-container">
            <?php
               // include("tpl/footer_template.php");
            ?>

        </div>
    </body>
</html>
