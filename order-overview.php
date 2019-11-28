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
            <div class="order-overview">
                <table>
                    <tr>
                        <th class="ProductMandTableEntry1">
                            Product
                        </th><th class="ProductMandTableEntry2"></th>
                        <th class="ProductMandTableEntry3">
                            Prijs per stuk
                        </th>
                        <th class="ProductMandTableEntry4">
                            Aantal
                        </th>
                        <th class="ProductMandTableEntry5">
                            Totaalprijs
                        </th>
                    </tr>
                    <?php

                        //hier wordt een variabele gemaakt om de prijs in op te slaan
                        $totalprice = 0;

                        foreach (Cart::get() as $item => $aantal){

                            $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            extract($row);
                            if($aantal>0) {
                                $totalprice = $totalprice + ($RecommendedRetailPrice * $aantal);
                                ?>
                                <tr>
                                    <th class="ProductMandTableEntry1">
                                        <br><img style="max-width: 100%" src="data:image/png;base64, <?php
                                        if (isset($Photo) && $Photo != null) {
                                            print($Photo);
                                        } else {
                                            print(MediaPortal::getCategoryImage($StockItemID));
                                        }
                                        ?>"><br><br>
                                    </th>
                                    <th class="ProductMandTableEntry2">
                                        <div class="ProductMand">
                                            <div><h4><?php print($StockItemName); ?></h4></div>
                                            <div><?php print($MarketingComments); ?></div>
                                        </div>
                                    </th>
                                    <th class="ProductMandTableEntry3">
                                        € <?php print($RecommendedRetailPrice); ?>
                                    </th>
                                    <th class="ProductMandTableEntry4">
                                        <?php print($aantal); ?>
                                    </th>
                                    <th class="ProductMandTableEntry5">
                                        € <?php print($RecommendedRetailPrice * $aantal); ?>
                                    </th>
                                </tr>
                                <?php
                            }
                        }

                    ?>
                    <tr class="ProductMandTotalLine">
                        <th class="ProductMandTableEntry1"></th><th class="ProductMandTableEntry2"></th><th class="ProductMandTableEntry3"></th><th class="ProductMandTableEntry4"></th>
                        <th class="ProductMandTableEntry5">Totaal:<br>€ <?php print($totalprice);?></th>
                    </tr>
                </table>
                <br><br>
                <div class="row">
                    <div class="col-4"><a href="winkelmand.php">Terug naar winkelmand</a></div><div class="col-6"></div><div class="col-2 ContinueButton">Afrekenen ></div>
                </div>
                <br><br>
            </div>
        </div>


    </div>
    <footer>
        <?php
        //include("tpl/footer_template.php");
        ?>
    </footer>
</body>
</html>
