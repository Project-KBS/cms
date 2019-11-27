<?php
// Uit deze php bestanden gebruiken wij functies of variabelen:
include_once("app/vendor.php");          // wordt gebruikt voor website beschrijving
include_once("app/database.php");        // wordt gebruikt voor database connectie
include_once("app/model/categorie.php"); // wordt gebruikt voor categorieen ophalen uit DB
include_once("app/model/product.php");   // wordt gebruikt voor producten ophalen uit DB
include_once("app/mediaportal.php");     // wordt gebruikt voor categorie foto's
include_once("app/cart.php");            // wordt gebruikt om de cart-inhoud op te halen
?>

<!doctype html>
<html lang="en">
<head>
    <?php
        include ("tpl/head-tag-template.php");
    ?>
</head>
<body>
    <!-- Onze website werkt niet met Internet Explorer 9 en lager-->
    <!--[if IE]>
    <div id="warning" class="fixed-top"><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p></div>
    <![endif]-->

    <!-- Hierin  -->
    <div id="pagina-container">
        <header>
            <!-- Dit gedeelte van de header komt in een lijn te staan met de body content -->
            <div id="header-inline" class="responsive-container">
                <div id="promotie">
                    <a href="index.php"><img src="img/logo/small-250x90.png" alt="Logo"></a>
                </div>
            </div>
        </header>
        <div class="order-overview">
            <table>
                <?php
                    foreach (Cart::get() as $item => $aantal){
                        print($item . " " . $aantal . "<br>");

                        $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        extract($row);
                        print($item . " " . $aantal . " " . $stockItemName . "<br>");

                    }
                ?>
            </table>
        </div>

    </div>
    <footer>
        <?php
        //include("tpl/footer_template.php");
        ?>
    </footer>
</body>
</html>
