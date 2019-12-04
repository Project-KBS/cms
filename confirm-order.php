<?php

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");
include_once("app/model/invoice.php");
include_once("app/database.php");
include_once("app/model/categorie.php");
include_once("app/cart.php");
include_once("app/vendor.php");

?>

<!doctype html>
<html lang="en">
<head>
    <?php  include("tpl/head-tag-template.php");

    ?>
</head>

<body>
<header>
    <?php include("tpl/header_template.php");

    ?>
</header>
<div id="pagina-container">
    <div class="content-container-home">
        <?php

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
            exit("Order ID bestaat niet!");
        }





        //Laat elk product in het winkelmandje zien
        // check of de betaling geslaagd is
        if ($payment->isPaid()) {
            echo "BETALING IS GESLAAGD!!!! Je hebt betaald met " . $payment->method . " en wij hebben " . $payment->amount->value . " " . $payment->amount->currency . " ontvangen!!";

            foreach(Cart::get() as $item => $aantal) {
                $stmt = Product::getbyid(Database::getConnection(), $item, 5);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                extract($row);

                ?>
                <div id="order-overview" class="row" style="padding-bottom: 2vh">
                    <div id="photo" class="col-2">
                        <br>
                        <img style="width: 100%" src="data:image/png;base64, <?php
                        if (isset($Photo) && $Photo != null) {
                            print($Photo);
                        } else {
                            print(MediaPortal::getCategoryImage($StockItemID));
                        }
                        ?>">
                        <br><br>
                    </div>
                    <div id="product-info" class="col-4">
                        <div class="ProductMand">
                            <div>
                                <h4><?php print($StockItemName); ?></h4>
                            </div>
                            <div>
                                <?php print($MarketingComments); ?>
                            </div>
                        </div>
                        <div id="filler" class="col-2 centerDivText">
                        </div>
                    </div>
                    <div id="aantalInMand" class="col-2 centerDivText">
                        <div class-1>
                            Aantal: <?php print($aantal); ?>
                        </div>
                    </div>
                    <div id="filler" class="col-2 centerDivText">
                    </div>
                </div>
                <?php
             }
        } else {
            echo "Betaling van " . $payment->amount->value . " " . $payment->amount->currency . " is niet geslaagd! :(";

        }
        ?>
    </div>
</div>
</body>
</html>


