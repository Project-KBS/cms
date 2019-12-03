<?php

class Customer {

    /**
     * zet alle beschikbare informatie van de klant in de database
     */

    public static function insertCustomer($database, $vNaam, $tVoegsel, $aNaam, $straat, $hNummer, $woonplaats, $postcode){

        $query = "INSERT INTO Customers (CustomerID,                                    BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate,   StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, DeliveryPostalCode, CustomerName, PostalAddressLine1, PostalPostalCode, LastEditedBy, ValidFrom,             ValidTo              )
                  VALUES                ((SELECT MAX(x.CustomerID) FROM Customers x)+1, 1,                1,                  1,                      1,                1,              1,            CURRENT_TIMESTAMP(), 0,                          1,               1,              0,           '',          '',        '',         :adres,               :postcode,          :naam,        :adres,             :postcode,        1,            '1000-01-01 01:00:00', '9999-01-01 01:00:00')";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":naam",       $vNaam . " " . $tVoegsel . " " . $aNaam, PDO::PARAM_STR);
        $stmt->bindValue(":straat",     $straat,                                 PDO::PARAM_STR);
        $stmt->bindValue(":adres",      $hNummer . " " . $woonplaats,            PDO::PARAM_STR);
        $stmt->bindValue(":postcode",   $postcode,                               PDO::PARAM_STR);
        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
