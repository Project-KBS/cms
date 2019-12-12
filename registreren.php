<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/account.php");        // wordt gebruikt voor database connectie
include_once("app/security/HashResult.php");        // wordt gebruikt voor database connectie
include_once("app/security/IHashMethod.php");        // wordt gebruikt voor database connectie
include_once("app/security/StandardHashMethod.php");        // wordt gebruikt voor database connectie

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
    <h3>Registreren</h3><br>

    <div >
        <?php
        $form = array(
            "Email" => true,
            "Wachtwoord" => true,
            "Voornaam" => true,
            "Tussenvoegsel" => false,
            "Achternaam" => true,
            "Straatnaam"=> true,
            "Huisnummer" => true,
            "Toevoeging" => false,
            "Postcode" => true,
            "Woonplaats" => true
        );
        ?>

        <form id="register-form" method="post">
            <table cellpadding="10">

                <?php foreach ($form as $index => $value) {

                    if ($index === "Email" || $index === "Voornaam" || $index === "Straatnaam" || $index === "Postcode") {
                        ?>
                        <tr>

                        <?php
                    }
                    ?>
                    <td>

                        <?php
                        print($index); ?>

                    </td>
                    <td>

                        <input
                            type="<?php if ($index === "Wachtwoord") {
                                print("password");
                            } elseif ($index === "Email") {
                                print("email");
                            } else {
                                print("text");
                            } ?>"

                            name="<?php print($index); ?>"

                            <?php if (!IS_DEBUGGING_ENABLED) {
                                print("placeholder='$index'");
                            } else {
                                print("value='test@test'");
                            }
                            if ($index === "Tussenvoegsel" || $index === "Toevoeging") {
                            } else print("required='required'");
                            ?>>
                    </td>

                    <?php if ($index === "Wachtwoord" || $index === "Achternaam" || $index === "Toevoeging" || $index === "Woonplaats") { ?>
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
    $int = intval($_POST["Huisnummer"]);

    if($int <= 0){
        print("Foutmelding");
    } else{



    $insert = true;
    foreach($form as $index => $value) {
            if($value === true){
                if (isset($_POST["$value"])) {

                    $insert = false;
                }

            }

    }
    if($insert === true){
        try{Account::insert(Database::getConnection(), $_POST["Email"], $_POST["Wachtwoord"], $_POST["Voornaam"], $_POST["Tussenvoegsel"], $_POST["Achternaam"],
            $_POST["Straatnaam"], $_POST["Huisnummer"], $_POST["Toevoeging"], $_POST["Woonplaats"], $_POST["Postcode"], " ", " ");
            } catch(PDOException $exception){
            print("Ongeldige input");
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
