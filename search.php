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
                            <label>Producten per pagina: </label>
                            <select name = "Hoeveelheid">
                                <!-- Het stukje php tussen de <select> zorgt er voor dat de gekozen hoeveelheid in het vakje blijft staan nadat je op OK hebt gedrukt-->
                                <option value = "10" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '10') ? 'selected="selected"' : ''; ?>>10</option>
                                <option value = "25" <?php echo (isset($_GET['Hoeveelheid']) && $_GET['Hoeveelheid'] == '25') ? 'selected="selected"' : ''; ?>>25</option>
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
                                <option value = "0" <?php echo (isset($_GET['Categorie']) && $_GET['Categorie'] == '0') ? 'selected="selected"' : ''; ?>>Geen filter</option>
                                <?php
                                    //Zoals in header_template.php ook gedaan wordt, worden hier de categorieen opgehaald.
                                    $stmt = (Categorie::read(Database::getConnection()));
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        extract($row);

                                        ?>

                                        <option value='<?php print($StockGroupID) ?>'
                                            <?php if (isset($_GET["Categorie"]) && $_GET["Categorie"] === $StockGroupID) {
                                                        print("selected=\"selected\"");
                                                    } ?>><?php print($StockGroupName) ?>
                                        </option>

                                        <?php
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="search" value="<?php if (isset($_GET["search"])) { echo $_GET["search"]; }?>" />
                            <input type="submit" name="submit" value="ok">
                    </fieldset>
                        </p>
                </form>

                <?php
                // Check of er een zoekterm is opgegeven in de URL
                if (isset($_GET["search"]) && $_GET["search"] != NULL) {
                    $zoekterm = trim($_GET['search']);

                    //Kijkt hoeveel de opgegeven hoeveelheid zichtbare producten is en maakt er een variabele van.
                    //Het variabele $aantal wordt meegenomen waar de zoek() functie wordt toegepast
                    // Als Hoeveelheid niet geset is of niet een nummer is wordt DEFAULT_PRODUCT_RETURN_AMOUNT gebruikt (zie constants.php)
                    $aantalPerPaginaFilter = DEFAULT_PRODUCT_RETURN_AMOUNT;
                    if (isset($_GET['Hoeveelheid']) && filter_var($_GET["Hoeveelheid"], FILTER_VALIDATE_INT) == true) {
                        $aantalPerPaginaFilter = $_GET['Hoeveelheid'];
                    }
                    // defaults voor wanneer het filter niet is ingevuld
                    $orderByFilter = "p.RecommendedRetailPrice " . DEFAULT_PRODUCT_SORT_ORDER;

                    //Checkt welke pagina er geselecteerd is, anders wordt autmoatisch pagina 1 geladen.
                    if (isset($_GET["page"]) && filter_var($_GET["page"], FILTER_VALIDATE_INT) == true) {
                        $selectedPage = $_GET["page"];
                    } else {
                        $selectedPage = 1;
                    }

                    //Berekent vanaf welke index de query dingen moet laat zien, stel $start_from is 20, dan gaat hij vanaf index (20-1) 19 dus dingen laten zien.
                    //Dus als je dan bijv. pagina 2 hebt, en 20 per pagina, wordt $start_from 20,
                    $startFrom = ($selectedPage - 1) * $aantalPerPaginaFilter;

                    //Deze switch-case zorgt er voor dat de lijst op de juiste volgorde wordt gesorteerd.
                    if(isset($_GET['Sort'])) {
                        switch ($_GET['Sort']) {
                            case "NaamASC";
                                $orderByFilter = "p.StockItemName ASC";
                                break;
                            case "NaamDESC";
                                $orderByFilter = "p.StockItemName DESC";
                                break;
                            case "PrijsASC";
                                $orderByFilter = "p.RecommendedRetailPrice ASC";
                                break;
                            case "PrijsDESC";
                                $orderByFilter = "p.RecommendedRetailPrice DESC";
                                break;
                        }
                    }

                    // Moeten we categorie-specifiek zoeken?
                    $selectedCategory = null;
                    if (isset($_GET["Categorie"]) && filter_var($_GET["Categorie"], FILTER_VALIDATE_INT) == true) {
                        $selectedCategory = $_GET["Categorie"];
                    }

                    // Alle SQL magie en PDO connectie shit gebeurt in `Product::zoek()` dus in deze file hebben we geen queries meer nodig. We kunnen direct lezen van de statement zoals hieronder:
                    $stmt = (Product::zoek(Database::getConnection(), $zoekterm, $selectedCategory, $orderByFilter, $startFrom, $aantalPerPaginaFilter));
                    print("<h1>Zoekresultaten</h1>");

                    // Per rij die we uit de database halen voeren we een stukje code uit
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // Dit zorgt er voor dat we alle database attributen kunnen gebruiken als variabelen
                        // (bijv. kolom "StockItemName" kunnen we gebruiken in PHP als "$StockItemName") (PHPStorm geeft rood streepje aan maar het werkt wel)
                        extract($row);

                        ?>

                        <!-- Print dit resultaat -->
                        <a href='product.php?id=<?php print($StockItemID)?>' class='SearchProductDisplayLink'>
                            <div class='row ProductDisplay'>
                                <div class='col-6 ProductDisplayLeft'>
                                    <img src="data:image/png;base64,<?php print($Photo)?>">
                                </div>
                                <div class='col-6 ProductDisplayRight'>
                                    <h3><?php print($StockItemName)?></h3>
                                    <p><?php print($SearchDetails)?></p>
                                    <div class='ProductDisplayPrice'>
                                        <h5>Prijs: <?php print($RecommendedRetailPrice)?></h5>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <?php
                        }
                    ?>

                <div class='aantalPaginas'>
                    <button class=\"back\">Forward</button>
                    <button class="back">Back</button>

                    <?php

                    // Job: dit werkt niet als je https gebruikt of een server achter een proxy hebt dus gebruik gewoon request_uri
                    //$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $url = "$_SERVER[REQUEST_URI]";

                    // Dit is hoeveel producten er in totaal zijn die aan de filter voldoen (hier wordt een nieuwe query gebruikt omdat de vorige het aantal met limit weergeeft!!!!!)
                    $totaalAantalProductenMatchingFilter = (Product::zoek(Database::getConnection(), $zoekterm, $selectedCategory, $orderByFilter, 0, MAX_PRODUCT_RETURN_AMOUNT))->rowCount();
                    $totaalAantalPaginas = ceil($totaalAantalProductenMatchingFilter / $aantalPerPaginaFilter + 1);

                    printf("<p>%d pagina's gevonden. (%d producten getoond, %d totaal)</p>", $totaalAantalPaginas-1, $aantalPerPaginaFilter, $totaalAantalProductenMatchingFilter);

                    // Voor elke pagina zet print hij een klikbaar nummertje
                    for ($paginaNummer = 1; $paginaNummer < ceil($totaalAantalProductenMatchingFilter / $aantalPerPaginaFilter + 1); $paginaNummer++) {

                        ?>

                        <!-- Hij plakt er nu gewoon '&page=69' achter, ongeacht of die er al instaat -->

                        <a href='<?php print($url . "&page=" . $paginaNummer ) ?>'><?php print($paginaNummer) ?></a>

                        <?php
                    }
                    ?>

                </div>

                <?php

                } else {
                    print("Geen zoekterm opgegeven");
                }

                // FIXME ------ DIT KAN WEG NEEM IK AAN ???? ----
                /*
                        //checkt of het product al is geprint.
                        //producten kunnen hier herhalen doordat ze meerdere categorieen hebben.
                    } elseif (in_array($StockItemID, $ProductRepeatCheck)===FALSE) {
                            array_push($ProductRepeatCheck, $StockItemID, $StockItemName);
                            $ProductCount++;
                            print("<a href='product.php?id=" . $StockItemID . "' class='SearchProductDisplayLink'>");
                            print("<div class='ProductDisplay'>");
                            print("<div class='ProductDisplayLeft'>");
                            print('<img src="data:image/png;base64,' . $Photo . '">');
                            print("</div>");
                            print("<div class='ProductDisplayRight'>");
                            print("<h3>" . $StockItemName . "</h3>");
                            print("<p>" . $SearchDetails . "</p>");
                            print("<div class='ProductDisplayPrice'>");
                            print("<h5>Prijs: " . $RecommendedRetailPrice . "</h5>");
                            print("</div></div></div></a>");
                        }
                    }
                        print($ProductCount);
                    } else {
                        print("Geen zoekterm opgegeven");
                    }
                */
                // FIXME --------------------------------------------------------

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
