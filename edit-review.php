<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/authentication.php");  // Accounts en login
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php"); // wordt gebruikt om informatie uit de database te halen
include_once("app/model/review.php"); //wordt gebruikt om de review class te includen

$email = Authentication::getEmail();
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
            $stmt = Review::readOne(Database::getConnection(), $_GET['id'], $email);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                ?>
                <form action="product.php?id=<?php print($_GET["id"]); ?>" id="reviews" method="post"
                      style="display:none; width: 50vw%; margin-left: 20%">

                    <input type="text"
                           name="title"
                           class="reviewInputs"
                           placeholder="Titel van je review"
                           value="<?php print($Title);?>"
                    />
                    <br>

                    <select name="cijfer" class="reviewInputs">
                        <?php

                        for ($i = 1; $i <= 10; $i++) {
                            print("<option value='$i' ");
                            if($i==$Score){
                                print("selected='true'");
                            }
                            print(">$i</option>");
                        }

                        ?>
                    </select><br>

                    <textarea name="reviewInputs"
                              class="reviewInputs"
                              placeholder="Schrijf hier je review"
                              style="height:20vw;"><?php print($Description);?></textarea>

                    <br>

                    <input type="hidden" name="edit" value="1">

                    <input type="submit"
                           name="verzenden"
                           value="verzenden">

                    <br>
                </form>
                <?php
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
