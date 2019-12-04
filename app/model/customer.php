<?php

class Customer {

    private static function getNextId(PDO $database) : int {

        $query = "SELECT (SELECT MAX(x.CustomerID) FROM Customers x)+1 NextId";

        $stmt = $database->query($query);

        if ($stmt) {
            return intval($stmt->fetchColumn(0));
        } else {
            return -1;
        }
    }

    /**
     * zet alle beschikbare informatie van de klant in de database
     *
     * Het nieuwe Customer ID wordt gereturned.
     */
    public static function insertCustomer($database, $vNaam, $tVoegsel, $aNaam, $straat, $hNummer, $woonplaats, $postcode) : int {

        $query = "INSERT INTO Customers (CustomerID, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate,   StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, DeliveryPostalCode, CustomerName, PostalAddressLine1, PostalPostalCode, LastEditedBy, ValidFrom,             ValidTo              )
                  VALUES                (:id,        1,                1,                  1,                      1,                1,              1,            CURRENT_TIMESTAMP(), 0,                          1,               1,              0,           '',          '',        '',         :adres,               :postcode,          :naam,        :adres,             :postcode,        1,            '1000-01-01 01:00:00', '9999-01-01 01:00:00')";

        $stmt = $database->prepare($query);

        $id = self::getNextId($database);
        $naam = 0;
        if($tVoegsel===NULL){
            $naam = $vNaam . " " . $aNaam;
        }else{
            $naam = $vNaam . " " . $tVoegsel . " " . $aNaam;
        }

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":id",         $id,                                     PDO::PARAM_INT);
        $stmt->bindValue(":naam",       $naam,                                   PDO::PARAM_STR);
        $stmt->bindValue(":straat",     $straat,                                 PDO::PARAM_STR);
        $stmt->bindValue(":adres",      $hNummer . " " . $woonplaats,            PDO::PARAM_STR);
        $stmt->bindValue(":postcode",   $postcode,                               PDO::PARAM_STR);

        // Voer de query uit
        // Als de klant AL BESTAAT gooit hij een exception, vang deze op:
        try {
            $stmt->execute();
        } catch (Exception $ignored) {
            // De klant bestaat al dus moeten we het BESTAANDE CustomerID returnen, niet de nieuwe!!!
            return self::getIdFromName($database, $naam);
        }

        return $id;
    }

    /**
     * Verkrijg CustomerID van een naam
     *
     * @param PDO $database Database connectie object
     * @param string $naam  Volledige klantnaam incl. spaties
     * @return int          Het CustomerID, of -1 als hij niet is gevonden
     */
    public static function getIdFromName(PDO $database, string $naam) : int {
        // De klant bestaat al dus moeten we het BESTAANDE CustomerID returnen, niet de nieuwe!!!
        $idQuery = "SELECT c.CustomerID FROM Customers c WHERE c.CustomerName = :naam LIMIT 1";

        $idStmt = $database->prepare($idQuery);

        $idStmt->bindValue(":naam",       $naam,                                 PDO::PARAM_STR);

        $idStmt->execute();

        // Naam is een Unique Constraint (UQ) dus dit kan maar 1 resultaat geven. (+ limit)
        extract($idStmt->fetch());

        if (isset($CustomerID)) {
            return intval($CustomerID);
        } else {
            return -1;
        }
    }
}
