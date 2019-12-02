<?php
// dit is een testontwerp en zo veilig als het achtereind van een paard natuurlijk


include_once("mollie-api-php/vendor/autoload.php");

// de mollie API activeren en een key zetten.
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("test_3mSHuyjnfdsnyBFRKv6P7ucgqPh4Tc"); // deze key zorgt ervoor dat de betaling wordt gekoppeld aan ons account bij Mollie, zodat we daar de betalingen kunnen zien en de betaalmethodes aanpassen.

// een betaling aanmaken
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR", //deze waarde zorgt voor het type valuta van de betaling
        "value" => "10.00"// deze waarde is het totaal inclusief BTW worden
    ],
    "description" => "Wide World Importers bestelling", // dit is de beschrijving van de betaling bij het bankafschrift van de klant
    "redirectUrl" => "http://localhost/confirm-order.php", // dit is de locatie waar Mollie de klant heenstuurt na de betaling
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/", // geen flauw idee wat dit is
]);

//nu moeten we het payment id opslaan om later te controleren of er betaald is


//de gebruiker wordt doorgestuurd naar een betalingspagina
header("Location: " . $payment->getCheckoutUrl(), true, 303);
?>
