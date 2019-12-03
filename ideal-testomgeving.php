<?php
// dit is een testontwerp en zo veilig als het achtereind van een paard natuurlijk

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");


// de mollie API activeren en een key zetten.
$mollie = Mollie::getApi();

// genereer een uniek orderID
$orderId = time() + rand(0, 99999);

print($orderId . "\n\n");

// een betaling aanmaken
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR", //deze waarde zorgt voor het type valuta van de betaling
        "value" => "10.00"// deze waarde is het totaal inclusief BTW worden
    ],
    "description" => "Wide World Importers bestelling", // dit is de beschrijving van de betaling bij het bankafschrift van de klant
    "redirectUrl" => "http://localhost/confirm-order.php?orderId=$orderId", // dit is de locatie waar Mollie de klant heenstuurt na de betaling
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/", // geen flauw idee wat dit is
    "metadata" => [
        "order_id" => $orderId, // dit geeft de betaling een orderID mee
    ],
]);

//nu moeten we het paymentID samen met het orderID opslaan om later te controleren of er betaald is
// dit moeten we vervangen door onze eigen database
//database_write($orderId, $payment->status);
// TODO sla het paymentId op in de order database (LINK DEZE MET ORDER ID) zodat we hem in confirm-order.php weer kunnen krijgen

//de gebruiker wordt doorgestuurd naar een betalingspagina
header("Location: " . $payment->getCheckoutUrl(), true, 303);

?>
