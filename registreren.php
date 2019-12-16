<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/authentication.php");          // wordt gebruikt voor website beschrijving
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/account.php");        // wordt gebruikt voor database connectie
include_once("app/security/HashResult.php");        // wordt gebruikt voor database connectie
include_once("app/security/IHashMethod.php");        // Voor het hashen van wachtwoorden
include_once("app/security/StandardHashMethod.php");        // Voor het hashen van wachtwoorden
include_once("app/security/Formvalidate.php");        // Ter controle van formulieren
include_once("app/field.php");                          // voor field in array

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

<!-- Hierin  -->
<div id="pagina-container">
    <div>

        <!-- Print de header (logo, navigatiebalken, etc.)-->
        <?php
            include("tpl/header_template.php");
        ?>

    </div>
</div>


<div class="content-container-narrow">
    <!-- Inhoud pagina -->




    <?php

    //array maken voor de loop om input velden met text te maken
    $secties = array(
        "Inloggegevens" => [new Register("Email",          true),
                            new Register("Wachtwoord",     true)],

        "Naam"          => [new Register("Voornaam",       true),
                            new Register("Tussenvoegsel",   false),
                            new Register ("Achternaam",     true)],

        "Adres"         =>[ new Register("Straatnaam",     true),
                            new Register("Huisnummer",     true),
                            new Register("Toevoeging",     false),
                            new Register("Postcode",       true),
                            new Register("Woonplaats",     true)]
    );



 ?>

    <div>
        <!-- Form voor de inputs van gegevens om een account te maken -->
        <form id="register-form" method="post" action="registreren.php">

        <?php
        foreach ($secties as $naam => $veldenArray) {

    ?>


        <hr style="margin: 1.5rem 0"/>

        <div>

            <h4 class="account-form-title"
                style="margin-bottom: 1.0rem">
                <?php print($naam); ?>
            </h4>



                <div class="row">

                    <?php

                    foreach ($veldenArray as $veld) {
                        ?>

                        <div class="account-form-field-container col-4">

                            <label class="account-form-field-label w-100">

                                        <span class="account-form-field-title"
                                              style="color: <?php print(VENDOR_THEME_COLOR_TEXT_DISABLED); ?>">
                                            <?php print($veld->getNaam()); ?>
                                        </span>

                                <input type="text"
                                       class="account-form-field-input form-control w-100"
                                    <?php

                                    if ($veld->isRequired()) {
                                        print("required");
                                    }
                                    ?>
                                />


                            </label>

                        </div>


                    <?php } ?>

                </div>

        </div>

        <?php } ?>

                <div class="row">

                    <!-- Opvuller -->
                    <div class="col-9">

                    </div>

                    <div class="col-3">
                        <input type="submit"
                               value="Registreer"
                               class="btn btn-secondary w-100"
                               style="margin-top: 1.2rem">
                    </div>
                </div>



        </form>

    </div>




    <?php


    //De insert variabele wordt naar false gezet als een input niet geldig is
    $insert = true;

    foreach ($secties as $naam => $veldArray) {


        foreach($veldArray as $veld){
            // De $value uit form wordt opgeslagen als true of false om de required fields bij te houden
            if ($veld->isRequired()){
                // Als een waarde niet is ingevuld zal de insert een false geven
                if (!isset($_POST[$veld->getNaam()])) {
                    $insert = false;
                } else {
                    //Controleert voor elke waarde of ze voldoen aan de eisen van de grootte
                    //Bij postcode wordt er controle gedaan of er 4 integers staan en daarna 2 alfabetische waarden
                    //Bij huisnummer wordt een controle gedaan of het een integer is
                    if (Form::$veld->getNaam()($_POST[$veld->getNaam()]) === false) {
                        $insert = false;
                        print("Foute ". $veld->getNaam(). "<br>");
                    }
                }
            }
        }
    }

    // Als aan alle checks wordt voldaan zal er een poging worden gedaan om te inserten in de database
    if ($insert === true) {

        try {
            Account::insert(Database::getConnection(), $_POST["Email"], $_POST["Wachtwoord"], $_POST["Voornaam"], $_POST["Tussenvoegsel"], $_POST["Achternaam"],
                                                       $_POST["Straatnaam"], $_POST["Huisnummer"], $_POST["Toevoeging"], $_POST["Woonplaats"], $_POST["Postcode"], " ", " ");

            // Verstuur de gebruiker naar het login scherm (header werkt meestal niet omdat er al data verstuurd is)
            header("Location: inloggen.php");
            print("<meta http-equiv='refresh' content='0;inloggen.php'>");

        } catch(PDOException $exception) {
            // Bij een fout in het proces krijg je ongeldige input terug
            print("Ongeldige input, of er bestaat al een account met dit e-mailadres.");

            // Als debug modus aan staat print een heerlijke foutmelding
            if (IS_DEBUGGING_ENABLED) {
                print($exception->getMessage());
            }
        }

    }


    ?>

</div>


<div class="footer-container">

    <?php
        include("tpl/footer_template.php");
    ?>

</div>

</body>
</html>
