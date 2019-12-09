<?php

class Order {

    public const TABLE_NAME = "Orders";

    /**
     * Maak een nieuwe invoice aan waar we de payment ID in de comments opslaan.
     *
     * @param PDO $database
     * @param int $customerId
     * @param int $orderId
     * @param int $paymentId
     * @return PDOStatement
     */
    public static function insert(PDO $database, int $customerId) {

        // FIXME expected delivery date en IsUndersupplyBackordered
        $query = "INSERT INTO Orders (OrderID,                                   CustomerID,  ContactPersonID, SalespersonPersonID, OrderDate,      ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy,  LastEditedWhen)
                  VALUES             ((SELECT MAX(O.OrderID) + 1 FROM Orders O), :customerId, :contactId,      1,                   CURRENT_DATE(), CURRENT_DATE(),       1,                        :lastEditedBy, CURRENT_DATE())";

        $stmt = $database->prepare($query);

        // We voegen de variabelen niet direct in de SQL query, maar binden ze later, dit doen we om SQL injection te voorkomen
        $stmt->bindValue(":customerId",   $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":contactId",    $customerId,      PDO::PARAM_INT);
        $stmt->bindValue(":lastEditedBy",     1,      PDO::PARAM_INT); // FIXME

        // Voer de query uit
        $stmt->execute();

        return $stmt;
    }
}
