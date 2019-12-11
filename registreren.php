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
            "Email",
            "Wachtwoord",
            "Voornaam",
            "Tussenvoegsel",
            "Achternaam",
            "Straatnaam",
            "Huisnummer",
            "Toevoeging",
            "Postcode",
            "Woonplaats"
        );
        ?>

        <form id="register-form" method="post">
            <table cellpadding="10">

            <?php foreach($form as $index => $value){
                switch($value){


                }
                if($value === "Email" || $value === "Voornaam" || $value === "Straatnaam" || $value === "Postcode"){
                    ?>
                    <tr>

                    <?php
                }
                ?>

                    <td>

                    <?php
                print($value); ?>

                </td>
                <td>

                <input type="
                <?php
                switch($value){
                    case "Email":
                    print("email");
                    break;

                    case "Wachtwoord":
                    print("password");
                    break;

                    default;
                        print("text");
                        break;

                } ?>"
                       name="<?php print($value); ?>"

                    <?php if (!IS_DEBUGGING_ENABLED) {
                    print("placeholder='$value'");
                    } else {
                    print("value='test@test'");
                    } if ($value != "Tussenvoegsel") {
                        print("required='required'");
                } ?>
                >

                </td>

                <?php if($value === "Wachtwoord" || $value === "Achternaam" || $value === "Toevoeging" || $value === "Woonplaats"){ ?>
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
    account::insert($_POST["Email"], $_POST["Wachtwoord"], $_POST["Voornaam"], $_POST["Tussenvoegsel"], $_POST["Achternaam"],
        $_POST["Straatnaam"], $_POST["Huisnummer"], $_POST["Toevoeging"], $_POST["Woonplaats"], $_POST["Postcode"], " ", " ");




    ?>
</div>


    <div class="footer-container">
        <?php
        include("tpl/footer_template.php");
        ?>

    </div>


</body>
</html>
