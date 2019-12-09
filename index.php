<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's

session_start();
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

                        <!-- Print dit resultaat -->
                        <div class="product-display d-flex">

                            <!-- Maakt alles klikbaar zodat de gebruiker naar de pagina gestuurd wordt -->
                            <a href="product.php?id=<?php print($StockItemID) ?>">

                                <div class="product-foto">

                                    <!-- Kijkt of het product een foto in de database heeft, zo niet dan geeft hij de categoriefoto -->
                                    <img src="data:image/png;base64, <?php
                                                                        if (isset($Photo) && $Photo != null) {
                                                                            print($Photo);
                                                                        } else {
                                                                            print(MediaPortal::getCategoryImage($StockItemID));
                                                                        }
                                                                      ?>">

                                </div>

                                <div class="product-beschrijving">

                                    <h4><?php print($StockItemName) ?></h4>
                                    <p><?php print($SearchDetails) ?></p>

                                    <div class="product-prijs">
                                        <h5>€<?php printf("%0.2f",$RecommendedRetailPrice * (1 + $TaxRate / 100)); ?></h5>
                                    </div>

                                    <form method="POST" name="winkelmandje" action="">
                                        <input type="hidden" name="product:<?php print($StockItemID); ?>" value="1">
                                        <input type="submit" class="WinkelwagenKnop" value="Toevoegen aan winkelmandje">


                                    </form>

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
                include("tpl/footer_template.php");
            ?>

        </div>
    </body>
</html>
