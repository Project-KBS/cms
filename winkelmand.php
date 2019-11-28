<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/cart.php");            // Wordt gebruikt om de huidige test producten te gebruiken
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

    <!-- Onze website werkt niet met Internet Explorer 9 en lager, laat een waarschuwing zien -->
    <!--[if IE]>
        <div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
    <![endif]-->

    <?php

        // Als er
        if (isset($_GET["id"])) {
            $nieuwProduct = $_GET["id"];
            $nieuwProductId = intval($nieuwProduct);

            if ($nieuwProductId > 0) {
                Cart::add($nieuwProductId, 1);
            }
        }

    ?>

    <!-- Hierin  -->
    <div id="pagina-container">

        <!-- Print de header (logo, navigatiebalken, etc.)-->
        <?php
            include("tpl/header_template.php");
        ?>

        <!-- Inhoud pagina -->
        <div class="content-container-home">

            <form method="post" action="order-overview.php">

            <?php
                $teller = 0;
                foreach (Cart::get() as $item => $aantal) {
                    $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);
                        ?>

                    <div id="geheel" class="row" style="padding-bottom: 2vh">

                            <!-- Links komen de foto's en informatie van het product-->
                            <div id="Foto" class="col-2">

                                <!-- Foto van product of categorie -->
                                <img src="data:image/png;base64, <?php
                                                       if (isset($Photo) && $Photo != null) {
                                                           print($Photo);
                                                       } else {
                                                           print(MediaPortal::getCategoryImage($StockGroupID));
                                                       }
                                                        ?>" id="Productphoto" class="Productphoto" >

                            </div>

                            <div id="Omschrijving" class="col-4">

                                <?php
                                    if (isset($StockItemName) && $StockItemName != null) {
                                        print($StockItemName);
                                    }

                                    print("<br>");

                                    if (isset($MarketingComments) && $MarketingComments != null) {
                                        print($MarketingComments);
                                    }
                                ?>

                            </div>

                            <div id="Aantal" class="col-1">
                                <input style="width: 100%" type="number" id="test<?php print($teller); ?>" name="<?php print($StockItemID); ?>" value="<?php print($aantal); ?>" min="0">
                            </div>

                            <!-- hier komen de aantallen en totaalprijzen-->
                            <div id="Prijs" class="col-5">

                                <p id="prijs<?php print($teller); ?>">Totaalprijs: € <?php print( round($RecommendedRetailPrice * (1+ $TaxRate/100),2)); ?></p>


                                <!-- Zorgt ervoor dat je geen negatief getal kan invullen-->
                                <script>
                                    // Dit is het input veld
                                    const hoeveelheid_input<?php print($teller); ?> = document.getElementById('test<?php print($teller); ?>');

                                    // Listen for input event on numInput. ( blokkeert negatieve getallen)
                                    hoeveelheid_input<?php print($teller); ?>.onkeydown = function(e) {
                                        if(!((e.keyCode > 95 && e.keyCode < 106)
                                            || (e.keyCode > 47 && e.keyCode < 58)
                                            || e.keyCode === 8)) {
                                            return false;
                                        }
                                    };

                                    // Dit is de <p> tag van totaalprijs
                                    const totaalprijs<?php print($teller); ?> = document.getElementById(('prijs<?php print($teller); ?>'));

                                    hoeveelheid_input<?php print($teller); ?>.onchange = function () {
                                        totaalprijs<?php print($teller); ?>.innerHTML = "Totaalprijs: €" +
                                            (hoeveelheid_input<?php print($teller); ?>.value * <?php print($RecommendedRetailPrice * (1+ $TaxRate/100)) ?>).toFixed(2);
                                    }

                                </script>

                            </div>

                        </div>

                        <?php

                    $teller++;
                }

                ?>

            <div class="row">

                <div id="opvul" class="col-7">

                </div>

                <!-- Bestelknop komt hier met foto's van betalmethoden-->
                <div id="Totaal" class="col-3">

                    <!-- Prijzen totaal -->
                    <p id="prijs-excl">Exclusief btw:</p>
                    <p id="prijs-incl">Inclusief btw: fuck</p>
                    <p id="prijs-totaal">Totaalprijs: </p>

                </div>

                <div id="verstuur" class="col-2">

                    <!-- Submit knop en betaalmethoden afbeeldingen -->
                    <input type="submit" value="Verder naar bestellen">

                    <img src="img/logo/ideal.png" style="max-width: 60%">
                </div>

            </div>

            </form>

        </div>

    </div>

    <div class="footer-container">
        <?php
            // Dit is de footer
            include("tpl/footer_template.php");
        ?>
    </div>

    </body>
</html>
