<?php

    // Uit deze php bestanden gebruiken wij functies of variabelen:
    include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
    include_once("app/database.php");        // wordt gebruikt voor database connectie
    include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
    include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
    include_once("app/model/account.php");   // wordt gebruikt voor producten ophalen uit DB
    include_once("app/security/HashResult.php");        // wordt gebruikt voor database connectie
    include_once("app/security/IHashMethod.php");        // Voor het hashen van wachtwoorden
    include_once("app/security/StandardHashMethod.php");        // Voor het hashen van wachtwoorden
    include_once("app/security/Formvalidate.php");        // Ter controle van formulieren
    include_once("app/authentication.php");   // wordt gebruikt voor producten ophalen uit DB
    include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's

?>

<!doctype html>
<html class="no-js" lang="">

    <head>

        <link rel="stylesheet" type="text/css" href="css/forms.css.php">

        <?php

            //Hier include je de head-tag-template, alles wat in de header komt pas je aan in "tpl/head-tag-template.php"
            include("tpl/head-tag-template.php");

            // Als de gebruiker is ingelogd wordt hij uitgelogd.
            if (Authentication::isLoggedIn()) {
                Authentication::logout();
            }

        ?>

    </head>

    <body>

        <!-- Onze website werkt niet met Internet Explorer 9 en lager-->
        <!--[if IE]>
            <div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
        <![endif]-->

        <!-- Hierin  -->
        <div id="pagina-container">

            <!-- Print de header (logo, navigatiebalken, etc.)-->
            <?php
                include("tpl/header_template.php");
            ?>

            <!-- Inhoud pagina -->
            <div class="content-container-narrow">

                <div id="form-title">

                    <h1>
                        U bent uitgelogd.
                    </h1>

                    <p>
                        De uitlogprocedure is succesvol verlopen. U wordt nu doorgestuurd naar de startpagina.
                    </p>

                </div>

                <form method="post" action="index.php">

                    <div id="form-footer">

                        <button type="submit" class="btn btn-primary bootstrap-btn">Log in</button>

                    </div>

                </form>

                <meta http-equiv="refresh" content="0;index.php">

                <script type="text/javascript">
                    window.location = "index.php";
                </script>

            </div>

        </div>

        <div class="footer-container">

            <?php
                include("tpl/footer_template.php");
            ?>

        </div>

    </body>

</html>
