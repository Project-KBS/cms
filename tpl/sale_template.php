<?php
    /**
     * Dit is enkel en alleen een set-up voor de aanbiedingen banner,
     * op het moment doet het nog niks, als we weten hoe de database werkt wat dat betreft kunnen we het fixen.
     * Ik heb op dit moment nog geen idee hoe het tot stand gebracht gaat worden, dus ik maak nu zo veel als ik kan.
     * In de read() óf zoek() function moet een filter komen waarin je alleen de SpecialDealID komt
    */
    //Roepts de zoek() function aan:
    $limit = 2;
    $stmt = (Product::zoek(Database::getConnection(), $limit ));

    //Voor elk gevonden item doet hij alles binnen de while-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //extract zorgt er voor dat je alle collumn namen kan gebruiken al variabelen
        extract($row);

        ?>
        <!-- Print dit resultaat -->
        <a href='product.php?id=<?php print($StockItemID)?>' class='SearchProductDisplayLink'>
            <div class='row ProductDisplay'>
                <div class='col-6 ProductDisplayLeft'>
                    <!--foto van het product wordt geladen -->
                    <img src="data:image/png;base64,<?php print($Photo)?>">
                    <!--de png file wordt geladen en over de foto van het product geplakt.
                    een png file is een soort foto, maar alles wat niet is ingekleurd is doorzichtig, hierdoor kun je een mooie overlay maken.-->
                    <img src="/png/sales.png">

                </div>
                <div class='col-6 ProductDisplayRight'>
                    <h3><?php print($StockItemName)?></h3>
                    <p><?php print($SearchDetails)?></p>
                    <div class='ProductDisplayPrice'>
                        <h5>Prijs: <?php print(round($RecommendedRetailPrice * (1 + $TaxRate / 100), 2))?></h5>
                    </div>
                </div>
            </div>
        </a>

        <?php
    }
    ?>
