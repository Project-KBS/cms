<?php


class customer
{
    /**
     * zet alle beschikbare informatie van de klant in de database
     */

    public static function insertCustomer($database, $vNaam, $tVoegsel, $aNaam, $straat, $hNummer, $woonplaats, $postcode, $mailAdres){

        $query = "INSERT INTO customers (CustomerName, DeliveryAddressLine2,  PostalAddressLine2, PostalPostalCode)
                  VALUES ('$vNaam $tVoegsel $aNaam', '$straat $hNummer', '$woonplaats', '$postcode'  )";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
