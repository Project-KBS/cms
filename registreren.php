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

    //array maken voor de loop om naam, type en een boolean voor required mee te geven
    $secties = array(
        "Inloggegevens" => [new Register("Email",           "email",        true),
                            new Register("Wachtwoord",      "password",     true)],

        "Naam"          => [new Register("Voornaam",        "text",         true),
                            new Register("Tussenvoegsel",   "text",         false),
                            new Register ("Achternaam",     "text",         true)],

        "Adres"         =>[ new Register("Straatnaam",      "text",         true),
                            new Register("Huisnummer",      "text",         true),
                            new Register("Toevoeging",      "text",         false),
                            new Register("Postcode",        "text",         true),
                            new Register("Woonplaats",      "text",         true)]
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

                <!-- De titels van tussenkopjes-->
                <h4 class="account-form-title"
                    style="margin-bottom: 1.0rem">
                    <?php print($naam); ?>
                </h4>



                <div class="row">

                    <?php

                    // voor elk veld wordt een input gemaakt en de naam geprint
                    foreach ($veldenArray as $veld) {
                        ?>

                        <div class="account-form-field-container col-4">

                            <label class="account-form-field-label w-100">

                                        <span class="account-form-field-title"
                                              style="color: <?php print(VENDOR_THEME_COLOR_TEXT_DISABLED); ?>">
                                            <?php print($veld->getNaam()); ?>
                                        </span>

                                <!-- Past de input type aan naar de waarde aangegeven in de array $secties-->
                                <input name="<?php print($veld->getNaam()); ?>"
                                       type="<?php print($veld->getType()); ?>"
                                       class="account-form-field-input form-control w-100"
                                    <?php

                                    // Bij een foute submit wordt de waarde die in de velden stond opnieuw geprint zodat niet alles opnieuw getypt moet worden
                                    if(isset($_POST[$veld->getNaam()])){
                                        printf('value="%s"', $_POST[$veld->getNaam()]);
                                    }

                                    // In de array $secties staan booleans die hier worden gebruikt voor een true of false bij required veld
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

                    <!-- Submit knop -->
                    <div class="col-3">
                        <input type="submit"
                               value="Registreer"
                               class="btn btn-primary w-100 bootstrap-btn"
                               style="margin-top: 1.2rem">
                    </div>
                </div>



        </form>

    </div>




    <?php


    //De insert variabele wordt naar false gezet als een input niet geldig is
    $insert = true;

    //loop om elke sectie te checken
    foreach ($secties as $naam => $veldArray) {

        //loop om een controle te voeren over alle ingevulde velden
        foreach($veldArray as $veld) {

            // uit de array $secties worden de booleans opgevraagd om te kijken of de velden verplicht waren
            // indien verplicht wordt gecontroleerd of deze is ingevuld
            // Als een waarde niet is ingevuld zal de insert een false geven
            if (!isset($_POST[$veld->getNaam()]) && $veld->isRequired()) {
                $insert = false;
            } else {
                //Controleert voor elke waarde of ze voldoen aan de eisen van de grootte
                //Bij postcode wordt er controle gedaan of er 4 integers staan en daarna 2 alfabetische waarden
                //Bij huisnummer wordt een controle gedaan of het een integer is
                // Lokale var moet aangemaakt worden anders verkloot PHP het.
                if (isset($_POST[$veld->getNaam()])) {
                    $str = $veld->getNaam();

                    if (Form::$str($_POST[$str]) === false) {
                        $insert = false;
                        print("Foute " . $str . "<br>");
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
