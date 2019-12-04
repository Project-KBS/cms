<?php

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");
include_once("app/model/invoice.php");
include_once("app/database.php");

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


// check of de betaling geslaagd is
if ($payment->isPaid()) {
    echo "BETALING IS GESLAAGD!!!! Je hebt betaald met " . $payment->method . " en wij hebben " . $payment->amount->value . " " . $payment->amount->currency . " ontvangen!!";
} else {
    echo "Betaling van " . $payment->amount->value . " " . $payment->amount->currency . " is niet geslaagd! :(";
}

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
        <div id="pagina-container">
            <header>
                <!-- Dit gedeelte van de header komt in een lijn te staan met de body content -->
                <div id="header-inline" class="responsive-container">
                    <div id="promotie">
                        <a href="index.php">
                            <img src="img/logo/small-250x90.png" alt="Logo">
                        </a>
                    </div>
                </div>
            </header>
            <div class="content-container-home">

                <?php

                    //hier wordt een variabele gemaakt om de prijs in op te slaan
                    $totalprice = 0;

                    foreach (Cart::get() as $item => $aantal) {

                        $stmt = (Product::getbyid(Database::getConnection(), $item, 5));

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        extract($row);
                        if ($aantal > 0) {
                            ?>
                            <div id="order-overview" class="row" style="padding-bottom: 2vh">
                                <div  id="photo" class="col-2">
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
                                </div>
                                <div id="retailPrijs" class="col-2 centerDivText">
                                    € <?php print($roundedRecommendedRetailPrice); ?>
                                </div>
                                <div id="aantalInMand" class="col-2 centerDivText">
                                    <div class-1>
                                        Aantal: <?php print($aantal); ?>
                                    </div>
                                </div>
                                <div id="PrijsBTW" class="col-2 centerDivText">
                                    € <?php print($totalPriceInlc); ?>
                                </div>
                            </div>
                            <hr>
                            <?php
                            $totalprice += $totalPriceInlc;
                        }
                    }
                    if ($totalprice === 0) {
                        ?>
                            <div style="text-align: center">
                                <p>Je hebt niets in je winkelmandje.</p>
                            </div>
                        <?php
                    }
                    $totalprice = round($totalprice,2);
                ?>
                <div class="row">
                    <div id="opvul" class="col-10"></div>
                    <div id="Totaal" class="col-2">
                        <!-- Prijzen totaal-->
                        <p id="prijs-excl">Exclusief btw: €<?php print($totalPriceExcl); ?></p>
                        <p id="prijs-incl">Inclusief btw: €<?php print($totalprice); ?></p>
                        <p id="prijs-totaal">Totaalbedrag: €<?php print(round($totalprice,2)); ?></p>
                    </div>

                </div>
                <div class="row">
                    <div class="col-4">
                        <a href="index.php">Verder winkelen</a>
                    </div>
                    <form name="form" method="post">
                        <div class="col-6"></div>
                        <input type="hidden" value="<?php print($totalprice); ?>">
                        <a href="checkout.php">
                            <div type="submit" class="ContinueButton">Afrekenen</div>
                        </a>
                    </form>
                </div>
                <br><br>
            </div>
            <footer>
                <?php
                    include("tpl/footer_template.php");
                ?>
            </footer>
        </div>
    </body>
</html>
