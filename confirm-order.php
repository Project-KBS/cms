<?php

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");


// bij deze pagina moet een GET parameter genaamd "orderId" zijn met integer value hoger dan 0.

// check of orderId aanwezig is in URL
if (!isset($_GET["orderId"])) {
    exit(1); // TODO laat error zien
}

$orderId = $_GET["orderId"];

// check of orderId een getal is boven de nul
if (filter_var($orderId, FILTER_VALIDATE_INT) == false || $orderId <= 0) {
    exit(2); // TODO laat error zien (ongeldige order id)
}

// check of de betaling bestaat

// TODO verkrijg paymentId uit de database.... deze is waarschijnlijk gelinkt aan het ORDER ID !!!!!!!!
$paymentId = 69;

try {
    $payment = $mollie->payments->get($paymentId);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    exit(3); // TODO laat error zien
}


// check of de betaling geslaagd is
if ($payment->isPaid()) {
    echo "Payment received.";
}

?>
