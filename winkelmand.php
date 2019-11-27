<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
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

    <!-- Print de header (logo, navigatiebalken, etc.)-->
    <?php
    include("tpl/header_template.php");
    ?>

    <!-- Inhoud pagina -->
    <div class="content-container-home">
       <div id="geheel" class="row">

           <!-- Links komen de foto's en informatie van het product-->
           <div id="links" class="col-6">

           </div>

           <!-- hier komen de aantallen en totaalprijzen-->
           <div id="midden" class="col-6">

           </div>


           <!-- Bestelknop komt hier met foto's van betalmethoden-->
           <div id="rechts" class="col-6">

           </div>
       </div>
    </div>

</div>

<div class="footer-container">
    <?php
    include("tpl/footer_template.php");
    ?>

</div>
</body>
</html>
