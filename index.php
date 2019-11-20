<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">

        <!-- De beschrijving en eigenschappen van deze pagina -->
        <title><?php echo VENDOR_NAME ?></title>
        <meta name="description" content="<?php echo VENDOR_DESCRIPTION ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="<?php echo VENDOR_THEME_COLOR_PRIMARY?>">
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
            <div class="content-container-home">
                <div id="product-sectie" class="d-flex flex-row">
                <?php
                //Als de sales template klaar is kunnen we de comments hier onder weghalen en dan worden de sales bovenaan de pagina geladen.
                //include("tpl/sale_template.php");

                // Alle SQL magie en PDO connectie shit gebeurt in `Product::read()` dus in deze file hebben we geen queries meer nodig. We kunnen direct lezen van de statement zoals hieronder.
                // De volgende code laadt de 16 eerste producten in de DB en geeft ze weer:
                $stmt = (Product::read(Database::getConnection(), 16));

                // Per rij die we uit de database halen voeren we een stukje code uit
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        // Dit zorgt er voor dat we `$StockItemID` enzo kunnen gebruiken (PHPStorm geeft rood streepje aan maar het werkt wel)
                        extract($row);
                        //"perfect" ~ Matthijs Bakker - 19/11/2019 16:02

                        ?>



                        <div class="product-display d-flex">

                            <a href="product.php?id=<?php print($StockItemID) ?>">
                                <div class="product-foto">
                                    <!-- Kijkt of het product een foto in de database heeft, zo niet dan geeft hij de categoriefoto -->
                                    <img src="data:image/png;base64, <?php
                                    if (isset($Photo) && $Photo != null) {
                                        print($Photo);
                                    } else {
                                        print(MediaPortal::getCategoryImage($StockItemID));
                                    } ?>">
                                </div>
                                <div class="product-beschrijving">
                                    <h4><?php print($StockItemName) ?></h4>
                                    <p><?php print($SearchDetails) ?></p>
                                    <div class="product-prijs">
                                        <h5>€<?php print($RecommendedRetailPrice) ?></h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <?php
                }
                ?>
                </div>
            </div>

        </div>

        <div class="footer-container">
            <?php
                // include("tpl/footer_template.php");
            ?>

        </div>
    </body>
</html>
