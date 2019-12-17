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
                    <input type="text"
                           placeholder="Typ om te zoeken"
                           name="search"
                           id="search"
                           style="margin-right: 0; padding-right: 0;">

                    <div id="search-results"
                         class="flex-column">

                    </div>
                </a>

                <script>
                        const jq_search_results = $('#search-results');
                        const div_search_results = jq_search_results[0];
                        const jq_search_bar = $('#search');
                        const div_search_bar = document.getElementById("search");//jq_search_bar[0];

                        function functie_update_breedte() {
                            jq_search_results.css("width", jq_search_bar.width() + div_search_bar.paddingLeft);
                        }

                        $(window).resize(functie_update_breedte());
                        $(document).ready(functie_update_breedte());

                        function refresh() {
                            let zoekterm = div_search_bar.value.trim();

                            if (zoekterm.length > 0) {

                                // Voer een nieuwe AJAX query uit naar de search product endpoint
                                $.ajax({
                                    type: 'GET',
                                    url: '/api/v1/product/search.php',
                                    data: {
                                        zoekterm: zoekterm,
                                        limit: 5,
                                        short: true
                                    },
                                    dataType: 'json',
                                    success: function (data) {
                                        // Verwijder alle huidige elementen uit de zoekresultaten
                                        div_search_results.innerHTML = "";

                                        // for-each loop voor alle gereturnde producten
                                        $.each(data, function (index, element) {
                                            // Maak een nieuw flex child aan voor ieder product
                                            $("<a href='/product.php?id=" + element.id + "' class='w-100' '>" +
                                                  "<div class='row search-results-entry w-100'>" +
                                                      "<div class='col-3'><img src='data:image/png;base64," + element.photo + "'/></div>" +
                                                      "<div class='col-7'><p class='w-100' style='text-align: left'>" + element.name + "</p></div>" +
                                                      "<div class='col-2 search-results-entry-price'>€" + element.price_recommended + "</div>" +
                                                  "</div>" +
                                              "</a>").appendTo(jq_search_results);
                                        });
                                    }
                                });
                            } else {
                                // Verwijder alle elementen uit de zoekresultaten
                                div_search_results.innerHTML = "";

                                $("<div class='search-results-entry'>" +
                                      "<p>Voer een zoekterm in...</p>" +
                                  "</div>").appendTo(jq_search_results);
                            }
                        }

                        window.onload = function() {
                            jq_search_results.hide();
                        };

                        jq_search_bar.keyup(function () {
                            refresh();
                        });

                        div_search_bar.onfocus = function() {
                            jq_search_results.fadeIn(80);
                        };

                        div_search_bar.onblur = function() {
                            jq_search_results.fadeOut(80);
                        };

                        refresh();

                </script>

                <a>
                    <input type="submit"
                           value="Search"
                           name="knop"
                           id="knop"
                           style="border-radius: 0 !important;">
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
