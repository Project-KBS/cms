<?php

// Include al onze benodigde php files
include_once '../../../app/common.php';
include_once '../../../app/constants.php';
include_once '../../../app/database.php';
include_once '../../../app/model/product.php';

// Laat de client weten dat we JSON-data geven
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verkrijg database connectie object.
$database = Database::getConnection();

// Check if de database connectie nog werkt.
// Als de database niet online is, of ./db/setup.sql nog niet uitgevoerd is, dan wordt er een standaard error gegeven.
// Om de exacte oorzaak te achterhalen, zet IS_DEBUGGING_ENABLED aan in constants.php!
if (is_null($database) || !Database::isConnectionValid($database)) {
    respond_error(503, "Error connecting with database.");
}

// Laat de user het aantal producten bepalen. // TODO limiteer met api key ???
$limit = DEFAULT_PRODUCT_RETURN_AMOUNT;
if (isset($_GET["limit"]) && intval($_GET["limit"]) > 0) {
    $limit = intval($_GET["limit"]);
}

// Waar op te zoeken? (verplicht veld)
$zoekterm = "";
if (isset($_GET["zoekterm"]) ) {
    $zoekterm = strval($_GET["zoekterm"]);
} else {
    respond_error(412, "No search keywords specified.");
}

$categorie = null;
if (isset($_GET["categorie"]) && intval($_GET["categorie"]) > 0) {
    $categorie = intval($_GET["categorie"]);
}

// Voer de mysql query uit met het aangegeven aantal (limiet), en verkrijg het prepared statement object.
// TODO order-by
$stmt = Product::zoek($database, $zoekterm, $categorie, "p.RecommendedRetailPrice ASC", 0, $limit);

// Als er 1 of meer producten gevonden zijn in de database
if ($stmt->rowCount()> 0) {

    $result = array("record_name" => "product");
    $result["records"] = array();

    // Zolang er resultaten zijn, append ze naar de $result array.
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Protected fields (alleen voor intern gebruik) worden NIET vrijgegeven hieronder:
        $item = array(
            "id"                 => $StockItemID,
            "name"               => $StockItemName,
            "supplier"           => $SupplierName,
            "color"              => $ColorName,
            "package_unit"       => $UnitPackageTypeName,
            "package_outer"      => $OuterPackageTypeName,
            "qty_per_outer"      => $QuantityPerOuter,
            "brand"              => $Brand,
            "size"               => $Size,
            "lead_time"          => $LeadTimeDays,
            "is_chill"           => $IsChillerStock,
            "barcode"            => $Barcode,
            "tax"                => $TaxRate,
            "price_unit"         => $UnitPrice,
            "price_recommended"  => $RecommendedRetailPrice,
            "weight"             => $TypicalWeightPerUnit,
            "comments_marketing" => $MarketingComments,
            "photo"              => $Photo,
            "custom_fields"      => $CustomFields,
            "tags"               => $Tags,
        );

        // Voeg dit record toe aan de $result array.
        array_push($result["records"], $item);
    }

    if (isset($_GET["short"])) {
        print(json_encode($result["records"]));
    } else {
        // Return producten
        respond_array(200, $result);
    }

// Geen producten gevonden, geef een error.
} else {
    respond_error(404, "No products found.");
}

?>
