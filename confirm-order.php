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


