<?php
// dit is een testontwerp en zo veilig als het achtereind van een paard natuurlijk

include_once("app/mollie.php");
include_once("mollie-api-php/vendor/autoload.php");
include_once("app/model/customer.php");
include_once("app/model/product.php");
include_once("app/model/order.php");
include_once("app/model/transactie.php");
include_once("app/model/invoice.php");
include_once("app/database.php");
include_once("app/cart.php");

// Start de sessie als hij nog niet gestart is
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$postVars = ["Voornaam", "Achternaam", "Straatnaam", "Huisnummer", "Postcode", "Woonplaats"];

foreach ($postVars as $postVar) {
    if (!isset($_POST[$postVar]) || $_POST[$postVar] == NULL) {
        header("Location: index.php");
        die("$postVar is ongeldig!");
    }
}

// Verkrijg het ID van de klant (maakt automatisch nieuwe klant aan als dat nodig is)
$customerId = Customer::insertCustomer(Database::getConnection(), $_POST['Voornaam'], $_POST['Tussenvoegsel'], $_POST['Achternaam'], $_POST['Straatnaam'], $_POST['Huisnummer'], $_POST['Postcode'], $_POST['Woonplaats']);


$database    = Database::getConnection();
$winkelwagen = Cart::get();

$prijsExcl = 0;
$btw       = 0;
$prijsIncl = 0;

foreach ($winkelwagen as $productId => $hoeveelheid) {
    extract(Product::getbyid($database, $productId)->fetch(PDO::FETCH_ASSOC));

    $prijsExcl += $RecommendedRetailPrice;
    $btw       += $RecommendedRetailPrice * ($TaxRate / 100);
    $prijsIncl += ($RecommendedRetailPrice * (1 + $TaxRate / 100) * $hoeveelheid);
}

// de mollie API activeren en een key zetten.
$mollie = Mollie::getApi();

// genereer een uniek orderID
$orderId = time(); // TODO haal nieuw order id uit database, microtime werkt niet omdat het een te lang getal is.
print($hoeveelheid);
// een betaling aanmaken
$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR", //deze waarde zorgt voor het type valuta van de betaling
        "value" => strval(round($prijsIncl, 2))// deze waarde is het totaal inclusief BTW worden
    ],
    "description" => "Wide World Importers bestelling", // dit is de beschrijving van de betaling bij het bankafschrift van de klant
    "redirectUrl" => "http://localhost/confirm-order.php?orderId=$orderId", // dit is de locatie waar Mollie de klant heenstuurt na de betaling
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/", // geen flauw idee wat dit is
    "metadata" => [
        "order_id" => $orderId, // dit geeft de betaling een orderID mee
    ],
]);

error_log("\n\n----> " . $payment->id . "\n");

//nu moeten we het paymentID samen met het orderID opslaan om later te controleren of er betaald is
//Transactie::insert(Database::getConnection(), $orderId, $customerId, $prijsExcl, $btw, $prijsIncl);
Invoice::insert(Database::getConnection(), $customerId, $orderId, $payment->id);
Order::insert(Database::getConnection(), $customerId);

//de gebruiker wordt doorgestuurd naar een betalingspagina
header("Location: " . $payment->getCheckoutUrl(), true, 303);

?>
