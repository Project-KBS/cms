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
    include_once("app/security/FormValidation.php");        // Ter controle van formulieren
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

            // Als de login gegevens meegegeven zijn in de POST, probeer inlog.
            if (isset($_POST["emailadres"]) && $_POST["emailadres"] != null &&
                isset($_POST["wachtwoord"]) && $_POST["wachtwoord"] != null) {

                // Check of de CSRF-protection token geldig is
                if (isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {
                    $result = Authentication::login(Database::getConnection(), $_POST["emailadres"], $_POST["wachtwoord"]);
                }
            }

            if (Authentication::isLoggedIn()) {
                // Als de gebruiker al ingelogd is, verstuur naar accountpagina.
                header("Location: account.php");
                print('<meta http-equiv="refresh" content="0;account.php">
                           <script type="text/javascript">
                               window.location = "account.php";
                           </script>');
                die();
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
                        Inloggen bij Wide World Importers
                    </h1>

                    <p>
                        Hier kunt u met uw persoonlijke account inloggen.
                    </p>

                </div>

                <!-- CSRF-protection omdat een aanvaller anders met een malicious account kan inloggen. -->
                <form id="form-main" class="form-main" method="post">

                    <?php

                        if (isset($result)) {

                            ?>

                                <div class="form-message btn btn-primary">

                                    Ongeldige inloggegevens.

                                </div>

                            <?php

                        }

                    ?>

                    <div class="form-group">

                        <label for="invoer-email" class="col-form-label-lg">
                            E-mailadres
                        </label>

                        <div class="input-group mb-2">

                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    @
                                </div>
                            </div>

                            <input type="email"
                                   id="invoer-email"
                                   name="emailadres"
                                   required aria-required="true"
                                   class="form-control form-control-lg"
                                   aria-describedby="emailHelp"
                                   placeholder="Voer hier uw e-mailadres in..."

                                    <?php

                                        if (isset($_POST["emailadres"]) && $_POST["emailadres"] != null) {
                                            printf('value="%s"', $_POST["emailadres"]);
                                        }

                                    ?>
                            />

                        </div>

                        <small id="emailHelp" class="form-text text-muted">
                            E-mail vergeten? Neem contact met ons op.
                        </small>

                    </div>

                    <div class="form-group">

                        <label for="invoer-wachtwoord" class="col-form-label-lg">
                            Wachtwoord
                        </label>

                        <div class="input-group mb-2">

                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    >
                                </div>
                            </div>

                            <input type="password"
                                   id="invoer-wachtwoord"
                                   required aria-required="true"
                                   name="wachtwoord"
                                   class="form-control form-control-lg"
                                   placeholder="Voer hier uw wachtwoord in..."
                            />

                        </div>

                        <small id="emailHelp" class="form-text text-muted">
                            Wachtwoord vergeten? Klik hier om te resetten.
                        </small>

                    </div>

                    <input type="hidden"
                           name="csrf_token"
                           value="<?php print($csrf_token);?>"
                    />

                    <div id="form-footer">

                        <button type="submit"
                                class="btn btn-primary bootstrap-btn">

                            Log in
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <div class="footer-container">

            <?php
                include("tpl/footer_template.php");
            ?>

        </div>

    </body>

</html>
