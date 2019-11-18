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
                                <!-- Het stukje php tussen de <select> zorgt er voor dat de gekozen hoeveelheid in het vakje blijft staan nadat je op OK hebt gedrukt-->
                                <option value = "5" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '5') ? 'selected="selected"' : ''; ?>>5</option>
                                <option value = "10" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '10') ? 'selected="selected"' : ''; ?>>10</option>
                                <option value = "20" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '20') ? 'selected="selected"' : ''; ?>>20</option>
                                <option value = "50" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '50') ? 'selected="selected"' : ''; ?>>50</option>
                            </select>
                            <label>Sorteren: </label>
                            <select name = "Sort">
                                <option value = "NaamASC"  <?php echo (isset($_GET['Sort']) && $_GET['Sort'] == 'NaamASC') ? 'selected="selected"' : ''; ?>>A-Z</option>
                                <option value = "NaamDESC" <?php echo (isset($_GET['Sort']) && $_GET['Sort'] == 'NaamDESC') ? 'selected="selected"' : ''; ?>>Z-A</option>
                                <option value = "PrijsASC" <?php echo (isset($_GET['Sort']) && $_GET['Sort'] == 'PrijsASC') ? 'selected="selected"' : ''; ?>>Prijs oplopend</option>
                                <option value = "PrijsDESC"<?php echo (isset($_GET['Sort']) && $_GET['Sort'] == 'PrijsDESC') ? 'selected="selected"' : ''; ?>>Prijs aflopend</option>
                            </select>
                            <label>Categorie: </label>
                            <select name = "Categorie">
                                <option value = "0" selected="selected">Geen filter</option>
                                <option value = "1">Novelty Items</option>
                                <option value = "2">Clothing</option>
                                <option value = "3">Mugs</option>
                                <option value = "4">T-Shirts</option>
                                <option value = "5">Airline Novelties</option>
                                <option value = "6">Computing Novelties</option>
                                <option value = "7">USB Novelties</option>
                                <option value = "8">Furry Footwear</option>
                                <option value = "9">Toys</option>
                                <option value = "10">Packaging Materials</option>
                            </select>
                            <input type="hidden" name="search" value="<?php if (isset($_GET["search"])) {echo $_GET["search"]; }?>" />
                            <input type="submit" name="submit" value="ok">
                    </fieldset>
                        </p>
                </form>

                <?php
                // Check of er een zoekterm is opgegeven in de URL
                if ($_GET['search']!=NULL) {
                    $zoekterm = $_GET['search'];

                    //Kijkt hoeveel de opgegeven hoeveelheid zichtbare producten is en maakt er een variabele van.
                    //Het variabele $aantal wordt meegenomen waar de zoek() functie wordt toegepast
                    // Als Hoeveelheid niet geset is of niet een nummer is wordt DEFAULT_PRODUCT_RETURN_AMOUNT gebruikt.
                    $aantal = DEFAULT_PRODUCT_RETURN_AMOUNT;
                    if (isset($_GET['Hoeveelheid']) && filter_var($_GET["Hoeveelheid"], FILTER_VALIDATE_INT) == true) {
                        $aantal = $_GET['Hoeveelheid'];
                    }
                    // defaults voor wanneer het filter niet is ingevuld
                    $OrderBy = "p.RecommendedRetailPrice " . DEFAULT_PRODUCT_SORT_ORDER;

                    //if-statement om te kijken op welke manier de resultaten gesorteerd moeten worden
                    /**
                     * Hij geeft wel aan dat hij de juiste parameters krijgt, maar het werkt nog niet, kan iemand hier naar kijken
                     */
                    if(isset($_GET['Sort'])){
                        if($_GET['Sort'] === "NaamASC"){
                            $OrderBy = "p.StockItemName ASC";
                        } elseif ($_GET['Sort'] === "NaamDESC"){
                            $OrderBy = "p.StockItemName DESC";
                        } elseif ($_GET['Sort'] === "PrijsASC"){
                            $OrderBy = "p.RecommendedRetailPrice ASC";
                        } elseif($_GET['Sort'] === "PrijsDESC"){
                            $OrderBy = "p.RecommendedRetailPrice DESC";
                        }
                    }

                    // Alle SQL magie en PDO connectie shit gebeurt in `Product::zoek()` dus in deze file hebben we geen queries meer nodig. We kunnen direct lezen van de statement zoals hieronder.

                    $stmt = (Product::zoek(Database::getConnection(), $zoekterm, $OrderBy, $aantal));

                    // Per rij die we uit de database halen voeren we een stukje code uit
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // Dit zorgt er voor dat we alle database attributen kunnen gebruiken als variabelen
                        // (bijv. kolom "StockItemName" kunnen we gebruiken in PHP als "$StockItemName") (PHPStorm geeft rood streepje aan maar het werkt wel)
                        extract($row);




                        //Laat alle zoekresultaten zien
                        print("<a href='product.php?id=" . $StockItemID . "'>");
                        print($StockItemName . "<br>");
                        print('<img src="data:image/png;base64,' . $Photo . '"><br>');
                        print("</a>");
                        print("Prijs: " . $RecommendedRetailPrice . "<br><br><br>");

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
