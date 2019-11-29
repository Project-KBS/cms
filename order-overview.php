<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/cart.php");            // wordt gebruikt om de cart-inhoud op te halen
?>

<!doctype html>
<html lang="en">
<head>
    <?php
        include ("tpl/head-tag-template.php");
    ?>
</head>
<body>
    <!-- Onze website werkt niet met Internet Explorer 9 en lager-->
    <!--[if IE]>
        <div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
    <![endif]-->

    <?php
        // Update de winkelmand met de laatste aantallen
        Cart::update();
    ?>

    <!-- Hierin  -->
    <div id="pagina-container">
        <header>
            <!-- Dit gedeelte van de header komt in een lijn te staan met de body content -->
            <div id="header-inline" class="responsive-container">
                <div id="promotie">
                    <a href="index.php"><img src="img/logo/small-250x90.png" alt="Logo"></a>
                </div>
            </div>
        </header>
        <div class="content-container-home">

                    <?php

                        //hier wordt een variabele gemaakt om de prijs in op te slaan
                        $totalprice = 0;


                        foreach (Cart::get() as $item => $aantal){


                            $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            extract($row);
                            if($aantal>0) {
                                $totalPriceExcl = round($totalprice + ($RecommendedRetailPrice * $aantal),2);
                                $totalPriceInlc = round($RecommendedRetailPrice * (1+ $TaxRate/100) * $aantal,2);
                                $roundedRecommendedRetailPrice = round($RecommendedRetailPrice * (1+ $TaxRate/100),2);
                                ?>
                                <div id="order-overview" class="row" style="padding-bottom: 2vh">
                                    <div  id="photo" class="col-2">
                                        <br><img style="width: 100%" src="data:image/png;base64, <?php
                                        if (isset($Photo) && $Photo != null) {
                                            print($Photo);
                                        } else {
                                            print(MediaPortal::getCategoryImage($StockItemID));
                                        }
                                        ?>"><br><br>
                                    </div>
                                    <div id="product-info" class="col-4">
                                        <div class="ProductMand">
                                            <div><h4><?php print($StockItemName); ?></h4></div>
                                            <div><?php print($MarketingComments); ?></div>
                                        </div>
                                    </div>
                                    <div id="retailPrijs" class="col-2 centerDivText">
                                        € <?php print($roundedRecommendedRetailPrice); ?>
                                    </div>
                                    <div id="aantalInMand" class="col-2 centerDivText">
                                        <div class-1>
                                            Aantal: <?php print($aantal); ?>
                                        </div>
                                    </div>
                                    <div id="PrijsBTW" class="col-2 centerDivText">
                                        € <?php print($totalPriceInlc); ?>
                                    </div>
            </div>

                                <?php
                                $totalprice += $totalPriceInlc;

                                   ?> <hr>
                                <?php
                            } elseif($aantal === 0){
                                print("Je hebt niks in je winkelmandje");
                            }
                        }   $totalprice = round($totalprice,2);

                    ?>
                <div class="row">
                    <div id="opvul" class="col-10"></div>
                    <div id="Totaal" class="col-2">

                        <!-- Prijzen totaal-->
                        <p id="prijs-excl">Exclusief btw: €<?php print($totalPriceExcl); ?></p>
                        <p id="prijs-incl">Inclusief btw: €<?php print($totalprice); ?></p>
                        <p id="prijs-totaal">Totaalbedrag: €<?php print(round($totalprice,2)); ?></p>
                    </div>

                </div>
            <div class="row">
                <div class="col-4">
                    <a href="winkelmand.php">Terug naar winkelmand</a>
                </div>
                <div class="col-6"></div>
                <a href="checkout.php">
                    <div class="ContinueButton">Afrekenen ></div>
                </a>
            </div>
            <br><br>

    <footer>
        <?php
        include("tpl/footer_template.php");
        ?>
    </footer>
</body>
</html>
