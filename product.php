<?php

// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php"); // wordt gebruikt om informatie uit de database te halen

// Deze pagina vereist een GET parameter: "id" met integer value van het product.
// Als deze param niet meegegeven is sturen we de user terug naar index.php
if (!isset($_GET["id"]) || filter_var($_GET["id"], FILTER_VALIDATE_INT) == false) {
    header("Location: index.php");
}


function specificaties(){

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

                $stmt = (Product::getbyid(Database::getConnection(), $_GET["id"], 5));
                if($stmt->rowCount()> 0) {

                    $categories = array();

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);

                        array_push($categories, $StockGroupName);
                    }

                    $stmt = (Product::getbyid(Database::getConnection(), $_GET["id"], 5));

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);

                    //Opmaak van de pagina
                    ?>

                    <h1> <?php print($StockItemName) ?></h1>

                    <h3> <?php
                        foreach ($categories as $index => $value) {
                        print($value." ");
                    }
                    ?>
                    </h3>

                    <div id="geheel" class="row">
                        <div id="links" class="col-6" style="background: hotpink">
                            <div id="linksboven">
                            <img src="data:image/png;base64, <?php
                                                                if (isset($Photo) && $Photo != null) {
                                                                    print($Photo);
                                                                } else {
                                                                    print(MediaPortal::getCategoryImage($StockGroupID));
                                                                }
                                                             ?>" id="Productphoto"><br>
                            </div>
                            <div id="linksonder" style="background: black">

                                <script>
                                    const hoofdfoto = document.getElementById("Productphoto");
                                </script>

                                <?php

                                // Zoek alle productfoto's
                                $images = MediaPortal::getProductImages($StockItemID);

                                // Zet de hoofdfoto ook in deze array
                                array_unshift($images, $Photo);

                                $teller = 0;
                                foreach ($images as $image) {
                                    ?>

                                    <img id="foto<?php print($teller)?>"style="width: 25%; padding: 10px"  src="data:image/png;base64, <?php print($image) ?> ">

                                    <script>
                                        const foto<?php print($teller)?> = document.getElementById("foto<?php print($teller)?>");

                                        // Als je op de foto klikt dan wordt deze foto in de hoofdfoto verplaatst
                                        foto<?php print($teller)?>.addEventListener("click", function() {
                                            hoofdfoto.setAttribute("src", foto<?php print($teller)?>.getAttribute("src"));
                                        });


                                    </script>

                                    <?php
                                    $teller++;
                                }
                                ?>

                            </div>

                        </div>

                        <div id="rechts" class="col-6" style="background: aqua; padding: 0">
                            <h1>€ <?php print($RecommendedRetailPrice) ?></h1>

                            <h3> <?php
                                if($QuantityOnHand > 0) {
                                    print("Op voorraad");
                                } else {
                                    print("In de backorder");
                                }
                                ?>
                            </h3>

                            <div id="winkelwagen">
                                <input type="number" id="hoeveelheid_input" min="0">
                                <input type="submit" value="Toevoegen aan winkelwagen">
                                <script>
                                    // Dit is het input veld
                                    const hoeveelheid_input = document.getElementById('hoeveelheid_input');

                                    // Listen for input event on numInput. ( blokkeert negatieve getallen)
                                    hoeveelheid_input.onkeydown = function(e) {
                                        if(!((e.keyCode > 95 && e.keyCode < 106)
                                            || (e.keyCode > 47 && e.keyCode < 58)
                                            || e.keyCode === 8)) {
                                            return false;
                                        }
                                    }
                                </script>
                            </div>
                            <h3>Productomschrijving</h3>

                            <?php
                            if(isset($SearchDetails) && $SearchDetails != null){
                                print($SearchDetails);
                            }
                            ?>

                            <hr>

                            <h3>Productspecificaties</h3>
                            <?php
                            //Controleert of de waarde bestaat en daarna of het geen null waarde is.
                            //Daarna wordt de informatie geprint
                            if(isset($SupplierName) && $SupplierName != null) {
                                print("Leverancier: $SupplierName <br>");
                            }
                            if(isset($ColorName) && $ColorName != null) {
                                print("Kleur: $ColorName <br>");
                            }
                            if(isset($Brand) && $Brand!=null){
                                print("Merk: $Brand <br>");
                            }
                            if(isset($Size) && $Size !=null){
                                print("Grootte: $Size<br>");
                            }
                            if(isset($QuantityPerOuter) && $QuantityPerOuter != null){
                                print("Het aantal per verpakking: $QuantityPerOuter <br>");
                            }
                            if(isset($TypicalWeightPerUnit) && $TypicalWeightPerUnit != null){
                                print("Het gewicht: $TypicalWeightPerUnit <br>");
                            }
                            if(isset($IsChillerStock) && $IsChillerStock != null &&$IsChillerStock != 0){
                                print("Gekoeld: Ja<br>");
                            }

                            ?>


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
