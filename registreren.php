<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/account.php");        // wordt gebruikt voor database connectie
include_once("app/security/HashResult.php");        // wordt gebruikt voor database connectie
include_once("app/security/IHashMethod.php");        // Voor het hashen van wachtwoorden
include_once("app/security/StandardHashMethod.php");        // Voor het hashen van wachtwoorden
include_once("app/security/Formvalidate.php");        // Ter controle van formulieren

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


<div class="content-container-home">
    <!-- Inhoud pagina -->
    <h3>Registreren</h3>
    <br>

    <div >
        <?php

        //array maken voor de loop om input velden met text te maken
        $form = array(
            "Email"         => true,
            "Wachtwoord"    => true,
            "Voornaam"      => true,
            "Tussenvoegsel" => false,
            "Achternaam"    => true,
            "Straatnaam"    => true,
            "Huisnummer"    => true,
            "Toevoeging"    => false,
            "Postcode"      => true,
            "Woonplaats"    => true
        );

        ?>

        <!-- Form voor de inputs van gegevens om een account te maken -->
        <form id="register-form" method="post">
            <table cellpadding="10">

                <?php

                    foreach ($form as $index => $value) {

                    if ($index === "Email" || $index === "Voornaam" || $index === "Straatnaam" || $index === "Postcode") {
                        ?>
                        <!-- Begin van table rows om overeen te komen met schermontwerp-->
                        <tr>

                        <?php
                    }
                    ?>
                    <!-- Maakt in de table een print van de naam van het gevraagde gegeven-->
                    <td>

                        <?php
                            print($index);
                        ?>

                    </td>

                    <!-- Maakt de inputvelden in de table en verandert het type naar de gewenste soort input -->
                    <td>

                        <!--Bij debugging print hij test@test om makkelijker velden te auto fillen -->
                        <input
                            type="
                            <?php

                                if ($index === "Wachtwoord") {
                                    print("password");
                                } elseif ($index === "Email") {
                                    print("email");
                                } else {
                                    print("text");
                                }

                            ?>"


                            name="<?php print($index); ?>"


                            <?php

                                if (!IS_DEBUGGING_ENABLED) {
                                    print("placeholder='$index'");
                                } else {
                                    print("value='test@test'");
                                }

                                // Als de verplichte velden niet ingevuld zijn geeft hij aan dat ze nog ingevuld moeten worden
                                if ($index !== "Tussenvoegsel" && $index !== "Toevoeging") {
                                    print("required='required'");
                                }
                            ?>
                        >
                    </td>

                    <!-- Einde van table rows -->
                    <?php
                        if ($index === "Wachtwoord" || $index === "Achternaam" || $index === "Toevoeging" || $index === "Woonplaats") { ?>
                            </tr>
                        <?php
                    }
                }
                ?>


            </table>

            <input type="submit" value="registreren">

        </form>

    </div>

    <?php

    //De insert variabele wordt naar false gezet als een input niet geldig is
    $insert = true;

    foreach ($form as $index => $value) {
        // De $value uit form wordt opgeslagen als true of false om de required fields bij te houden
        if ($value === true){
            // Als een waarde niet is ingevuld zal de insert een false geven
            if (!isset($_POST[$index])) {
               $insert = false;
            } else {
                //Controleert voor elke waarde of ze voldoen aan de eisen van de grootte
                //Bij postcode wordt er controle gedaan of er 4 integers staan en daarna 2 alfabetische waarden
                //Bij huisnummer wordt een controle gedaan of het een integer is
                if (Form::$index($_POST[$index]) === false) {
                    $insert = false;
                    print("Foute ". $index. "<br>");
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
            header ("Location: url=inloggen.php");
            print("<meta http-equiv='refresh' content='0;url=inloggen.php'>");

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
