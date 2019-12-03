<?php

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");

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

// check of de betaling bestaat

// TODO verkrijg paymentId uit de database.... deze is waarschijnlijk gelinkt aan het ORDER ID !!!!!!!!
$paymentId = 69;

try {
    $payment = $mollie->payments->get($paymentId);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    exit("Order ID bestaat niet!");
}


// check of de betaling geslaagd is
if ($payment->isPaid()) {
    echo "Payment received.";
}

?>
