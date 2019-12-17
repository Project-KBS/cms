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
    include_once("app/security/FormValidation.php");        // Ter controle van formulieren
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

        // Het account is verwijderd maar de sessie is nog niet geupdatet (dit komt bijna nooit voor)
        if (!$row) {
            Authentication::logout();
            header("Location: index.php");
            print('<meta http-equiv="refresh" content="0;index.php">
                               <script type="text/javascript">
                                   window.location = "index.php";
                               </script>');
            die("Ongecontroleerde fout opgetreden.");
        }
        extract($row);

        if (IS_DEBUGGING_ENABLED) {
            print_r($row);
            print("<br><br>");
        }

        /**
         * @var array string => Field[]
         */
        $secties = array(
            "Authenticatie" => [new Field("Huidige_wachtwoord", "password", "",               null,               true),
                                new Field("Nieuwe_wachtwoord",  "password", "",               null,               true)],
            "Naam"          => [new Field("Voornaam",           "text",     "firstName",      $FirstName,         true),
                                new Field("Tussenvoegsel",      "text",     "middleName",     $MiddleName,        false),
                                new Field("Achternaam",         "text",     "lastName",       $LastName,          true)],
            "Adres"         => [new Field("Straatnaam",         "text",     "addrStreet",     $AddressStreet,     true),
                                new Field("Huisnummer",         "text",     "addrNumber",     $AddressNumber,     true),
                                new Field("Toevoeging",         "text",     "addrToevoeging", $AddressToevoeging, false),
                                new Field("Postcode",           "text",     "addrPostal",     $AddressPostalCode, true),
                                new Field("Woonplaats",         "text",     "addrCity",       $AddressCity,       true)]
        );

        $changedValues = array();

        // Check en update de values als ze gepost zijn.
        foreach ($secties as $naam => $veldenArray) {
            foreach ($veldenArray as $veld) {
                if (isset($_POST[$veld->getNaam()]) && $_POST[$veld->getNaam()] != null && strlen($veld->getId()) > 0) {
                    $changedValues[$veld->getId()] = $_POST[$veld->getNaam()];
                }
            }
        }

        if (IS_DEBUGGING_ENABLED) {
            var_dump($changedValues);
        }

        $nieuweWachtwoord = null;
        if (isset($_POST["Huidige_wachtwoord"]) && isset($_POST["Nieuwe_wachtwoord"])) {
            if (Authentication::login(Database::getConnection(), $email, strval($_POST["Huidige_wachtwoord"]))) {
                $nieuweWachtwoord = strval($_POST["Nieuwe_wachtwoord"]);
                $changedValues["new_pass"] = true;
            }
        }

        if (isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {

            if (count($changedValues) > 0) {
                Account::update(Database::getConnection(), $email, $nieuweWachtwoord, $changedValues);
                header("Location: account.php");
                print('<meta http-equiv="refresh" content="0;account.php">
                       <script type="text/javascript">
                           window.location = "account.php";
                       </script>');
                die("Refresh de pagina a.u.b.");
            }

        }

        // Genereer de HTML layout
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
                                            <?php print(str_replace("_", " ", $veld->getNaam())); ?>
                                        </span>

                                        <input name="<?php print($veld->getNaam()); ?>"
                                               type="<?php print($veld->getType()); ?>"
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

                    <input type="hidden"
                           name="csrf_token"
                           value="<?php print($csrf_token);?>"
                    />

                </form>

            </div>

            <?php

        }

        ?>

        <div id="account-actions">

            <div id="account-actions-delete" style="margin: 1.4rem 0">

                <form action="" method="post">

                    <input type="hidden"
                           name="action"
                           value="delete"
                    />

                    <input type="hidden"
                           name="csrf_token"
                           value="<?php print($csrf_token);?>"
                    />

                    <div class="row">

                        <!-- Opvuller -->
                        <div class="col-9">

                        </div>

                        <div class="col-3">

                            <input type="submit"
                                   value="Verwijder account"
                                   class="btn btn-danger w-100"
                            />

                        </div>

                    </div>
                </form>

            </div>

        </div>

        <?php

            // Als de gebruiker op een actieknop heeft gedrukt
            if (isset($_POST["action"]) && strlen(strval($_POST["action"])) > 0) {

                // Als de CSRF token goed is
                if (isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {

                    switch (strval($_POST["action"])) {
                        // Verwijder account
                        case "delete":
                            Account::delete(Database::getConnection(), $email);
                            Authentication::logout();
                            header("Location: account.php");
                            print('<meta http-equiv="refresh" content="0;account.php">
                                  <script type="text/javascript">
                                      window.location = "account.php";
                                  </script>');
                            break;
                    }
                }

            }

        ?>

    </div>

</div>


<div class="footer-container">

    <?php
        include("tpl/footer_template.php");
    ?>

</div>


</body>
</html>
