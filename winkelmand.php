<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/cart.php");            // Wordt gebruikt om de huidige test producten te gebruiken

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

    <!-- Onze website werkt niet met Internet Explorer 9 en lager, laat een waarschuwing zien -->
    <!--[if IE]>
        <div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
    <![endif]-->

    <?php

        // Als er een Product ID in de $_GET[] staat voegen we die toe aan de winkelmand.
        if (isset($_GET["id"])) {
            $nieuwProduct = $_GET["id"];
            $nieuwProductId = intval($nieuwProduct);

            if ($nieuwProductId > 0) {
                Cart::add($nieuwProductId, 1);
            }
        }

    ?>

    <form id="update-aantal" style="display: none" method="POST" action="winkelmand.php">
        <input id="update-aantal-input" type="hidden" name="" value="-1">
    </form>

    <!-- Hierin  -->
    <div id="pagina-container">

        <!-- Print de header (logo, navigatiebalken, etc.)-->
        <?php
            include("tpl/header_template.php");
        ?>

        <!-- Inhoud pagina -->
        <div class="content-container-home">

            <form method="post" action="order-overview.php">

                <script>

                    class InputVeld {

                        constructor(element, prijs, taxrate) {
                            this.element = element;
                            this.prijs = prijs;
                            this.taxrate = taxrate;
                        }

                    }

                    // Dit is de array met alle aantal omhoog/omlaag knoppen.
                    var input_elementen = [];

                </script>

            <?php
                $teller = 0;
                $isResultaat = false;
                foreach (Cart::get() as $item => $aantal) {
                    $isResultaat = true;
                    $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);
                        ?>

                    <div id="geheel<?php print($teller); ?>" class="row" style="padding-bottom: 2vh">

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
                                <input style="width: 100%" type="number" id="hoeveelheid<?php print($teller); ?>" name="<?php print($StockItemID); ?>" value="<?php print($aantal); ?>">

                                <!-- Verwijder uit winkelwagen knop -->
                                <button id="verwijder<?php print($teller); ?>" type="button"  style="width: 100%; margin-top: 0.5em">
                                    Verwijder
                                </button>

                                <script>
                                    // Dit is de verwijder knop
                                    const verwijderen<?php print($teller); ?> = document.getElementById('verwijder<?php print($teller); ?>');
                                    // Dit is de rij met alles over het product
                                    const geheel<?php print($teller); ?> = document.getElementById('geheel<?php print($teller); ?>');
                                    // Dit is de hoeveelheid input veld
                                    const test<?php print($teller); ?> = document.getElementById('hoeveelheid<?php print($teller); ?>');
                                    // Dit is het onzichtbare verwijder form
                                    const form<?php print($teller); ?> = document.getElementById('update-aantal');
                                    const form_input<?php print($teller); ?> = document.getElementById('update-aantal-input');

                                    verwijderen<?php print($teller); ?>.onclick = function(){
                                        if(confirm("Weet u dit zeker?")){
                                            geheel<?php print($teller); ?>.style.display = "none";
                                            test<?php print($teller); ?>.value = -1;

                                            functie_bereken();
                                            form_input<?php print($teller); ?>.name = "<?php print("product:" . $StockItemID); ?>";
                                            form_input<?php print($teller); ?>.value = "<?php print("-1"); ?>";
                                            form<?php print($teller); ?>.submit();
                                        }


                                    }

                                </script>

                            </div>

                            <!-- hier komen de aantallen en totaalprijzen-->
                            <div id="Prijs" class="col-5">

                                <p id="prijs<?php print($teller); ?>">Totaalprijs: € <?php print( round($RecommendedRetailPrice * (1+ $TaxRate/100) * $aantal,2)); ?></p>


                                <!-- Zorgt ervoor dat je geen negatief getal kan invullen-->
                                <script>
                                    // Dit is het input veld
                                    const hoeveelheid_input<?php print($teller); ?> = document.getElementById('hoeveelheid<?php print($teller); ?>');
                                    var inputVeld = new InputVeld(hoeveelheid_input<?php print($teller); ?>, <?php print($RecommendedRetailPrice); ?>, <?php print($TaxRate); ?>);

                                    // Voeg dit veld toe aan de input elementen array
                                    input_elementen.push(inputVeld);

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
                                        // Bereken de totaalprijs
                                        totaalprijs<?php print($teller); ?>.innerHTML = "Totaalprijs: € " +
                                            (hoeveelheid_input<?php print($teller); ?>.value * <?php print($RecommendedRetailPrice * (1+ $TaxRate/100)) ?>).toFixed(2);

                                        if (hoeveelheid_input<?php print($teller);?>.value <= 0) {
                                            verwijderen<?php print($teller);?>.onclick(null);
                                        }

                                        // Bereken de prijs incl/excl btw opnieuw.
                                        functie_bereken();
                                    }

                                </script>

                            </div>

                        </div>

                        <?php

                    $teller++;
                }

                if ($isResultaat) {


            ?>

                <div class="row">

                    <div id="opvul" class="col-7">

                    </div>

                    <!-- Bestelknop komt hier met foto's van betalmethoden-->
                    <div id="Totaal" class="col-3">

                        <!-- Prijzen totaal-->
                        <p id="prijs-excl">Exclusief btw: €</p>
                        <p id="prijs-incl">Inclusief btw: €</p>
                        <p id="prijs-totaal">Totaalbedrag: €</p>

                        <script>

                            const element_prijs_excl = document.getElementById("prijs-excl");
                            const element_prijs_incl = document.getElementById("prijs-incl");
                            const element_prijs_totaal = document.getElementById("prijs-totaal");

                            const functie_bereken = function () {

                                let prijsExcl = 0;
                                let prijsIncl = 0;

                                // foreach loop voor alle input elementen
                                input_elementen.forEach(function (element, index, array) {

                                    // Als hoeveelheid positief is
                                    if (element.element.value > 0) {
                                        // Voeg de totaalprijs van dit product toe
                                        prijsExcl += element.element.value * element.prijs;
                                        prijsIncl += element.element.value * (element.prijs * (1 + element.taxrate / 100));
                                    }

                                });

                                // Rond de prijzen af naar 2 decimalen en voeg ze in op de juiste plek
                                element_prijs_excl.innerHTML = "Exclusief btw: €" + prijsExcl.toFixed(2);
                                element_prijs_incl.innerHTML = "Inclusief btw: €" + prijsIncl.toFixed(2);
                                element_prijs_totaal.innerHTML = "Totaalbedrag: €" + prijsIncl.toFixed(2);
                            };

                            functie_bereken();

                        </script>

                    </div>

                    <div id="verstuur" class="col-2">

                        <!-- Submit knop en betaalmethoden afbeeldingen -->
                        <input type="submit" value="Verder naar bestellen">

                        <img src="img/logo/ideal.png" style="max-width: 60%">
                    </div>

                    <?php

                    } else {

                    ?>

                        <h2>Uw winkelmand is leeg</h2>
                        <br>
                        <p>klik op de link om verder te winkelen.</p>
                        <a href="index.php">
                            <p>Verder winkelen</p>
                        </a>

                    <?php

                    }

                ?>

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
