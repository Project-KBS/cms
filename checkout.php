<?php
    // Uit deze php bestanden gebruiken wij functies of variabelen:
    include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
    include_once("app/database.php");        // wordt gebruikt voor database connectie
    include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
    include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
    include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
    //include_once("composer/mollie.json");
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
                        <a href="index.php">
                            <img src="img/logo/small-250x90.png" alt="Logo">
                        </a>
                    </div>
                </div>
            </header>
            <div class="content-container-home">
                <div class="contact-info">
                    <!-- Moet nog veilig worden gemaakt, en ik weet op dit moment nog niet waarnaar de action moet.-->
                    <form id="contact-info-form" method="post" action="">
                        <?php
                            $inputArray = array(    "Voornaam",
                                                    "Tussenvoegsel",
                                                    "Achternaam",
                                                    "Straatnaam",
                                                    "Huisnummer",
                                                    "Postcode",
                                                    "Woonplaats",
                                                    "Email-adres"
                            );

                            foreach($inputArray as $index => $value){
                        ?>
                        <div class="row">
                            <div class="col-3 FormLabels">
                                <?php print($value); ?>:
                            </div>
                            <div class="col-9">
<<<<<<< Updated upstream
                                <input type="text" placeholder="<?php print($value); ?>" <?php if($value!="Tussenvoegsel"){print("required='required'");}?>>
=======
                                <input type="text" placeholder="<?php print($value); ?>" name="<?php print($value);?>" required="required">
>>>>>>> Stashed changes
                            </div>
                        </div>
                        <br>
                        <?php
                            }
                            print('<input type="submit" value="submit"> ');
                            /*
                             * Dit moet wat worden om de iDeal testomgeving te fixen
                            if(isset($_POST['submit'])){
                                $mollie = new \Mollie\Api\MollieApiClient();
                                $mollie->setApiKey("test_wy2mdWeV32D6nJdqkbtpn392Q9zDg7");

                                $payment = $mollie->payments->create([
                                    "amount" => [
                                        "currency" => "EUR",
                                        "value" => "10.00"
                                    ],
                                    "description" => "My first API payment",
                                    "redirectUrl" => "https://webshop.example.org/order/12345/",
                                    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
                                    "method"      => \Mollie\Api\Types\PaymentMethod::IDEAL,
                                    "issuer"      => $selectedIssuerId, // e.g. "ideal_INGBNL2A"
                                ]);
                            }*/
                        ?>

                        <div class="row lineTop">
                            <div class="col-4">
                                <a href="order-overview.php">Terug naar overzicht</a>
                            </div>
                            <a href="">
                                <input type="submit" value="Betalen >" class="ContinueButton">
                            </a>
                        </div>
                    </form>
                </div>
<<<<<<< Updated upstream
=======
                 <!--Ronald, please zet deze knoppen in de form hier boven
                <div class="row lineTop">
                    <div class="col-4">
                        <a href="order-overview.php">Terug naar overzicht</a>
                    </div>
                    <a href="">
                        <div class="ContinueButton">Betalen ></div>
                    </a>
                </div>
>>>>>>> Stashed changes
                <br>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-2 iDealLogo">
                        <img class="iDealLogo"src="img/logo/ideal.png" alt="iDeal">
                    </div>
                </div>
                -->
            </div>
        </div>
        <footer>
            <?php
                //include("tpl/footer_template.php");
            ?>
        </footer>
    </body>
</html>
