<?php
// dit is een testontwerp en zo veilig als het achtereind van een paard natuurlijk


include_once("mollie-api-php/vendor/autoload.php");

// de mollie API activeren en een key zetten.
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("test_3mSHuyjnfdsnyBFRKv6P7ucgqPh4Tc");

// een betaling aanmaken
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => "10.00"
    ],
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
]);

//de gebruiker wordt doorgestuurd naar een betalingspagina
header("Location: " . $payment->getCheckoutUrl(), true, 303);
?>
