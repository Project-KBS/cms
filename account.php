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
    <h3>Account informatie</h3><br>

    <div>
        <?php


        // Oproepen van de huidige gegevens die dan weer geprint zullen worden
        $stmt = Account::get(Database::getConnection(),"test@test");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        print_r($row);


        //array maken voor de loop om input velden met text te maken
        $form = array(
            "Wachtwoord" => 0,
            "Nieuwe Wachtwoord" => 0,
            "Voornaam" => $FirstName ,
            "Tussenvoegsel" => $MiddleName,
            "Achternaam" => $LastName,
            "Straatnaam" => $AddressStreet,
            "Huisnummer" => $AddressNumber,
            "Toevoeging" => $AddressToevoeging,
            "Postcode" => $AddressPostalCode,
            "Woonplaats" => $AddressCity
        );
        ?>

        <!-- Form voor de inputs van gegevens om een account te maken -->
        <form id="account-form" method="post">
            <table cellpadding="10">

                <?php foreach ($form as $index => $value) {


                    if ($index === "Wachtwoord" || $index === "Voornaam" || $index === "Straatnaam" || $index === "Postcode") {
                        ?>
                        <!-- Begin van table rows om overeen te komen met schermontwerp-->
                        <tr>

                        <?php
                    }
                    ?>
                    <!-- Maakt in de table een print van de naam van het gevraagde gegeven-->
                    <td>

                        <?php
                        print($index); ?>

                    </td>

                    <!-- Maakt de inputvelden in de table en verandert het type naar de gewenste soort input -->
                    <td>

                        <!--Bij debugging print hij test@test om makkelijker velden te auto fillen -->
                        <input
                            type="<?php if ($index === "Wachtwoord" || $index ==="Nieuwe Wachtwoord") {
                                print("password");
                            } else {
                                print("text");
                            } ?>"

                            name="<?php print($value); ?>"

                            <?php
                            if($index === "Wachtwoord" || $index ==="Nieuwe Wachtwoord"){

                            } else{
                                if (!IS_DEBUGGING_ENABLED) {
                                    //Moet getten uit de database en dan huidige informatie hier invullen
                                    print("value = '$value'");
                                } else {
                                    print("value='test@test'");
                                }
                            }

                            // Als de verplichte velden niet ingevuld zijn geeft hij aan dat ze nog ingevuld moeten worden
                            if ($index === "Tussenvoegsel" || $index === "Toevoeging" || $index === "Nieuwe Wachtwoord") {
                            } else print("required='required'");
                            ?>>
                    </td>

                    <!-- Einde van table rows -->
                    <?php if ($index === "Nieuwe Wachtwoord" || $index === "Achternaam" || $index === "Toevoeging" || $index === "Woonplaats") { ?>
                        </tr>
                        <?php
                    }
                }
                ?>


            </table>

            <input type="submit" value="Update informatie">

        </form>

    </div>

    <?php

    if(isset($_POST["Wachtwoord"])){
        $insert = true;



        if($_POST["Wachtwoord"] === "Huidige wachtwoord die nog moet opvragen uit database"){

            if ($insert === true) {

                try{
                    //Account::update(Database::getConnection(), ;
                }

                catch(PDOException $exception) {
                    // Bij een fout in het proces krijg je ongeldige input terug
                    print("Ongeldige input");

                }



            }
        } else{
            print("Verkeerd wachtwoord");
        }
    }




/*
    //De insert variabele wordt naar false gezet als een input niet geldig is
    $insert = false;

    foreach($form as $index => $value) {
        // De $value uit form wordt opgeslagen als true of false om de required fields bij te houden
        if($value === true){
            // Als een waarde niet is ingevuld zal de insert een false geven
            if (!isset($_POST[$index])) {
                $insert = false;
            } else {
                //Controleert voor elke waarde of ze voldoen aan de eisen van de grootte
                //Bij postcode wordt er controle gedaan of er 4 integers staan en daarna 2 alfabetische waarden
                //Bij huisnummer wordt een controle gedaan of het een integer is
                if( Form::$index($_POST[$index]) === false ){
                    $insert = false;
                    print("Foute ". $index. "<br>");
                }

            }
        }
    }
    // Als aan alle checks wordt voldaan zal er een poging worden gedaan om te inserten in de database
    if ($insert === true) {

        try{
            Account::update(Database::getConnection(), $_POST["Email"], $_POST["Wachtwoord"], $_POST["Voornaam"], $_POST["Tussenvoegsel"], $_POST["Achternaam"],
                $_POST["Straatnaam"], $_POST["Huisnummer"], $_POST["Toevoeging"], $_POST["Woonplaats"], $_POST["Postcode"], " ", " ");
        }

        catch(PDOException $exception) {
            // Bij een fout in het proces krijg je ongeldige input terug
            print("Ongeldige input");

        }

    }
*/
    ?>

</div>


<div class="footer-container">
    <?php
    include("tpl/footer_template.php");
    ?>

</div>


</body>
</html>
