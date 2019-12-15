<?php

include_once("app/authentication.php");
include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");
include_once("app/model/invoice.php");
include_once("app/model/order.php");
include_once("app/model/orderline.php");
include_once("app/model/categorie.php");
include_once("app/model/product.php");
include_once("app/database.php");
include_once("app/cart.php");
include_once("app/vendor.php");

$mollie = Mollie::getApi();
// bij deze pagina moet een GET parameter genaamd "orderId" zijn met integer value hoger dan 0.

// check of orderId aanwezig is in URL
if (!isset($_GET["orderId"])) {
    exit("Order ID is niet aanwezig!");
}

$orderId = intval($_GET["orderId"]);

// check of orderId een getal is boven de nul
if (filter_var($orderId, FILTER_VALIDATE_INT) == false || $orderId <= 1) {
    exit("Order ID is ongeldig!");
}

// check of de betaling bestaat in de database en verkrijg de paymentId (dit heet CustomerPurchaseOrderNumber in de DB!)
$stmt = Invoice::get(Database::getConnection(), $orderId);
extract($stmt->fetch());

if (!isset($CustomerPurchaseOrderNumber)) {
    exit("Geen order gevonden met het opgegeven ID!!!");
}

$paymentId = $CustomerPurchaseOrderNumber;

try {
    $payment = $mollie->payments->get($paymentId);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    print($e->getMessage());
    exit("Order ID bestaat niet!");
}

?>

<!doctype html>
<html lang="en">

<head>
    <?php
        include("tpl/head-tag-template.php");

        if ($payment->isPaid()) {
            session_destroy();
        }
    ?>
</head>

<body>

<header>
    <?php
        include("tpl/header_template.php");
    ?>
</header>

<div id="pagina-container">
    <div class="content-container-home">
        <?php

        //Laat elk product in het winkelmandje zien
        // check of de betaling geslaagd is
        if ($payment->isPaid()) {
            ?>

            <h2>
                Bedankt voor uw bestelling! De betaling is succesvol afgerond!
            </h2>

            <br><hr>

        <?php

            $database = Database::getConnection();
            // Verkrijg
            $stmt = OrderLine::get($database, $orderId);

            // Zolang er producten in de order lines staan
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                extract((Product::getbyid($database, $StockItemID, 1))->fetch(PDO::FETCH_ASSOC));

                ?>
                <div id="order-overview" class="row" style="padding-bottom: 2vh">

                    <div id="photo" class="col-2">
                        <img style="width: 100%" src="data:image/png;base64, <?php
                                                                         if (isset($Photo) && $Photo != null) {
                                                                             print($Photo);
                                                                         } else {
                                                                             print(MediaPortal::getCategoryImage($StockItemID));
                                                                         }
                                                                     ?>"
                             alt="Productfoto"
                        />
                    </div>

                    <div id="product-info" class="col-6">

                        <div class="ProductMand">

                            <div>
                                <h4>
                                    <?php print($StockItemName); ?>
                                </h4>
                            </div>

                            <div>
                                <?php print($MarketingComments); ?>
                            </div>

                        </div>

                        <div id="filler" class="col-2 centerDivText">

                        </div>
                    </div>

                    <div id="aantalInMand" class="col-2 centerDivText">
                        <div class="col-1">
                            Aantal: <?php print($Quantity); ?>
                        </div>
                    </div>

                </div>
                <hr>
                <?php
             }

        } else {

            ?>

            <h2>
                Betaling van <?php print($payment->amount->value . " " .$payment->amount->currency); ?> is niet geslaagd.
            </h2>

            <?php

        }
        ?>
    </div>
</div>
</body>
</html>


