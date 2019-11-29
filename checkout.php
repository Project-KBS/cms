<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
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
            <div class="contact-info">
                <!-- Moet nog veilig worden gemaakt, en ik weet op dit moment nog niet waarnaar de action moet.-->
                <form id="contact-info-form" method="post" action="ideal-testomgeving.php">
                    <?php $inputArray = array(  "Voornaam",
                                                "Tussenvoegsel",
                                                "Achternaam",
                                                "Straatnaam",
                                                "Huisnummer",
                                                "Postcode",
                                                "Woonplaats",
                                                "Email-adres");

                    foreach($inputArray as $index => $value){
                    ?>
                    <div class="row">
                        <div class="col-3 FormLabels">
                            <?php print($value); ?>:
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="<?php print($value); ?>" required="required">
                        </div>
                    </div>
                    <br>
                    <?php
                    }
                    ?>
                </form>
            </div>
            <div class="row lineTop">
                <div class="col-4">
                    <a href="order-overview.php">Terug naar overzicht</a>
                </div>
                <a href="">
                    <div class="ContinueButton">Betalen ></div>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-2 iDealLogo">
                    <img class="iDealLogo"src="img/logo/ideal.png" alt="iDeal">
                </div>
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
