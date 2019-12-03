<?php
// dit is een testontwerp en zo veilig als het achtereind van een paard natuurlijk

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");
include_once("app/model/customer.php");
include_once("app/model/transactie.php");
include_once("app/database.php");

customer::insertCustomer(Database::getConnection(),$_POST['Voornaam'],$_POST['Tussenvoegsel'],$_POST['Achternaam'],$_POST['Straatnaam'],$_POST['Huisnummer'],$_POST['Postcode'],$_POST['Woonplaats']);


$customerId = 42069; /// FIXME
$prijsExcl = 19.95;
$btw       = 5.00;
$prijsIncl = 24.95;

// de mollie API activeren en een key zetten.
$mollie = Mollie::getApi();

// genereer een uniek orderID
$orderId = time() + rand(0, 99999);

print($orderId . "\n\n");

// een betaling aanmaken
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR", //deze waarde zorgt voor het type valuta van de betaling
        "value" => $prijsIncl// deze waarde is het totaal inclusief BTW worden
    ],
    "description" => "Wide World Importers bestelling", // dit is de beschrijving van de betaling bij het bankafschrift van de klant
    "redirectUrl" => "http://localhost/confirm-order.php?orderId=$orderId", // dit is de locatie waar Mollie de klant heenstuurt na de betaling
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/", // geen flauw idee wat dit is
    "metadata" => [
        "order_id" => $orderId, // dit geeft de betaling een orderID mee
    ],
]);

//nu moeten we het paymentID samen met het orderID opslaan om later te controleren of er betaald is
Transactie::insert(Database::getConnection(), intval($payment->id), $customerId, $prijsExcl, $btw, $prijsIncl);

//de gebruiker wordt doorgestuurd naar een betalingspagina
header("Location: " . $payment->getCheckoutUrl(), true, 303);

?>
