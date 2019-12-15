<!-- Header die aan de bovenkant van de pagina bevindt -->
<div id="header">

    <!-- Dit gedeelte van de header komt in een lijn te staan met de body content -->
    <div id="header-inline" class="responsive-container">
        <div id="promotie">
             <a href="index.php">
                 <img src="img/logo/small-250x90.png" alt="Logo">
             </a>
        </div>
     </div>

    <!-- Navigatie balk met website navigatie (home, contact, etc..)-->
    <div id="navigatie-site">

       <!-- De container beperkt de items tot 70% van de schermbreedte-->
        <div id="navigatie-site-container" class="responsive-container">

            <!-- De zoekbalk -->
            <form autocomplete="off" action="search.php?page=1" name="zoekForm" method="get">

                <a>
                    <input type="text" placeholder="Typ om te zoeken" name="search" id="search" style="margin-right: 0; padding-right: 0;">
                </a>

                <a>
                    <input type="submit" value="Search" name="knop" id="knop">
                </a>

            </form>

            <a href="winkelmand.php" class="flex-push">
                <div>
                    Winkelmandje

                <?php
                    $telling = 0;

                    foreach(Cart::get() as $index => $value){
                        $telling += $value;
                    }

                    if ($telling > 0) {
                        if ($telling < 1000) {
                            print("(".$telling.")");
                        } else {
                            print("(1.000+)");
                        }
                    }

                ?>

                </div>
            </a>

            <?php
                if (Authentication::isLoggedIn()) {
            ?>

                <a id="button-account" href="account.php">
                    <div>
                        Mijn Account
                    </div>
                </a>

                <a id="button-uitloggen" href="uitloggen.php">
                    <div>
                        Uitloggen
                    </div>
                </a>

            <?php
                } else {
            ?>

                <a id="button-inloggen" href="inloggen.php">
                    <div>
                        Inloggen
                    </div>
                </a>

                <a id="button-registreren" href="registreren.php">
                    <div>
                        Registreren
                    </div>
                </a>

            <?php
                }
            ?>

        </div>

    </div>

    <!-- Navigatie balk met categorieen -->
    <div id="navigatie-categorieen">

        <?php
            // Alle SQL magie en PDO connectie shit gebeurt in `Product::read` dus in deze file hebben we geen queries meer nodig. We kunnen direct lezen van de statement zoals hieronder.
            $stmt = (Categorie::read(Database::getConnection()));

            // Per rij die we uit de database halen voeren we een stukje code uit
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                // Dit zorgt er voor dat we alle database attributen kunnen gebruiken als variabelen
                // (bijv. kolom "StockItemName" kunne we gebruiken in PHP als "$StockItemName") (PHPStorm geeft rood streepje aan maar het werkt wel)
                extract($row);

                // Print een HTML element met de naam en een link naar de pagina (de %s worden vervangen door de variabelen na de komma)
                printf("<a href=\"search.php?Hoeveelheid=%s&Sort=%s&Categorie=%s&search=&submit=ok\"><div>%s</div></a>", DEFAULT_PRODUCT_RETURN_AMOUNT, "NaamASC", $StockGroupID, $StockGroupName);
            }
        ?>

    </div>

</div>
