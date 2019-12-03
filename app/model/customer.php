<?php


class customer
{
    /**
     * zet alle beschikbare informatie van de klant in de database
     */

    public static function insertCustomer($database, $vNaam, $tVoegsel, $aNaam, $straat, $hNummer, $woonplaats, $postcode){

        $query = "INSERT INTO customers (CustomerName, DeliveryAddressLine2,  PostalAddressLine2, PostalPostalCode)
                  VALUES (:vNaam :tVoegsel :aNaam, :straat :hNummer, :woonplaats, :postcode)";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":vNaam",   $vNaam,    PDO::PARAM_STR);
        $stmt->bindValue(":tVoegsel",   $tVoegsel,   PDO::PARAM_STR);
        $stmt->bindValue(":aNaam",   $aNaam,    PDO::PARAM_STR);
        $stmt->bindValue(":straat",   $straat,   PDO::PARAM_STR);
        $stmt->bindValue(":hNummer",   $hNummer,    PDO::PARAM_STR);
        $stmt->bindValue(":woonplaats",   $woonplaats,   PDO::PARAM_STR);
        $stmt->bindValue(":postcode",   $postcode,    PDO::PARAM_STR);
        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
