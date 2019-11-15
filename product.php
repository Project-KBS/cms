<?php

// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php"); // wordt gebruikt om informatie uit de database te halen

// Deze pagina vereist een GET parameter: "id" met integer value van het product.
// Als deze param niet meegegeven is sturen we de user terug naar index.php
if (!isset($_GET["id"]) || filter_var($_GET["id"], FILTER_VALIDATE_INT) == false) {
    header("Location: index.php");
}

// TODO ---------------------------------------------

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

        <!-- Hierin komt de pagina -->
        <div id="pagina-container">

            <!-- Print de header (logo, navigatiebalken, etc.)-->
            <?php
                include("tpl/header_template.php");
            ?>

            <!-- Inhoud van de pagina -->
            <div class="content-container">




                <?php
                // TODO ------ HIER MOET DE INHOUD VAN PRODUCT PAGINA KOMEN!!!!!!!

                $stmt = (Product::getbyid(Database::getConnection(), $_GET["id"], 5));
                if($stmt->rowCount()> 0){



                    $categories = array();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    array_push($categories, $StockGroupName);

                }


                $stmt = (Product::getbyid(Database::getConnection(), $_GET["id"], 5));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    extract($row);



                    //Opmaak van de pagina

                    printf("<h1>%s</h1>",$StockItemName);

                    foreach ($categories as $index => $value){
                        print($value." ");
                    }
                    ?>
                    <div id="geheel" class="row">

                    <div id="links" class="col-6" style="background: hotpink">
                    <img src="data:image/png;base64, <?php print($Photo) ?> "><br>

                    </div>
                    <div id="rechts" class="col-6" style="background: aqua">
                        <h1>€ <?php print($RecommendedRetailPrice) ?></h1>


                    </div>


                    </div>


                <?php
                } else {
                    include("tpl/Foutproduct.html");
                }



                ?>
            </div>

        </div>
        <div class="footer-container">

            <!-- Print de footer (contact info, etc.) -->
            <?php
                // include("tpl/footer_template.php");
            ?>

        </div>
    </body>
</html>
