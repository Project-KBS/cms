<?php

    // Uit deze php bestanden gebruiken wij functies of variabelen:
    include_once("app/authentication.php");  // Accounts en login
    include_once("app/constants.php");          // wordt gebruikt voor website beschrijving
    include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
    include_once("app/database.php");        // wordt gebruikt voor database connectie
    include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
    include_once("app/model/account.php");        // wordt gebruikt voor database connectie
    include_once("app/security/HashResult.php");        // wordt gebruikt voor database connectie
    include_once("app/security/IHashMethod.php");        // Voor het hashen van wachtwoorden
    include_once("app/security/StandardHashMethod.php");        // Voor het hashen van wachtwoorden
    include_once("app/security/Formvalidate.php");        // Ter controle van formulieren
    include_once("app/field.php");                          // voor field in array

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $email = Authentication::getEmail();
    if (!Authentication::isLoggedIn() || $email == null) {
        header("Location: index.php", true, 303);
        die();
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
    <h3>Account informatie</h3><br>
    <p>Op deze pagina kunt u de gegevens wijzigen en inzien welke gelinkt zijn aan uw persoonlijke account.</p>

    <div>
        <?php

        // Oproepen van de huidige gegevens die dan weer geprint zullen worden
        $stmt = Account::get(Database::getConnection(), $email);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        if (IS_DEBUGGING_ENABLED) {
            print_r($row);
        }


        /**
         * @var array string => Field[]
         */
        $secties = array(
            "Authenticatie" => [new Field("Huidige wachtwoord", null,          true),
                                new Field("Nieuwe wachtwoord",  null,          true)],
            "Naam"          => [new Field("Voornaam",           $FirstName,         true),
                                new Field("Tussenvoegsel",      $MiddleName,        false),
                                new Field("Achternaam",         $LastName,          true)],
            "Adres"         => [new Field("Straatnaam",         $AddressStreet,     true),
                                new Field("Huisnummer",         $AddressNumber,     true),
                                new Field("Toevoeging",         $AddressToevoeging, false),
                                new Field("Postcode",           $AddressPostalCode, true),
                                new Field("Woonplaats",         $AddressCity,       true)]
        );

        foreach ($secties as $naam => $veldenArray) {

            ?>

            <hr style="margin: 1.5rem 0" />

            <div id="account-form-<?php print($naam); ?>">

                <h4 class="account-form-title"
                    style="margin-bottom: 1.0rem">
                    <?php print($naam); ?>
                </h4>

                <!-- Form voor de inputs van gegevens om een account te maken -->
                <form action="" method="post">

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
                                                   if ($veld->getVar() != null) {
                                                       printf('value="%s"', $veld->getVar());
                                                   }
                                                   if ($veld->isRequired()) {
                                                       print("required");
                                                   }
                                               ?>
                                        />

                                    </label>

                                </div>

                            <?php

                        }

                    ?>

                    </div>

                    <div class="row">

                        <!-- Opvuller -->
                        <div class="col-9">

                        </div>

                        <div class="col-3">
                            <input type="submit"
                                   value="Update informatie"
                                   class="btn btn-secondary w-100"
                                   style="margin-top: 1.2rem">
                        </div>

                    </div>

                </form>

            </div>

            <?php

        }

        ?>

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


    ?>

</div>


<div class="footer-container">
    <?php
    include("tpl/footer_template.php");
    ?>

</div>


</body>
</html>
