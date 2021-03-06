<?php

    // Uit deze php bestanden gebruiken wij functies of variabelen:
    include_once("app/authentication.php");  // Accounts en login
    include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
    include_once("app/database.php");        // wordt gebruikt voor database connectie
    include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
    include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
    include_once("app/model/product.php"); // wordt gebruikt om informatie uit de database te halen
    include_once("app/model/review.php"); //wordt gebruikt om de review class te includen

    // Deze pagina vereist een GET parameter: "id" met integer value van het product.
    // Als deze param niet meegegeven is sturen we de user terug naar index.php
    if (!isset($_GET["id"]) || filter_var($_GET["id"], FILTER_VALIDATE_INT) == false) {
        header("Location: index.php");
    }

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

        <!-- Hierin komt de pagina -->
        <div id="pagina-container">

            <!-- Print de header (logo, navigatiebalken, etc.)-->
            <?php
                include("tpl/header_template.php");
            ?>

            <!-- Inhoud van de pagina -->
            <div class="content-container" style="margin-top: 2.75vw;">

                <?php

                $stmt = (Product::getbyid(Database::getConnection(), $_GET["id"], 5));
                if ($stmt->rowCount() > 0) {

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

                    <h1>
                        <?php print($StockItemName); ?>
                    </h1>

                    <h4 style="font-weight: normal; color: <?php print(VENDOR_THEME_COLOR_TEXT_DISABLED); ?>; margin-bottom: 1.7vw">
                        <?php
                            //PRINT alle categorieën
                            if (count($categories) > 1) {
                                print("Categorieën: ");
                            } else {
                                print("Categorie: ");
                            }
                            $first = true;
                            foreach ($categories as $index => $value) {
                                if ($first) {
                                    $first = false;
                                    print($value);
                                } else {
                                    print( ", " . $value );
                                }
                            }
                        ?>
                    </h4>

                    <div id="geheel" class="row">

                        <div id="links" class="col-6">

                            <div id="linksboven">

                                <!-- Dit is de hoofdfoto (grote foto) -->
                                <img src="data:image/png;base64, <?php
                                                                    if (isset($Photo) && $Photo != null) {
                                                                        print($Photo);
                                                                    } else {
                                                                        print(MediaPortal::getCategoryImage($StockGroupID));
                                                                    }
                                                                 ?>" id="Productphoto" class="Productphoto" alt="Productfoto">
                                <br>

                                <!-- Dit is de hoofdvideo (grote video) die pas zichtbaar wordt als je op een video thumbnail klikt -->
                                <video id="Productvideo" class="Productphoto" style="display: none" controls>
                                    <source  type="video/mp4">
                                    Your browser does not support HTML5 video.
                                </video>

                            </div>

                            <div id="linksonder">

                                <script>
                                    /* Maakt een variabele aan voor de foto */
                                    const hoofdfoto = document.getElementById("Productphoto");
                                    /* Maakt een variabele aan voor de video */
                                    const hoofdvideo = document.getElementById("Productvideo");
                                    /* Maakt een variabele aan voor de videosource */
                                    const hoofdvideosource = hoofdvideo.getElementsByTagName('source')[0];
                                </script>

                                <?php

                                // Zoek alle productfoto's
                                $images = MediaPortal::getProductImages($StockItemID);

                                // Zoek alle productvideo's
                                $videos = MediaPortal::getProductVideos($StockItemID);


                                // Als er een hoofdfoto is
                                if (isset($Photo) && $Photo != null) {
                                    // Zet deze hoofdfoto ook in de thumbnail array.
                                    array_unshift($images, $Photo);
                                } else {
                                    // Zet de categorie foto in de thumbnail array
                                    array_unshift($images, MediaPortal::getCategoryImage($StockGroupID));
                                }

                                $teller = 0;
                                // Deze loop zorgt ervoor dat de thumbnailfoto's onder de hoofdfoto komen. Er worden meerdere geprint
                                foreach ($images as $image) {
                                    ?>

                                    <img id="foto<?php print($teller)?>"style="width: 25%; padding: 10px"  src="data:image/png;base64, <?php print($image) ?> ">

                                    <!-- Maak een uniek javascript voor deze foto zodat de foto klikbaar is -->
                                    <script>
                                        // Dit is de variabele voor deze foto html tag ("<img />")
                                        const foto<?php print($teller)?> = document.getElementById("foto<?php print($teller)?>");

                                        // Als je op de foto klikt dan wordt deze foto in de hoofdfoto verplaatst
                                        foto<?php print($teller)?>.addEventListener("click", function() {
                                            // Kopieer letterlijk de waarde van het source attribuut van de foto naar de hoofdfoto
                                            hoofdfoto.setAttribute("src", foto<?php print($teller)?>.getAttribute("src"));
                                            /* Zorgt ervoor dat de video niet zichtbaar is*/
                                            hoofdvideo.style.display="none";
                                            /* Zorgt ervoor dat de foto zichtbaar wordt */
                                            hoofdfoto.style.display="block";
                                        });
                                    </script>

                                    <?php
                                    $teller++;
                                } ?>

                                <!-- Maak een aparte div aan omdat de thumbnails een "absolute" positie hebben!!!-->
                                <div>

                                <?php
                                $teller = 0;
                                foreach ($videos as $video) {
                                    ?>
                                    <!-- plakt het rode play emblem OP de videos zodat deze te onderscheiden zijn van foto's -->
                                    <img id="thumbnail<?php print($teller)?>" style="width: 10%; padding: 10px; z-index: 2; position: absolute"  src="img/video/video.png">

                                    <!-- Maakt een kleine weergave van de video voor het overzicht-->
                                    <video id="video<?php print($teller)?>" style="width: 25%; padding: 10px; z-index: 1">
                                        <source src=<?php print($video); ?> type="video/mp4">
                                        Your browser does not support HTML5 video.
                                    </video>

                                    <!-- Het javascript om de video af te spelen in het groot (op de plaats van de hoofdvideo) -->
                                    <script>
                                        // Dit is de variabele voor deze video html tag ("<video />")
                                        const video<?php print($teller)?> = document.getElementById("video<?php print($teller)?>");
                                        // Dit is de variabele voor deze source html tag in de video tag ("<source />")
                                        const source<?php print($teller)?> = video<?php print($teller); ?>.getElementsByTagName('source')[0];
                                        // Dit is de variabele voor deze rode knop ("<img />")
                                        const thumbnail<?php print($teller)?> = document.getElementById("thumbnail<?php print($teller)?>");

                                        // Deze functie zorgt er voor dat, wanneer hij uitgevoerd wordt, de hoofdvideo source tag de URL krijgt van deze video zijn source tag.
                                        // Assign de functie naar een variabele zodat we hem niet 2x hoeven te schrijven
                                        const functie<?php print($teller)?> = function() {
                                            // Verberg de hoofdfoto indien hij er nog staat
                                            hoofdvideo.style.display="block";
                                            hoofdfoto.style.display="none";

                                            hoofdvideosource.src= source<?php print($teller)?>.src;

                                            // Herlaad de hoofdvideo om de veranderingen te merken
                                            hoofdvideo.load();
                                        };

                                        // Als je op de video klikt dan wordt de functie uitgevoerd
                                        video<?php print($teller)?>.addEventListener("click", functie<?php print($teller); ?>);

                                        // Als je op de rode knop klikt dan wordt de functie ook uitgevoerd
                                        thumbnail<?php print($teller)?>.addEventListener("click", functie<?php print($teller); ?>);
                                    </script>

                                    <?php
                                    $teller++;
                                }
                                ?>
                                </div>

                            </div>

                        </div>

                        <div id="rechts" class="col-6" style="padding: 0">




                            <!-- Print de prijs-->
                            <h2 style="color: <?php echo VENDOR_THEME_COLOR_HIGHLIGHT ?>">
                                € <?php print(round($RecommendedRetailPrice * (1 + $TaxRate / 100), 2)); ?>
                            </h2>

                            <p>
                                <?php

                                    // print of een product op voorraad is als de voorraad >0 is, anders print deze dat het product in de backorder zit
                                    // Als een product de tag "Limited Stock" heeft zal een urgentie bericht worden getoond ipv een voorraad
                                    if($QuantityOnHand > 0) {
                                        if($Tags === '["Limited Stock"]'){ ?>
                                            <button class="btn btn-danger btn-lg" style="margin-top: 10px; margin-bottom: 10px">  Beperkt beschikbaar koop nu!</button>
                                        <?php }
                                        else{
                                            print("Op voorraad");
                                        }
                                    } else {
                                        if($Tags === '["Limited Stock"]'){ ?>
                                            <button class="btn btn-danger btn-lg" style="margin-bottom: 10px">  Dit product is uitverkocht</button>
                                        <?php }
                                        print("In de backorder");
                                    }

                                ?>
                            </p>

                            <div id="winkelwagen" style="margin-bottom: 2rem">

                                <form method="post" name="winkelmandje" action="">

                                    <div class="row">

                                        <div class="col-6" style="padding-right: 0">

                                            <input type="number"
                                                   name="product:<?php print($StockItemID); ?>"
                                                   id="hoeveelheid_input"
                                                   min="1"
                                                   value="<?php print("1"); ?>"
                                                   class="form-control"
                                                   style="margin: 0 !important;"
                                            />

                                        </div>

                                        <div class="col-6">

                                            <input type="submit"
                                                   value="Toevoegen aan winkelwagen"
                                                   class="btn btn-primary bootstrap-btn"
                                            />
                                        </div>

                                    </div>

                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?php print($csrf_token);?>"
                                    />

                                </form>


                                <script>
                                    // Dit is het input veld
                                    const hoeveelheid_input = document.getElementById('hoeveelheid_input');

                                    // Als er een min-teken getypt wordt hij verwijderd
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
                                //Als er een omschrijving bestaat print deze dat uit en anders blijft omschrijving leeg
                                if (isset($MarketingComments) && $MarketingComments != null) {
                                    print($MarketingComments);
                                } else {
                                    print("Er is geen productomschrijving beschikbaar.");
                                }
                            ?>

                            <hr>

                            <h3>Productspecificaties</h3>
                            <?php

                                //Controleert of de waarde bestaat en daarna of het geen null waarde is.
                                //Daarna wordt de informatie geprint
                                if (isset($SupplierName) && $SupplierName != null) {
                                    print("Leverancier: $SupplierName <br>");
                                }

                                if (isset($ColorName) && $ColorName != null) {
                                    print("Kleur: $ColorName <br>");
                                }

                                if (isset($Brand) && $Brand!=null){
                                    print("Merk: $Brand <br>");
                                }

                                if (isset($Size) && $Size !=null){
                                    print("Grootte: $Size<br>");
                                }

                                if (isset($QuantityPerOuter) && $QuantityPerOuter != null){
                                    print("Het aantal per verpakking: $QuantityPerOuter <br>");
                                }

                                if (isset($TypicalWeightPerUnit) && $TypicalWeightPerUnit != null){
                                    print("Het gewicht: $TypicalWeightPerUnit Kg<br>");
                                }

                                if (isset($IsChillerStock) && $IsChillerStock != null &&$IsChillerStock != 0){
                                    print("Gekoeld: Ja<br>");
                                }

                            ?>

                        </div>

                    </div>

                <?php

                } else {
                    // Voor de veiligheid en tegen errors geeft dit een foutmelding aan de gebruiker wanneer een product niet bestaat van dat ID
                    include("tpl/Foutproduct.html");
                }
                if ($stmt->rowCount() > 0) {
                    include("tpl/reviews.php");
                }
                ?>


            </div>

        </div>
        <div class="footer-container">

            <!-- Print de footer (contact info, etc.) -->
            <?php
                 include("tpl/footer_template.php");
            ?>

        </div>
    </body>
</html>
